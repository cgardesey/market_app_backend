<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProviderEduCert extends Model
{
    protected $guarded=['id', 'provider_edu_cert_id'];
    protected $primaryKey = 'provider_edu_cert_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $hidden = ['id'];

    public function getRouteKeyName()
    {
        return 'provider_edu_cert_id';
    }
}
