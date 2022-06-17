@php
    use App\Http\Controllers\Frontend\CommonController as Common;
    $username = Common::getUsername();
    $user_image = Common::getUserimage();
    $following = Common::userFollowing();
    $profile = Common::profileCommon(request()->route('id'));
@endphp
@extends('frontend.profile.profile-master')
@section('css')
<style type="text/css">
    .news-feed-widget .news-feed-img video {
        height: 100%;
        width: 100%;
        object-fit: cover;
        border-radius: 0;
    }
    .nf-user, .share-post{
        cursor: pointer;
    }
    .follower-user, .usr-profile{
        text-decoration: none;
    }
    .usr-profile .follow-wrapper{
        font-weight: 400;
    }
</style>
@endsection
@section('content')
<div class="profile-page-head">
    <div class="profile-main">
        <div class="profile-img">
            <img src="{{ $user_image }}">
            @if(Auth::guard('users')->check())
                @php
                    $usr_id = Common::decrypt(request()->route('id'));
                @endphp
                @if($usr_id == Auth::guard('users')->user()->id)
                    <a href="{{ route('front.my-account') }}" class="profile-setting">
                        <i class="fas fa-cog"></i>
                    </a>
                    <a class="btn-sign-out" href="javascript:void(0)" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Sign Out</span>
                    </a>
                @endif
            @endif
            <form id="logout-form" action="{{ route('front.logout') }}" method="POST" class="d-none">
              @csrf
            </form>
        </div>

        <h4 class="username">
            {{ $username }}
        </h4>
    </div>
    <div class="main-content-top">
        <div class="main-content-head">
            @if($profile == 1)
                <div class="add-post-wrapper">
                    <!-- <i class="fas fa-plus-circle"></i> -->
                    <img src="{{ asset('public/frontend/img/icons/button-black.png') }}" width="20"><span class="d-none d-md-block">Add Post</span>
                </div>
            @endif
            <div class="followers-mobile d-block d-md-none">
                <i class="fas fa-user-friends"></i>
            </div>
            @php
                $follow_user = Common::userFollowerStatus(request()->route('id'));
            @endphp
            @if($profile == 0)
                <a href="{{ route('front.follow-feed',[request()->route('id')]) }}" class="usr-profile">
                    <div class="follow-wrapper">
                        @if(Auth::guard('users')->check())
                            @if($follow_user == 1)
                                <i class="fas fa-heart" style="color: #FF000C;"></i>
                            @else
                                <i class="fas fa-heart"></i>
                            @endif
                        @else
                            <i class="fas fa-heart"></i>
                        @endif
                        <span class="d-none d-md-block">
                            {{ isset($followers_count) ? $followers_count->follower_count : "" }} followers
                        </span>
                    </div>
                </a>
            @else
                @php $style = ""; @endphp
                @if($profile == 2)
                    @if($follow_user == 1)
                        @php
                            $style = "color: #FF000C;";
                        @endphp
                    @endif
                    <a href="{{ route('front.follow-feed',[request()->route('id')]) }}" class="usr-profile">
                        <div class="follow-wrapper sc-follow-wrapper">
                            <i class="fas fa-heart" style="{{ $style }}"></i><span class="d-none d-md-block">{{ isset($followers_count) ? $followers_count->follower_count : "" }} followers</span>
                        </div>
                    </a>
                @else
                    @if($follow_user == 1)
                        @php
                            $style = "color: #FF000C;";
                        @endphp
                    @endif
                    <div class="follow-wrapper follow-user">
                        <i class="fas fa-heart" style="{{ $style }}"></i><span class="d-none d-md-block">{{ isset($followers_count) ? $followers_count->follower_count : "" }} followers</span>
                    </div>
                @endif
            @endif
        </div>
        <!-- add post toggle -->
        @include('frontend.profile.post-form')
        <!-- end add post toggle -->
        @include('frontend.profile.following')
        <!-- follow toggle -->
        @include('frontend.profile.followers')
        <!-- end follow toggle -->
    </div>
</div>
<!-- food list wrapper -->
<div class="news-feed-wrapper">
    <!-- <div class="row">
        <div class="col-12">
            <h4 class="title-h4">
                Home cooked food near you
            </h4>
        </div>
    </div> -->
    <div class="">
        <div class="spinner-grow text-muted spin-loader" style="display: none;"></div>
    </div>
    @if(count($feeds) > 0)
        <div class="grid list-news-feed">
            @include('frontend.profile.list-feed')
        </div>
    @else
        <div class="text-center no-result-found">Create and share your first food related post.</div>
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
<div class="commentmodal modal" id="commentmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="commentmodalLabel" aria-hidden="true">
</div>
@endsection