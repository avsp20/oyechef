$(document).ready(function() {
    // $('.mobile-foodmap').on('click', function() {
    //     $(".mobile-foodmap").toggleClass("hide-map");
    //     $(".mobile-loc-map-wrapper").toggleClass("hide-map");
    // });
    setInterval(function() {
        var add_section = $(".add-post-toggle").css("display");
        var follower_section = $(".menu-wrapper-content").css("display");
        if (add_section == 'none') {
            // if (follow_section == 'none') {
            if (follower_section == 'none') {
                $("body").removeClass("no-scroll");
            }
            // }
        }
    }, 500);
    $(".map-wrapper").click(function() {
        // alert("mapyes");
        $('.mobilesidebar').removeClass('show');
        $('.mobilemenu-icon.btn-filter').attr('aria-expanded', 'false');
        $('.home-page').removeClass('no-scroll');
        $(".main-content-toggle-data .map-wrapper-content").slideToggle();
        $(".main-content-toggle-data .search-wrapper-content").css("display", "none");
        $(".main-content-toggle-data .menu-wrapper-content").css("display", "none");
        $(".main-content-toggle-data .mobilemenu-icon.btn-filter").css("display", "none");
        $(".map-wrapper").toggleClass("open");
        $(".search-wrapper").removeClass("open");
        $(".menu-wrapper").removeClass("open");
        $(".mobilemenu-icon.btn-filter").removeClass("open");
        var add_section = $(".map-wrapper-content").css("display");
        var follow_section = $(".search-wrapper-content").css("display");
        var follower_section = $(".menu-wrapper-content").css("display");
        var left_filter_section = $(".mobilemenu-icon.btn-filter").css("display");
        if (add_section == 'block' || follow_section == 'block' || follower_section == 'block') {
            $("body").addClass("no-scroll");
        }
        // $("body").toggleClass("no-scroll");
    });
    $(".search-wrapper").click(function() {
        // alert("searchyes");
        $('.mobilesidebar').removeClass('show');
        $('.mobilemenu-icon.btn-filter').attr('aria-expanded', 'false');
        $('.home-page').removeClass('no-scroll');
        $(".main-content-toggle-data .search-wrapper-content").slideToggle();
        $(".main-content-toggle-data .map-wrapper-content").css("display", "none");
        $(".main-content-toggle-data .menu-wrapper-content").css("display", "none");
        $(".search-wrapper").toggleClass("open");
        $(".map-wrapper").removeClass("open");
        $(".menu-wrapper").removeClass("open");
        var add_section = $(".map-wrapper-content").css("display");
        var follower_section = $(".menu-wrapper-content").css("display");
        if (add_section == 'block' || /*follow_section == 'block' || */follower_section == 'block') {
            $("body").addClass("no-scroll");
        }
    });
    $(".mobilemenu-icon.btn-filter").click(function() {
        // alert("searchyes");

        // $(".main-content-toggle-data .map-wrapper-content").slideToggle();
        // $(".main-content-toggle-data .search-wrapper-content").css("display", "none");
        // $(".search-wrapper").removeClass("open");
        // $(".menu-wrapper").removeClass("open");

    });
    $(".mobilemenu-icon.btn-filter").click(function() {
        //  $(".main-content-toggle-data .menu-wrapper-content").slideToggle();
        $(".main-content-toggle-data .map-wrapper-content").css("display", "none");
        $(".main-content-toggle-data .search-wrapper-content").css("display", "none");
        //  $(".menu-wrapper").toggleClass("open");
        $(".map-wrapper").removeClass("open");
        $(".search-wrapper").removeClass("open");
        var add_section = $(".map-wrapper-content").css("display");
        var follower_section = $(".menu-wrapper-content").css("display");
        if (add_section == 'block' || /*follow_section == 'block' ||*/follower_section == 'block') {
            $("body").addClass("no-scroll");
        }
    });
    // $(".menu-wrapper").click(function() {
    //     $(".main-content-toggle-data .menu-wrapper-content").slideToggle();
    //     $(".main-content-toggle-data .map-wrapper-content").css("display", "none");
    //     $(".main-content-toggle-data .search-wrapper-content").css("display", "none");
    //     $(".menu-wrapper").toggleClass("open");
    //     $(".map-wrapper").removeClass("open");
    //     $(".search-wrapper").removeClass("open");
    //     var add_section = $(".map-wrapper-content").css("display");
    //     var follower_section = $(".menu-wrapper-content").css("display");
    //     if (add_section == 'block' || follow_section == 'block' || follower_section == 'block') {
    //         $("body").addClass("no-scroll");
    //     }
    // });
});

function deleteFollowingUser(user_id) {
    var url = base_url + '/remove-following';
    event.preventDefault()
    var ctext = 'Yes, delete!';
    var title = "Are you sure you want to delete person you're following?";
    var success = "Your following user will be removed."
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
                data: { "_token": csrf_token, id: user_id },
                success: function(data) {
                    if (data) {
                        Swal.fire(
                            'Success!',
                            success,
                            'success'
                        ).then((result) => {
                            if (result.value) {
                                location.reload();
                            }
                        })
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    Swal.fire(
                        'Error!',
                        jqXHR.responseJSON.data,
                        'error'
                    ).then((result) => {
                        if (result.value) {
                            location.reload();
                        }
                    })
                }
            })
        }
    })
}

function showComments(post_id) {
    $.ajax({
        type: "GET",
        url: base_url + '/show-comment/' + post_id,
        processData: false,
        contentType: false,
        success: function(data) {
            $("#commentmodal").empty();
            $("#commentmodal").append(data);
            $("#commentmodal").modal('show');
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