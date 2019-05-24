$(document).ready(function(){
    var baseUrl = "vps589558.ovh.net";
    $("#filterForm").hide();
    $("#hideFilters").hide();
    var filters = [];
    if (localStorage.getItem('userFilters') !== null) {
        filters = JSON.parse(localStorage.getItem('userFilters'));
    }
    checkFilters(filters);
    var apiToken = $("#data").html();
    var data = {'filters': filters};
    ajaxCall(data, apiToken, baseUrl);

    $("#filterActivities").click(function(){
        filters = [];
        $('input[type=checkbox]').each(function () {
            if(this.checked){
                filters.push($(this).val());
            }
        });
        console.log(filters);
        checkFilters(filters);
        localStorage.setItem('userFilters',JSON.stringify(filters));
        data = {'filters': filters};
        ajaxCall(data, apiToken, baseUrl);
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

function ajaxCall(data, apiToken, baseUrl){
    var url = "http://" + baseUrl + "/api/activeActivities"
    $.ajax({
        method: "GET",
        url: url,
        data: data,
        headers: {
            'X-AUTH-TOKEN':apiToken
        },
        success: function (response) {
            console.log('ok');
            console.log(response);
            showActivities(response, baseUrl);
        },
        error: function (response) {
            console.log(response);
            console.log('call failed')
        }
    });
}

function showActivities(activities, baseUrl){
    var html = "";

    activities.forEach(function(activity){
        var card = "      <div class=\"col s12 m4\">\n" +
            "                <div class=\"card large blue-grey darken-1\">\n" +
            "                    <div class=\"card-image\">\n";
        if(activity.main_image == null){
            card += "<img src=\"http://lorempixel.com/800/400/technics\" alt=\"\">\n"
        }else{
            var imageUrl = "http://" + baseUrl + "/uploads/" + activity.main_image;
            card += "<img src=\"" + imageUrl + "\" alt=\"\">\n"
        }

        card +=  "               </div>\n" +
            "                    <div class=\"card-content white-text\">\n" +
            "                        <span class=\"card-title\">" + activity.name;

        var activityShowUrl = "http://" + baseUrl + "/activity/" + activity.id;
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
    if(filters.length !== 0){
        $("#filterForm").show();
        $("#hideFilters").show();
        $("#showFilters").hide();
    }
}