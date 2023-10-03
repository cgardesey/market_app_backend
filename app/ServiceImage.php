<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceImage extends Model
{
    protected $guarded=['id', 'service_image_id'];
    protected $primaryKey = 'service_image_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $hidden = ['id'];

    public function getRouteKeyName()
    {
        return 'service_image_id';
    }
}
