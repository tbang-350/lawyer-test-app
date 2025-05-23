@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">Legal Appointment Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Calendar will go here --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold mb-4">Calendar</h2>
            <button id="add-appointment-button" class="px-4 py-2 mb-4 bg-teal-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500">
                Add Appointment
            </button>
            <div id="calendar">
                {{-- The calendar will be rendered here by JavaScript --}}
            </div>
        </div>


        {{-- Stats or other dashboard elements will go here --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold mb-4">Stats Summary</h2>
            <p>Upcoming appointments: 5</p>
            <ul>
                <li>Total cases handled: <span id="total-cases">10</span></li>
                <li>Active lawyers: <span id="active-lawyers">3</span></li>
                <li>Upcoming appointments: <span id="upcoming-appointments">5</span></li>
                <li>Scheduled reminders summary: <span id="scheduled-reminders">Reminders set for 8 upcoming appointments.</span></li>
            </ul>
        </div>
    </div>

    {{-- Appointment Modal will be here (hidden by default) --}}
    <div id="appointment-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Appointment Details</h3>
                <div class="mt-2" id="modal-body">
                    <form id="appointment-form">
                        <input type="hidden" id="appointment-id">
                        <div class="mb-4">
                            <label for="appointment-title" class="block text-gray-700 text-sm font-bold mb-2">Title:</label>
                            <input type="text" id="appointment-title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label for="assigned-lawyers" class="block text-gray-700 text-sm font-bold mb-2">Assign Lawyers:</label>
                            <select multiple id="assigned-lawyers" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                {{-- Options will be loaded by JavaScript --}}
                            </select>
                        </div>
                </div>
                <div class="items-center px-4 py-3">
                    <button id="close-modal" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        Close
                    </button>
                </div>
            </div>
        </div>    </div>

</div>
@endsection
