$(document).ready(function () {
    var students;
    var activities;
    var semester;
    var apiToken = $("#data").html();
    getUsers();

    function getUsers() {
        $.ajax({
            method: "GET",
            url: "http://localhost:8000/api/admin/users",
            headers: {
                'X-AUTH-TOKEN':apiToken
            },
            success: function (response) {
                students = response;
                getActiveSemester();
            },
            error: function (response) {
                console.log(response);
                console.log('call failed')
            }
        });
    }

    function getActiveSemester() {
        $.ajax({
            method: "GET",
            url: "http://localhost:8000/api/admin/semester",
            headers: {
                'X-AUTH-TOKEN':apiToken
            },
            success: function (response) {
                semester = response;
                activities = semester.activities;
                console.log(semester);
                all()
            },
            error: function (response) {
                console.log(response);
                console.log('call failed')
            }
        });
    }

    /*function getActis() {
        $.ajax({
            method: "GET",
            url: "http://localhost:8000/api/admin/activities",
            headers: {
                'X-AUTH-TOKEN':apiToken
            },
            success: function (response) {
                activities = response;
                all()
            },
            error: function (response) {
                console.log(response);
                console.log('call failed')
            }
        });
    }*/

    function all(){
        var lastSaved = 0;
        var alreadySaved = false;

        var count = 1;
        activities.forEach(function(activity) {
            if (activity.active === true) {
                $("#tableHead").append('<th id="a' + activity.id + '" data-number="' + count + '">' +
                    '<a href="/activity/' + activity.id + '-/admin/listApplications">' + activity.name + '</a>' +
                    ' <span id="span-' + activity.id +'">(' + activity.users.length + '/' + activity.max_amount_students + ')</span>' +
                    '<span id="mark-' + activity.id + '" class="red-text text-darken-3"></span>'+
                    '</th>');
                count++;
            }
        });

        students.forEach(function(student) {
            console.log(student);
            var applications = [];
            var registrations = [];
            student.applications.forEach(function(application){
                if(application.semester.id === semester.id){
                    applications.push(application);
                }
            });
            student.registrations.forEach(function(registration){
               if(registration.semester.id === semester.id){
                   registrations.push(registration);
               }
            });

            if(student.roles[0] === "ROLE_USER" && applications.length !== 0){
                var url = "http://localhost:8000/uploads/" + applications[0].motivation_letter_path;
                if(registrations.length !== 0){
                    $("#tableBody").append('<tr id="s' + student.id + '" class="registrated"><td>' + student.email + " (" + student._orientation
                        + ')<a title="Lettre de motivation" href="' + url + '" download><i class="material-icons right">mail</i></a>' + '</td>');
                }else{
                    $("#tableBody").append('<tr id="s' + student.id + '"><td>' + student.email + " (" + student._orientation
                        + ')<a title="Lettre de motivation" href="' + url + '" download><i class="material-icons right">mail</i></a>' + '</td>');
                }
            }
            activities.forEach(function(activity) {
                if (activity.active === true) {
                    $("#s" + student.id).append('<td data-clickable-cell="true" id="' + student.id + "-" + activity.id + '"></td>');
                    if(applications.length !== 0){
                        applications.forEach(function(application){
                            application.activities.forEach(function(application){
                                if(activity.id === application.id){
                                    $("#" + student.id + "-" + application.id).addClass("red");
                                }
                            });
                        });
                    }
                }
            });
            $("#tableBody").append("</tr>");
        });

        students.forEach(function(student) {
            var applications = [];
            var registrations = [];
            student.applications.forEach(function(application){
                if(application.semester.id === semester.id){
                    applications.push(application);
                }
            });
            student.registrations.forEach(function(registration){
                if(registration.semester.id === semester.id){
                    registrations.push(registration);
                }
            });
            activities.forEach(function(activity) {
                if(registrations.length !== 0 ){
                    registrations.forEach(function(registration){
                        if(registration.id === activity.id){
                            var matched = false;
                            if(applications.length !== 0){
                                applications.forEach(function(application){
                                    application.activities.forEach(function(application){
                                        if(application.id === registration.id){
                                            matched = true;
                                        }
                                    });
                                });
                            }
                            if(matched){
                                $("#" + student.id + "-" + activity.id).addClass("blue");
                            }else{
                                $("#" + student.id + "-" + activity.id).addClass("light-blue lighten-3");
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
                var count = getStudentsPerActivity(activity.id);

                if(count >= activity.max_amount_students){
                    var number = $("#" + activity.id).data('number');
                    number++;
                    $('td:nth-child(' + number  + ')').show();
                    $('th:nth-child(' + number + ')').show();
                }
            });
        });

        $("#hide-activities-button").click(function(){
            activities.forEach(function(activity){
                var count = getStudentsPerActivity(activity.id);

                if(count >= activity.max_amount_students){
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
                var count = getCount(activity.id);
                $("#span-" + activity.id).html(
                    ' (' + count + '/' + activity.max_amount_students + ')'
                );
            });
        }

        function getCount(activityId){
            var count = 0;
            students.forEach(function(student){
                var selector = "#" + student.id + "-" + activityId;
                if($(selector).hasClass("blue") || $(selector).hasClass("light-blue lighten-3"))count++;
            });

            return count;
        }

        function checkCount(){
            var errorActivities = [];
            activities.forEach(function (activity) {
                var count = getStudentsPerActivity(activity.id);

                if(count > activity.max_amount_students){
                    errorActivities.push(activity);
                }
            });

            return errorActivities;
        }

        function saveConfig(){
            var errorActivities = checkCount();
            noErrorCount(errorActivities);

            if(errorActivities.length === 0) {
                $("#error-text").html("");
                $("#autosave-text").show();
                $("#autosave-text").html('<i class="material-icons left" style="margin-right: 5px;">sync</i>' + 'Dernier sauvegardé maintenant');
                lastSaved = 0;
                alreadySaved = true;

                var registrations = [];

                students.forEach(function (student) {
                    if (student.roles[0] === "ROLE_USER") {
                        var studentObj = {
                            'userId': student.id,
                            'activities': []
                        };
                        activities.forEach(function (activity) {
                            var className = $("#" + student.id + "-" + activity.id).attr('class');
                            console.log(className);
                            if (className === "red blue" || className === "light-blue lighten-3") {
                                studentObj.activities.push(activity.id);
                            }
                        });
                        registrations.push(studentObj)
                    }
                });

                registrations.forEach(function(registration) {
                    $.ajax({
                        method: "POST",
                        url: "http://localhost:8000/api/admin/registration/add",
                        data: registration,
                        headers: {
                            'X-AUTH-TOKEN':apiToken
                        },
                        success: function (response) {
                            console.log(response);
                        },
                        error: function (response) {
                            console.log(response)
                        }
                    });
                });
            }
            else{
                var html = '<p>Trop d\'étudiants assignés aux activités suivantes:<ul class="collection">';

                errorActivities.forEach(function(error){
                    html += '<li class="collection-item">' + error.name + '</li>';
                });

                html += '</ul>';

                $("#error-text").html(html);
                errorCount(errorActivities);
            }
        }

        function errorCount(eActivities){
            eActivities.forEach(function (activity) {
                var selectorCount = "#span-" + activity.id;
                var selector = "#mark-" + activity.id;
                $(selector).text("!");
                $(selectorCount).addClass("red-text text-darken-3");
            });
        }

        function noErrorCount(eActivities){
            activities.forEach(function (activity) {
                var inError = false;
                eActivities.forEach(function (error) {
                    if(error.id === activity.id){
                        inError = true;
                    }
                });
                if(!inError){
                    console.log(activity.name);
                    var selectorCount = "#span-" + activity.id;
                    var selector = "#mark-" + activity.id;
                    $(selector).text("");
                    $(selectorCount).removeClass("red-text text-darken-3");
                }
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

        function getStudentsPerActivity(activityId){
            var header = $("#" + activityId).text();
            var count = header.substring(
                header.lastIndexOf("(") + 1,
                header.lastIndexOf("/")
            );

            return count
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
    }

});