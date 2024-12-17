<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Archive extends Model
{
    use HasFactory;

    protected $fillable = [
        'last_id',
        'source_table',
        'data',
        'archived_at',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public $timestamps = false;
}
