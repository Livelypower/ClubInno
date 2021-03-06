$(document).ready(function(){
    var baseUrl = "vps589558.ovh.net";
    var apiToken = $('#data').html();
    $('#openCommentForm').click(function(){
        if($('#userId').val() != ""){
            $('#commentView').toggle();
        }else{
            console.log("not logged in");
            var instance = M.Modal.getInstance($('#loginModal'));
            instance.open();
        }
    });
    getData();

    $("#commentSection").on('click', '.dltcmt', function(){
        event.stopPropagation();
        event.stopImmediatePropagation();
        var commentId = $(this).data('comment');
        deleteComment(commentId);
    }).on('click', '.edtcmt', function(){
        var id = $(this).data('comment');
        var textselector = "#text-" + id;
        var buttonselector = '#svedit-' + id;
        var deleteselector = '#dltcmt-' + id;
        var stopselector = '#spedit-' +id;
        var editselector = '#edtcmt-' + id;
        var text = $(textselector).text();
        $(textselector).text("");
        $(textselector).append(
            "<input id='input-" + id + "' value='" + text + "' data-comment='" + id + "' type='text'>"
        );
        $(buttonselector).css('display', 'inline');
        $(stopselector).css('display', 'inline');
        $(editselector).css('display', 'none');
        $(deleteselector).css('display', 'none');
    }).on('click', '.svedit', function(){
        var id = $(this).data('comment');
        var inputselector = "#input-" + id;
        var buttonselector = '#svedit-' + id;
        var stopselector = '#spedit-' + id;
        var editselector = '#edtcmt-' + id;
        var deleteselector = '#dltcmt-' + id;
        var text = $(inputselector).val();

        $(buttonselector).css('display', 'none');
        $(stopselector).css('display', 'none');
        $(editselector).css('display', 'inline');
        $(deleteselector).css('display', 'inline');

        editComment(id, text);
    }).on('click', '.spedit', function(){
        var id = $(this).data('comment');
        var inputselector = "#input-" + id;
        var buttonselector = '#svedit-' + id;
        var stopselector = '#spedit-' + id;
        var editselector = '#edtcmt-' + id;
        var deleteselector = '#dltcmt-' + id;
        var textselector = "#text-" + id;
        var text = $(inputselector).val();

        $(buttonselector).css('display', 'none');
        $(stopselector).css('display', 'none');
        $(editselector).css('display', 'inline');
        $(deleteselector).css('display', 'inline');

        $(textselector).text(text);
    });



    $('#addComment').click(function () {
        var formContent = {
            "blogPost" : $('#postId').val(),
            "userId" : $('#userId').val(),
            "body" : $('#commentBody').val()
        };
        console.log(formContent);
        $.ajax({
            method: "POST",
            url: "http://" + baseUrl + "/api/comment/add",
            data: formContent,
            headers: {
                'X-AUTH-TOKEN': apiToken
            },
            success: function (response) {
                console.log(response);
                $('#commentBody').val("");
                getData();
                $('#commentSection').empty();
                $('#commentView').toggle();
            },
            error: function (response) {
                console.log(response)
            }
        });
    });

    function getData() {
        var blogPost = $('#postId').val();
        $.ajax({
            method: "GET",
            url: "http://" + baseUrl + "/api/blog/"+blogPost+"/comments",
            headers: {
                'X-AUTH-TOKEN':apiToken
            },
            success: function (response) {
                $.each(response.comments, function (index, value) {
                    var date = new Date(Date.parse(value.datetime));
                    var dateString = date.getDate() + "/" + date.getMonth() + "/" + date.getFullYear()
                        + " " + date.getHours() + ":" + (date.getMinutes()<10?'0':'') + date.getMinutes();
                    var userId = $('#commentSection').data('user');
                    console.log("userId: " + userId);
                    console.log("comment userId: " + value.user.id);
                    var htmlData = "";
                    if(value.user.id === userId){
                        htmlData = "<div class=\"row\">\n" +
                            "                            <div class=\"col s12\">\n" +
                            "                                <div class=\"card blue-grey lighten-2\">\n" +
                            "\n" +
                            "                                    <div class=\"card-content white-text\">\n" +
                            "                                        <div class=\"card-title white-text\">\n" +
                            "                                            " + value.user.first_name + " " + value.user.last_name + "<span\n" +
                            "                                                    style=\"font-style: italic\"> "+ dateString + "</span>\n" +
                            "                                        </div>\n" +
                            "                                        <p><span id='text-"+ value.id + "'>" +value.body + "</span>\n" +
                            "                                               <a id='dltcmt-" + value.id +  "' class='dltcmt' data-comment='" + value.id + "'><i class='material-icons right'>delete</i></a>\n" +
                            "                                               <span id='edtcmt-" + value.id +  "' class='edtcmt' data-comment='" + value.id + "'><a><i class='material-icons right'>edit</i>\n" +
                            "                                           </a></span>\n" +
                            "                                               <span class='spedit' id='spedit-" + value.id +"' data-comment='" + value.id + "' style='display: none'><a><i class='material-icons right'>clear</i></a></span>\n" +
                            "                                               <span class='svedit' id='svedit-" + value.id +"' data-comment='" + value.id + "' style='display: none'><a><i class='material-icons right'>done</i>\n" +
                            "                                           </a></span>\n" +
                            "                                        </p>\n" +
                            "                                    </div>\n" +
                            "                                </div>\n" +
                            "                            </div>\n" +
                            "                        </div>"
                    }
                    else{
                        htmlData = "<div class=\"row\">\n" +
                            "                            <div class=\"col s12\">\n" +
                            "                                <div class=\"card blue-grey lighten-2\">\n" +
                            "\n" +
                            "                                    <div class=\"card-content white-text\">\n" +
                            "                                        <div class=\"card-title white-text\">\n" +
                            "                                            " + value.user.first_name + " " + value.user.last_name + "<span\n" +
                            "                                                    style=\"font-style: italic\"> "+ dateString + "</span>\n" +
                            "                                        </div>\n" +
                            "                                        <p>"+ value.body + "\n" +
                            "                                        </p>\n" +
                            "                                    </div>\n" +
                            "                                </div>\n" +
                            "                            </div>\n" +
                            "                        </div>"
                    }

                    $('#commentSection').append(htmlData);
                });
            },
            error: function (response) {
                console.log(response);
                console.log('call failed')
            }
        });
    }

    function deleteComment(commentId){
        $.ajax({
            method: "DELETE",
            url: "http://" + baseUrl+ "/api/comment/delete/" + commentId,
            headers: {
                'X-AUTH-TOKEN':apiToken
            },
            success: function (response) {
                console.log(response);
                $('#commentBody').val("");
                getData();
                $('#commentSection').empty();
            },
            error: function (response) {
                console.log(response);
                console.log('call failed')
            }
        });
    }

    function editComment(id, text){
        console.log(text);
        var formContent = {
            "comment" : text
        };
        $.ajax({
            method: "PATCH",
            url: "http://" + baseUrl + "/api/comment/update/" + id,
            data: formContent,
            headers: {
                'X-AUTH-TOKEN':apiToken
            },
            success: function (response) {
                console.log(response);
                $('#commentBody').val("");
                getData();
                $('#commentSection').empty();
            },
            error: function (response) {
                console.log(response);
                console.log('call failed')
            }
        });
    }
});




