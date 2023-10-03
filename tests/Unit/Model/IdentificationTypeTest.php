<?php

namespace Tests\Unit;

use App\IdentificationType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IdentificationTypeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_correct_table_name()
    {
        $identificationType = new IdentificationType();
        $this->assertEquals('identification_types', $identificationType->getTable());
    }

    /** @test */
    public function it_uses_identificationType_id_as_the_primary_key()
    {
        $identificationType = new IdentificationType();

        $this->assertEquals('identification_type_id', $identificationType->getKeyName());
    }

    /** @test */
    public function it_is_not_incrementing()
    {
        $identificationType = new IdentificationType();

        $this->assertFalse($identificationType->getIncrementing());
    }

    /** @test */
    public function it_has_correct_primary_key_type()
    {
        $identificationType = new IdentificationType();
        $this->assertEquals('string', $identificationType->getKeyType());
    }

    /** @test */
    public function it_has_hidden_attributes()
    {
        $identificationType = new IdentificationType();
        $hidden = ['id'];
        $this->assertEquals($hidden, $identificationType->getHidden());
    }

    /** @test */
    public function it_returns_identificationType_id_for_route_key()
    {
        $identificationType = new IdentificationType();

        $this->assertEquals('identification_type_id', $identificationType->getRouteKeyName());
    }
}
