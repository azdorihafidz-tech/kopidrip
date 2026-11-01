<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('website_settings', function (Blueprint $table): void {
            $table->boolean('site_is_demo')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('website_settings', fn (Blueprint $table) => $table->dropColumn('site_is_demo'));
    }
};
