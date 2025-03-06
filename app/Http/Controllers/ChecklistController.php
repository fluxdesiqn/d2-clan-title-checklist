<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChecklistController extends Controller
{
    public function index()
    {
        $raids = $this->getRaids();
        return view('checklist', compact('raids'));
    }

    public function submit()
    {
        // Submit the checklist
    }

    private function getRaids()
    {
        $apiKey = env('BUNGIE_API_KEY');
        $response = Http::withHeaders([
            'X-API-Key' => $apiKey,
        ])->get('https://www.bungie.net/Platform/Destiny2/Manifest/DestinyActivityDefinition/');

        $data = $response->json();

        // Filter the data to get only raids
        $raids = collect($data['Response']['data']['activities'])->filter(function ($activity) {
            return $activity['activityTypeHash'] == 'raid';
        });

        return $raids;
    }
}
