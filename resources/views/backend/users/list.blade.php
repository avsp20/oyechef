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
        <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold"></span>Users</h4>
      </div>
    </div>
  </div>
@endsection
@section('content')
  <!-- Basic datatable -->
    <div class="panel panel-flat">
      <div class="panel-heading">
        <h5 class="panel-title">Manage Users</h5>
      </div>
      <div class="panel-body table-responsive">
        <table class="table table-bordered table-striped" id="users_table">
          <thead>
            <tr>
              <th>Sr. No.</th>
              <th data-orderable="false" style="width: 15%;">Image</th>
              <th>Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Created Date</th>
              <th data-orderable="false">Status</th>
              <th data-orderable="false">Action</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  <!-- /basic datatable -->
@section('script')
  <script src="{{ asset('public/backend/js/custom.js') }}"></script>
  <script src="{{ asset('public/backend/js/user.js') }}"></script>
@endsection
@endsection

