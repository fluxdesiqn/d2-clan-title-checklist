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
        // Data from Bungie
        $code = $request->query('code');
        $apiKey = env('BUNGIE_API_KEY');

        $response = Http::asForm()->withHeaders([
            'Authorization' => 'Basic ' . $apiKey,
        ])->post('https://www.bungie.net/platform/app/oauth/token/', [
            'grant_type' => 'authorization_code',
            'code' => $code,
        ]);

        $token = $response->json();

        dd($token);

        session()->put('bungie_token', $token);
        session()->put('bungie_membership_id', $request->query('membership_id'));

        return redirect('checklist')->with('success', 'Logged in with Bungie!');
    }
}