<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChecklistController extends Controller
{
    public function index()
    {
        return view('checklist');
    }

    public function submit()
    {
        // Submit the checklist
    }
}
