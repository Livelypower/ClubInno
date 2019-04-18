$(document).ready(function(){
    var filters = [];

    $("#filterActivities").click(function(){
        $('input[type=checkbox]').each(function () {
            if(this.checked){
                filters.push($(this).val());
            }
        });
        var data = {'filters': filters};
        var apiToken = $("#data").html();

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
    });
});


function showActivities(activities){
    $('#activities').html('');
}