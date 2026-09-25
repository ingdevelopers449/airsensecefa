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
        Schema::create('sensor_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('node_id')->constrained('nodes')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('environment_id')->constrained('environments')->onUpdate('cascade')->onDelete('restrict');
            $table->string('device_message_id', 180)->nullable()->unique();
            $table->dateTime('measured_at');
            $table->dateTime('received_at')->useCurrent();
            $table->enum('source', ['online', 'offline_sync'])->default('online');
            $table->boolean('is_valid')->default(true);
            $table->decimal('reported_latitude', 10, 7)->nullable();
            $table->decimal('reported_longitude', 10, 7)->nullable();
            $table->json('raw_payload')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['environment_id', 'measured_at'], 'idx_sensor_readings_environment_time');
            $table->index(['node_id', 'measured_at'], 'idx_sensor_readings_node_time');
            $table->index('received_at', 'idx_sensor_readings_received');
        });

        Schema::create('sensor_measurements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reading_id')->constrained('sensor_readings')->onUpdate('cascade')->onDelete('cascade');
            $table->enum('variable_type', ['co2', 'temperature', 'humidity']);
            $table->decimal('value', 14, 4);
            $table->string('unit', 20);
            $table->boolean('is_valid')->default(true);
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['reading_id', 'variable_type'], 'uq_measurement_reading_variable');
            $table->index(['variable_type', 'created_at'], 'idx_measurement_variable_time');
            $table->index('reading_id', 'idx_measurement_reading');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensor_measurements');
        Schema::dropIfExists('sensor_readings');
    }
};
