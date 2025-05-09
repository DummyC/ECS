import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';
import timeGridPlugin from '@fullcalendar/timegrid';

document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');
    var isMobile = window.innerWidth < 640;

    var calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, interactionPlugin, timeGridPlugin],
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: isMobile ? 'dayGridMonth' : 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        initialView: isMobile ? 'dayGridMonth' : 'dayGridMonth',
        events: '/events',
        selectable: false,
        eventClick: function (info) {

            document.getElementById('eventModal').classList.remove('hidden');
            document.getElementById('eventModalLabel').innerText = info.event.title;

            // Format and set dates
            const startDate = formatDateTime(info.event.start);
            const endDate = formatDateTime(info.event.end);

            document.getElementById('eventStart').innerText = startDate;
            document.getElementById('eventEnd').innerText = endDate;
            document.getElementById('eventDateSeparator').classList.toggle('hidden', info.event.allDay);
            document.getElementById('eventEnd').classList.toggle('hidden', info.event.allDay);

            // Set description
            document.getElementById('eventDescription').innerText = info.event.extendedProps.description || '';

            // Show modal

        },
        eventMouseEnter: function (info) {
            // Change cursor to pointer and darken background
            info.el.style.cursor = 'pointer';
            info.el.style.backgroundColor = '#1d4ed8'; // Darkened color for hover
        },
        eventMouseLeave: function (info) {
            // Reset cursor and background
            info.el.style.cursor = '';
            info.el.style.backgroundColor = ''; // Reset to default color
        }
    });

    calendar.render();

    const urlParams = new URLSearchParams(window.location.search);
    const eventId = urlParams.get('event');
    if (eventId) {
        // Wait for calendar to load, then open modal for this event
        // You may need to adjust this depending on how you fetch events
        setTimeout(function () {
            // Find the event in FullCalendar and trigger the modal
            const event = calendar.getEvents().find(e => e.id == eventId);
            if (event) {
                // Simulate event click to open modal
                // You may need to call your modal-opening function directly
                // Example:
                openEventModal(event);
            }
        }, 500); // Adjust delay as needed
    }

    function openEventModal(event) {
            document.getElementById('eventModalLabel').innerText = event.title;
            document.getElementById('eventDescription').innerText = event.extendedProps.description || '';

            const startDate = formatDateTime(event.start);
            const endDate = formatDateTime(event.end);

            document.getElementById('eventStart').innerText = startDate;
            document.getElementById('eventEnd').innerText = endDate;
            document.getElementById('eventDateSeparator').classList.toggle('hidden', event.allDay);
            document.getElementById('eventEnd').classList.toggle('hidden', event.allDay);

            document.getElementById('eventModal').classList.remove('hidden');
        }

    // Close modal
    document.getElementById('closeModal').addEventListener('click', function () {
        document.getElementById('eventModal').classList.add('hidden');
    });

    // Close modal on outside click
    document.getElementById('eventModal').addEventListener('click', function (event) {
        if (event.target.id === 'eventModal') {
            document.getElementById('eventModal').classList.add('hidden');
        }
    });

    function formatDateTime(date) {
        if (!date) return 'N/A';
        return new Date(date).toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: 'numeric',
            minute: 'numeric'
        });
    }

    window.addEventListener('resize', function () {
        var newIsMobile = window.innerWidth < 640;
        if (newIsMobile !== isMobile) {
            isMobile = newIsMobile;
            calendar.changeView(isMobile ? 'dayGridMonth' : 'dayGridMonth');
        }
    });


});
