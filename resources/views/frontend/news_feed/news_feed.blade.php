@php
    use App\Http\Controllers\Frontend\CommonController as Common;
    $following = Common::userFollowing();
    $profile = Common::profileCommon(request()->route('id'));
@endphp
@extends('frontend.profile.profile-master')
@section('css')
<style type="text/css">
    .nf-user, .share-post{
        cursor: pointer;
    }
    .action-btn .icon-delete{
        color: red;
    }
    .icon-cancel.icon-delete{
        color: red;
    }
    .follower-user{
        text-decoration: none;
    }
</style>
@endsection
@section('content')
<div class="main-content-top">
    <div class="main-content-head">
        <div class="add-post-wrapper">
            <!-- <i class="fas fa-plus-circle"></i> -->
            <img src="{{ asset('public/frontend/img/icons/button-black.png') }}" width="20"><span class="d-none d-md-block">Add Post</span>
        </div>
        <div class="followers-mobile d-block d-md-none">
            <i class="fas fa-user-friends"></i>
        </div>
        <div class="follow-wrapper follow-user">
            <i class="fas fa-heart"></i><span class="d-none d-md-block">follow</span>
        </div>
    </div>
    <!-- add post toggle -->
    @include('frontend.profile.post-form')
    <!-- end add post toggle -->
    @include('frontend.profile.following')
    <!-- follow toggle -->
    @include('frontend.profile.followers')
    <!-- end follow toggle -->
</div>
<!-- food list wrapper -->
<div class="news-feed-wrapper">
    <div class="">
        <div class="spinner-grow text-muted spin-loader" style="display: none;"></div>
    </div>
    @if(count($feeds) > 0)
        <div class="grid list-news-feed">
            @include('frontend.profile.list-feed')
        </div>
    @else
        <div class="text-center no-result-found">No Posts Yet. Create your first post.</div>
    @endif
    <!-- Loader -->
    <div class="row">
        <div class="col-12">
            <div class="load-more-food">
                <img src="{{ asset('public/frontend/img/loader.png') }}">
            </div>
        </div>
    </div>
    <!-- End Loader -->
</div>
<!-- food list wrapper -->
<div class="commentmodal modal fade" id="commentmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="commentmodalLabel" aria-hidden="true">
</div>
@endsection