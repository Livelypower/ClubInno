$(document).ready(function() {
    var userId = $("#data").html();
    var apiToken = $("#data2").html();
    $.ajax({
        url: 'http://vps589558.ovh.net/api/calendar/'+userId,
        method: 'GET',
        headers: {
          'X-AUTH-TOKEN':apiToken
        },
        success: function (response) {
            eventsArray = [];
            $.each(response, function (index, responseObject) {
                $.each(responseObject, function (index, element) {
                    var startDate = new Date(Date.parse(element.start_date));
                    var dateStringStart_Date =  startDate.getFullYear()+ "-" + (startDate.getMonth() + 1) + "-" + startDate.getDate() ;
                    var endDate = new Date(Date.parse(element.end_date));
                    var dateStringEnd_Date = startDate.getFullYear()+ "-" + (startDate.getMonth() + 1) + "-" + startDate.getDate() ;
                    var startTime = new Date(Date.parse(element.start_time));
                    var dateStringStart_Time = startTime.getHours() + ":" + (startTime.getMinutes()<10?'0':'') + startTime.getMinutes();
                    var endTime = new Date(Date.parse(element.end_time));
                    var dateStringEnd_Time = endTime.getHours() + ":" + (endTime.getMinutes()<10?'0':'') + endTime.getMinutes();
                    eventsArray.push({
                        title: element.location + " - " + element.name,
                        start: dateStringStart_Date + " " + dateStringStart_Time,
                        end: dateStringEnd_Date + " " + dateStringEnd_Time,
                        classNames: ['teal']
                    });
                });
            });
            buildCalendar(eventsArray);
        },
        error: function (response) {
            console.log('Error getting calendar');
            console.log(response);
        }
    });
});

function buildCalendar (eventsArray) {
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
        events: eventsArray,
        nowIndicator: true
    });

    calendar.render();
}