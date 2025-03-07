<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class DungeonService
{
    public function getDungeons()
    {
        $apiKey = env('BUNGIE_API_KEY');
        $token = Session::get('bungie_token');

        $manifestData = $this->getManifest($apiKey, $token);
        if (empty($manifestData)) {
            return [];
        }

        $dungeonActivityTypeHash = $this->getDungeonActivityTypeHash($manifestData);
        if (!$dungeonActivityTypeHash) {
            return [];
        }

        $dungeons = $this->getDungeonActivities($manifestData, $dungeonActivityTypeHash);

        return $dungeons;
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

    private function getDungeonActivityTypeHash($manifestData)
    {
        $activityModeDefinitionPath = $manifestData['Response']['jsonWorldComponentContentPaths']['en']['DestinyActivityModeDefinition'];
        $activityModeDefinitionUrl = 'https://www.bungie.net' . $activityModeDefinitionPath;
        $activityModeResponse = Http::get($activityModeDefinitionUrl);

        if ($activityModeResponse->failed()) {
            return null;
        }

        $activityModeData = $activityModeResponse->json();

        foreach ($activityModeData as $mode) {
            if (isset($mode['displayProperties']['name']) && $mode['displayProperties']['name'] === 'Dungeon') {
                return $mode['hash'];
            }
        }

        return null;
    }

    private function getDungeonActivities($manifestData, $dungeonActivityTypeHash)
    {
        $activityDefinitionPath = $manifestData['Response']['jsonWorldComponentContentPaths']['en']['DestinyActivityDefinition'];
        $activityDefinitionUrl = 'https://www.bungie.net' . $activityDefinitionPath;
        $activityResponse = Http::get($activityDefinitionUrl);

        if ($activityResponse->failed()) {
            return [];
        }

        $activityData = $activityResponse->json();

        $dungeons = collect($activityData)->filter(function ($activity) use ($dungeonActivityTypeHash) {
            return isset($activity['activityTypeHash']) &&
                   $activity['activityTypeHash'] == $dungeonActivityTypeHash &&
                   isset($activity['tier']) && $activity['tier'] == 0 &&
                   isset($activity['matchmaking']['minParty']) && $activity['matchmaking']['minParty'] == 1 &&
                   isset($activity['matchmaking']['maxParty']) && $activity['matchmaking']['maxParty'] == 3;
        })->map(function ($activity) {
            // Remove anything after ':'
            $name = explode(':', $activity['displayProperties']['name'])[0];
            return trim($name);
        })->unique()->values()->all();

        return $dungeons;
    }
}