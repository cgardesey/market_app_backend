<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $guarded=['id', 'service_id'];
    protected $primaryKey = 'service_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $hidden = ['id'];

    public function getRouteKeyName()
    {
        return 'service_id';
    }

    public function provisionTags()
    {
        return $this->morphToMany(ProvisionTag::class, 'provision_taggable',null, 'provision_tag_id',
            'provision_taggable_id', 'service_id',
            'provision_tag_id', false);
    }

    public function serviceImages()
    {
        return $this->hasMany(ServiceImage::class, 'service_image_id', 'service_image_id');
    }
}
