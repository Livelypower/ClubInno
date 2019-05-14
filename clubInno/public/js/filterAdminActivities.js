$(document).ready(function(){
    $("#filterForm").hide();
    $("#hideFilters").hide();
    var own = false;

    if (localStorage.getItem('adminFilters') !== null) {
        var data = JSON.parse(localStorage.getItem('adminFilters'));
    }
    checkFilters(data);

    var apiToken = $("#data").html();
    getActivities(data, apiToken);

    $("#filterActivities").click(function(){
        var filters = [];
        $('#filters input:checkbox').each(function () {
            if(this.checked){
                filters.push($(this).val());
            }
        });

        own = $('#ownActivities').prop('checked');

        data = {'filters': filters, 'own': own};
        checkFilters(data);
        localStorage.setItem('adminFilters',JSON.stringify(data));
        getActivities(data, apiToken);
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
    console.log(data);
    $.ajax({
        method: "GET",
        url: "http://localhost:8000/api/activities",
        data: data,
        headers: {
            'X-AUTH-TOKEN':apiToken
        },
        success: function (response) {
            console.log("RESPONSE");
            console.log(response);
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
        url: "http://localhost:8000/api/currentUser",
        headers: {
            'X-AUTH-TOKEN':apiToken
        },
        success: function (response) {

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
            var imageUrl = "http://localhost:8000/uploads/" + activity.main_image;
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


        var currentUrl = "http://localhost:8000/admin/activities";
        var activityShowUrl = "http://localhost:8000/activity/" + activity.id + "-" + currentUrl;
        card += "</span>\n" +
            "                        <div class=\"row\"><div class='col s12 grey-text text-lighten-1'><blockquote>Créé par: " + activity.creator.email + "</blockquote></div></div>\n" +
            "                        <p><b>Inscriptions: </b>" + activity.users.length + "/" + activity.max_amount_students + "</p>\n" +
            "                        <br/>\n" +
            "                        <p>\n" +
            "                            " + activity.description + "\n" +
            "                        </p>\n" +
            "\n" +
            "                    </div>\n" +
            "                    <div class=\"card-action\">\n" +
            "                        <a href=\"" + activityShowUrl + "\">Plus d'info</a>\n";

                var toggleUrl = "http://localhost:8000/admin/activity/toggle/" + activity.id;
                var editUrl = "http://localhost:8000/admin/activity/edit/" + activity.id ;
                var deleteUrl = "http://localhost:8000/admin/activity/delete/" + activity.id;

                card +=  "<div class=\"right hide-on-med-and-down\">\n";

                if(role === 'admin'){
                    card += "                                <a href=\"" + toggleUrl + "\">toggle</a>\n";
                }

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

function checkFilters(data){
    console.log(data);
    if(data.length !== 0){
        var filters = data.filters;
        var own = data.own;
        filters.forEach(function(filter){
            $("#"+filter).prop("checked", true);
        });
        $("#ownActivities").prop("checked", own);
        if(filters.length !== 0 || own){
            $("#filterForm").show();
            $("#hideFilters").show();
            $("#showFilters").hide();
        }
    }

}