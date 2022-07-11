<?php
 
namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class TwitchClientApi extends TwitchApi {
    /**
     * Get Authorization Token for api requests
     * 
     * @return string
     */
    public function getAuthorizationToken(): string {
        return 'Bearer ' . Cache::remember('api:token', 86400, function() {
            $url = $this->getOAuthUrl('/token');
            $params = [
                'client_id' => $this->getClientId(),
                'client_secret' => $this->getClientSecret(),
                'grant_type' => 'client_credentials',
            ];
            return Http::asForm()->post($url, $params)->json('access_token');
        });
    }

    /**
     * Get Streams from Twitch api
     * 
     * @return array
     */
    public function getStreams(string $after = null, int $first = 100): array {
        $url = $this->getApiUrl('/helix/streams');
        $params = [
            'first' => $first,
            'after' => $after,
        ];
        return Http::asForm()->withHeaders([
            'Authorization' => $this->getAuthorizationToken(),
            'Client-Id' => $this->getClientId(),
        ])->get($url, $params)->throw()->json();
    }

    /**
     * Get Stream Tags from Twitch api
     * 
     * @return array
     */
    public function getStreamTags(string $after = null, int $first = 100): array {
        $url = $this->getApiUrl('/helix/tags/streams');
        $params = [
            'first' => $first,
            'after' => $after,
        ];
        return Http::asForm()->withHeaders([
            'Authorization' => $this->getAuthorizationToken(),
            'Client-Id' => $this->getClientId(),
        ])->get($url, $params)->throw()->json();
    }
}