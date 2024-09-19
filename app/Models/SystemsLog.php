<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemsLog extends Model
{
    use HasFactory;

    protected $table = 'system_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'users_id',
        'log_timestamp',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public $timestamps = false;
}
