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


    var count = 1;
    activities.forEach(function(activity) {
        $("#tableHead").append('<th id="' + activity.id + '" data-number="' + count + '">' +
            '<a href="/activity/' + activity.id + '-/admin/listApplications">' + activity.name + '</a>' +
            ' (' + activity.users.length + '/' + activity.maxAmountStudents + ') ' +
            '</th>');
        count++;
    });

    students.forEach(function(student) {
        if(student.roles[0] === "ROLE_USER"){
            var url = "http://localhost:8000/uploads/" + student.applications[0].motivationLetterPath;
            if(student.registrations.length !== 0){
                $("#tableBody").append('<tr id="' + student.id + '" class="registrated"><td>' + student.username
                   + '<a title="Lettre de motivation" href="' + url + '" download><i class="material-icons right">mail</i></a>' + '</td>');
            }else{
                $("#tableBody").append('<tr id="' + student.id + '"><td>' + student.username
                    + '<a title="Lettre de motivation" href="' + url + '" download><i class="material-icons right">mail</i></a>' + '</td>');
            }
        }
        activities.forEach(function(activity) {
            $("#" + student.id).append('<td data-clickable-cell="true" id="' + student.id + "-" + activity.id + '"></td>');
            if(student.applications.length !== 0){
                student.applications[0].activities.forEach(function(application){
                    if(activity.id === application.id){
                        $("#" + student.id + "-" + application.id).addClass("red");
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
                                $("#" + student.id + "-" + activity.id).addClass("blue");
                            }else{
                                $("#" + student.id + "-" + activity.id).addClass("light-blue lighten-3");
                            }
                        }
                   }
                });
            }
        });
    });

    $("#show-students-button").click(function(){
        $(".registrated").show();
    });

    $("#hide-students-button").click(function(){
        $(".registrated").hide();
    });

    $("#show-activities-button").click(function(){
        activities.forEach(function(activity){
            var header = $("#" + activity.id).text();
            var count = header.substring(
                header.lastIndexOf("(") + 1,
                header.lastIndexOf("/")
            );

            if(count >= activity.maxAmountStudents){
                var number = $("#" + activity.id).data('number');
                number++;
                $('td:nth-child(' + number  + ')').show();
                $('th:nth-child(' + number + ')').show();
            }
        });
    });

    $("#hide-activities-button").click(function(){
        activities.forEach(function(activity){
            var header = $("#" + activity.id).text();
            var count = header.substring(
                header.lastIndexOf("(") + 1,
                header.lastIndexOf("/")
            );

            if(count >= activity.maxAmountStudents){
                var number = $("#" + activity.id).data('number');
                number++;
                $('td:nth-child(' + number  + ')').hide();
                $('th:nth-child(' + number + ')').hide();
            }
        });
    });



    $("td").click(function () {
        if ($(this).data("clickableCell") === true) {
            var id = $(this).attr('id');
            var studentId = id.substr(0, id.indexOf('-'));

            if ($(this).hasClass("red")) {
                $(this).toggleClass("blue");
            } else {
                $(this).toggleClass("light-blue lighten-3");
            }
            updateRegistrated(studentId);
            updateCount();

        }
    });

    function updateCount(){
        activities.forEach(function(activity) {
            var count = 0;
            students.forEach(function(student){
                var selector = "#" + student.id + "-" + activity.id;
                if($(selector).hasClass("blue") || $(selector).hasClass("light-blue lighten-3"))count++;
            });
            $("#" + activity.id).html(
            '<a href="/activity/' + activity.id + '-/admin/listApplications">' + activity.name + '</a>' +
            ' (' + count + '/' + activity.maxAmountStudents + ')'
            );





        });
    }

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
                var className = $("#" + student.id + "-" + activity.id).attr('class');
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

    function updateRegistrated(studentId){
      var hasRegistrated = false;

      activities.forEach(function(activity){
          var selector = "#" + studentId + "-" + activity.id;
          if($(selector).hasClass("blue") || $(selector).hasClass("light-blue lighten-3"))hasRegistrated=true;
      });
      if(hasRegistrated){
          $("#"+ studentId).addClass("registrated");
      }else{
          $("#"+ studentId).removeClass("registrated");
      }
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