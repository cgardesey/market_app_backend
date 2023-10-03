<?php

namespace Tests\Unit\Factories;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserFactoryTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function it_creates_a_valid_user_instance()
    {
        $user = factory(User::class)->create();

        $this->assertInstanceOf(User::class, $user);
        $this->assertDatabaseHas('users', [
            'user_id' => $user->user_id,
            'phone_number' => $user->phone_number,
            'username' => $user->username,
            'email' => $user->email,
        ]);
    }
}
