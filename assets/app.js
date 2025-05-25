import './styles/fullcalendar/daygrid.css';
import './styles/fullcalendar/timegrid.css';


// Import JS FullCalendar
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';
import frLocale from '@fullcalendar/core/locales/fr';

document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');
    if (!calendarEl || !window.calendarEvents) return;

    const calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
        initialView: 'dayGridMonth',
        locale: frLocale,
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        slotMinTime: "08:00:00",
        slotMaxTime: "19:00:01",
        events: window.calendarEvents,
        eventClick: function (info) {
            window.location.href = "/emargement/" + info.event.id;
        }
    });

    calendar.render();
});
