<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_messages', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 120);
            $table->string('email');
            $table->string('subject', 150);
            $table->text('message');
            $table->timestamp('read_at')->nullable()->index();
            $table->timestamps();
            $table->index('created_at');
        });

        Schema::create('faqs', function (Blueprint $table): void {
            $table->id();
            $table->string('question', 255);
            $table->string('category', 100)->nullable();
            $table->text('answer');
            $table->string('status', 20)->default('draft')->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->index(['status', 'sort_order']);
        });

        Schema::create('testimonials', function (Blueprint $table): void {
            $table->id();
            $table->string('customer_name', 120);
            $table->string('customer_label', 120)->nullable();
            $table->text('content');
            $table->string('status', 20)->default('draft')->index();
            $table->boolean('featured')->default(false)->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->index(['status', 'sort_order']);
        });

        Schema::create('promotions', function (Blueprint $table): void {
            $table->id();
            $table->string('title', 180);
            $table->string('slug', 200)->unique();
            $table->string('summary', 500)->nullable();
            $table->longText('description')->nullable();
            $table->string('image_path')->nullable();
            $table->text('terms')->nullable();
            $table->string('status', 20)->default('draft')->index();
            $table->timestamp('starts_at')->nullable()->index();
            $table->timestamp('ends_at')->nullable()->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->index(['status', 'starts_at', 'ends_at']);
        });

        $this->grantAdminPermissions();
        $this->addNavigationLinks();
    }

    private function grantAdminPermissions(): void
    {
        if (! Schema::hasTable('permissions') || ! Schema::hasTable('roles') || ! Schema::hasTable('role_has_permissions')) {
            return;
        }

        foreach (['contact-messages.manage', 'faq.manage', 'testimonials.manage', 'promotions.manage'] as $name) {
            $permissionId = DB::table('permissions')
                ->where('name', $name)
                ->where('guard_name', 'web')
                ->value('id');

            if (! $permissionId) {
                $permissionId = DB::table('permissions')->insertGetId([
                    'name' => $name,
                    'guard_name' => 'web',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $roleIds = DB::table('roles')->whereIn('name', ['super-admin', 'admin'])->pluck('id');

            foreach ($roleIds as $roleId) {
                DB::table('role_has_permissions')->insertOrIgnore([
                    'permission_id' => $permissionId,
                    'role_id' => $roleId,
                ]);
            }
        }

        $editorRoleId = DB::table('roles')->where('name', 'editor')->value('id');

        if ($editorRoleId) {
            foreach (['faq.manage', 'testimonials.manage', 'promotions.manage'] as $name) {
                $permissionId = DB::table('permissions')
                    ->where('name', $name)
                    ->where('guard_name', 'web')
                    ->value('id');

                if ($permissionId) {
                    DB::table('role_has_permissions')->insertOrIgnore([
                        'permission_id' => $permissionId,
                        'role_id' => $editorRoleId,
                    ]);
                }
            }
        }
    }

    private function addNavigationLinks(): void
    {
        if (! Schema::hasTable('navigation_items')) {
            return;
        }

        $order = (int) DB::table('navigation_items')->max('sort_order');

        foreach ([['FAQ', '/faq'], ['Promo', '/promotions']] as [$label, $url]) {
            if (! DB::table('navigation_items')->where('url', $url)->exists()) {
                $order += 10;
                DB::table('navigation_items')->insert([
                    'label' => $label,
                    'url' => $url,
                    'sort_order' => $order,
                    'is_active' => true,
                    'open_new_tab' => false,
                    'is_cta' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('promotions')) {
            Schema::dropIfExists('promotions');
        }

        if (Schema::hasTable('testimonials')) {
            Schema::dropIfExists('testimonials');
        }

        if (Schema::hasTable('faqs')) {
            Schema::dropIfExists('faqs');
        }

        Schema::dropIfExists('contact_messages');
    }
};
