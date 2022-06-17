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
        <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold"></span>Dashboard</h4>
      </div>
    </div>
  </div>
@endsection
@section('content')
<div class="container-fluid bg-white">
  <div class="row">
    <div class="col-lg-4 dashboard-content">
      <div class="bg-info-400 panel-cont">
        <div class="panel-body">
          <h3 class="no-margin"><i class="icon-users2 dashboard-icon"></i> <br>Users <br>{{ (isset($users) ? $users : '') }}</h3>
        </div>
        <div id="today-revenue"></div>
      </div>
    </div>
    <div class="col-lg-4 dashboard-content">
      <div class="bg-primary-400 panel-cont">
        <div class="panel-body">
          <h3 class="no-margin"><i class="icon-list3"></i> <br>Categories <br>{{ (isset($categories) ? $categories : '') }}</h3>
        </div>
        <div id="today-revenue"></div>
      </div>
    </div>
    <div class="col-lg-4 dashboard-content">
      <div class="bg-success-400 panel-cont">
        <div class="panel-body">
          <h3 class="no-margin"><i class="icon-menu2 dashboard-icon"></i> <br>Menus <br>{{ (isset($menus) ? $menus : '') }}</h3>
        </div>
        <div id="today-revenue"></div>
      </div>
    </div>
    <div class="col-lg-4 dashboard-content">
      <div class="bg-grey-400 panel-cont">
        <div class="panel-body">
          <h3 class="no-margin"><i class="icon-store dashboard-icon"></i> <br>Dishes <br>{{ (isset($dishes) ? $dishes : '') }}</h3>
        </div>
        <div id="today-revenue"></div>
      </div>
    </div>
  </div>
</div>
@endsection

