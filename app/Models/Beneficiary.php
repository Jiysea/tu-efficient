<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beneficiary extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'beneficiaries';

    protected $fillable = [
        'batches_id',
        'first_name',
        'middle_name',
        'last_name',
        'extension_name',
        'birthdate',
        'barangay_name',
        'contact_num',
        'occupation',
        'avg_monthly_income',
        'city_municipality',
        'province',
        'district',
        'type_of_id',
        'id_number',
        'e_payment_acc_num',
        'beneficiary_type',
        'sex',
        'civil_status',
        'age',
        'dependent',
        'self_employment',
        'skills_training',
        'is_pwd',
        'is_senior_citizen',
        'spouse_first_name',
        'spouse_middle_name',
        'spouse_last_name',
        'spouse_extension_name',
        'is_signed',
        'is_paid',
        'is_safe'
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batches_id');
    }

    public function credential()
    {
        return $this->hasMany(Credential::class, 'beneficiaries_id');
    }

    protected function casts(): array
    {
        return [
            'age' => 'integer',
            'birthdate' => 'date:Y-m-d',
        ];
    }
}
