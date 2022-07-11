<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginTwitchCallbackRequest;
use App\Models\User;
use App\Services\TwitchUserApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class AuthControlller extends Controller
{
    /** 
     * Redirect to Twitch OAuth login page
     */
    public function login(Request $request, TwitchUserApi $api) {
        $state = Str::random(40);
        $request->session()->put('auth:state', $state);

        return redirect()->away($api->getAuthorizeUserUrl($state));
    }

    /**
     * Handle an incoming Twitch OAuth login callback
     */
    public function callback(LoginTwitchCallbackRequest $request, TwitchUserApi $api) {
        $code = $request->get('code');
        Session::put('auth:code', $code);

        // Generate user token from Twitch api
        $token = $api->getUserToken($code);
        Session::put('auth:token', $token);

        // Get User data from Twitch api
        $twitchUser = $api->getUser();

        // Create user model
        $user = User::firstOrCreate([
            'import_id' => $twitchUser['id'],
        ], [
            'name' => $twitchUser['login'],
            'email' => $twitchUser['email'],
        ]);

        Auth::loginUsingId($user->id);

        return redirect()->to('/');
    }

    /** 
     * Handle logout request
     */
    public function logout() {
        Auth::logout();
        return redirect()->back();
    }

    /** 
     * Get auth user data
     */
    public function user(Request $request) {
        return $request->user()->only(['name', 'email']);
    }
}
