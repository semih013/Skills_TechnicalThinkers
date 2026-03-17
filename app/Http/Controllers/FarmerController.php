<?php

namespace App\Http\Controllers;

use App\Models\Farmer;

class FarmerController extends Controller
{
    public function index()
    {
        $farmers = Farmer::latest()->get();

        return view('farmers.index', compact('farmers'));
    }
}
