<?php

namespace Tests\Feature;

use App\Models\Stream;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class TopStreamsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test get top streams api request
     *
     * @return void
     */
    public function test_top_streams_api_response()
    {
        Stream::factory()->count(5)->create();
        
        $this->get('/api/streams/top')
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('data', 5)
                    ->hasAll([
                        'data.0.title',
                        'data.0.game',
                        'data.0.views',
                    ])->whereAllType([
                        'data.0.title' => 'string',
                        'data.0.game' => 'string',
                        'data.0.views' => 'integer',
                    ])
                );
    }

    /**
     * Test get top streams api sort param
     *
     * @return void
     */
    public function test_top_streams_api_sorting()
    {
        Stream::factory()->create(['viewer_count' => 100]);
        Stream::factory()->create(['viewer_count' => 1000]);
        
        $this->get('/api/streams/top?sort=asc')
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('data.0.views', 100)
            );

        $this->get('/api/streams/top?sort=desc')
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('data.0.views', 1000)
            );
    }
}