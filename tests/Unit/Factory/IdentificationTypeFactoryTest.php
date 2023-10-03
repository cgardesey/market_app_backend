<?php

namespace Tests\Unit\Factories;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Collection;
use App\IdentificationType;

class IdentificationTypeFactoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_identification_types()
    {
        $identificationType = factory(IdentificationType::class)->create();

        $this->assertInstanceOf(IdentificationType::class, $identificationType);
    }

    /** @test */
    public function it_can_create_multiple_identification_types()
    {
        $count = 3;

        factory(IdentificationType::class, $count)->create();

        $this->assertCount($count, IdentificationType::all());
    }

    /** @test */
    public function it_has_correct_attributes()
    {
        $attributes = [
            'identification_type_id' => 'sample-uuid',
            'name' => 'Sample Name',
            'description' => 'Sample Description',
        ];

        factory(IdentificationType::class)->create($attributes);

        $this->assertDatabaseHas('identification_types', $attributes);
    }
}
