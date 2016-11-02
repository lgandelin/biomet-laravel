<?php

namespace Webaccess\BiometLaravel\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
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
    ];
}