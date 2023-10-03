<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    protected $guarded = ['id', 'service_category_id'];
    protected $primaryKey = 'service_category_id';
    public $incrementing = false;
    protected $keyType = 'string';

    public function getRouteKeyName()
    {
        return 'service_category_id';
    }
}
