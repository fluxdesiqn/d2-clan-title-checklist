<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ChecklistController extends Controller
{
    public function index()
    {
        $raids = $this->getRaids();
        return view('checklist', compact('raids'));
    }

    public function submit(Request $request)
    {
        // Submit the checklist
    }

    private function getRaids()
    {
        $apiKey = env('BUNGIE_API_KEY');
        $token = Session::get('bungie_token');

        // Step 1: Get the manifest
        $manifestResponse = Http::withHeaders([
            'X-API-Key' => $apiKey,
            'Authorization' => 'Bearer ' . $token,
        ])->get('https://www.bungie.net/Platform/Destiny2/Manifest/');

        if ($manifestResponse->failed()) {
            return [];
        }

        $manifestData = $manifestResponse->json();

        // Step 2: Extract the URL for DestinyActivityDefinition
        $activityDefinitionPath = $manifestData['Response']['jsonWorldComponentContentPaths']['en']['DestinyActivityDefinition'];

        // Step 3: Fetch the actual data
        $activityDefinitionUrl = 'https://www.bungie.net' . $activityDefinitionPath;
        $activityResponse = Http::get($activityDefinitionUrl);

        if ($activityResponse->failed()) {
            return [];
        }

        $activityData = $activityResponse->json();

        // Step 4: Filter the data to get only raids
        $raids = collect($activityData)->filter(function ($activity) {
            return isset($activity['activityTypeHash']) && $activity['activityTypeHash'] == 'raid';
        });

        dd($raids);

        return $raids;
    }
}
