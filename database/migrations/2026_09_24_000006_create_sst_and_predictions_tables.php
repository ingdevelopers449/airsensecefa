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
        Schema::create('risk_categories', function (Blueprint $table) {
            $table->id();
            $table->string('code', 60)->unique();
            $table->string('name', 120)->unique();
            $table->string('description', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('manuals', function (Blueprint $table) {
            $table->id();
            $table->string('version', 50);
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->boolean('is_current')->default(false);
            $table->foreignId('published_by')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('set null');
            $table->dateTime('published_at')->nullable();
            $table->timestamps();

            $table->index(['is_current', 'published_at'], 'idx_manuals_current');
        });

        Schema::create('manual_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('manual_id')->constrained('manuals')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('risk_category_id')->constrained('risk_categories')->onUpdate('cascade')->onDelete('restrict');
            $table->string('title', 255);
            $table->longText('content');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['manual_id', 'sort_order'], 'idx_manual_sections_manual_order');
        });

        Schema::create('protocols', function (Blueprint $table) {
            $table->id();
            $table->foreignId('risk_category_id')->constrained('risk_categories')->onUpdate('cascade')->onDelete('restrict');
            $table->string('title', 255);
            $table->longText('instructions');
            $table->enum('trigger_variable', ['co2', 'temperature', 'humidity', 'combined', 'occupancy', 'system']);
            $table->enum('trigger_state', ['green', 'yellow', 'red', 'predictive', 'any'])->default('any');
            $table->string('version', 50)->default('1.0');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('set null');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['trigger_variable', 'trigger_state', 'is_active'], 'idx_protocols_trigger');
        });

        Schema::create('predictions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('environment_id')->constrained('environments')->onUpdate('cascade')->onDelete('restrict');
            $table->string('model_version', 100);
            $table->enum('status', ['learning', 'completed', 'failed', 'superseded'])->default('learning');
            $table->unsignedInteger('sample_count')->default(0);
            $table->decimal('confidence', 7, 4)->nullable();
            $table->enum('risk_level', ['low', 'moderate', 'high'])->nullable();
            $table->text('justification')->nullable();
            $table->dateTime('generated_at')->useCurrent();
            $table->dateTime('valid_until')->nullable();
            $table->boolean('is_current')->default(false);
            $table->timestamp('created_at')->useCurrent();

            $table->index(['environment_id', 'is_current', 'generated_at'], 'idx_predictions_environment_current');
            $table->index(['status', 'generated_at'], 'idx_predictions_status');
        });

        Schema::create('prediction_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prediction_id')->constrained('predictions')->onUpdate('cascade')->onDelete('cascade');
            $table->enum('variable_type', ['co2', 'temperature', 'humidity', 'combined']);
            $table->dateTime('predicted_at');
            $table->decimal('predicted_value', 14, 4)->nullable();
            $table->string('unit', 20);
            $table->decimal('confidence', 7, 4)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['prediction_id', 'variable_type', 'predicted_at'], 'idx_prediction_points_curve');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prediction_points');
        Schema::dropIfExists('predictions');
        Schema::dropIfExists('protocols');
        Schema::dropIfExists('manual_sections');
        Schema::dropIfExists('manuals');
        Schema::dropIfExists('risk_categories');
    }
};
