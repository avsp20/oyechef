var page = 1;
loadFeeds(page);
// var masonryOptions = {
//     // columnWidth: 10,
//     itemSelector: '.grid-item',
//     // isFitWidth: true
// };
// var $grid = $('.list-news-feed');
// $grid.masonry(masonryOptions);
var container = $('.list-news-feed');
container.imagesLoaded( function(){
    container.masonry({
        itemSelector : '.grid-item'
    });
});
$(document).ready(function() {

    var isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
    if (isMobile) {
        $(document.body).on('touchmove', onScroll); // for mobile
        $(window).on('scroll', onScroll);
    } else {
        $(window).data('ajaxready', true).scroll(function(e) {
            if ($(window).data('ajaxready') == false) return;
            if ($(window).scrollTop() >= ($(document).height() - $(window).height())) {
                $(window).data('ajaxready', false);
                page++;
                loadFeeds(page);
            }
        });
    }
});

function onScroll() {
    var addition = ($(window).scrollTop() + window.innerHeight);
    var scrollHeight = (document.body.scrollHeight - 5);
    if (addition > scrollHeight && page < addition) {
        page = addition;
        loadFeeds(page);
    }
}

function loadFeeds(page) {
    var url = base_url + '/load-feeds-on-scroll/' + page;
    var id_arr = [];
    var check_arr = [];
    $.ajax({
        url: url,
        type: "GET",
        datatype: "html",
        async: false,
        cache: false,
        beforeSend: function() {
            $(".spin-loader").show();
        },
        complete: function() {
            $(".spin-loader").hide();
        },
        success: function(data) {
            if(feed_url == "get-news-feed-ids"){
                var load_url = base_url + '/' + feed_url;
            }else if(feed_url == "get-user-news-feed-ids"){
                var load_url = base_url + '/' + feed_url + '/' + uId;
            }
            $(window).data('ajaxready', true);
            $(".spin-loader").hide();
            if (page > 1) {
                $(".feed-post").each(function(i) {
                    var id = $(this).data('id');
                    id_arr[i] = id;
                });
                $.ajax({
                    url: load_url,
                    type: "POST",
                    data: {
                        "_token": csrf_token,
                        "ids": $.unique(id_arr)
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.data != null || response.data != "") {
                            // $grid.append(response.data);
                            // $grid.masonry('destroy');
                            // $grid.masonry(masonryOptions);
                            $(document).find('.list-news-feed .feed-post:last').addClass('last-child');
                        }
                    },
                    error: function() {

                    }
                });
            } else {
                $(document).find('.list-news-feed .feed-post:last').addClass('last-child');
            }
        },
        error: function() {}
    });
}

function likeUserFeed(id) {
    $.ajax({
        type: "GET",
        url: base_url + '/like-feed/' + id,
        success: function(data) {
            if (data.status == 1) {
                $("#like_feed_" + parseInt(data.data.feed_id)).attr('class', 'far fa-thumbs-up');
                $("#like_feed_" + parseInt(data.data.feed_id)).css('color', '#73e5ea');
                $(".like_feed_cnt_" + parseInt(data.data.feed_id)).text(data.data.feed_count);
            } else {
                $("#like_feed_" + parseInt(data.data.feed_id)).attr('class', 'far fa-thumbs-up');
                $("#like_feed_" + parseInt(data.data.feed_id)).css('color', '#030303');
                $(".like_feed_cnt_" + parseInt(data.data.feed_id)).text(data.data.feed_count);
            }
        },
        error: function(error) {
            if (error.responseJSON.status == 0) {
                new Noty({
                    theme: ' alert alert-danger alert-styled-left p-0 bg-red',
                    text: error.responseJSON.error,
                    type: "danger",
                }).show();
            }
        }
    });
}

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            if (input.files[0].type == "video/mp4") {
                $("#feed_image").hide();
                $(".video-file .vd-file").html('<video controls>' +
                    '<source src="' + base_url + '/public/frontend/img/feeds/' + input.files[0].name + '" type="' + input.files[0].name + '">' +
                    '</video>');
            } else {
                $('#feed_image').attr('src', e.target.result);
                $(".vd-file video").hide();
                $("#feed_image").show();
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}
$("#search_data").autocomplete({
    source: function(request, response) {
        if (request.term.length > 0) {
            searchFollowers(request.term);
        } else {
            $(".search-follow").empty();
        }
    },
    minLength: 3
}).keyup(function() {
    if ($(this).val() == "") {
        $(".search-follow").empty();
    }
});
$("#search_follower").submit(function(e)
{   
    e.preventDefault(); 
    //STOP default action
    var follower = $("#search_data").val();
    searchFollowers(follower);
});

