<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProvisionTag extends Model
{
    protected $guarded=['id', 'provision_tag_id'];
    protected $primaryKey = 'provision_tag_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $hidden = ['id'];

    public function getRouteKeyName()
    {
        return 'provision_tag_id';
    }

    public function provisions()
    {
        return $this->morphedByMany(Product::class, 'provision_taggable', null, 'provision_tag_id',
            'provision_taggable_id', 'provision_tag_id', 'product_id');
    }

    public function services()
    {
        return $this->morphedByMany(Service::class, 'provision_taggable', null, 'provision_tag_id',
            'provision_taggable_id', 'provision_tag_id', 'service_id');
    }
}
