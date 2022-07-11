<?php

namespace Tests\Unit;

use App\Services\TwitchClientApi;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TwitchClientApiServiceTest extends TestCase
{
    /**
     * Test get authorization method
     *
     * @return void
     */
    public function test_get_authorization_token()
    {
        $api = $this->createApi();

        Http::fake([
            '*/token*' => Http::response(['access_token' => 'test_access_token'], 200),
        ]);

        $this->assertEquals($api->getAuthorizationToken(), 'Bearer test_access_token');
        $this->assertEquals(Cache::get('api:token'), 'test_access_token');
    }

    /**
     * Test get streams method
     *
     * @return void
     */
    public function test_get_streams()
    {
        $api = $this->createApi();

        Cache::put('api:token', 'test_token');

        Http::fake([
            '*/helix/streams*' => Http::response(['data' => []], 200),
        ]);

        $this->assertEquals($api->getStreams(), ['data' => []]);
    }

    /**
     * Test get stream tags method
     *
     * @return void
     */
    public function test_get_stream_tags()
    {
        $api = $this->createApi();

        Cache::put('api:token', 'test_token');

        Http::fake([
            '*/helix/tags/streams*' => Http::response(['data' => []], 200),
        ]);

        $this->assertEquals($api->getStreamTags(), ['data' => []]);
    }

    /**
     * Create test Twitch client api service
     * 
     * @return TwitchClientApi
     */
    private function createApi(): TwitchClientApi {
        return new TwitchClientApi([
            'client_id' => 'test_client_id',
            'client_secret' => 'test_client_secret',
            'redirect' => 'test_redirect',
            'api_url' => 'test_api_url',
            'oauth2_url' => 'test_oauth2_url',
        ]);
    }
}
