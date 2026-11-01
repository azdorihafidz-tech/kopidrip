<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('analytics_events', function (Blueprint $table): void {
            $table->id();
            $table->string('event_name', 40)->index();
            $table->string('path', 255);
            $table->string('referrer_host', 190)->nullable();
            $table->char('visitor_key', 64)->index();
            $table->json('campaign')->nullable();
            $table->timestamp('created_at')->useCurrent()->index();
            $table->index(['event_name', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analytics_events');
    }
};
