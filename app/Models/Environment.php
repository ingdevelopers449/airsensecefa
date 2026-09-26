<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Environment extends Model
{
    use HasFactory;

    protected $table = 'environments';

    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function nodes(): HasMany
    {
        return $this->hasMany(Node::class, 'environment_id');
    }

    public function readings(): HasMany
    {
        return $this->hasMany(SensorReading::class, 'environment_id');
    }
}
