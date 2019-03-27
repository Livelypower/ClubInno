$(document).ready(function () {
    var lastSaved = 0;
    var alreadySaved = false;
    var studentsS = $('#users').data("users");
    var activitiesS = $('#acties').data("acties");
    var students = [];
    var activities = [];

    studentsS.forEach(function(student) {
       students.push(JSON.parse(student))
    });

    activitiesS.forEach(function(activity) {
        activities.push(JSON.parse(activity))
    });


    activities.forEach(function(activity) {
        $("#tableHead").append('<th>' + activity.name + '</th>');
    });

    students.forEach(function(student) {
        if(student.roles[0] === "ROLE_USER"){
            $("#tableBody").append('<tr id="' + student.id + '"><td>' + student.username + '</td>');
        }
        activities.forEach(function(activity) {
            $("#" + student.id).append('<td data-clickable-cell="true" id="' + student.id + activity.id + '"></td>');
            if(student.applications.length !== 0){
                student.applications[0].activities.forEach(function(application){
                    if(activity.id === application.id){
                        $("#" + student.id + application.id).addClass("red");
                    }
                });
            }
        });
        $("#tableBody").append("</tr>");
    });

    students.forEach(function(student) {
        activities.forEach(function(activity) {
            if(student.registrations.length !== 0){
                student.registrations.forEach(function(registration){
                   if(registration.id === activity.id){
                        if(student.applications[0].length !== 0){
                            var matched = false;
                            student.applications[0].activities.forEach(function(application){
                                if(application.id === registration.id){
                                    matched = true;
                                }
                            });
                            if(matched){
                                $("#" + student.id + activity.id).addClass("blue");
                            }else{
                                $("#" + student.id + activity.id).addClass("light-blue lighten-3");
                            }
                        }
                   }
                });
            }
        });
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
        $("#autosave-text").show();
        $("#autosave-text").html('<i class="material-icons left" style="margin-right: 5px;">sync</i>' + 'Dernier sauvegardé maintenant');
        lastSaved = 0;
        alreadySaved = true;

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

    setInterval(function(){
        if(alreadySaved){
            lastSaved++;
            if(lastSaved === 1){
                $("#autosave-text").html('<i class="material-icons left" style="margin-right: 5px;">sync</i>' + 'Dernière sauvegarde il y a ' + lastSaved + ' minute');
            }else{
                $("#autosave-text").html('<i class="material-icons left" style="margin-right: 5px;">sync</i>' + 'Dernière sauvegarde il y a ' + lastSaved + ' minutes');
            }
        }
    }, 60000);

    setInterval(function() {
        saveConfig();
        M.toast({html: 'Sauvegardé automatiquement'}, 4000);
    }, 240000);



    $('#saveConfig').click(function(){
        saveConfig();
    });
});