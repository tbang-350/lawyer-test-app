@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-bold mb-4">Legal Appointment Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Calendar will go here --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold mb-4">Calendar</h2>
            <div id="calendar">
                {{-- The calendar will be rendered here by JavaScript --}}
            </div>
        </div>

        {{-- Stats or other dashboard elements will go here --}}
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold mb-4">Stats Summary</h2>
            <p>Upcoming appointments: 5</p>
            <p>Completed appointments: 10</p>
            {{-- More stats can be added here --}}
        </div>
    </div>

    {{-- Appointment Modal will be here (hidden by default) --}}
    <div id="appointment-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Appointment Details</h3>
                <div class="mt-2" id="modal-body">
                    {{-- Appointment details will be loaded here by JavaScript --}}
                </div>
                <div class="items-center px-4 py-3">
                    <button id="close-modal" class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
