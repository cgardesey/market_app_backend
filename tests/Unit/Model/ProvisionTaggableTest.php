<?php

namespace Tests\Unit;

use App\ProvisionTaggable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProvisionTaggableTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_correct_table_name()
    {
        $provisionTaggable = new ProvisionTaggable();
        $this->assertEquals('provision_taggables', $provisionTaggable->getTable());
    }

    /** @test */
    public function it_uses_provisionTaggable_id_as_the_primary_key()
    {
        $provisionTaggable = new ProvisionTaggable();

        $this->assertEquals('provision_taggable_id', $provisionTaggable->getKeyName());
    }

    /** @test */
    public function it_is_not_incrementing()
    {
        $provisionTaggable = new ProvisionTaggable();

        $this->assertFalse($provisionTaggable->getIncrementing());
    }

    /** @test */
    public function it_has_correct_primary_key_taggable()
    {
        $provisionTaggable = new ProvisionTaggable();
        $this->assertEquals('string', $provisionTaggable->getKeyType());
    }

    /** @test */
    public function it_has_hidden_attributes()
    {
        $provisionTaggable = new ProvisionTaggable();
        $hidden = ['id'];
        $this->assertEquals($hidden, $provisionTaggable->getHidden());
    }

    /** @test */
    public function it_returns_provisionTaggable_id_for_route_key()
    {
        $provisionTaggable = new ProvisionTaggable();

        $this->assertEquals('provision_taggable_id', $provisionTaggable->getRouteKeyName());
    }
}
