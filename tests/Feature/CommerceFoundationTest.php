<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Models\Product;
use App\Models\User;
use App\Services\CartService;
use App\Services\CheckoutService;
use App\Services\ManualPaymentWebhookService;
use App\Services\OrderWorkflowService;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class CommerceFoundationTest extends TestCase
{
    use RefreshDatabase;

    private function product(array $overrides = []): Product
    {
        return Product::query()->create(array_merge([
            'name' => 'Kopi Uji', 'slug' => 'kopi-uji-'.fake()->unique()->numerify('####'),
            'price' => '12500.00', 'stock' => 10, 'status' => 'published', 'is_demo' => false,
        ], $overrides));
    }

    public function test_cart_reloads_prices_checks_stock_and_excludes_demo_products(): void
    {
        $product = $this->product();
        $cart = app(CartService::class);
        $cart->add($product->id, null, 2);
        $product->update(['price' => '15000.00']);
        $summary = $cart->summary();
        $this->assertSame(3000000, $summary['subtotal_minor']);

        $demo = $this->product(['is_demo' => true]);
        try {
            $cart->add($demo->id, null, 1);
            $this->fail('Demo products cannot be added to the cart.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('product_id', $exception->errors());
        }

        $this->expectException(ValidationException::class);
        $cart->add($product->id, null, 9);
    }

    public function test_checkout_reserves_stock_snapshots_item_and_protects_order_with_secret_token(): void
    {
        $product = $this->product(['name' => 'Kopi Snapshot']);
        $cart = app(CartService::class);
        $cart->add($product->id, null, 2);
        $product->update(['price' => '13000.00']);
        $summary = $cart->assertCheckoutReady();
        [$order, $token] = app(CheckoutService::class)->place([
            'name' => 'Pelanggan Uji', 'email' => 'guest@example.test', 'phone' => '081234567890',
            'address' => 'Jalan Uji 1', 'city' => 'Sidikalang', 'province' => 'Sumatera Utara', 'postal_code' => '22211',
        ], $summary);

        $this->assertSame(8, $product->fresh()->stock);
        $this->assertSame('13000.00', $order->items()->first()->unit_price);
        $this->assertSame(hash('sha256', $token), $order->access_token_hash);
        $this->assertSame(404, $this->get(route('orders.show', ['order' => $order->order_number, 'token' => str_repeat('x', 64)]))->status());
        $this->get(route('orders.show', ['order' => $order->order_number, 'token' => $token]))->assertOk()->assertSee($order->order_number);
    }

    public function test_order_quote_cancellation_restores_reserved_stock_only_once(): void
    {
        $product = $this->product();
        $cart = app(CartService::class);
        $cart->add($product->id, null, 3);
        [$order] = app(CheckoutService::class)->place([
            'name' => 'Pelanggan Uji', 'email' => 'cancel@example.test', 'phone' => '081234567890',
            'address' => 'Jalan Uji 1', 'city' => 'Sidikalang', 'province' => 'Sumatera Utara', 'postal_code' => '22211',
        ], $cart->assertCheckoutReady());

        $workflow = app(OrderWorkflowService::class);
        $order = $workflow->setShippingQuote($order, 2500000, 'Kurir Uji', 'Reguler');
        $this->assertSame(6250000, $order->total_minor);
        $order = $workflow->transition($order, OrderStatus::Cancelled);
        $this->assertSame(10, $product->fresh()->stock);
        $this->assertFalse($order->stock_reserved);
    }

    public function test_signed_payment_webhook_is_idempotent_and_rejects_bad_signatures(): void
    {
        $product = $this->product();
        $cart = app(CartService::class);
        $cart->add($product->id, null, 1);
        [$order] = app(CheckoutService::class)->place([
            'name' => 'Pelanggan Uji', 'email' => 'pay@example.test', 'phone' => '081234567890',
            'address' => 'Jalan Uji 1', 'city' => 'Sidikalang', 'province' => 'Sumatera Utara', 'postal_code' => '22211',
        ], $cart->assertCheckoutReady());
        app(OrderWorkflowService::class)->setShippingQuote($order, 500000, 'Kurir Uji', 'Reguler');

        config(['services.manual_payments.webhook_secret' => 'test-webhook-secret']);
        $payload = json_encode(['event_id' => 'evt-1', 'timestamp' => time(), 'order_number' => $order->order_number, 'amount' => '17500.00', 'status' => 'paid', 'reference' => 'ref-1'], JSON_THROW_ON_ERROR);
        $decodedTimestamp = json_decode($payload, true)['timestamp'];
        $signature = hash_hmac('sha256', $decodedTimestamp.'.'.$payload, 'test-webhook-secret');
        try {
            app(ManualPaymentWebhookService::class)->handle($payload, 'invalid-signature');
            $this->fail('A webhook with an invalid signature must be rejected.');
        } catch (HttpException $exception) {
            $this->assertSame(401, $exception->getStatusCode());
        }
        $this->call('POST', '/webhooks/manual-payment', [], [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_X_KOPIDRIP_SIGNATURE' => $signature,
        ], $payload)->assertNoContent();
        app(ManualPaymentWebhookService::class)->handle($payload, $signature);

        $lateFailure = json_encode(['event_id' => 'evt-2', 'timestamp' => time(), 'order_number' => $order->order_number, 'amount' => '17500.00', 'status' => 'failed', 'reference' => 'ref-2'], JSON_THROW_ON_ERROR);
        $lateTimestamp = json_decode($lateFailure, true)['timestamp'];
        app(ManualPaymentWebhookService::class)->handle($lateFailure, hash_hmac('sha256', $lateTimestamp.'.'.$lateFailure, 'test-webhook-secret'));

        $this->assertSame('paid', $order->fresh()->payment_status->value);
        $this->assertSame(1, \DB::table('payments')->count());
        $this->assertSame('rejected', \DB::table('payment_webhook_events')->where('event_id', 'evt-2')->value('status'));
        $this->assertSame(2, \DB::table('payment_webhook_events')->count());
    }

    public function test_public_analytics_omits_private_routes_and_unapproved_query_values(): void
    {
        $this->get('/?utm_source=instagram&utm_campaign=sidikalang&email=private%40example.test')
            ->assertOk()->assertHeader('X-Content-Type-Options', 'nosniff')->assertHeader('X-Frame-Options', 'DENY');

        $event = \DB::table('analytics_events')->first();
        $this->assertSame('/', $event->path);
        $this->assertStringContainsString('utm_source', $event->campaign);
        $this->assertStringNotContainsString('private@example.test', $event->campaign);
        $this->get('/cart')->assertOk();
        $this->assertSame(1, \DB::table('analytics_events')->count());
    }

    public function test_guest_can_add_checkout_and_place_order_through_public_routes(): void
    {
        $product = $this->product();
        $this->get('/cart')->assertOk()->assertSee('Keranjang Anda masih kosong');
        $this->post('/cart/items', ['product_id' => $product->id, 'quantity' => 2])->assertRedirect('/cart');
        $this->get('/checkout')->assertOk()->assertSee('Ongkir menyusul konfirmasi admin');
        $response = $this->post('/checkout', [
            'name' => 'Pelanggan Route', 'email' => 'route@example.test', 'phone' => '081234567890',
            'address' => 'Jalan Uji 1', 'city' => 'Sidikalang', 'province' => 'Sumatera Utara', 'postal_code' => '22211',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseCount('orders', 1);
        $this->assertSame(8, $product->fresh()->stock);
        $this->get('/cart')->assertOk()->assertSee('Keranjang Anda masih kosong');
    }

    public function test_admin_only_can_access_order_and_customer_management(): void
    {
        $this->seed(RoleSeeder::class);
        $editor = User::factory()->create();
        $editor->assignRole('editor');
        $this->actingAs($editor)->get('/admin/orders')->assertForbidden();

        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $this->actingAs($admin)->get('/admin/orders')->assertOk()->assertSee('Pesanan');
        $this->actingAs($admin)->get('/admin/customers')->assertOk()->assertSee('Pelanggan');
    }
}
