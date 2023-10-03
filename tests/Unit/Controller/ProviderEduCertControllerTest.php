<?php

namespace Tests\Unit\Controllers;

use App\ProviderEduCert;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProviderEduCertControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test index edu of ProviderEduCertController.
     *
     * @return void
     */
    public function testIndex()
    {
        $user = factory(User::class)->create();

        $providerEduCerts = factory(ProviderEduCert::class, 3)->create();

        $response = $this->get('/api/provider-edu-certs', [
            'Authorization' => 'Bearer ' . $user->api_token,
        ]);

        $response->assertStatus(200);


        $response->assertJsonFragment([
            'provider_edu_cert_id' => $providerEduCerts[0]->provider_edu_cert_id,
            'cert_title' => $providerEduCerts[0]->cert_title,
            'institution_name' => $providerEduCerts[0]->institution_name
        ]);
    }

    /**
     * Test store edu of ProviderEduCertController.
     *
     * @return void
     */
    public function testStore()
    {
        $requestData = [
            'cert_title' => "WASSCE",
            'institution_name' => "KNUST"
        ];

        $user = factory(User::class)->create();
        $response = $this->post('/api/provider-edu-certs', $requestData, [
            'Authorization' => 'Bearer ' . $user->api_token
        ]);

        $response->assertStatus(200);

        $response->assertJsonFragment($requestData);

        $this->assertDatabaseHas('provider_edu_certs', $requestData);
    }

    /**
     * Test show edu of ProviderEduCertController.
     *
     * @return void
     */
    public function testShow()
    {
        $providerEduCert = factory(ProviderEduCert::class)->create();

        $user = factory(User::class)->create();
        $response = $this->get('api/provider-edu-certs/' . $providerEduCert->provider_edu_cert_id , [
            'Authorization' => 'Bearer ' . $user->api_token
        ]);

        $response->assertStatus(200);

        $response->assertJsonFragment([
            'cert_title' => $providerEduCert->cert_title,
            'institution_name' => $providerEduCert->institution_name
        ]);
    }

    /**
     * Test update edu of ProviderEduCertController.
     *
     * @return void
     */
    public function testUpdate()
    {
        $providerEdu = factory(ProviderEduCert::class)->create();
        $providerEdu->makeVisible('provider_edu_cert_id');

        $requestData = [
            'cert_title' => "WASSCE",
            'institution_name' => "KNUST"
        ];

        $user = factory(User::class)->create();

        $response = $this->patch('/api/provider-edu-certs/' . $providerEdu->provider_edu_cert_id, $requestData, [
            'Authorization' => 'Bearer ' . $user->api_token
        ]);

        $response->assertStatus(200);

        $response->assertJsonFragment($requestData);

        $providerEdu->refresh();

        $this->assertDatabaseHas('provider_edu_certs', $requestData);
    }
}
