<?php

namespace Tests\Feature;

use App\Models\Stream;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class TopGamesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test get top games api request
     *
     * @return void
     */
    public function test_top_games_api_response()
    {
        Stream::factory()->count(5)->create();

        $response = $this->get('/api/games/top');

        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('data', 5)
                    ->hasAll([
                        'data.0.name',
                        'data.0.views',
                        'data.0.streams',
                    ])->whereAllType([
                        'data.0.name' => 'string',
                        'data.0.views' => 'integer',
                        'data.0.streams' => 'integer',
                    ])
            );
    }
}