import 'fullcalendar/main.css';
import { Calendar } from 'fullcalendar';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var appointmentModal = new bootstrap.Modal(document.getElementById('appointmentModal'));
    var modalTitle = document.getElementById('appointmentModalLabel');
    var modalBody = document.getElementById('appointmentModalBody');
    var saveAppointmentBtn = document.getElementById('saveAppointmentBtn'); // Assuming you add this button to your modal
    var deleteAppointmentBtn = document.getElementById('deleteAppointmentBtn'); // Assuming you add this button to your modal
    var dashboardStats = document.getElementById('dashboardStats'); // Assuming you have a div with this ID for stats

    let currentAppointmentId = null; // To keep track of the appointment being edited

    // Helper function to clear and populate the modal form
    var calendar = new Calendar(calendarEl, {
        plugins: [ dayGridPlugin, timeGridPlugin, interactionPlugin ],
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: '/api/appointments', // Fetch appointments from this endpoint
        dateClick: function(info) {
            currentAppointmentId = null; // Clear current appointment ID for new appointment
            modalTitle.textContent = 'Add New Appointment';
            clearAndPopulateModalForm({ date: info.dateStr });
            appointmentModal.show();
        },
        eventClick: function(info) {
            // Open modal to display event details
            fetch(`/api/appointments/${info.event.id}`)
                .then(response => response.json())
                .then(appointment => {
                    currentAppointmentId = appointment.id;
                    modalTitle.textContent = 'Edit Appointment';
                    clearAndPopulateModalForm(appointment);
                    appointmentModal.show();
                })
                .catch(error => {
                    console.error('Error fetching appointment details:', error);
                });
        },
        eventDidMount: function(info) {
            // Example: Add background color based on assigned lawyers or other criteria
            // if (info.event.extendedProps.lawyers && info.event.extendedProps.lawyers.length > 0) {
            //     info.el.style.backgroundColor = '#009688'; // Teal
            // }
        },
        eventDrop: function(info) {
            // Handle event drop - update appointment date/time
            const appointmentId = info.event.id;
            const newDate = info.event.start.toISOString().slice(0, 10);
            const newTime = info.event.start.toTimeString().slice(0, 5);

            updateAppointmentDateTime(appointmentId, newDate, newTime);
        }
    });

    calendar.render();

    // Call fetchDashboardStats after the calendar is initialized
    fetchDashboardStats();

    // Function to clear the modal body and populate it with the form
    function clearAndPopulateModalForm(appointmentData = {}) {
        modalBody.innerHTML = `
            <form id="appointmentForm">
                ${appointmentData.id ? '<input type="hidden" name="_method" value="PUT">' : ''}
                <input type="hidden" name="date" value="${appointmentData.date || ''}">
                <div class="mb-3">
                    <label for="appointmentTitle" class="form-label">Title</label>
                    <input type="text" class="form-control" id="appointmentTitle" name="title" value="${appointmentData.title || ''}" required>
                </div>
                <div class="mb-3">
                    <label for="appointmentTime" class="form-label">Time</label>
                    <input type="time" class="form-control" id="appointmentTime" name="time" value="${appointmentData.time || ''}">
                </div>
                <div class="mb-3">
                    <label for="appointmentDescription" class="form-label">Description</label>
                    <textarea class="form-control" id="appointmentDescription" name="description">${appointmentData.description || ''}</textarea>
                </div>
                 <div class="mb-3">
                    <label for="appointmentLocation" class="form-label">Location</label>
                    <input type="text" class="form-control" id="appointmentLocation" name="location" value="${appointmentData.location || ''}">
                </div>
                 <div class="mb-3">
                    <label for="assignedLawyers" class="form-label">Assigned Lawyers</label>
                    <select multiple class="form-control" id="assignedLawyers" name="lawyers[]">
                        {{-- Options will be loaded dynamically --}}
                    </select>
                </div>
                <div class="mb-3">
                    <label for="reminderSettings" class="form-label">Reminder Settings</label>
                    <select multiple class="form-control" id="reminderSettings" name="reminder_settings[]">
                        <option value="1 day before" ${appointmentData.reminder_settings && JSON.parse(appointmentData.reminder_settings).includes('1 day before') ? 'selected' : ''}>1 day before</option>
                        <option value="1 hour before" ${appointmentData.reminder_settings && JSON.parse(appointmentData.reminder_settings).includes('1 hour before') ? 'selected' : ''}>1 hour before</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" id="saveAppointmentBtn">${appointmentData.id ? 'Update Appointment' : 'Save Appointment'}</button>
                ${appointmentData.id ? '<button type="button" class="btn btn-danger ms-2" id="deleteAppointmentBtn">Delete Appointment</button>' : ''}
            </form>
        `;

        fetch('/api/lawyers')
            .then(response => response.json())
            .then(lawyers => {
                const lawyerSelect = document.getElementById('assignedLawyers');
                lawyers.forEach(lawyer => {
                    const option = document.createElement('option');
                    option.value = lawyer.id;
                    option.textContent = lawyer.name;
                    // Select lawyers already assigned to this appointment during edit
                    if (appointmentData.lawyers && appointmentData.lawyers.some(assignedLawyer => assignedLawyer.id === lawyer.id)) {
                        option.selected = true;
                    }
                    lawyerSelect.appendChild(option);
                });
            });

        // Add event listeners to the newly created buttons
        if (appointmentData.id) {
            document.getElementById('deleteAppointmentBtn').addEventListener('click', handleDeleteAppointment);
        }
    }

    // Event listener for the "Add New Appointment" button outside the calendar
    const addAppointmentButton = document.getElementById('addAppointmentButton'); // Assuming you have a button with this ID
    if (addAppointmentButton) {
        addAppointmentButton.addEventListener('click', function() {
             modalTitle.textContent = 'Add New Appointment';
            modalBody.innerHTML = `
                <form id="newAppointmentForm">
                    <div class="mb-3">
                        <label for="appointmentDate" class="form-label">Date</label>
                        <input type="date" class="form-control" id="appointmentDate" name="date" required>
                    </div>
                    <div class="mb-3">
                        <label for="appointmentTitle" class="form-label">Title</label>
                        <input type="text" class="form-control" id="appointmentTitle" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="appointmentTime" class="form-label">Time</label>
                        <input type="time" class="form-control" id="appointmentTime" name="time">
                    </div>
                    <div class="mb-3">
                        <label for="appointmentDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="appointmentDescription" name="description"></textarea>
                    </div>
                     <div class="mb-3">
                        <label for="appointmentLocation" class="form-label">Location</label>
                        <input type="text" class="form-control" id="appointmentLocation" name="location">
                    </div>
                     <div class="mb-3">
                        <label for="assignedLawyers" class="form-label">Assigned Lawyers</label>
                        <select multiple class="form-control" id="assignedLawyers" name="lawyers[]">
                            {{-- Options will be loaded dynamically --}}
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="reminderSettings" class="form-label">Reminder Settings</label>
                        <select multiple class="form-control" id="reminderSettings" name="reminder_settings[]">
                            <option value="1 day before">1 day before</option>
                            <option value="1 hour before">1 hour before</option>
                            <option value="on the day">On the day</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Appointment</button>
                </form>
            `;
            appointmentModal.show();

            fetch('/api/lawyers')
                .then(response => response.json())
                .then(lawyers => {
                    const lawyerSelect = document.getElementById('assignedLawyers');
                    lawyers.forEach(lawyer => {
                        const option = document.createElement('option');
                        option.value = lawyer.id;
                        option.textContent = lawyer.name;
                        lawyerSelect.appendChild(option);
                    });
                });

            // Add form submission logic (AJAX/Fetch) to save the new appointment
            document.getElementById('newAppointmentForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                 const data = {};
                formData.forEach((value, key) => {
                     if (key === 'lawyers[]' || key === 'reminder_settings[]' || key === '_method') {
                         if (!data[key]) {
                            data[key] = [];
                        }
                        data[key].push(value);
                    } else {
                        data[key] = value;
                    }
                });

                fetch('/api/appointments', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // Include CSRF token for Laravel
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(appointment => {
                    calendar.addEvent(appointment); // Add the new event to the calendar
                    appointmentModal.hide();
                })
                .catch(error => {
                    console.error('Error saving appointment:', error);
                    // Handle errors, e.g., display validation messages
                });
            });
        });
    }

    // Function to handle form submission (create or update)
    function handleFormSubmission(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const data = {};
        formData.forEach((value, key) => {
            if (key === 'lawyers[]' || key === 'reminder_settings[]' || key === '_method') {
                 if (!data[key]) {
                    data[key] = [];
                }
                data[key].push(value);
            } else {
                data[key] = value;
            }
        });

        const url = currentAppointmentId ? `/api/appointments/${currentAppointmentId}` : '/api/appointments';
        const method = currentAppointmentId ? 'POST' : 'POST'; // Use POST for method spoofing

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // Include CSRF token for Laravel
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(appointment => {
            calendar.refetchEvents(); // Refetch all events after create/update
            appointmentModal.hide();
        })
        .catch(error => {
            console.error('Error saving appointment:', error);
            // Handle errors, e.g., display validation messages
        });
    }

    // Function to handle appointment deletion
    function handleDeleteAppointment() {
        if (confirm('Are you sure you want to delete this appointment?')) {
            fetch(`/api/appointments/${currentAppointmentId}`, {
                method: 'DELETE',
                headers: {
                     'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // Include CSRF token for Laravel
                }
            })
            .then(response => {
                if (response.ok) {
                    calendar.refetchEvents(); // Refetch events after deletion
                    appointmentModal.hide();
                }
            });
        }
    }

    // Function to fetch and display dashboard statistics
    function fetchDashboardStats() {
        fetch('/api/dashboard-stats')
            .then(response => response.json())
            .then(stats => {
                // Assuming you have elements with these IDs in your dashboard.blade.php
                document.getElementById('upcomingAppointmentsCount').textContent = stats.upcoming_appointments_count;
                document.getElementById('totalCasesHandled').textContent = stats.total_cases_handled;
                document.getElementById('activeLawyersCount').textContent = stats.active_lawyers_count;
                document.getElementById('scheduledRemindersSummary').textContent = stats.scheduled_reminders_summary;
            })
            .catch(error => {
                console.error('Error fetching dashboard stats:', error);
            });
    }

     // Function to update appointment date/time after drag-and-drop
    function updateAppointmentDateTime(appointmentId, date, time) {
        fetch(`/api/appointments/${appointmentId}`, {
            method: 'PUT', // Or POST with _method=PUT
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ date: date, time: time, _method: 'PUT' })
        })
        .then(response => response.json())
        .then(appointment => {
            console.log('Appointment date/time updated:', appointment);
             // Refetch events to ensure consistency if needed, but FullCalendar often handles this visually
            // calendar.refetchEvents();
        })
        .catch(error => {
            console.error('Error updating appointment date/time:', error);
            // Revert the event's position if the update fails
            calendar.refetchEvents();
        });
    }

     // Add event listener for the modal form submission
    modalBody.addEventListener('submit', function(e) {
        // Check if the submitted form is the one within the modal body
        if (e.target.id === 'appointmentForm' || e.target.id === 'newAppointmentForm') {
            handleFormSubmission.call(e.target, e);
        }
    });

     // Add event listeners to the dynamically created buttons when the modal form is populated
    modalBody.addEventListener('click', function(e) {
        if (e.target.id === 'saveAppointmentBtn') {
            // This event listener is handled by the form submit listener now
        } else if (e.target.id === 'deleteAppointmentBtn') {
            handleDeleteAppointment();
        }
    });
});
