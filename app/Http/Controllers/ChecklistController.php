<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\RaidService;

class ChecklistController extends Controller
{
    protected $raidService;

    public function __construct(RaidService $raidService)
    {
        $this->raidService = $raidService;
    }

    public function index()
    {
        $raids = $this->raidService->getRaids();
        return view('checklist', compact('raids'));
    }

    public function submit(Request $request)
    {
        $guardians = [];
        for ($i = 1; $i <= 6; $i++) {
            $platform = $request->input("platform{$i}");
            $guardian = $request->input("guardian{$i}");
            if ($platform && $guardian) {
                $guardians[] = [
                    'platform' => $platform,
                    'guardian' => $guardian,
                ];
            }
        }

        $titles = $this->getGuardianTitles($guardians);

        dd($titles);
    }

    private function getGuardianTitles($guardians)
    {
        $apiKey = env('BUNGIE_API_KEY');
        $titles = [];

        foreach ($guardians as $guardian) {
            $platform = $guardian['platform'];
            $guardianName = $guardian['guardian'];

            // Make API call to get membership ID
            $membershipResponse = Http::withHeaders([
                'X-API-Key' => $apiKey,
            ])->get("https://www.bungie.net/Platform/Destiny2/SearchDestinyPlayer/{$platform}/{$guardianName}/");

            if ($membershipResponse->failed() || empty($membershipResponse->json()['Response'])) {
                continue;
            }

            $membershipId = $membershipResponse->json()['Response'][0]['membershipId'];

            // Make API call to get profile information
            $profileResponse = Http::withHeaders([
                'X-API-Key' => $apiKey,
            ])->get("https://www.bungie.net/Platform/Destiny2/{$platform}/Profile/{$membershipId}/?components=900");

            if ($profileResponse->failed() || empty($profileResponse->json()['Response'])) {
                continue;
            }

            dd($profileResponse->json());

            $profileData = $profileResponse->json()['Response']['profile']['data'];

            // Extract title information
            $titles[$guardianName] = $profileData['titleRecordHashes'] ?? [];
        }

        return $titles;
    }
}