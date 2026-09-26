<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Node extends Model
{
    use HasFactory;

    protected $table = 'nodes';

    protected $fillable = [
        'environment_id',
        'device_uid',
        'device_token_hash',
        'token_version',
        'name',
        'is_active',
        'connectivity_status',
        'last_seen_at',
        'last_keep_alive_at',
        'last_reported_latitude',
        'last_reported_longitude',
        'token_rotated_at',
        'first_seen_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_seen_at' => 'datetime',
        'last_keep_alive_at' => 'datetime',
        'token_rotated_at' => 'datetime',
        'first_seen_at' => 'datetime',
        'last_reported_latitude' => 'decimal:7',
        'last_reported_longitude' => 'decimal:7',
    ];

    public function environment(): BelongsTo
    {
        return $this->belongsTo(Environment::class, 'environment_id');
    }

    public function readings(): HasMany
    {
        return $this->hasMany(SensorReading::class, 'node_id');
    }
}
