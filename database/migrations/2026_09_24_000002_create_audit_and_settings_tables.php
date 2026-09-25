<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('set null');
            $table->string('category', 100);
            $table->string('action', 150);
            $table->boolean('success')->default(true);
            $table->string('target_type', 100)->nullable();
            $table->unsignedBigInteger('target_id')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->string('attempted_email', 180)->nullable();
            $table->text('description')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('created_at', 'idx_audit_created');
            $table->index(['category', 'action', 'created_at'], 'idx_audit_category_action');
            $table->index(['user_id', 'created_at'], 'idx_audit_user_created');
            $table->index(['category', 'success', 'created_at'], 'idx_audit_failed_auth');
        });

        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('setting_key', 120)->unique();
            $table->string('setting_value', 255);
            $table->enum('value_type', ['integer', 'decimal', 'boolean', 'string', 'json'])->default('string');
            $table->string('description', 255)->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('environmental_thresholds', function (Blueprint $table) {
            $table->id();
            $table->enum('variable_type', ['co2', 'temperature', 'humidity'])->unique();
            $table->decimal('operational_min', 12, 4)->nullable();
            $table->decimal('operational_max', 12, 4)->nullable();
            $table->decimal('warning_min', 12, 4)->nullable();
            $table->decimal('warning_max', 12, 4)->nullable();
            $table->decimal('critical_min', 12, 4)->nullable();
            $table->decimal('critical_max', 12, 4)->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('environmental_thresholds');
        Schema::dropIfExists('system_settings');
        Schema::dropIfExists('audit_logs');
    }
};
