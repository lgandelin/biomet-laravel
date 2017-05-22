<?php

namespace Webaccess\BiometLaravel\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    protected $table = 'equipments';
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
        'facility_id',
        'name',
        'tag',
        'partial_counter',
        'total_counter',
    ];
}