<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        dd($request->all());
    }
}