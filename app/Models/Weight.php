<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Weight extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'weight',
        'measured_date',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'measured_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
