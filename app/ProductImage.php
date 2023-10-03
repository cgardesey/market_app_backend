<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $guarded=['id', 'product_image_id'];
    protected $primaryKey = 'product_image_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $hidden = ['id'];

    public function getRouteKeyName()
    {
        return 'product_image_id';
    }
}
