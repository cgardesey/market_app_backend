<?php

namespace Tests\Unit\Factories;

use App\Provider;
use App\ProviderEduCert;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProviderEduCertFactoryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_creates_a_provider_edu_cert_with_valid_data()
    {
        $eduCert = factory(ProviderEduCert::class)->create();

        $this->assertInstanceOf(ProviderEduCert::class, $eduCert);
        $this->assertDatabaseHas('provider_edu_certs', [
            'provider_edu_cert_id' => $eduCert->provider_edu_cert_id,
            'cert_title' => $eduCert->cert_title,
            'institution_name' => $eduCert->institution_name,
            'start_date' => $eduCert->start_date,
            'end_date' => $eduCert->end_date,
            'provider_id' => $eduCert->provider_id,
            'deleted' => false,
        ]);
    }
}
