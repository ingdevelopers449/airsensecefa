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
        Schema::create('environments', function (Blueprint $table) {
            $table->id();
            $table->string('code', 60)->unique();
            $table->string('name', 180)->unique();
            $table->text('description')->nullable();
            $table->enum('current_semaphore_state', ['green', 'yellow', 'red', 'no_data'])->default('no_data');
            $table->dateTime('current_state_updated_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('is_active', 'idx_environments_active');
        });

        Schema::create('node_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120)->unique();
            $table->string('description', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('nodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('environment_id')->nullable()->constrained('environments')->onUpdate('cascade')->onDelete('set null');
            $table->string('device_uid', 150)->unique();
            $table->string('device_token_hash', 255);
            $table->unsignedInteger('token_version')->default(1);
            $table->string('name', 180)->nullable();
            $table->boolean('is_active')->default(true);
            $table->enum('connectivity_status', ['online', 'offline', 'unknown'])->default('unknown');
            $table->dateTime('last_seen_at')->nullable();
            $table->dateTime('last_keep_alive_at')->nullable();
            $table->decimal('last_reported_latitude', 10, 7)->nullable();
            $table->decimal('last_reported_longitude', 10, 7)->nullable();
            $table->dateTime('token_rotated_at')->nullable();
            $table->dateTime('first_seen_at')->useCurrent();
            $table->timestamps();

            $table->index(['environment_id', 'is_active'], 'idx_nodes_environment_active');
            $table->index(['connectivity_status', 'last_seen_at'], 'idx_nodes_connectivity');
        });

        Schema::create('map_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('node_id')->unique()->constrained('nodes')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('category_id')->nullable()->constrained('node_categories')->onUpdate('cascade')->onDelete('set null');
            $table->string('place_name', 180);
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('category_id', 'idx_map_points_category');
            $table->index(['latitude', 'longitude'], 'idx_map_points_coordinates');
        });

        Schema::create('node_location_changes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('node_id')->constrained('nodes')->onUpdate('cascade')->onDelete('restrict');
            $table->string('previous_place_name', 180)->nullable();
            $table->decimal('previous_latitude', 10, 7);
            $table->decimal('previous_longitude', 10, 7);
            $table->string('new_place_name', 180);
            $table->decimal('new_latitude', 10, 7);
            $table->decimal('new_longitude', 10, 7);
            $table->foreignId('changed_by')->constrained('users')->onUpdate('cascade')->onDelete('restrict');
            $table->dateTime('confirmed_at')->useCurrent();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['node_id', 'confirmed_at'], 'idx_location_changes_node_date');
            $table->index(['changed_by', 'confirmed_at'], 'idx_location_changes_user_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('node_location_changes');
        Schema::dropIfExists('map_points');
        Schema::dropIfExists('nodes');
        Schema::dropIfExists('node_categories');
        Schema::dropIfExists('environments');
    }
};
