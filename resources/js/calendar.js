import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new Calendar(calendarEl, {
        plugins: [ dayGridPlugin, interactionPlugin ],
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        initialView: 'dayGridMonth',
        events: '/events',
        selectable: true,
        select: function(info) {
            // Open modal for creating a new event
            document.getElementById('eventModal').classList.remove('hidden');
            document.getElementById('eventId').value = '';
            document.getElementById('eventTitle').value = '';
            document.getElementById('eventDescription').value = '';
            document.getElementById('eventStart').value = formatDateForInput(info.start, true);
            document.getElementById('eventEnd').value = formatDateForInput(info.end, true);
            document.getElementById('allDay').checked = false;
            document.getElementById('deleteEvent').classList.add('hidden');
        },
        eventClick: function(info) {
            // Open modal for editing an existing event
            document.getElementById('eventModal').classList.remove('hidden');
            document.getElementById('eventId').value = info.event.id;
            document.getElementById('eventTitle').value = info.event.title;
            document.getElementById('eventDescription').value = info.event.extendedProps.description || '';
            document.getElementById('eventStart').value = formatDateForInput(info.event.start);
            document.getElementById('eventEnd').value = formatDateForInput(info.event.end);
            document.getElementById('allDay').checked = info.event.allDay;
            document.getElementById('deleteEvent').classList.remove('hidden');
        }
    });

    calendar.render();

    // Handle form submission
    document.getElementById('saveEvent').addEventListener('click', function() {
        var eventId = document.getElementById('eventId').value;
        var allDay = document.getElementById('allDay').checked;
        var eventData = {
            title: document.getElementById('eventTitle').value,
            description: document.getElementById('eventDescription').value,
            start: document.getElementById('eventStart').value,
            end: document.getElementById('eventEnd').value,
            allDay: allDay
        };

        if (eventId) {
            // Update event
            fetch('/events/' + eventId, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(eventData)
            })
            .then(response => response.json())
            .then(data => {
                calendar.getEventById(data.event.id).setExtendedProp('title', data.event.title);
                calendar.getEventById(data.event.id).setExtendedProp('description', data.event.description);
                calendar.getEventById(data.event.id).setStart(data.event.start);
                calendar.getEventById(data.event.id).setEnd(data.event.end);
                calendar.getEventById(data.event.id).setAllDay(data.event.allDay);
                document.getElementById('eventModal').classList.add('hidden');
            });
        } else {
            // Create event
            fetch('/events', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(eventData)
            })
            .then(response => response.json())
            .then(data => {
                calendar.addEvent(data.event);
                document.getElementById('eventModal').classList.add('hidden');
            });
        }
    });

    // Handle delete button
    document.getElementById('deleteEvent').addEventListener('click', function() {
        var eventId = document.getElementById('eventId').value;
        if (confirm('Are you sure you want to delete this event?')) {
            fetch('/events/' + eventId, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (response.ok) {
                    calendar.getEventById(eventId).remove();
                    document.getElementById('eventModal').classList.add('hidden');
                }
            });
        }
    });

    // Close modal
    document.getElementById('closeModal').addEventListener('click', function() {
        document.getElementById('eventModal').classList.add('hidden');
    });

    // Close modal on outside click
    document.getElementById('eventModal').addEventListener('click', function(event) {
        if (event.target.id === 'eventModal') {
            document.getElementById('eventModal').classList.add('hidden');
        }
    });

    // Helper function to format date for datetime-local input
    function formatDateForInput(date, isNewEvent = false) {
        if (!date) return '';
        const eventDate = new Date(date);
        if (isNewEvent) {
            const offset = 8 * 60; // UTC+8 offset in minutes
            const adjustedDate = new Date(eventDate.getTime() + (offset * 60 * 1000));
            return adjustedDate.toISOString().slice(0, 16); // Format to YYYY-MM-DDTHH:MM
        }
        return eventDate.toISOString().slice(0, 16); // Format to YYYY-MM-DDTHH:MM
    }
});
