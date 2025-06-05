import './bootstrap.js';
import './styles/fullcalendar/daygrid.css';
import './styles/fullcalendar/timegrid.css';

import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';
import frLocale from '@fullcalendar/core/locales/fr';

document.addEventListener('turbo:load', function () {
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

            const startHour = new Date(event.start).toLocaleTimeString('fr-FR', {
                hour: '2-digit',
                minute: '2-digit'
            });
            const endHour = new Date(event.end).toLocaleTimeString('fr-FR', {
                hour: '2-digit',
                minute: '2-digit'
            });

            let content = '';

            if (view.type === 'dayGridMonth') {
                content = `${startHour} - ${data.matiere}`;
            } else {
                content = `
                    <div class="fc-content">
                        <b>${data.matiere}</b><br>
                        <small>Salle ${data.salle}</small><br>
                        <i>${data.formateur}</i><br>
                        <span>${startHour} - ${endHour}</span>
                    </div>
                `;
            }

            return { html: content };
        },

        eventDidMount: function (info) {

            info.el.style.cursor = 'pointer';

            info.el.style.transition = 'transform 0.2s ease';

            info.el.addEventListener('mouseenter', function () {
                info.el.style.transform = 'scale(1.05)';
            });

            info.el.addEventListener('mouseleave', function () {
                info.el.style.transform = 'scale(1)';
            });
        }
    });

    calendar.render();
});