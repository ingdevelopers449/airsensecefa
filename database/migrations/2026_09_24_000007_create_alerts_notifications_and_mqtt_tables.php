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
        Schema::create('maintenance_windows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scheduled_by')->constrained('users')->onUpdate('cascade')->onDelete('restrict');
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->unsignedSmallInteger('estimated_duration_minutes');
            $table->dateTime('notice_created_at')->nullable();
            $table->text('message');
            $table->enum('status', ['scheduled', 'active', 'completed', 'cancelled'])->default('scheduled');
            $table->timestamps();

            $table->index(['starts_at', 'ends_at', 'status'], 'idx_maintenance_schedule');
        });

        Schema::create('alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('environment_id')->constrained('environments')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('node_id')->nullable()->constrained('nodes')->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('reading_id')->nullable()->constrained('sensor_readings')->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('prediction_id')->nullable()->constrained('predictions')->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('protocol_id')->nullable()->constrained('protocols')->onUpdate('cascade')->onDelete('set null');
            $table->enum('alert_type', ['critical', 'predictive']);
            $table->enum('severity', ['warning', 'critical']);
            $table->enum('variable_type', ['co2', 'temperature', 'humidity', 'combined']);
            $table->decimal('measured_value', 14, 4)->nullable();
            $table->decimal('threshold_value', 14, 4)->nullable();
            $table->decimal('estimated_minutes_to_critical', 10, 2)->nullable();
            $table->decimal('confidence', 7, 4)->nullable();
            $table->enum('status', ['active', 'resolved', 'discarded', 'unconfirmed'])->default('active');
            $table->dateTime('triggered_at')->useCurrent();
            $table->dateTime('resolved_at')->nullable();
            $table->dateTime('discarded_at')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['environment_id', 'status', 'triggered_at'], 'idx_alerts_environment_status_time');
            $table->index(['alert_type', 'status', 'triggered_at'], 'idx_alerts_type_status');
            $table->index(['node_id', 'triggered_at'], 'idx_alerts_node_time');
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipient_user_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('alert_id')->nullable()->constrained('alerts')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('environment_change_event_id')->nullable()->constrained('environment_change_events')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('maintenance_window_id')->nullable()->constrained('maintenance_windows')->onUpdate('cascade')->onDelete('cascade');
            $table->enum('notification_type', ['critical_alert', 'predictive_alert', 'environment_change', 'maintenance', 'system']);
            $table->enum('channel', ['visual', 'email']);
            $table->string('title', 255);
            $table->text('body');
            $table->enum('delivery_status', ['pending', 'sent', 'failed', 'read'])->default('pending');
            $table->dateTime('sent_at')->nullable();
            $table->dateTime('read_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['recipient_user_id', 'delivery_status', 'created_at'], 'idx_notifications_user_status');
            $table->index(['alert_id', 'created_at'], 'idx_notifications_alert');
        });

        Schema::create('system_events', function (Blueprint $table) {
            $table->id();
            $table->string('event_type', 100);
            $table->foreignId('environment_id')->nullable()->constrained('environments')->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('node_id')->nullable()->constrained('nodes')->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('reading_id')->nullable()->constrained('sensor_readings')->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('alert_id')->nullable()->constrained('alerts')->onUpdate('cascade')->onDelete('set null');
            $table->foreignId('prediction_id')->nullable()->constrained('predictions')->onUpdate('cascade')->onDelete('set null');
            $table->enum('variable_type', ['co2', 'temperature', 'humidity', 'combined', 'system'])->nullable();
            $table->decimal('event_value', 14, 4)->nullable();
            $table->enum('previous_state', ['green', 'yellow', 'red', 'no_data'])->nullable();
            $table->enum('new_state', ['green', 'yellow', 'red', 'no_data'])->nullable();
            $table->dateTime('occurred_at')->useCurrent();
            $table->json('details')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['environment_id', 'occurred_at'], 'idx_system_events_environment_time');
            $table->index(['node_id', 'occurred_at'], 'idx_system_events_node_time');
            $table->index(['event_type', 'occurred_at'], 'idx_system_events_type_time');
        });

        Schema::create('mqtt_publications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('node_id')->constrained('nodes')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('environment_id')->constrained('environments')->onUpdate('cascade')->onDelete('restrict');
            $table->enum('semaphore_state', ['green', 'yellow', 'red']);
            $table->enum('priority', ['normal', 'high'])->default('normal');
            $table->string('topic', 255);
            $table->text('payload');
            $table->dateTime('published_at')->useCurrent();
            $table->dateTime('ack_received_at')->nullable();
            $table->enum('status', ['pending', 'published', 'confirmed', 'failed'])->default('pending');
            $table->text('error_message')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['node_id', 'published_at'], 'idx_mqtt_node_time');
            $table->index(['status', 'published_at'], 'idx_mqtt_status_time');
        });

        Schema::create('system_performance_metrics', function (Blueprint $table) {
            $table->id();
            $table->dateTime('measured_at')->useCurrent();
            $table->unsignedInteger('active_nodes')->default(0);
            $table->unsignedInteger('concurrent_users')->default(0);
            $table->unsignedInteger('alert_latency_ms')->nullable();
            $table->unsignedInteger('dashboard_update_latency_ms')->nullable();
            $table->enum('status', ['normal', 'degraded'])->default('normal');
            $table->json('details')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('measured_at', 'idx_performance_measured');
            $table->index(['status', 'measured_at'], 'idx_performance_status_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_performance_metrics');
        Schema::dropIfExists('mqtt_publications');
        Schema::dropIfExists('system_events');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('alerts');
        Schema::dropIfExists('maintenance_windows');
    }
};
