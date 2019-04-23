$(document).ready(function(){
    var filters = [];
    if (localStorage.getItem('filters') !== null) {
        filters = JSON.parse(localStorage.getItem('filters'));
    }
    checkFilters(filters);
    var apiToken = $("#data").html();
    var data = {'filters': filters};
    ajaxCall(data, apiToken);

    $("#filterActivities").click(function(){
        filters = [];
        $('input[type=checkbox]').each(function () {
            if(this.checked){
                filters.push($(this).val());
            }
        });
        console.log(filters);
        checkFilters(filters);
        localStorage.setItem('filters',JSON.stringify(filters));
        data = {'filters': filters};
        ajaxCall(data, apiToken);
    });
});

function ajaxCall(data, apiToken){
    $.ajax({
        method: "GET",
        url: "http://localhost:8000/api/activities",
        data: data,
        headers: {
            'X-AUTH-TOKEN':apiToken
        },
        success: function (response) {
            console.log('ok');
            console.log(response);
            showActivities(response);
        },
        error: function (response) {
            console.log(response);
            console.log('call failed')
        }
    });
}

function showActivities(activities){
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

                var toggleUrl = "http://localhost:8000/admin/activity/" + activity.id + "/toggle";
                var editUrl = "http://localhost:8000/admin/activity/" + activity.id + "/edit";
                var deleteUrl = "http://localhost:8000/admin/activity/" + activity.id + "/delete";

                card +=  "<div class=\"right hide-on-med-and-down\">\n" +
                "                                <a href=\"" + toggleUrl + "\">toggle</a>\n" +
                "                                <a href=\"" + editUrl + "\">edit</a>\n" +
                "                                <a href=\"" + deleteUrl + "\">delete</a>\n" +
                "                            </div>\n";

            card += "                    </div>\n" +
            "                </div>\n" +
            "            </div>\n";

            html += card;
    });

    $('#activities').html(html);
}

function checkFilters(filters){
    filters.forEach(function(filter){
       $("#"+filter).prop("checked", true);
       console.log($("#"+filter).val());
    });
}