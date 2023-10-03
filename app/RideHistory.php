<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RideHistory extends Model
{
    protected $guarded=[];
    protected $primaryKey = 'ride_history_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $hidden = [];

    public function getRouteKeyName()
    {
        return 'ride_history_id';
    }
}
