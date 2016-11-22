<?php

namespace Webaccess\BiometLaravel\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class Intervention extends Model
{
    protected $table = 'interventions';
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
        'personal_information',
        'description',
    ];

    public function getEventDateAttribute()
    {
        return DateTime::createFromFormat('Y-m-d H:i:s', $this->attributes['event_date'])->format('Y-m-d');
    }
}