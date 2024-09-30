<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Implementation extends Model
{
    use HasFactory;

    function formatToTwoDecimalPoints($value)
    {
        return number_format($value, 2, '.', '');
    }

    function pesosToCents($pesos)
    {
        return intval(round(self::formatToTwoDecimalPoints($pesos) * 100));
    }

    function centsToPesos($cents)
    {
        return self::formatToTwoDecimalPoints($cents / 100);
    }

    protected $fillable = [
        'users_id',
        'project_num',
        'project_title',
        'purpose',
        'province',
        'district',
        'city_municipality',
        'budget_amount',
        'minimum_wage',
        'total_slots',
        'days_of_work',
    ];

    public function focal()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function batch()
    {
        return $this->hasMany(Batch::class, 'implementations_id');
    }

    protected function casts(): array
    {
        return [
            'budget_amount' => 'integer',
            'total_slots' => 'integer',
            'days_of_work' => 'integer',
        ];
    }
}
