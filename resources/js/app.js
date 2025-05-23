import './bootstrap';
import '../css/app.css'; // Make sure this is present
import '@fullcalendar/core/main.css'; // Import FullCalendar CSS
// ... other imports


import { Calendar } from '@fullcalendar/core';
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';
import '@fullcalendar/core/main.css'; // Import the CSS

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    if (calendarEl) {
        var calendar = new Calendar(calendarEl, {
            plugins: [ dayGridPlugin, interactionPlugin ], // Add interaction plugin
            initialView: 'dayGridMonth',
            events: [
                // Example events (you'll fetch these from your backend later)
                { title: 'Court Hearing - Case 1', date: '2023-10-26' },
                { title: 'Client Meeting', date: '2023-10-28', color: '#009688' }
            ],
            dateClick: function(info) {
                // When a date is clicked, fetch appointments for that date and show the modal
                fetchAppointmentsForDate(info.dateStr);
            }
        });
        calendar.render();
    }

    // Basic modal functionality
    const modal = document.getElementById('appointment-modal');
    const closeModalButton = document.getElementById('close-modal');

    if (closeModalButton) {
        closeModalButton.addEventListener('click', () => {
            hideAppointmentModal();
        });
    }

    // Function to show the modal
    function showAppointmentModal(date, appointments) {
        modal.classList.remove('hidden');
        document.getElementById('modal-title').innerText = `Appointments on ${date}`;

        const modalBody = document.getElementById('modal-body');
        modalBody.innerHTML = ''; // Clear previous content

        if (appointments.length > 0) {
            let html = '<ul>';
            appointments.forEach(appointment => {
                html += `<li><strong>${appointment.title}</strong> - ${appointment.time || ''}<br>${appointment.description || ''}</li>`;
            });
            html += '</ul>';
            modalBody.innerHTML = html;
        } else {
            modalBody.innerHTML = '<p>No appointments for this date.</p>';
        }
    }

    // Function to hide the modal
    function hideAppointmentModal() {
        modal.classList.add('hidden');
    }

    // Function to fetch appointments for a specific date (Placeholder - implement backend later)
    async function fetchAppointmentsForDate(date) {
        // In a real application, you would make an API call here
        console.log(`Fetching appointments for ${date}...`);

        // Placeholder data
        const dummyAppointments = {
            '2023-10-26': [
                { title: 'Court Hearing - Case 1', time: '10:00 AM', description: 'Courtroom 3B' }
            ],
            '2023-10-28': [
                { title: 'Client Meeting', time: '2:00 PM', description: 'Discuss case strategy' },
                { title: 'Prepare for deposition', time: '3:30 PM', description: 'Review documents' }
            ]
            // Add more dummy data as needed for testing
        };

        // Simulate fetching data
        const appointments = dummyAppointments[date] || [];

        showAppointmentModal(date, appointments);
    }
});
