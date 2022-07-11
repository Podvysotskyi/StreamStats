<?php

namespace Tests\Feature;

use Carbon\Carbon;
use App\Models\Stream;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class StreamStatisticsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test get streams statistics api request
     *
     * @return void
     */
    public function test_streams_statistics_api_response()
    {
        Stream::factory()->count(2)->create([
            'started_at' => Carbon::today(),
            'viewer_count' => 3,
        ]);
        Stream::factory()->create([
            'started_at' => Carbon::yesterday(),
            'viewer_count' => 6,
        ]);

        $this->get('/api/streams/statistics')
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('data', 2)
                    ->where('median_viewers_count', 4)
                    ->hasAll(['data.0.date', 'data.0.streams', 'data.0.time'])
                    ->whereAllType([
                        'data.0.date' => 'string',
                        'data.0.time' => 'string',
                        'data.0.streams' => 'integer',
                    ])->whereAll([
                        'data.0.streams' => 2,
                        'data.1.streams' => 1,
                    ])
            );
    }
}
