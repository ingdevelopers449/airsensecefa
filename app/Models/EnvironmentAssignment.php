<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EnvironmentAssignment extends Model
{
    use HasFactory;

    protected $table = 'environment_assignments';

    protected $fillable = [
        'environment_id',
        'instructor_user_id',
        'assigned_by',
        'assigned_at',
        'ended_at',
        'is_current',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'ended_at' => 'datetime',
        'is_current' => 'boolean',
    ];

    /**
     * Relación con el Ambiente de formación.
     */
    public function environment(): BelongsTo
    {
        return $this->belongsTo(Environment::class, 'environment_id');
    }

    /**
     * Relación con el usuario Instructor asignado.
     */
    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_user_id');
    }

    /**
     * Relación con el Administrador que realizó la asignación.
     */
    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
