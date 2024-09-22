<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    use HasFactory;

    protected $fillable = [
        'batches_id',
        'access_code',
        'is_accessible',
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batches_id');
    }

}
