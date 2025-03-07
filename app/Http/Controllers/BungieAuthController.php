<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class BungieAuthController extends Controller
{
    public function redirectToBungie()
    {
        $clientId = env('BUNGIE_CLIENT_ID');
        $redirectUri = route('bungie.redirect');

        // Generate a random 8-digit string for state
        $state = bin2hex(random_bytes(4));
        Session::put('bungie_state', $state);

        $url = "https://www.bungie.net/en/OAuth/Authorize?client_id={$clientId}&response_type=code&redirect_uri={$redirectUri}&state={$state}";

        return redirect($url);
    }

    public function handleBungieCallback(Request $request)
    {
        // Data from Bungie
        $code = $request->query('code');
        $state = $request->query('state');
        $storedState = Session::get('bungie_state');

        // Check if the state matches
        if ($state !== $storedState) {
            return redirect('/')->with('error', 'Invalid state parameter');
        }

        // Data from .env
        $clientId = env('BUNGIE_CLIENT_ID');
        $clientSecret = env('BUNGIE_CLIENT_SECRET');

        $response = Http::asForm()->withHeaders([
            'Authorization' => 'Basic ' . base64_encode($clientId . ':' . $clientSecret),
        ])->post('https://www.bungie.net/platform/app/oauth/token/', [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'state' => $storedState,
        ]);

        if ($response->failed()) {
            return redirect('/')->with('error', 'Failed to get OAuth token from Bungie');
        }

        $token = $response->json();

        session()->put('bungie_token', $token);
        session()->put('bungie_membership_id', $token['membership_id'] ?? null);

        return redirect('checklist')->with('success', 'Logged in with Bungie!');
    }
}