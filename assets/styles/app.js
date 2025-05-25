import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import '@fullcalendar/daygrid/main.min.css';

document.addEventListener('DOMContentLoaded', function () {
  const calendarEl = document.getElementById('calendar');

  if (calendarEl) {
    const calendar = new Calendar(calendarEl, {
      plugins: [dayGridPlugin],
      initialView: 'dayGridMonth',
      locale: 'fr',
      events: window.cours || [],
      eventClick: function (info) {
        window.location.href = "/emargement/" + info.event.id;
      }
    });

    calendar.render();
  }
});