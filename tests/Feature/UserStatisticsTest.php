<?php

namespace Tests\Feature;

use Carbon\Carbon;
use App\Models\Stream;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UserStatisticsTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * Test get users statistics api request
     *
     * @return void
     */
    public function test_users_statistics_api_response()
    {
        $user = User::factory()->create();

        $stream = Stream::factory()->create(['viewer_count' => 2]);
        Stream::factory()->count(4)->create(['viewer_count' => 5]);

        Http::fake([
            '*/helix/streams/followed*' => Http::response(['data' => [
                ['id' => $stream->import_id, 'title' => $stream->title, 'game_name' => $stream->game_name, 'tag_ids' => [], 'viewer_count' => $stream->viewer_count],
                ['id' => 'test', 'title' => 'test_title', 'game_name' => 'test_name', 'tag_ids' => [], 'viewer_count' => 1],
            ]], 200),
        ]);

        $this->actingAs($user)->get('/api/user/statistics')
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('followed_top_streams', 1)
                    ->has('shared_stream_tags', 0)
                    ->where('lowest_followed_stream_for_top', 1)
            );
    }
}