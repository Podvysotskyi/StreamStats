<?php
 
namespace App\Services;

abstract class TwitchApi {
    /**
     * Get Twitch config
     */
    private $config;
    
    public function __construct(array $config) {
        $this->config = $config;
    }

    /**
     * Get redirect url for twitch authorization
     * 
     * @return string
     */
    public function getRedirectUrl(): string {
        return url($this->config['redirect']);
    }

    /**
     * Get Twitch Client Id value
     * 
     * @return string
     */
    public function getClientId(): string {
        return $this->config['client_id'];
    }

    /**
     * Get Twitch Client Secret value
     * 
     * @return string
     */
    public function getClientSecret(): string {
        return $this->config['client_secret'];
    }

    /**
     * Build url for Twitch api
     * 
     * @return string
     */
    public function getApiUrl(string $url, array $params = []): string {
        return $this->config['api_url'] . $url . (count($params) > 0 ? '?' . http_build_query($params) : '');
    }

    /**
     * Build url for Twitch OAuth api
     * 
     * @return string
     */
    public function getOAuthUrl(string $url, array $params = []): string {
        return $this->config['oauth2_url'] . $url . (count($params) > 0 ? '?' . http_build_query($params) : '');
    }

    /**
     * Get Authorization Token for api requests
     * 
     * @return string
     */
    abstract protected function getAuthorizationToken(): string;
}