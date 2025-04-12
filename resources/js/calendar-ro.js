import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';
import timeGridPlugin from '@fullcalendar/timegrid';

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new Calendar(calendarEl, {
        plugins: [ dayGridPlugin, interactionPlugin, timeGridPlugin ],
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        initialView: 'dayGridMonth',
        events: '/events',
        selectable: false,
        eventClick: function(info) {
            console.log(info.event);
            // Open modal for viewing an existing event
            document.getElementById('eventModal').classList.remove('hidden');
            document.getElementById('eventTitle').innerText = info.event.title || 'N/A';
            document.getElementById('eventDescription').innerText = info.event.extendedProps.description || '';
            document.getElementById('eventStart').innerText = formatDateForInput(info.event.start) || 'N/A';
            document.getElementById('eventEnd').innerText = formatDateForInput(info.event.end) || 'N/A';
            document.getElementById('allDay').innerText = info.event.allDay ? 'Yes' : 'No';
        },
        eventMouseEnter: function(info) {
            // Change cursor to pointer and darken background
            info.el.style.cursor = 'pointer';
            info.el.style.backgroundColor = '#319795'; // Darkened color for hover
        },
        eventMouseLeave: function(info) {
            // Reset cursor and background
            info.el.style.cursor = '';
            info.el.style.backgroundColor = ''; // Reset to default color
        }
    });

    calendar.render();

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

    function formatDateForInput(date) {
        if (!date) return 'N/A';
        return new Date(date).toLocaleDateString('en-US', {
            weekday: 'long',
            month: 'long',
            day: 'numeric',
        });
    }


});
