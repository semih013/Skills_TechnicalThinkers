<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use Illuminate\Http\Request;

class FarmerController extends Controller
{
    public function index()
    {
        $farmers = Farmer::latest()->get();

        return view('farmers.index', compact('farmers'));
    }

    public function create()
    {
        return view('farmers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:20', 'unique:farmers,phone_number'],
            'region' => ['required', 'string', 'max:255'],
            'village' => ['nullable', 'string', 'max:255'],
            'preferred_language' => ['required', 'string', 'max:255'],
            'wants_sms' => ['nullable'],
        ]);

        $data['wants_sms'] = $request->has('wants_sms');

        Farmer::create($data);

        return redirect()->route('farmers.index')->with('success', 'Farmer created successfully.');
    }

    public function edit(Farmer $farmer)
    {
        return view('farmers.edit', compact('farmer'));
    }

    public function update(Request $request, Farmer $farmer)
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:20', 'unique:farmers,phone_number,' . $farmer->id],
            'region' => ['required', 'string', 'max:255'],
            'village' => ['nullable', 'string', 'max:255'],
            'preferred_language' => ['required', 'string', 'max:255'],
            'wants_sms' => ['nullable'],
        ]);

        $data['wants_sms'] = $request->has('wants_sms');

        $farmer->update($data);

        return redirect()->route('farmers.index')->with('success', 'Farmer updated successfully.');
    }

    public function destroy(Farmer $farmer)
    {
        $farmer->delete();

        return redirect()->route('farmers.index')->with('success', 'Farmer deleted successfully.');
    }
}
