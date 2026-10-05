<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContingencyProtocol extends Model
{
    use HasFactory;

    protected $table = 'contingency_protocols';

    protected $fillable = [
        'category',
        'risk_level',
        'title',
        'description',
        'action_steps',
        'is_active',
        'created_by',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
