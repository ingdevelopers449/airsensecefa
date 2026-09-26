<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SensorReading extends Model
{
    use HasFactory;

    protected $table = 'sensor_readings';

    public $timestamps = false; // Manejado por columnas personalizadas measured_at / received_at / created_at

    protected $fillable = [
        'node_id',
        'environment_id',
        'device_message_id',
        'measured_at',
        'received_at',
        'source',
        'is_valid',
        'reported_latitude',
        'reported_longitude',
        'raw_payload',
        'created_at',
    ];

    protected $casts = [
        'is_valid' => 'boolean',
        'measured_at' => 'datetime',
        'received_at' => 'datetime',
        'created_at' => 'datetime',
        'raw_payload' => 'array',
        'reported_latitude' => 'decimal:7',
        'reported_longitude' => 'decimal:7',
    ];

    public function node(): BelongsTo
    {
        return $this->belongsTo(Node::class, 'node_id');
    }

    public function environment(): BelongsTo
    {
        return $this->belongsTo(Environment::class, 'environment_id');
    }

    public function measurements(): HasMany
    {
        return $this->hasMany(SensorMeasurement::class, 'reading_id');
    }
}
