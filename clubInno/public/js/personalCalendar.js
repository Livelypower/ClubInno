$(document).ready(function() {
    var calendarElement = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarElement, {
        locales: ['fr'],
        locale: 'fr',
        height: 800,
        minTime: '6:00',
        maxTime: '21:00',
        plugins: ['dayGrid', 'timeGrid'],
        defaultView: 'timeGridWeek',
        eventBorderColor: 'transparent',
        events: [
            {
                title: 'The Title - 306',
                start: '2019-03-29 15:00',
                end: '2019-03-29 16:00',
                classNames: ['red']

            }
        ]
    });

    calendar.render();
});