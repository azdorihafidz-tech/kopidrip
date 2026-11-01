<?php

namespace App\Services;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\Payment;
use App\Support\Money;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ManualPaymentWebhookService
{
    public function handle(string $rawBody, string $signature): void
    {
        $secret = (string) config('services.manual_payments.webhook_secret');
        if ($secret === '') {
            throw new HttpException(503, 'Webhook payment belum dikonfigurasi.');
        }

        $payload = json_decode($rawBody, true);
        if (! is_array($payload) || ! isset($payload['event_id'], $payload['timestamp'], $payload['order_number'], $payload['amount'], $payload['status'], $payload['reference'])) {
            throw new HttpException(400, 'Payload tidak valid.');
        }

        if (! is_string($payload['event_id']) || ! preg_match('/^[A-Za-z0-9._:-]{1,160}$/', $payload['event_id'])
            || ! is_string($payload['order_number']) || ! preg_match('/^KD-[A-Z0-9-]{1,37}$/', $payload['order_number'])
            || ! is_string($payload['reference']) || ! preg_match('/^[A-Za-z0-9._:-]{1,160}$/', $payload['reference'])
            || ! is_string($payload['status']) || ! in_array($payload['status'], ['paid', 'failed'], true)
            || (! is_string($payload['amount']) && ! is_int($payload['amount']))
            || ! preg_match('/^\d{1,10}(?:\.\d{1,2})?$/', (string) $payload['amount'])) {
            throw new HttpException(400, 'Payload payment tidak valid.');
        }

        $timestamp = filter_var($payload['timestamp'], FILTER_VALIDATE_INT);
        if (! $timestamp || abs(time() - $timestamp) > 300) {
            throw new HttpException(401, 'Timestamp webhook tidak valid.');
        }
        $expected = hash_hmac('sha256', $timestamp.'.'.$rawBody, $secret);
        if (! hash_equals($expected, $signature)) {
            throw new HttpException(401, 'Signature webhook tidak valid.');
        }

        DB::transaction(function () use ($payload, $timestamp, $rawBody): void {
            $event = DB::table('payment_webhook_events')->insertOrIgnore([
                'provider' => 'manual', 'event_id' => $payload['event_id'],
                'payload_hash' => hash('sha256', $rawBody), 'provider_timestamp' => now()->setTimestamp($timestamp),
                'status' => 'received', 'created_at' => now(), 'updated_at' => now(),
            ]);
            if ($event === 0) {
                return;
            }

            $order = Order::query()->where('order_number', $payload['order_number'])->lockForUpdate()->first();
            if (! $order || $order->total === null) {
                DB::table('payment_webhook_events')->where('provider', 'manual')->where('event_id', $payload['event_id'])->update(['status' => 'rejected', 'processed_at' => now(), 'updated_at' => now()]);

                return;
            }

            $amountMinor = is_string($payload['amount']) || is_int($payload['amount'])
                ? Money::toMinor($payload['amount']) : -1;
            $totalMinor = Money::toMinor((string) $order->total);
            if ($amountMinor !== $totalMinor || $order->status === OrderStatus::Cancelled
                || ($order->payment_status === PaymentStatus::Paid && $payload['status'] !== 'paid')) {
                DB::table('payment_webhook_events')->where('provider', 'manual')->where('event_id', $payload['event_id'])->update(['status' => 'rejected', 'processed_at' => now(), 'updated_at' => now()]);

                return;
            }

            if ($order->payment_status === PaymentStatus::Paid) {
                DB::table('payment_webhook_events')->where('provider', 'manual')->where('event_id', $payload['event_id'])->update(['status' => 'processed', 'processed_at' => now(), 'updated_at' => now()]);

                return;
            }

            $status = $payload['status'] === 'paid' ? PaymentStatus::Paid : PaymentStatus::Failed;
            Payment::query()->updateOrCreate(['idempotency_key' => 'manual:'.$order->id], [
                'order_id' => $order->id, 'provider' => 'manual', 'provider_reference' => $payload['reference'],
                'amount' => $order->total, 'currency' => 'IDR', 'status' => $status,
                'paid_at' => $status === PaymentStatus::Paid ? now() : null,
            ]);
            $order->payment_status = $status;
            $order->save();
            DB::table('payment_webhook_events')->where('provider', 'manual')->where('event_id', $payload['event_id'])->update(['status' => 'processed', 'processed_at' => now(), 'updated_at' => now()]);
        }, 3);
    }
}
