import './bootstrap.js';
import './styles/fullcalendar/daygrid.css';
import './styles/fullcalendar/timegrid.css';

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
        initialView: 'timeGridWeek',
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
        },
        eventContent: function (arg) {
            const view = arg.view;
            const event = arg.event;
            const data = event.extendedProps;

            let content = '';

            if (view.type === 'dayGridMonth') {
                // Vue mois : simple
                content = `${data.matiere}`;
            } else {
                // Vue semaine/jour : détaillée
                content = `
                    <div class="fc-content">
                        <b>${data.matiere}</b><br>
                        <small>Salle ${data.salle}</small><br>
                        <i>${data.formateur}</i>
                    </div>
                `;
            }

            return { html: content };
        }
    });

    calendar.render();
});
