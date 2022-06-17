$(".icon-edit").click(function(){
    var item_id = $(this).data('id');
    $("#product_section_"+item_id).slideToggle();
});

function menuFilter(url,content_class) {
    $(".no-result-found").html('');
    var ids = [];
    var checkValues = $('input[name="menu_category"]:checked').map(function()
    {
        return $(this).val();
    }).get();
    $.ajax(
    {
        url : url,
        type : 'post',
        data : {"_token":csrf_token ,"ids":checkValues},
        dataType:'json',
        success:function(data, textStatus, jqXHR) 
        { 
            if(data.html.length > 0){
                $(".no-item").empty();
                $("."+content_class).html(data.html);
            }else{
                $("."+content_class).html('<div class="text-center no-result-found mt-3">No items match your search.</div>');
            }
        },
        error: function(jqXHR, textStatus, errorThrown) 
        {
            console.log(jqXHR);
        }
    });
}
function userStoreUpdateStatus(user_id,id) {
    var url = base_url + '/edit-status/' + id.value;
    $("#open_close_shop label").removeClass('btn-checked');
    $.ajax(
    {
        url : url,
        type : 'GET',
        success:function(data, textStatus, jqXHR) 
        { 
            if(data != null){
                new Noty({
                    theme: ' alert alert-success alert-styled-left p-0 bg-green',
                    text: data.responseMessage,
                    type: data.success,
            }).show();
                setTimeout(function(){
                    location.reload();
                }, 3000);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) 
        {
            new Noty({
                    theme: ' alert alert-danger alert-styled-left p-0 bg-red',
                    text: jqXHR.responseJSON.responseMessage,
                    type: jqXHR.responseJSON.data,
            }).show();
            setTimeout(function(){
                location.reload();
            }, 3000);
        }
    });
}
function userStoreUpdateDeliveryStatus(user_id,id) {
    var url = base_url + '/edit-delivery-status';
    // $("#pick_del_check btn-check").
    var check_id = $(id).attr('id');
    if($("#"+check_id).is(":checked")){
        $("#"+check_id).removeAttr('checked',false);
    }
    var checkValues = $('input[name="store_delivery_option"]:checked').map(function()
    {
        return $(this).val();
    }).get();
    $.ajax(
    {
        url : url,
        type : 'POST',
        data : {"_token":csrf_token, ids: checkValues},
        success:function(data, textStatus, jqXHR) 
        { 
            if(data != null){
                new Noty({
                    theme: ' alert alert-success alert-styled-left p-0 bg-green',
                    text: data.responseMessage,
                    type: data.success,
            }).show();
                setTimeout(function(){
                    location.reload();
                }, 3000);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) 
        {
            new Noty({
                    theme: ' alert alert-danger alert-styled-left p-0 bg-red',
                    text: jqXHR.responseJSON.responseMessage,
                    type: jqXHR.responseJSON.data,
            }).show();
            setTimeout(function(){
                location.reload();
            }, 3000);
        }
    });
}
function deleteUserMenuItem(menuitem_id) {
    var url = base_url + '/delete-item';
    event.preventDefault()
    if(menuitem_id > 0){
        var ctext = 'Yes, delete!';
        var title = "Are you sure you want to delete this menu item?";
        var success = "Your menu item will be deleted."
    }
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
                data: {"_token":csrf_token, id: menuitem_id},
                success: function(data) {
                    if (data) {
                        Swal.fire(
                            'Success!',
                            success,
                            'success'
                        ).then((result) => {
                            if (result.value) {
                                location.reload()
                            }
                        })
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) 
                {
                    Swal.fire(
                        'Error!',
                        jqXHR.responseJSON.data,
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
function uploadBanner(input) {
    var url = base_url + '/change-banner';

    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            // $('#user_img').attr('src', e.target.result);
            var ctext = 'Yes, upload it!';
            var title = "Are you sure you want to change your banner?";
            var success = "Your banner will be changed."
            Swal.fire({
                title: title,
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: ctext
            }).then((result) => {
                var formData = new FormData();
                formData.append('banner_image', input.files[0]);

                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('input[name=_token]').val()
                        }
                    });
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(data) {
                            if (data) {
                                Swal.fire(
                                    'Success!',
                                    success,
                                    'success'
                                ).then((result) => {
                                    if (result.value) {
                                        location.reload()
                                    }
                                })
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) 
                        {
                            Swal.fire(
                                'Error!',
                                jqXHR.responseJSON.data,
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
        };
        reader.readAsDataURL(input.files[0]);
    }
}
$(".u-share-hover .share-a").remove();
// Ratings for mobile
$('.rating-li').click(function(){
    $('.u-contact-hover').toggleClass('mobile-ratings');
    $('.u-share-hover').removeClass('mobile-sharing');
    $('.u-favorite-hover').removeClass('mobile-favorites');
    $('.overlap-sharing').removeClass('open');
    $('.overlap-favorites').removeClass('open');
     $('.overlap-ratings').toggleClass('open');
});
$('.overlap-ratings').click(function(){
    $('.u-contact-hover').removeClass('mobile-ratings');
     $('.overlap-ratings').removeClass('open');
});
// Share options for mobile
$('.share-li').click(function(){
    $('.u-share-hover').toggleClass('mobile-sharing');
    $('.u-contact-hover').removeClass('mobile-ratings');
    $('.u-favorite-hover').removeClass('mobile-favorites');
    $('.overlap-ratings').removeClass('open');
    $('.overlap-favorites').removeClass('open');
     $('.overlap-sharing').toggleClass('open');
});
$('.overlap-sharing').click(function(){
    $('.u-share-hover').removeClass('mobile-sharing');
     $('.overlap-sharing').removeClass('open');
});
// Favorites for mobile
$('.favorite-li').click(function(){
    $('.u-favorite-hover').toggleClass('mobile-favorites');
    $('.u-share-hover').removeClass('mobile-sharing');
    $('.u-contact-hover').removeClass('mobile-ratings');
    $('.overlap-sharing').removeClass('open');
    $('.overlap-ratings').removeClass('open');
     $('.overlap-favorites').toggleClass('open');
});
$('.overlap-favorites').click(function(){
    $('.u-favorite-hover').removeClass('mobile-favorites');
     $('.overlap-favorites').removeClass('open');
});
// Close edit menu
$(".close-edit").click(function () {
    var id = $(this).data('id');
    $("#product_section_"+id).slideToggle();
})

$('.homestore-btn').click(function () {
    $(".addmenu-btn").removeClass('open');
    $('#menuFoodForm').hide();
    $('.main-content .addmenu-head .mobile-tabs').slideToggle();
    $(".addmenu-btn").removeClass('edit-menu-active');
    $(this).addClass('edit-menu-active');
});

$(".addmenu-btn").click(function(){
    $(".homestore-btn").removeClass('open');
    $('#homestoreBtn').hide();
    $(this).addClass('edit-menu-active');
    $(".homestore-btn").removeClass('edit-menu-active');
    $(".addmenu-body").slideToggle();
});

 /*$('.addmenu-head a').on('click', function () {
      $('.addmenu-head a.edit-menu-active').removeClass('edit-menu-active');
      $(this).addClass('edit-menu-active');
      console.log($("#homestoreBtn").css('display','none').length);
    });*/
function mobMenuFilterOption(url,content_class) {
    var checkValues = $('input[name="homemob_menu"]:checked').map(function()
    {
        return $(this).val();
    }).get();
    $.ajax(
    {
        url : url,
        type : 'post',
        data : {"_token":csrf_token ,"ids":checkValues},
        dataType:'json',
        beforeSend: function(){
            $(".spin-loader").show();
        },
        success:function(data, textStatus, jqXHR)
        {
            if(data.html.length > 0){
                $(".no-item").empty();
                $("."+content_class).html(data.html);
            }else{
                $("."+content_class).html('<div class="text-center no-result-found mt-3">No items match your search.</div>');
            }
        },
        error: function(jqXHR, textStatus, errorThrown)
        {
            //if fails
        }
    });
}