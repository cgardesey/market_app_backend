<?php

namespace Tests\Unit\Factories;

use App\ProvisionTag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProvisionTagFactoryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_creates_a_provision_tag_with_valid_data()
    {
        $provisionTag = factory(ProvisionTag::class)->create();

        $this->assertInstanceOf(ProvisionTag::class, $provisionTag);
        $this->assertDatabaseHas('provision_tags', [
            'provision_tag_id' => $provisionTag->provision_tag_id,
            'name' => $provisionTag->name,
            'description' => $provisionTag->description,
        ]);
    }
}
