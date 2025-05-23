<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Lawyer;
use App\Models\Firm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $appointments = Appointment::with('lawyers')->get();
        return response()->json($appointments);

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
        $validator = Validator::make($request->all(), [
            'firm_id' => 'required|exists:firms,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'time' => 'nullable',
            'location' => 'nullable|string|max:255',
            'reminder_settings' => 'nullable|json',
            'lawyer_ids' => 'nullable|array',
            'lawyer_ids.*' => 'exists:lawyers,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $appointment = Appointment::create([
            'firm_id' => $request->firm_id,
            'title' => $request->title,
            'description' => $request->description,
            'date' => $request->date,
            'time' => $request->time,
            'location' => $request->location,
            'reminder_settings' => $request->reminder_settings,
        ]);

        if ($request->has('lawyer_ids')) {
            $appointment->lawyers()->attach($request->lawyer_ids);
        }
        return response()->json($appointment->load('lawyers'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $appointment = Appointment::with('lawyers')->findOrFail($id);
        return response()->json($appointment);
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
        $appointment = Appointment::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'sometimes|required|date',
            'time' => 'nullable',
            'location' => 'nullable|string|max:255',
            'reminder_settings' => 'nullable|json',
            'lawyer_ids' => 'nullable|array',
            'lawyer_ids.*' => 'exists:lawyers,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $appointment->update($request->except('lawyer_ids'));

        if ($request->has('lawyer_ids')) {
            $appointment->lawyers()->sync($request->lawyer_ids);
        }

        return response()->json($appointment->load('lawyers'));
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Get dashboard statistics.
     */
    public function dashboardStats()
    {
        $upcomingAppointments = Appointment::where('date', '>=', now()->toDateString())->count();
        $totalCasesHandled = Appointment::count(); // Assuming each appointment is a case for this stat
        $activeLawyers = Lawyer::count(); // Assuming all lawyers in the table are active
        // This is a simplified summary of scheduled reminders.
        // A more detailed summary would require iterating through appointments and their reminder settings.
        $scheduledRemindersSummary = [
            'upcoming' => $upcomingAppointments,
            'total' => $totalCasesHandled,
        ];

        return response()->json([
            'upcomingAppointments' => $upcomingAppointments,
            'totalCasesHandled' => $totalCasesHandled,
            'activeLawyers' => $activeLawyers,
            'scheduledRemindersSummary' => $scheduledRemindersSummary,
        ]);
    }

}
