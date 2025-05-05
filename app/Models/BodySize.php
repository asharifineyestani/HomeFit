<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BodySize extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'neck',
        'shoulders',
        'arm_relaxed',
        'arm_flexed',
        'forearm',
        'wrist',
        'chest',
        'stomach',
        'waist',
        'hip',
        'thigh',
        'calf',
        'ankle',
        'measured_date',
    ];

    protected $casts = [
        'measured_date' => 'date',
        'neck' => 'decimal:2',
        'shoulders' => 'decimal:2',
        'arm_relaxed' => 'decimal:2',
        'arm_flexed' => 'decimal:2',
        'forearm' => 'decimal:2',
        'wrist' => 'decimal:2',
        'chest' => 'decimal:2',
        'stomach' => 'decimal:2',
        'waist' => 'decimal:2',
        'hip' => 'decimal:2',
        'thigh' => 'decimal:2',
        'calf' => 'decimal:2',
        'ankle' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
