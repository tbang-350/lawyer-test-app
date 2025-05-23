<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lawyer;
use App\Models\Firm;

class LawyerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Lawyer::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // This method is typically for showing a form, not needed for an API
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'firm_id' => 'required|exists:firms,id',
            'name' => 'required',
            'email' => 'required|email|unique:lawyers,email',
        ]);

        $lawyer = Lawyer::create($request->only([
            'firm_id', 'name', 'email', 'phone', 'specialization', 'notification_preferences'
        ]));

        return response()->json($lawyer, 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Lawyer::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // This method is typically for showing a form, not needed for an API
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $lawyer = Lawyer::findOrFail($id);

        $request->validate([
            'firm_id' => 'exists:firms,id',
            'email' => 'email|unique:lawyers,email,' . $lawyer->id,
        ]);

        $lawyer->update($request->only(['firm_id', 'name', 'email', 'phone', 'specialization', 'notification_preferences']));

        return response()->json($lawyer, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $lawyer = Lawyer::findOrFail($id);
        $lawyer->delete();
        return response()->json(null, 204);
    }
}
