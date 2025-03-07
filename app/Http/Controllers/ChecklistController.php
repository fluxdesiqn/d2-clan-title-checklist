<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\RaidService;
use App\Services\DungeonService;
use App\Models\Title;
use Illuminate\Support\Str;

class ChecklistController extends Controller
{
    protected $raidService;
    protected $dungeonService;

    public function __construct(RaidService $raidService, DungeonService $dungeonService)
    {
        $this->raidService = $raidService;
        $this->dungeonService = $dungeonService;
    }

    public function index()
    {
        $raids = $this->raidService->getRaids();
        $dungeons = $this->dungeonService->getDungeons();
        return view('checklist', compact('raids', 'dungeons'));
    }

    public function submit(Request $request)
    {
        $guardians = [];
        for ($i = 1; $i <= 6; $i++) {
            $platform = $request->input("platform{$i}");
            $guardian = $request->input("guardian{$i}");
            $code = $request->input("code{$i}");
            if ($platform && $guardian) {
                $guardians[] = [
                    'platform' => $platform,
                    'guardian' => $guardian,
                    'code' => $code,
                ];
            }
        }

        $titleTriumphs = $this->getTitleTriumphs($request->input('activity'));
        dd($titleTriumphs);

        $titles = $this->getGuardianTitles($guardians);
        dd($titles);
    }

    private function getGuardianTitles($guardians)
    {
        $apiKey = env('BUNGIE_API_KEY');
        $token = session('bungie_token');
        $titles = [];

        foreach ($guardians as $guardian) {
            $platform = $guardian['platform'];
            $guardianName = $guardian['guardian'];
            $code = $guardian['code'];

            // Make API call to get membership ID using the new endpoint
            $membershipResponse = Http::withHeaders([
                'X-API-Key' => $apiKey,
                'Authorization' => 'Bearer ' . $token,
            ])->post("https://www.bungie.net/Platform/Destiny2/SearchDestinyPlayerByBungieName/3/", [
                'displayName' => $guardianName,
                'displayNameCode' => $code,
            ]);

            if ($membershipResponse->failed() || empty($membershipResponse->json()['Response'])) {
                continue;
            }

            $membershipId = $membershipResponse->json()['Response'][0]['membershipId'];

            // Make API call to get profile information
            $profileResponse = Http::withHeaders([
                'X-API-Key' => $apiKey,
                'Authorization' => 'Bearer ' . $token,
            ])->get("https://www.bungie.net/Platform/Destiny2/{$platform}/Profile/{$membershipId}?components=900");

            if ($profileResponse->failed() || empty($profileResponse->json()['Response'])) {
                continue;
            }

            $profileData = $profileResponse->json()['Response']['profile']['data'];

            // Extract title information
            $titles[$guardianName] = $profileData['titleRecordHashes'] ?? [];
        }

        return $titles;
    }

    private function getTitleTriumphs($activity)
    {
        $title = Title::where('activity', Str::slug($activity))->first();

        if (!$title) {
            return response()->json(['error' => 'Title not found'], 404);
        }

        $apiKey = env('BUNGIE_API_KEY');
        $token = session('bungie_token');

        // Make API call to get triumphs required for the title
        $triumphsResponse = Http::withHeaders([
            'X-API-Key' => $apiKey,
            'Authorization' => 'Bearer ' . $token,
        ])->get("https://www.bungie.net/Platform/Destiny2/Manifest/DestinyRecordDefinition/{$title->title_hash}/");

        if ($triumphsResponse->failed() || empty($triumphsResponse->json()['Response'])) {
            return response()->json(['error' => 'Failed to fetch triumphs'], 500);
        }

        $triumphsData = $triumphsResponse->json()['Response']['completionInfo']['requirements']['records'];

        $data = [
            'title' => $title->name,
            'triumphs' => $triumphsData,
        ];

        return $data;
    }
}