$(document).ready(function () {
    var studentsS = $('#users').data("users");
    var activitiesS = $('#acties').data("acties");
    var students = [];
    var activities = [];

    studentsS.forEach(student => {
       students.push(JSON.parse(student))
    });

    activitiesS.forEach(activity => {
        activities.push(JSON.parse(activity))
    });

    console.log(students);



    activities.forEach(activity => {
        $("#tableHead").append('<th>' + activity.name + '</th>');
});

    students.forEach(student => {
        if(student.roles[0] == "ROLE_USER"){
        $("#tableBody").append('<tr id="' + student.id + '"><td>' + student.username + '</td>');
    }
    activities.forEach(activity => {
        $("#" + student.id).append('<td data-clickable-cell="true" id="' + student.id + activity.id + '"></td>');
        if(student.applications.length !== 0){
            student.applications[0].activities.forEach(application => {
                if(student.registrations.length !== 0){
                    student.registrations.forEach(registration => {
                    if(application.id == registration.id){
                         $("#" + student.id + registration.id).addClass("blue");
                        }
                        else{
                            $("#" + student.id + registration.id).addClass("light-blue lighten-3");
                        }
                    });
                }
                if(activity.id === application.id){
                    $("#" + student.id + application.id).addClass("red");
                }
            });
        }
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


    function saveConfig(){
        var registrations = [];


        students.forEach(student=>{
            if(student.roles[0] == "ROLE_USER"){
            var studentObj = {
                'userId': student.id,
                'activities': []
            };
            activities.forEach(activity => {
                var className = $("#" + student.id + activity.id).attr('class');
            console.log(className);
            if(className == "red blue" || className == "light-blue lighten-3"){
                studentObj.activities.push(activity.id);
            }
        });
            registrations.push(studentObj)
        }
    });


        registrations.forEach(registration => {
            $.ajax({
            method: "POST",
            url: "http://localhost:8000/api/registration/add",
            data: registration,
            success: function (response) {
                console.log(response);
            },
            error: function (response) {
                console.log(response)
            }
        });
    });


    }

    setInterval(function() {
        saveConfig();
        M.toast({html: 'Sauvegardé automatiquement'}, 4000);
    }, 120000);


    $('#saveConfig').click(saveConfig());
});