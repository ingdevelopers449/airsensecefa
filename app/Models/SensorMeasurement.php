<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SensorMeasurement extends Model
{
    use HasFactory;

    protected $table = 'sensor_measurements';

    public $timestamps = false; // Manejado por columna created_at

    protected $fillable = [
        'reading_id',
        'variable_type',
        'value',
        'unit',
        'is_valid',
        'created_at',
    ];

    protected $casts = [
        'is_valid' => 'boolean',
        'value' => 'decimal:4',
        'created_at' => 'datetime',
    ];

    public function reading(): BelongsTo
    {
        return $this->belongsTo(SensorReading::class, 'reading_id');
    }
}
