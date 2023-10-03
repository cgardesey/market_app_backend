<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IdentificationType extends Model
{
    protected $guarded=['id', 'identification_type_id'];
    protected $primaryKey = 'identification_type_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $hidden = ['id'];

    public function getRouteKeyName()
    {
        return 'identification_type_id';
    }
}
