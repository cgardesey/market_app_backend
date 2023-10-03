<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $guarded = ['id', 'product_category_id'];
    protected $primaryKey = 'product_category_id';
    public $incrementing = false;
    protected $keyType = 'string';

    public function getRouteKeyName()
    {
        return 'product_category_id';
    }
}
