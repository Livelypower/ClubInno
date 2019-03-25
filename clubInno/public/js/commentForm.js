$(document).ready(function(){
    $('#openCommentForm').click(function(){
        $('#commentView').toggle();
    });
    getData();
    $('#addComment').click(function () {
        var formContent = {
            "blogPost" : $('#postId').val(),
            "userId" : $('#userId').val(),
            "body" : $('#commentBody').val()
        };
        console.log(formContent);
        $.ajax({
            method: "POST",
            url: "http://localhost:8000/api/comment/add",
            data: formContent,
            success: function (response) {
                console.log(response);
                $('#commentBody').val("");
                getData();
                $('#commentSection').empty();
            },
            error: function (response) {
                console.log(response)
            }
        });
    });
});


function getData() {
    var blogPost = $('#postId').val();
    $.ajax({
        method: "GET",
        url: "http://localhost:8000/api/blog/"+blogPost+"/comments",
        success: function (response) {
            $.each(response.comments, function (index, value) {
                $('#commentSection').append("<div class=\"row\">\n" +
                    "                            <div class=\"col s12\">\n" +
                    "                                <div class=\"card blue-grey lighten-2\">\n" +
                    "\n" +
                    "                                    <div class=\"card-content white-text\">\n" +
                    "                                        <div class=\"card-title white-text\">\n" +
                    "                                            " + value.user.first_name + " " + value.user.last_name + "<span\n" +
                    "                                                    style=\"font-style: italic\"> "+ value.datetime + "</span>\n" +
                    "                                        </div>\n" +
                    "                                        <p>"+ value.body +"</p>\n" +
                    "                                    </div>\n" +
                    "                                </div>\n" +
                    "                            </div>\n" +
                    "                        </div>");
            });
        },
        error: function (response) {
            console.log(response);
            console.log('call failed')
        }
    });
}
