<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceRating extends Model
{
    protected $guarded=['id', 'service_rating_id'];
    protected $primaryKey = 'service_rating_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $hidden = ['id'];

    public function getRouteKeyName()
    {
        return 'service_rating_id';
    }
}
