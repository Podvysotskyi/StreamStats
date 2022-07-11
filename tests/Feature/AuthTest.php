<?php

namespace Tests\Feature;

use App\Http\Middleware\SessionExpired;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test get user api request
     *
     * @return void
     */
    public function test_get_user_response()
    {
        $user = User::factory()->create();

        $this->withoutMiddleware(SessionExpired::class);

        $this->actingAs($user)->get('/api/user')
            ->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->hasAll(['name', 'email'])
                    ->whereAll([
                        'name' => $user->name,
                        'email' => $user->email,
                    ])
            );
    }

     /**
     * Test twitch login request
     *
     * @return void
     */
    public function test_twitch_login_response()
    {
        $this->get('/login/twitch')
            ->assertRedirect()
            ->assertSessionHas('auth:state');
    }

    /**
     * Test logout request
     *
     * @return void
     */
    public function test_logout_response()
    {
        $user = User::factory()->create();

        $this->withoutMiddleware(SessionExpired::class);

        $this->actingAs($user)->get('/logout')
            ->assertRedirect();
    }

    /**
     * Test twitch login callback request
     *
     * @return void
     */
    public function test_twitch_login_callback_response()
    {
        Http::fake([
            '*/token' => Http::response(['access_token' => 'test_token'], 200),
            '*/helix/users*' => Http::response(['data' => [['id' => 1, 'login' => 'test_login', 'email' => 'test_email']]], 200),
        ]);

        $this->withSession(['auth:state' => 'test_state'])
            ->get('/login/twitch/callback?code=test_code&state=test_state&scope=test_scope')
            ->assertRedirect('/')
            ->assertSessionHas('auth:code', 'test_code')
            ->assertSessionHas('auth:token', 'test_token');

        $this->assertDatabaseHas('users', [
            'name' => 'test_login',
            'email' => 'test_email',
        ]);
    }
}