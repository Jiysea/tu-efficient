<?php

namespace App\Models;

use Carbon\Carbon;
use DB;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'extension_name',
        'email',
        'password',
        'contact_num',
        'regional_office',
        'field_office',
        'user_type',
        'last_login',
        'email_verified_at',
        'mobile_verified_at',
        'ongoing_verification',
    ];

    public function systems_log()
    {
        return $this->hasMany(SystemsLog::class, 'users_id');
    }

    public function implementation()
    {
        return $this->hasMany(Implementation::class, 'users_id');
    }

    public function assignment()
    {
        return $this->hasMany(Assignment::class, 'users_id');
    }

    public function user_setting()
    {
        return $this->hasMany(UserSetting::class, 'users_id');
    }

    public function isOnline()
    {
        $session = DB::table('sessions')
            ->where('user_id', $this->id) # Filter by the current user ID
            ->where('last_activity', '>=', Carbon::now()->subMinutes(5)->timestamp)
            ->first();

        return $session !== null;
    }

    public function isEmailVerified()
    {
        return $this->email_verified_at !== null;
    }

    public function isMobileVerified()
    {
        return $this->mobile_verified_at !== null;
    }

    public function markMobileAsVerified()
    {
        $this->mobile_verified_at = now();
        $this->save();
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // Check if the user is phone verified
    public function isOngoingVerification()
    {
        return $this->ongoing_verification === 1 ? true : false;
    }
}
