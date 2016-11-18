<?php

namespace Webaccess\BiometLaravel\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class Alarm extends Model
{
    protected $table = 'alarms';
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
        'event_date',
        'title',
        'description'
    ];

    public function getEventDateAttribute()
    {
        return DateTime::createFromFormat('Y-m-d H:i:s', $this->attributes['event_date'])->format('d/m/Y H:i:s');
    }
}