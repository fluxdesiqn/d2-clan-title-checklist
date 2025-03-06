<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BungieAuthController extends Controller
{
    public function redirectToBungie()
    {
        $clientId = env('BUNGIE_CLIENT_ID');
        $redirectUri = route('bungie.redirect');
        $url = "https://www.bungie.net/en/OAuth/Authorize?client_id={$clientId}&response_type=code&redirect_uri={$redirectUri}";

        return redirect($url);
    }

    public function handleBungieCallback(Request $request)
    {
        //Data from Bungie
        $code = $request->query('code');
        $codeExpires = $request->query('expires_in');
        $refreshToken = $request->query('refresh_token');
        $refreshTokenExpires = $request->query('refresh_expires_in');
        $membershipId = $request->query('membership_id');

        //Data from .env
        $clientId = env('BUNGIE_CLIENT_ID');
        $apiKey = env('BUNGIE_API_KEY');
        $redirectUri = route('bungie.redirect');

        $response = Http::asForm()->post('https://www.bungie.net/platform/app/oauth/token/', [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'client_id' => $clientId,
            'client_secret' => $apiKey,
            'redirect_uri' => $redirectUri
        ]);

        $token = $response->json();

        session()->put('bungie_token', $token);
        session()->put('bungie_membership_id', $membership_id);

        return redirect('checklist')->with('success', 'Logged in with Bungie!');
    }
}