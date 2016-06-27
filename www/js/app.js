$(document).ready(function(){
    var postId = localStorage.getItem('postId');
    processingLoginForm();
    if (postId == null) {
        buildPosts();
    } else {
        buildPostItem(postId);
    }
});

$('#addPost').on('click', function() {
   $('#addPostModal').modal('show');
});

$('#login').on('click', function() {
    $.ajax({
        type: 'POST',
        url: "/api/v1.0/login",
        data: JSON.stringify({email : $('#email').val(), password: $('#password').val()}),
        success: function(){
            localStorage.setItem('login', true);
            processingLoginForm()
        },
        error: function(msg) {
            msg = JSON.parse(msg.responseText);
            alert('Error: ' + msg.error)
        }
    });
});

$('#logout').on('click', function() {
    $.ajax({
        type: 'POST',
        headers: {
         "X-Token": $.cookie('PHPSESSID')
        },
        url: "/api/v1.0/logout",
        success: function(){
            localStorage.removeItem('login');
            processingLoginForm()
        },
        error: function(msg) {
            msg = JSON.parse(msg.responseText);
            alert('Error: ' + msg.error)
        }
    });
});

$(document).on('click', '.brand', function() {
    buildPosts();
});

$(document).on('click', '.title', function() {
    var postId = $(this).parents('.postsItem').find('.id').text();
    buildPostItem(postId)
});

$(document).on('click', '#addComment', function() {
    var postId = $('.postsItemView .id').text();
    $.ajax({
        type: 'POST',
        url: "/api/v1.0/posts/" + postId + '/comments',
        data: JSON.stringify({body : $('#commentBody').val()}),
        success: function(){
            buildPostItem(postId)
        },
        error: function(msg) {
            msg = JSON.parse(msg.responseText);
            alert('Error: ' + msg.error)
        }
    });
});

$(document).on('click', '#savePost', function(){
    $.ajax({
        type: 'POST',
        headers: {
            "X-Token": $.cookie('PHPSESSID')
        },
        url: "/api/v1.0/posts",
        data: JSON.stringify({
            title : $('#title').val(),
            body : $('#body').val()
        }),
        success: function(data){
            data = JSON.parse(data);
            $('#addPostModal').modal('hide');
            buildPostItem(data.id)
        },
        error: function(msg) {
            msg = JSON.parse(msg.responseText);
            alert('Error: ' + msg.error)
        }
    });
});

function buildPosts() {
    $.ajax({
        url: "/api/v1.0/posts",
        success: function(data){
            $('#posts').html('');
            data = JSON.parse(data);
            data.forEach(function(post){
                var postHtml = $('<div class="postsItem">' + $('.post').html() + '</div>');
                $('#posts').append(fullObject(postHtml, post)).append('<hr/>');
            });
            localStorage.removeItem('postId');
        }
    });
}

function buildPostItem(postId) {
    localStorage.setItem('postId', postId);
    $.ajax({
        url: "/api/v1.0/posts/"+postId,
        success: function(data){
            var posts = $('#posts');
            posts.html('');
            data = JSON.parse(data);
            var postHtml = $('<div class="postsItemView">' + $('.postItem').html() + '</div>');
            postHtml = fullObject(postHtml, data);
            posts.append(postHtml);
            buildComments(postId)
        },
        error: function() {
            buildPosts();
        }
    });
}

function buildComments(postId) {
    $.ajax({
        url: "/api/v1.0/posts/"+postId+"/comments",
        success: function(data){
            data = JSON.parse(data);
            data.forEach(function(comment){
                var commentHtml = $('<div class="commentItem">' + $('.comment').html() + '</div>');
                commentHtml = fullObject(commentHtml, comment);
                $('#posts').append(commentHtml).append('<hr/>');
            });
        },
        error: function() {
            buildPostItem(postId)
        }
    });
}

function processingLoginForm() {
    if (Boolean(localStorage.getItem('login')) != true){
        $('#logoutForm').hide();
        $('#loginForm').show();
    } else {
        $('#loginForm').hide();
        $('#logoutForm').show();
    }
}

function fullObject(htmlObject, jsonObject) {
    var parentClass = htmlObject.attr('class');
    $.each(jsonObject, function(index, value) {
        if(htmlObject.find('.' + index).html() != null) {
            htmlObject = htmlObject.find('.' + index).text(value).parents('.' + parentClass);
        }

    });
    return htmlObject;
}