<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProvisionTaggable extends Model
{
    protected $guarded=['id', 'provision_taggable_id'];
    protected $primaryKey = 'provision_taggable_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $hidden = ['id'];

    public function getRouteKeyName()
    {
        return 'provision_taggable_id';
    }
}
