<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = ['id', 'product_id'];
    protected $primaryKey = 'product_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $hidden = ['id'];

    public function getRouteKeyName()
    {
        return 'product_id';
    }

    public function provisionTags()
    {
        return $this->morphToMany(ProvisionTag::class, 'provision_taggable', null, 'provision_tag_id',
            'provision_taggable_id', 'product_id',
            'provision_tag_id', false);
    }

    public function productImages()
    {
        return $this->hasMany(ProductImage::class, 'product_image_id', 'product_image_id');
    }

    /*public function carts()
    {
        return $this->belongsToMany(Cart::class, 'cart_products', 'product_id', 'cart_id')
            ->using(CartProduct::class)
            ->withPivot(['cart_product_id', 'quantity', 'price']);
    }*/
}