function searchFollowers(follower) {
    var formElement = document.getElementById("search_follower");
    var formData = new FormData(formElement);
    var action = $("#search_follower").attr('action');
    $.ajax({
        url: action,
        dataType: "json",
        method: "GET",
        data: {
            term: follower
        },
        success: function(data) {
            $(".search-follow").empty();
            let name = '';
            var lname = '';
            if (data.status == 1) {
                if (data.data.length > 0) {
                    var resp = $.map(data.data, function(obj) {
                        if (obj.user_meta != null) {
                            var user_img = base_url + '/public/frontend/img/no-profile-img.jpg';
                            if (obj.user_meta.user_image != null) {
                                var user_img = base_url + '/public/frontend/img/user_profiles/' + obj.user_meta.user_image;
                            }
                            if (obj.user_meta.lname != null) {
                                lname = obj.user_meta.lname;
                            }
                            if (obj.user_meta.is_username_active == 1) {
                                name = obj.user_meta.username;
                            } else {
                                name = obj.name + ' ' + lname;
                            }
                        } else {
                            lname = obj.lname;
                            name = obj.name + ' ' + lname;
                        }
                        $(".search-follow").append('<a class="follower-user" href="' + base_url + '/profile/' + obj.user_id + '"><li>' +
                            '<div class="chef-detail">' +
                            '<img src="' + user_img + '" class="mCS_img_loaded">' +
                            '<span class="chef-name">' + name + '</span>' +
                            '</div>' +
                            '<div class="icon-add-follow">' +
                            '<a href="#">' +
                            '<i class="fas fa-plus-circle"></i>' +
                            '</a>' +
                            '</div>' +
                            '</li></a>');
                    });
                } else {
                    new Noty({
                        theme: ' alert alert-success alert-styled-left p-0 bg-red',
                        text: data.success,
                        type: "success",
                    }).show();
                }
            }
        },
        error: function(error) {
            if (error.responseJSON.status == 0) {
                new Noty({
                    theme: ' alert alert-danger alert-styled-left p-0 bg-red',
                    text: error.responseJSON.error,
                    type: "danger",
                }).show();
            }
        }
    });
}

function deleteFeedComment(comment_id) {
    var url = base_url + '/delete-comment';
    event.preventDefault()
    var ctext = 'Yes, Delete Comment!';
    var title = "Are you sure you want to delete this comment?";
    var success = "Comment Deleted Successfully!"
    Swal.fire({
        title: title,
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: ctext
    }).then((result) => {
        if (result.value) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('input[name=_token]').val()
                }
            });
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    comment_id: comment_id
                },
                success: function(data) {
                    if (data) {
                        Swal.fire(
                            'Success!',
                            success,
                            'success'
                        ).then((result) => {
                            if (result.value) {
                                $("#feed_id_" + data.data.id).remove();
                                setTimeout(function() {
                                    location.reload()
                                }, 2000);
                            }
                        })
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Swal.fire(
                        'Error!',
                        jqXHR.responseJSON.error,
                        'error'
                    ).then((result) => {
                        if (result.value) {
                            location.reload()
                        }
                    })
                }
            })
        }
    })
}

function editComments(comment_id) {
    $("#edit-cmt_" + comment_id).hide();
    $(".cmt-desc_" + comment_id).hide();
    $("#comment_desc_" + comment_id).show();
    $("#comment_desc_" + comment_id + " input").removeAttr('readonly');
}

function cancelComment(comment_id) {
    $("#comment_desc_" + comment_id).hide();
    $(".cmt-desc_" + comment_id).show();
    $("#edit-cmt_" + comment_id).show();
}

function editUserComment(comment_id) {
    var url = base_url + '/edit-comment';
    var comment = $("#comment_" + comment_id).val();
    $.ajax({
        type: "POST",
        url: url,
        data: { "_token": csrf_token, comment_id: comment_id, comment: comment },
        success: function(data) {
            if (data.data != null) {
                $("#comment_desc_" + comment_id).hide();
                $(".cmt-desc_" + comment_id).text(data.data.comment);
                $(".cmt-desc_" + comment_id).show();
                $("#edit-cmt_" + comment_id).show();
                new Noty({
                    theme: ' alert alert-success alert-styled-left p-0 bg-green',
                    text: data.success,
                    type: "success",
                }).show();
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            new Noty({
                theme: ' alert alert-danger alert-styled-left p-0 bg-green',
                text: jqXHR.responseJSON.error,
                type: "danger",
            }).show();
        }
    })
}