<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'batches';

    protected $fillable = [
        'implementations_id',
        'batch_num',
        'is_sectoral',
        'sector_title',
        'district',
        'barangay_name',
        'slots_allocated',
        'approval_status',
        'submission_status',
    ];

    public function implementation()
    {
        return $this->belongsTo(Implementation::class, 'implementations_id');
    }

    public function beneficiary()
    {
        return $this->hasMany(Beneficiary::class, 'batches_id');
    }

    public function code()
    {
        return $this->hasMany(Code::class, 'batches_id');
    }

    public function assignment()
    {
        return $this->hasMany(Assignment::class, 'batches_id');
    }
}
