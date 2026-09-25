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
        Schema::create('environment_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('environment_id')->constrained('environments')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('instructor_user_id')->constrained('users')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('assigned_by')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('set null');
            $table->dateTime('assigned_at')->useCurrent();
            $table->dateTime('ended_at')->nullable();
            $table->boolean('is_current')->default(true);
            $table->timestamps();

            $table->unique(['environment_id', 'is_current'], 'uq_assignment_environment_current');
            $table->unique(['instructor_user_id', 'is_current'], 'uq_assignment_instructor_current');
            $table->index(['environment_id', 'assigned_at', 'ended_at'], 'idx_assignment_environment_dates');
        });

        Schema::create('attendance_confirmations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_user_id')->constrained('users')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('assigned_environment_id')->constrained('environments')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('actual_environment_id')->constrained('environments')->onUpdate('cascade')->onDelete('restrict');
            $table->date('confirmation_date');
            $table->dateTime('confirmed_at')->useCurrent();
            $table->timestamps();

            $table->unique(['instructor_user_id', 'confirmation_date'], 'uq_attendance_confirmation_day');
            $table->index(['actual_environment_id', 'confirmation_date'], 'idx_attendance_environment_date');
        });

        Schema::create('occupancy_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('environment_id')->constrained('environments')->onUpdate('cascade')->onDelete('restrict');
            $table->dateTime('observed_at');
            $table->unsignedInteger('occupant_count');
            $table->enum('source', ['manual', 'other'])->default('manual');
            $table->timestamp('created_at')->useCurrent();

            $table->index(['environment_id', 'observed_at'], 'idx_occupancy_environment_time');
        });

        Schema::create('environment_change_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_user_id')->constrained('users')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('assigned_environment_id')->constrained('environments')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('actual_environment_id')->constrained('environments')->onUpdate('cascade')->onDelete('restrict');
            $table->unsignedInteger('student_count');
            $table->dateTime('occurred_at')->useCurrent();
            $table->enum('notification_status', ['pending', 'sent', 'failed', 'retrying'])->default('pending');
            $table->timestamp('created_at')->useCurrent();

            $table->index(['instructor_user_id', 'occurred_at'], 'idx_environment_changes_instructor_date');
            $table->index('occurred_at', 'idx_environment_changes_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('environment_change_events');
        Schema::dropIfExists('occupancy_records');
        Schema::dropIfExists('attendance_confirmations');
        Schema::dropIfExists('environment_assignments');
    }
};
