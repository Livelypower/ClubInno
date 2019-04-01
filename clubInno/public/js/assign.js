$(document).ready(function () {
    $.ajax({
        method: "GET",
        url: "http://localhost:8000/api/activity/groups/2/",
        success: function (response) {
            $.each(response.groups, function (index, element) {
                $("#content").append('<ul id="group'+(index+1)+'" class="sortable"><h4 class="white-text">'+element.name+'</h4><p class="white-text">Drag here</p>');
                $.each(element.users, function (index2, element2) {
                    $('#group'+(index+1)).append('<li class="chip" value="'+ element.id +'">'+element2.first_name + ' ' + element2.last_name +'</li>');
                });
                $("#content").append('<button class="btn green accent-4 button-press" id="saveGroup'+(index+1)+'">Sauvegarder</button></ul>');
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
        $("#unassignedUsers, .sortable" ).sortable({
            items: "> li",
            connectWith: "#unassignedUsers, #group1, #group2",
            receive: function( event, ui ) {
                console.log("dropped! " + ui.item.val());
            }
        });
    }
});