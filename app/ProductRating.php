<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductRating extends Model
{
    protected $guarded=['id', 'product_rating_id'];
    protected $primaryKey = 'product_rating_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $hidden = ['id'];

    public function getRouteKeyName()
    {
        return 'product_rating_id';
    }
}
