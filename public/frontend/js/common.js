function giveRatings(user_id, rate) {
    var id = rate.id;
    var star = id.substring(5);
    var url = base_url + '/give-ratings';
    if(star >= 1){
        $.ajax(
        {
            url : url,
            type: "POST",
            headers: {
                'X-CSRF-Token': csrf_token 
            },
            data : { rating: star, user_id: user_id },
            success:function(data, textStatus, jqXHR) 
            {
                if(data != null){
                    new Noty({
                        theme: ' alert alert-success alert-styled-left p-0 bg-green',
                        text: data.responseMessage,
                        type: data.data,
                    }).show();
                    setTimeout(function(){
                        location.reload();
                    }, 3000);
                    $(".overlap").removeClass('open');
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
                    location.href = base_url + '/login';
                }, 3000);
            }
        });
    }
}
function deleteFavoriteUser(user_id) {
    var url = base_url + '/remove-favorites';
    event.preventDefault()
    var ctext = 'Yes, delete!';
    var title = "Are you sure you want to delete this favorite chef?";
    var success = "Your favorite chef item will be removed."
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
                data: {"_token":csrf_token, id: user_id},
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