<?php

namespace Webaccess\BiometLaravel\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'clients';
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
        'name',
        'access_limit_date',
        'users_limit'
    ];

    public function setAccessLimitDateAttribute($value)
    {
        if ($value != '') {
            $this->attributes['access_limit_date'] = implode('-', array_reverse(explode('/', $value)));
        }
    }
}