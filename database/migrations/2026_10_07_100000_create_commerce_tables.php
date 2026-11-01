<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable()->unique()->constrained()->nullOnDelete();
            $table->string('name', 160);
            $table->string('email')->unique();
            $table->string('phone', 40);
            $table->string('address', 255);
            $table->string('city', 120);
            $table->string('province', 120);
            $table->string('postal_code', 20);
            $table->timestamps();
            $table->index(['name', 'email']);
        });

        Schema::create('orders', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('customer_id')->constrained()->restrictOnDelete();
            $table->string('order_number', 40)->unique();
            $table->char('access_token_hash', 64)->unique();
            $table->string('customer_name', 160);
            $table->string('customer_email');
            $table->string('customer_phone', 40);
            $table->string('shipping_address', 255);
            $table->string('shipping_city', 120);
            $table->string('shipping_province', 120);
            $table->string('shipping_postal_code', 20);
            $table->text('customer_notes')->nullable();
            $table->decimal('subtotal', 12, 2);
            $table->decimal('shipping_cost', 12, 2)->nullable();
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->nullable();
            $table->char('currency', 3)->default('IDR');
            $table->string('status', 24)->default('pending')->index();
            $table->string('payment_status', 24)->default('unpaid')->index();
            $table->string('shipping_status', 24)->default('quote_pending')->index();
            $table->string('courier', 100)->nullable();
            $table->string('shipping_service', 120)->nullable();
            $table->string('tracking_number', 120)->nullable();
            $table->boolean('stock_reserved')->default(true);
            $table->text('admin_notes')->nullable();
            $table->timestamp('placed_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
            $table->index(['status', 'created_at']);
            $table->index(['payment_status', 'created_at']);
            $table->index(['customer_id', 'created_at']);
        });

        Schema::create('order_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained('product_variants')->nullOnDelete();
            $table->string('product_name', 255);
            $table->string('product_sku', 120)->nullable();
            $table->string('variant_name', 160)->nullable();
            $table->unsignedInteger('quantity');
            $table->decimal('unit_price', 12, 2);
            $table->decimal('line_total', 12, 2);
            $table->unsignedInteger('weight_grams')->nullable();
            $table->timestamps();
            $table->index(['order_id', 'product_id']);
        });

        Schema::create('payments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('provider', 40)->default('manual');
            $table->string('provider_reference', 160)->nullable()->unique();
            $table->string('idempotency_key', 160)->unique();
            $table->decimal('amount', 12, 2);
            $table->char('currency', 3)->default('IDR');
            $table->string('status', 24)->default('unpaid')->index();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->index(['order_id', 'status']);
        });

        Schema::create('payment_webhook_events', function (Blueprint $table): void {
            $table->id();
            $table->string('provider', 40);
            $table->string('event_id', 160);
            $table->char('payload_hash', 64);
            $table->timestamp('provider_timestamp');
            $table->string('status', 24)->default('received');
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
            $table->unique(['provider', 'event_id']);
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_webhook_events');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('customers');
    }
};
