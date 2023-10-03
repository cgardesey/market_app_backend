<?php

namespace Tests\Unit;

use App\ProviderEduCert;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProviderEduCertTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_correct_table_name()
    {
        $providerEduCert = new ProviderEduCert();
        $this->assertEquals('provider_edu_certs', $providerEduCert->getTable());
    }

    /** @test */
    public function it_uses_providerEduCert_id_as_the_primary_key()
    {
        $providerEduCert = new ProviderEduCert();

        $this->assertEquals('provider_edu_cert_id', $providerEduCert->getKeyName());
    }

    /** @test */
    public function it_is_not_incrementing()
    {
        $providerEduCert = new ProviderEduCert();

        $this->assertFalse($providerEduCert->getIncrementing());
    }

    /** @test */
    public function it_has_correct_primary_key_edu_cert()
    {
        $providerEduCert = new ProviderEduCert();
        $this->assertEquals('string', $providerEduCert->getKeyType());
    }

    /** @test */
    public function it_has_hidden_attributes()
    {
        $providerEduCert = new ProviderEduCert();
        $hidden = ['id'];
        $this->assertEquals($hidden, $providerEduCert->getHidden());
    }

    /** @test */
    public function it_returns_providerEduCert_id_for_route_key()
    {
        $providerEduCert = new ProviderEduCert();

        $this->assertEquals('provider_edu_cert_id', $providerEduCert->getRouteKeyName());
    }
}
