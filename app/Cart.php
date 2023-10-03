<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $guarded = ['id', 'cart_id'];
    protected $primaryKey = 'cart_id';
    public $incrementing = false;
    protected $keyType = 'string';

    public function cartProducts()
    {
        return $this->hasMany(CartProduct::class, 'cart_id');
    }

    /*public function products()
    {
        return $this->belongsToMany(Product::class, 'cart_products', 'cart_id', 'product_id')
            ->using(CartProduct::class)
            ->withPivot(['cart_product_id', 'quantity', 'price']);
    }*/
}
