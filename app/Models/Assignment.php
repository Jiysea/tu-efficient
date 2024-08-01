<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'batches_id',
        'users_id',
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batches_id');
    }

    public function coordinator()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
}
