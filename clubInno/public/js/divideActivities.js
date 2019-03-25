$(document).ready(function () {
    console.log(students);
    console.log(activities);

    activities.forEach(activity => {
        $("#tableHead").append('<th>' + activity.name + '</th>');
});

    students.forEach(student => {
        $("#tableBody").append('<tr id="' + student._id + '"><td>' + student.fullName + '</td>');
    activities.forEach(activity => {
        $("#" + student._id).append('<td data-clickable-cell="true" id="' + student._id + activity._id + '"></td>');
    student.wishes.forEach(wish => {
        if (wish._id === activity._id) {
        $("#" + student._id + activity._id).toggleClass("red");
    }
});
});
    $("#tableBody").append("</tr>");
});

    $("td").click(function () {
        if ($(this).data("clickableCell") === true) {
            if ($(this).hasClass("red")) {
                $(this).toggleClass("blue");
            } else {
                $(this).toggleClass("light-blue lighten-3");
            }
        }
    });
});