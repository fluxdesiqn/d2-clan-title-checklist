<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class RaidService
{
    public function getRaids()
    {
        $apiKey = env('BUNGIE_API_KEY');
        $token = Session::get('bungie_token');

        $manifestData = $this->getManifest($apiKey, $token);
        if (empty($manifestData)) {
            return [];
        }

        $raidActivityTypeHash = $this->getRaidActivityTypeHash($manifestData);
        if (!$raidActivityTypeHash) {
            return [];
        }

        $raids = $this->getRaidActivities($manifestData, $raidActivityTypeHash);

        return $raids;
    }

    private function getManifest($apiKey, $token)
    {
        $manifestResponse = Http::withHeaders([
            'X-API-Key' => $apiKey,
            'Authorization' => 'Bearer ' . $token,
        ])->get('https://www.bungie.net/Platform/Destiny2/Manifest/');

        if ($manifestResponse->failed()) {
            return [];
        }

        return $manifestResponse->json();
    }

    private function getRaidActivityTypeHash($manifestData)
    {
        $activityModeDefinitionPath = $manifestData['Response']['jsonWorldComponentContentPaths']['en']['DestinyActivityModeDefinition'];
        $activityModeDefinitionUrl = 'https://www.bungie.net' . $activityModeDefinitionPath;
        $activityModeResponse = Http::get($activityModeDefinitionUrl);

        if ($activityModeResponse->failed()) {
            return null;
        }

        $activityModeData = $activityModeResponse->json();

        foreach ($activityModeData as $mode) {
            if (isset($mode['displayProperties']['name']) && $mode['displayProperties']['name'] === 'Raid') {
                return $mode['hash'];
            }
        }

        return null;
    }

    private function getRaidActivities($manifestData, $raidActivityTypeHash)
    {
        $activityDefinitionPath = $manifestData['Response']['jsonWorldComponentContentPaths']['en']['DestinyActivityDefinition'];
        $activityDefinitionUrl = 'https://www.bungie.net' . $activityDefinitionPath;
        $activityResponse = Http::get($activityDefinitionUrl);

        if ($activityResponse->failed()) {
            return [];
        }

        $activityData = $activityResponse->json();

        $raids = collect($activityData)->filter(function ($activity) use ($raidActivityTypeHash) {
            return isset($activity['activityTypeHash']) &&
                   $activity['activityTypeHash'] == $raidActivityTypeHash &&
                   isset($activity['tier']) && $activity['tier'] == 0 &&
                   isset($activity['matchmaking']['minParty']) && $activity['matchmaking']['minParty'] == 1 &&
                   isset($activity['matchmaking']['maxParty']) && $activity['matchmaking']['maxParty'] == 6;
        })->mapToGroups(function ($activity) {
            // Remove anything after ':'
            $name = explode(':', $activity['displayProperties']['name'])[0];
            return [$name => $activity];
        })->map(function ($activities) {
            return $activities->unique()->values()->all();
        })->all();

        return $raids;
    }
}