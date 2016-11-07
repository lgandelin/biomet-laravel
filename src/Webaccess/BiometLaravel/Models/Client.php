<?php

namespace Webaccess\BiometLaravel\Models;

class Client
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
    ];
}