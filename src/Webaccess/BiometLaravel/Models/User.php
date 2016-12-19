<?php

namespace Webaccess\BiometLaravel\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    const PROFILE_ID_AROL_ENERGY_ADMINISTRATOR = 1;
    const PROFILE_ID_CLIENT_USER = 2;
    const PROFILE_ID_CLIENT_ADMINISTRATOR = 3;
    const PROFILE_ID_PROVIDER = 4;

    protected $table = 'users';
    public $incrementing = false;
    public $casts = [
        'id' => 'string'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'profile_id', 'password', 'remember_token',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
