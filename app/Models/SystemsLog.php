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
        'alternative_sender',
        'description',
        'old_data',
        'new_data',
        'regional_office',
        'field_office',
        'log_type',
        'log_timestamp',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public $timestamps = false;
}
