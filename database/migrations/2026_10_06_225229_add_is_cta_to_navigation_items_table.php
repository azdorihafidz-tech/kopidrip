<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('navigation_items', function (Blueprint $table): void {
            $table->boolean('is_cta')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('navigation_items', function (Blueprint $table): void {
            $table->dropColumn('is_cta');
        });
    }
};
