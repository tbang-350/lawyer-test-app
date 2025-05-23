<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Firm;

class FirmController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $firms = Firm::all();

        return response()->json($firms);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'contact_information' => 'nullable|string',
            'default_notification_preferences' => 'nullable|json',
        ]);

        $firm = Firm::create($validatedData);

        return response()->json($firm, 201);


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $firm = Firm::findOrFail($id);

        return response()->json($firm);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $firm = Firm::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'contact_information' => 'nullable|string',
            'default_notification_preferences' => 'nullable|json',
        ]);

        $firm->update($validatedData);

        return response()->json($firm);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $firm = Firm::findOrFail($id);
        $firm->delete();
        return response()->json(null, 204);
    }
}
