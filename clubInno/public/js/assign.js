$(document).ready(function () {
    var activityId = $("#activityId").val();
    var groupIds = [];

    $.ajax({
        method: "GET",
        url: "http://localhost:8000/api/activity/groups/" + activityId + "/",
        success: function (response) {
            $.each(response.groups, function (index, element) {
                groupIds.push(element.id);
                $("#content").append('<ul class="group sortable" id="' + element.id + '" data-id="' + element.id + '"><h5 class="white-text">'+element.name+'</h5><p class="white-text">Drag here</p>');
                $.each(element.users, function (index2, element2) {
                    $('#' + element.id).append('<li class="chip" value="'+ element2.id +'">'+element2.first_name + ' ' + element2.last_name +'</li>');
                });
                $("#content").append('</ul>');
            });
            $.each(response.unassignedUsers, function (index, element) {
                $("#unassignedUsers").append('<li class="chip" value="'+ element.id +'">'+element.first_name + ' ' + element.last_name +'</li>');
            });
            createSortable();
        },
        error: function (response) {
            console.log("Error getting students");
            console.log(response);
        }
    });

    function createSortable() {
        var connectString = "#unassignedUsers, ";

        for (var i = 0; i < groupIds.length; i++) {
            connectString += "#" + groupIds[i];
            if(i !== groupIds.length-1){
                connectString += ", ";
            }
        }

        $("#unassignedUsers, .sortable" ).sortable({
            items: "> li",
            connectWith: connectString,
            receive: function( event, ui ) {
            }
        });
    }

    $("#saveGroup").click(function () {
        $(".group").each(function(){
            var userIds = [];
            $(this).find('li').each(function () {
                userIds.push($(this).val());
            });

            var groupId = $(this).data('id');
            console.log(userIds);

            $.ajax({
                method: "POST",
                url: "http://localhost:8000/api/activity/groups/addusers/" + groupId + "/",
                data: {"users": userIds},
                success: function (response) {
                    console.log(response);
                },
                error: function (response) {
                    console.log("Error posting students");
                    console.log(response);
                }
            });
        });

    })
});