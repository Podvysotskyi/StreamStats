<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\TwitchUserApi;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class TwitchUserApiServiceTest extends TestCase
{
    /**
     * Test get authorization method
     *
     * @return void
     */
    public function test_get_authorization_token()
    {
        $api = $this->createApi();

        Session::put('auth:token', 'test_token');

        $this->assertEquals($api->getAuthorizationToken(), 'Bearer test_token');
    }

    /**
     * Test get authorize user url method
     *
     * @return void
     */
    public function test_get_authorize_user_url()
    {
        $api = $this->createApi();
        $url = $api->getAuthorizeUserUrl('test_state');
        $this->assertStringContainsString('test_state', $url);
        $this->assertStringContainsString('test_client_id', $url);
        $this->assertStringContainsString('test_redirect', $url);
    }

    /**
     * Test get user token method
     *
     * @return void
     */
    public function test_get_user_token()
    {
        $api = $this->createApi();

        Http::fake([
            '*/token*' => Http::response(['access_token' => 'test_access_token'], 200),
        ]);

        $this->assertEquals($api->getUserToken('test_code'), 'test_access_token');
    }

    /**
     * Test get stream tags method
     *
     * @return void
     */
    public function test_get_user()
    {
        $api = $this->createApi();

        Session::put('auth:token', 'test_token');

        Http::fake([
            '*/helix/users*' => Http::response(['data' => [['test_user']]], 200),
        ]);

        $this->assertEquals($api->getUser(), ['test_user']);
    }

    /**
     * Test get followed streams method
     *
     * @return void
     */
    public function test_get_followed_streams()
    {
        $api = $this->createApi();

        Session::put('auth:token', 'test_token');

        Http::fake([
            '*/helix/streams/followed*' => Http::response([], 200),
        ]);

        $user = User::factory()->make();
        $this->assertEquals($api->getFollowedStreams($user), []);
    }

    /**
     * Create test Twitch client api service
     * 
     * @return TwitchUserApi
     */
    private function createApi(): TwitchUserApi {
        return new TwitchUserApi([
            'client_id' => 'test_client_id',
            'client_secret' => 'test_client_secret',
            'redirect' => 'test_redirect',
            'api_url' => 'test_api_url',
            'oauth2_url' => 'test_oauth2_url',
        ]);
    }
}
