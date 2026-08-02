<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Insert default system settings
        DB::table('system_settings')->insert([
            ['key' => 'app_name', 'value' => 'Creative Starter Dashboard', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'support_email', 'value' => 'support@example.com', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'default_locale', 'value' => 'en', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'maintenance_mode', 'value' => '0', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};
