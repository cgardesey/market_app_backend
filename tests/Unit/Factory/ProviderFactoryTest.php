<?php

namespace Tests\Unit\Factories;

use App\Provider;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProviderFactoryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_creates_a_provider_with_valid_data()
    {
        $user = factory(User::class)->create();

        $provider = factory(Provider::class)->create([
            'user_id' => $user->user_id,
        ]);

        $this->assertInstanceOf(Provider::class, $provider);
        $this->assertDatabaseHas('providers', [
            'provider_id' => $provider->provider_id,
            'confirmation_token' => null,
            'title' => $provider->title,
            'first_name' => $provider->first_name,
            'last_name' => $provider->last_name,
            // ... add assertions for the other fields here
            'availability' => 'Available',
            'user_id' => $user->user_id,
        ]);
    }
}
