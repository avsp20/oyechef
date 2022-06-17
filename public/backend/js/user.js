$(function() {
	var table = $('#users_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: window.location.href,
        columns: [
            {data: 'id', name: 'id'},
            {data: 'user_image', name: 'user_image'},
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'},
            {data: 'phone', name: 'phone'},
            {data: 'created_at', name: 'created_at'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action'},
        ]
    });
    $('div.dataTables_length select').addClass('datatable-select');
});
function deleteUser(id, el) {
  event.preventDefault()
          var ctext = 'Yes, Delete User!';
          var title = "Are you sure you want to delete this user?";
          var success = "User Deleted Successfully!"
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
        type: "GET",
        url: `delete-user/${id}`,
        success: function(data) {
          if (data) {
            Swal.fire(
              'Success!',
              success,
              'success'
            ).then((result) => {
              $('#users_table').DataTable().ajax.reload();
            })
          }
        }
      })
    }
  })
}
function activeUser(id,status) {
    event.preventDefault()
    if(status == 1){
        var ctext = 'Yes, make Active!';
        var title = "Are you sure you want to make this user active?";
        var success = "User account has been activated"
    }else{
        var ctext = 'Yes, make Inactive!';
        var title = "Are you sure you want to make this user inactive?";
        var success = "User account has been inactive"
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
                type: "GET",
                url: `user-status/${id}/${status}`,
                success: function(data) {
                    if (data) {
                        Swal.fire(
                            'Success!',
                            success,
                            'success'
                        ).then((result) => {
                            if (result.value) {
                                $('#users_table').DataTable().ajax.reload();
                                // location.reload()
                            }
                        })
                    }
                }
            })
        }
    })
}