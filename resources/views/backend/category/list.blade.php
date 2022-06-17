@extends('backend.master')
@section('css')
<style type="text/css">
  .datatable-select{
    border: 1px solid #ccc;
    padding: 5px;
    height: 35px;
    border-radius: 3px;
  }
</style>
@endsection
@section('breadcrumb')
  <div class="page-header page-header-default">
    <div class="page-header-content">
      <div class="page-title">
        <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold"></span>Menu Categories</h4>
      </div>
    </div>
  </div>
@endsection
@section('content')
  <!-- Basic datatable -->
    <div class="panel panel-flat">
      <div class="panel-heading">
        <h5 class="panel-title">Manage Menu Category</h5>
        <div class="text-right">
          <button type="button" class="btn btn-info" onclick="AddMenuCategoryModal()">Add Menu Category <i class="icon-plus-circle2 position-right"></i></button>
        </div>
      </div>
      <div class="panel-body table-responsive">
        <table class="table table-bordered table-striped" id="category_table">
          <thead>
            <tr>
              <th>Sr. No.</th>
              <th>Name</th>
              <th>Created Date</th>
              <th data-orderable="false">Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  <!-- /basic datatable -->
  <!-- Vertical form modal -->
    <div id="menu_category_modal" class="modal fade">
      <div class="modal-dialog">
        <div class="modal-content menu-category">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h5 class="modal-title"></h5>
          </div>
          <form action="{{ route('admin.add-category') }}" method="POST" id="category_form">
            @csrf
            <div class="modal-body">
              <div class="form-group">
                <div class="success-error"></div>
                <div class="row">
                  <div class="col-sm-12">
                    <label>Category Name</label>
                    <input type="text" name="category_name" id="category_name" placeholder="Enter menu category name" class="form-control">
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-success">Save</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div id="edit_menu_category_modal" class="modal fade">
      <div class="modal-dialog">
        <div class="modal-content menu-category">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h5 class="modal-title"></h5>
          </div>
          <form action="{{ route('admin.update-category') }}" method="POST" id="category_form">
            @csrf
            <input type="hidden" name="cat_id" id="cat_id" value="">
            <div class="modal-body">
              <div class="form-group">
                <div class="success-error"></div>
                <div class="row">
                  <div class="col-sm-12">
                    <label>Category Name</label>
                    <input type="text" name="category_name" id="category_name" placeholder="Enter menu category name" class="form-control">
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-success">Save</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  <!-- /vertical form modal -->
@section('script')
  <script src="{{ asset('public/backend/js/custom.js') }}"></script>
  <script src="{{ asset('public/backend/js/menu_category.js') }}"></script>
  <script type="text/javascript">
    var csrftoken = '{{ csrf_token() }}';
  </script>
@endsection
@endsection

