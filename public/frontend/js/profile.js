$(document).ready(function() {
    setInterval(function() {
        var add_section = $(".add-post-toggle").css("display");
        var follow_section = $(".follow-btn-toggle").css("display");
        var follower_section = $(".followers-toggle").css("display");
        if (add_section == 'none') {
            if (follow_section == 'none') {
                if (follower_section == 'none') {
                    $("body").removeClass("no-scroll");
                }
            }
        }
    }, 500);
    $(".add-post-wrapper").click(function() {
        $(".main-content-body.add-post-toggle").toggle();
        $(".main-content-body.follow-btn-toggle").css("display", "none");
        $(".main-content-body.followers-toggle").css("display", "none");
        $(".add-post-wrapper").toggleClass("open");
        $(".follow-wrapper.follow-user").removeClass("open");
        $(".followers-mobile").removeClass("open");
        var add_section = $(".add-post-toggle").css("display");

        var follow_section = $(".follow-btn-toggle").css("display");
        var follower_section = $(".followers-toggle").css("display");
        if (add_section == 'block' || follow_section == 'block' || follower_section == 'block') {
            $("body").addClass("no-scroll");
        }
        // $("body").toggleClass("no-scroll");
    });
    $(".follow-wrapper.follow-user").click(function() {
        $(".main-content-body.follow-btn-toggle").toggle();
        $(".main-content-body.add-post-toggle").css("display", "none");
        $(".main-content-body.followers-toggle").css("display", "none");
        $(".follow-wrapper.follow-user").toggleClass("open");
        $(".add-post-wrapper").removeClass("open");
        $(".followers-mobile").removeClass("open");
        var add_section = $(".add-post-toggle").css("display");
        var follow_section = $(".follow-btn-toggle").css("display");
        var follower_section = $(".followers-toggle").css("display");
        if (add_section == 'block' || follow_section == 'block' || follower_section == 'block') {
            $("body").addClass("no-scroll");
        }
    });
    $(".followers-mobile").click(function() {
        $(".main-content-body.followers-toggle").toggle();
        $(".main-content-body.add-post-toggle").css("display", "none");
        $(".main-content-body.follow-btn-toggle").css("display", "none");
        $(".followers-mobile").toggleClass("open");
        $(".add-post-wrapper").removeClass("open");
        $(".follow-wrapper.follow-user").removeClass("open");
        var add_section = $(".add-post-toggle").css("display");
        var follow_section = $(".follow-btn-toggle").css("display");
        var follower_section = $(".followers-toggle").css("display");
        if (add_section == 'block' || follow_section == 'block' || follower_section == 'block') {
            $("body").addClass("no-scroll");
        }
    });
});