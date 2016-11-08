<?php

namespace Webaccess\BiometLaravel\Models;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    protected $table = 'facilities';
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
        'longitude',
        'latitude',
        'address',
        'city',
        'department',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}