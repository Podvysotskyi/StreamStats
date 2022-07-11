<?php
 
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class TwitchUserApi extends TwitchApi {
    /**
     * List of scopes for Twitch api token
     */
    private $scopes = ['user:read:follows', 'user:read:email'];

    /**
     * Get Authorization Token for api requests
     * 
     * @return string
     */
    public function getAuthorizationToken(): string {
        return 'Bearer ' . Session::get('auth:token');
    }

    /**
     * Get url to autorize user on Twitch
     * 
     * @return string
     */
    public function getAuthorizeUserUrl(string $state): string {
        return $this->getOAuthUrl('/authorize', [
            'response_type' => 'code',
            'client_id' => $this->getClientId(),
            'redirect_uri' => $this->getRedirectUrl(),
            'scope' => implode(' ', $this->scopes),
            'state' => $state,
        ]);
    }

    /**
     * Get User token from Twitch api
     * 
     * @return string
     */
    public function getUserToken(string $code): string {
        $url = $this->getOAuthUrl('/token');
        $params = [
            'client_id' => $this->getClientId(),
            'client_secret' => $this->getClientSecret(),
            'code' => $code,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $this->getRedirectUrl(),
        ];
        return Http::asForm()->post($url, $params)->throw()->json('access_token');
    }

    /**
     * Get User from Twitch api
     * 
     * @return array
     */
    public function getUser(): array {
        $url = $this->getApiUrl('/helix/users');
        return Http::withHeaders([
            'Authorization' => $this->getAuthorizationToken(),
            'Client-Id' => $this->getClientId(),
        ])->get($url)->throw()->json('data')[0];
    }

    /**
     * Get user followed streams from Twitch Api
     * 
     * @return array
     */
    public function getFollowedStreams(User $user, string $after = null, int $count = 100): array {
        $url = $this->getApiUrl('/helix/streams/followed');
        $params = [
            'user_id' => $user->import_id,
            'after' => $after,
            'first' => $count,
        ];
        return Http::withHeaders([
            'Authorization' => $this->getAuthorizationToken(),
            'Client-Id' => $this->getClientId(),
        ])->asForm()->get($url, $params)->throw()->json();
    }
}