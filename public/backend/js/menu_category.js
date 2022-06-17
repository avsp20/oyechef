$(".menu-category form").submit(function(e)
{   //STOP default action
    var postData = $(this).serializeArray();
    var formURL = $(this).attr("action");
    var formid =$(this).attr("id");
    var type = "POST";
    
    $.ajax(
    {
      url : formURL,
      type: type,
      headers: {
        'X-CSRF-Token': csrftoken 
      },
      data : postData,
      success:function(data, textStatus, jqXHR) 
      {
        if(data.status == 1){
          $(".success-error").html('');
          $(".success-error").html('<div class="alert alert-success">' + data.success + '</div>');
          $("#edit_menu_category_modal").modal('hide');
          $("#menu_category_modal").modal('hide');
          $('#category_form input').val(" ");
          $(".success-error").html('');
          $('#category_table').DataTable().ajax.reload();
        }
      },
      error: function(jqXHR, textStatus, errorThrown) 
      {
        if(!$.isEmptyObject(jqXHR.responseJSON.errors)){
          var category = jqXHR.responseJSON.errors.category_name;
          $(".success-error").html('<div class="alert alert-danger">' + category.toString() + '</div>');
        }
      }
    });
  e.preventDefault(); 
});
$(function() {
  var table = $('#category_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: window.location.href,
        columns: [
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'created_at', name: 'created_at'},
            {data: 'action', name: 'action'}
        ]
    });
  $('div.dataTables_length select').addClass('datatable-select');
});
function AddMenuCategoryModal() {
  $("#menu_category_modal").modal('show');
  $('#cat_id').val('');
  $(".modal-title").html('Add Menu Category');
  $('#category_form input').val(" ");
}
function CategoryEditFunction(id){ 
  var url = "edit-category";
  var editUrl = url + '/' + id + '/edit';
  $("#edit_menu_category_modal").modal('show');
  $(".modal-title").html('Edit Menu Category');
  $('#cat_id').val(id);
  $.ajax(
    {
      url : editUrl,
      type: "get",
      success:function(data, textStatus, jqXHR) 
      {
        if(data.status == 1)
        {
          $("#edit_menu_category_modal #category_name").val(data.data.name);
        }
      },
      error: function(jqXHR, textStatus, errorThrown) 
      {
        console.log(jqXHR);
      }
  });
}
function deleteCategory(id, el) {
  event.preventDefault()
          var ctext = 'Yes, Delete Category!';
          var title = "Are you sure you want to delete this category?";
          var success = "Category Deleted Successfully!"
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
        url: `delete-category/${id}`,
        success: function(data) {
          if (data) {
            Swal.fire(
              'Success!',
              success,
              'success'
            ).then((result) => {
              $('#category_table').DataTable().ajax.reload();
            })
          }
        }
      })
    }
  })
}