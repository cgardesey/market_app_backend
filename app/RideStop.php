<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RideStop extends Model
{
    protected $guarded=['id', 'ride_stop_id'];
    protected $primaryKey = 'ride_stop_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $hidden = ['id'];

    public function getRouteKeyName()
    {
        return 'ride_stop_id';
    }
}
