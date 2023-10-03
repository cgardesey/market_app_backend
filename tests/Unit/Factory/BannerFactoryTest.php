<?php

namespace Tests\Unit;

use App\Banner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BannerFactoryTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function it_can_create_a_banner()
    {
        // Create a banner using the factory
        $banner = factory(Banner::class)->create();

        // Assert the banner was created successfully
        $this->assertDatabaseHas('banners', [
            'banner_id' => $banner->banner_id,
            'title' => $banner->title,
            'url' => $banner->url,
            'tag' => $banner->tag,
        ]);
    }

    /** @test */
    public function it_has_a_valid_url()
    {
        // Create a banner using the factory
        $banner = factory(Banner::class)->create();

        // Assert that the URL is valid
        $this->assertStringStartsWith('http', $banner->url);
    }
}
