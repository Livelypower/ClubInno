$(document).ready(function(){
    $("#filterForm").hide();
    $("#hideFilters").hide();
    var filters = [];
    if (localStorage.getItem('adminFilters') !== null) {
        filters = JSON.parse(localStorage.getItem('adminFilters'));
    }
    checkFilters(filters);
    var apiToken = $("#data").html();
    var data = {'filters': filters};
    getActivities(data, apiToken);

    $("#filterActivities").click(function(){
        filters = [];
        $('input[type=checkbox]').each(function () {
            if(this.checked){
                filters.push($(this).val());
            }
        });
        console.log(filters);
        checkFilters(filters);
        localStorage.setItem('adminFilters',JSON.stringify(filters));
        data = {'filters': filters};
        ajaxCall(data, apiToken);
    });

    $('#showFilters').click(function () {
        $(this).hide();
        $("#filterForm").show();
        $("#hideFilters").show();
    });

    $('#hideFilters').click(function(){
       $(this).hide();
       $("#filterForm").hide();
       $("#showFilters").show();
    });


});

function getActivities(data, apiToken){
    $.ajax({
        method: "GET",
        url: "http://vps589558.ovh.net/api/activities",
        data: data,
        headers: {
            'X-AUTH-TOKEN':apiToken
        },
        success: function (response) {
            console.log('ok');
            getUser(apiToken, response);
        },
        error: function (response) {
            console.log(response);
            console.log('call failed')
        }
    });
}

function getUser(apiToken, activities){
    $.ajax({
        method: "GET",
        url: "http://vps589558.ovh.net/api/currentUser",
        headers: {
            'X-AUTH-TOKEN':apiToken
        },
        success: function (response) {
            console.log('ok');
            console.log(response);
            showActivities(activities, response);
        },
        error: function (response) {
            console.log(response);
            console.log('call failed')
        }
    });
}

function showActivities(activities, user){
    var role;
    if(user.roles.includes('ROLE_ADMIN')){
        role = 'admin';
    }else if(user.roles.includes('ROLE_TEACHER')){
        role = 'teacher';
    }

    var html = "";

    activities.forEach(function(activity){
        var card = "      <div class=\"col s12 m4\">\n" +
            "                <div class=\"card large blue-grey darken-1\">\n" +
            "                    <div class=\"card-image\">\n";
        if(activity.main_image == null){
            card += "<img src=\"http://lorempixel.com/800/400/technics\" alt=\"\">\n"
        }else{
            var imageUrl = "http://vps589558.ovh.net/uploads/" + activity.main_image;
            card += "<img src=\"" + imageUrl + "\" alt=\"\">\n"
        }

        card +=  "               </div>\n" +
            "                    <div class=\"card-content white-text\">\n" +
            "                        <span class=\"card-title\">" + activity.name;

        if(activity.active === true){
            card +=  " <i class=\"material-icons green-text accent-2\">check</i>";
        }else{
            card += " <i class=\"material-icons red-text accent-4\">cancel</i>";
        }


        var currentUrl = "http://vps589558.ovh.net/admin/activities";
        var activityShowUrl = "http://vps589558.ovh.net/activity/" + activity.id + "-" + currentUrl;
        card += "</span>\n" +
            "                        <div class=\"row\"></div>\n" +
            "                        <p><b>Inscriptions: </b>" + activity.users.length + "/" + activity.max_amount_students + "</p>\n" +
            "                        <br/>\n" +
            "                        <p>\n" +
            "                            " + activity.description + "\n" +
            "                        </p>\n" +
            "\n" +
            "                    </div>\n" +
            "                    <div class=\"card-action\">\n" +
            "                        <a href=\"" + activityShowUrl + "\">Plus d'info</a>\n";

                var toggleUrl = "http://vps589558.ovh.net/admin/activity/" + activity.id + "/toggle";
                var editUrl = "http://vps589558.ovh.net/admin/activity/" + activity.id + "/edit";
                var deleteUrl = "http://vps589558.ovh.net/admin/activity/" + activity.id + "/delete";

                card +=  "<div class=\"right hide-on-med-and-down\">\n";

                if(role === 'admin'){
                    card += "                                <a href=\"" + toggleUrl + "\">toggle</a>\n";
                }

                console.log(checkIfCreated(user.created_activities, activity));
                if(role === 'admin' || checkIfCreated(user.created_activities, activity)){
                    card +=  "                                <a href=\"" + editUrl + "\">edit</a>\n" +
                        "                                <a href=\"" + deleteUrl + "\">delete</a>\n";
                }

            card += "                            </div>\n" +
                "                    </div>\n" +
            "                </div>\n" +
            "            </div>\n";

            html += card;
    });

    $('#activities').html(html);
}

function checkIfCreated(activities, activity){
    var created = false;
    activities.forEach(function(created_activity){
        if(created_activity.id === activity.id){
            created =  true;
        }
    });
  return created;
}

function checkFilters(filters){
    filters.forEach(function(filter){
       $("#"+filter).prop("checked", true);
       console.log($("#"+filter).val());
    });
    if(filters.length !== 0){
        $("#filterForm").show();
        $("#hideFilters").show();
        $("#showFilters").hide();
    }
}