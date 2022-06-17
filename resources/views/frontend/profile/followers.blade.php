@php
    use App\Http\Controllers\Frontend\CommonController as Common;
    $top_users = Common::randomFollowers();
@endphp
<!-- follow toggle -->
<div class="main-content-body follow-btn-toggle" style="display: none;">
    <h4 class="toggle-title d-block d-sm-none">
        Follow
    </h4>
    <div class="follow-form-wrapper">
        <form class="" id="search_follower" action="{{ route('front.search-followers') }}">
            @csrf
            <div class="header-search">
                <input class="form-control" type="search" name="search" id="search_data" placeholder="Search" aria-label="Search">
                <button class="btn btn-search" type="submit" id="search_btn">
              <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M19.8026 18.645L14.5483 13.3907C15.8752 11.7595 16.5256 9.68135 16.3655 7.58473C16.2054 5.4881 15.2469 3.53284 13.6877 2.12206C12.1285 0.711281 10.0874 -0.0474494 7.98525 0.00230061C5.88311 0.0520507 3.88019 0.906489 2.38945 2.38945C0.906489 3.88019 0.0520507 5.88311 0.00230061 7.98525C-0.0474494 10.0874 0.711281 12.1285 2.12206 13.6877C3.53284 15.2469 5.4881 16.2054 7.58473 16.3655C9.68135 16.5256 11.7595 15.8752 13.3907 14.5483L18.645 19.8026C18.8021 19.9371 19.0041 20.0074 19.2107 19.9994C19.4173 19.9914 19.6133 19.9058 19.7595 19.7595C19.9058 19.6133 19.9914 19.4173 19.9994 19.2107C20.0074 19.0041 19.9371 18.8021 19.8026 18.645ZM3.54704 12.8406C2.62807 11.9222 2.00211 10.752 1.74834 9.47781C1.49457 8.20364 1.62438 6.88283 2.12136 5.68246C2.61835 4.48208 3.46017 3.45606 4.54035 2.73418C5.62053 2.0123 6.89053 1.62699 8.18972 1.62699C9.48891 1.62699 10.7589 2.0123 11.8391 2.73418C12.9193 3.45606 13.7611 4.48208 14.2581 5.68246C14.7551 6.88283 14.8849 8.20364 14.6311 9.47781C14.3773 10.752 13.7514 11.9222 12.8324 12.8406C12.2254 13.455 11.5024 13.9427 10.7055 14.2756C9.9085 14.6085 9.05341 14.78 8.18972 14.78C7.32604 14.78 6.47094 14.6085 5.674 14.2756C4.87705 13.9427 4.1541 13.455 3.54704 12.8406Z" fill="#030303"></path>
              </svg>
              </button>
            </div>
            <div class="follow-result">
                <ul class="favorite-cheflist following-chef mCustomScrollbar search-follow">
                    @if(count($top_users) > 0)
                        @foreach($top_users as $top_user)
                            @php
                                $user_id = Common::encrypt($top_user->user_id);
                            @endphp
                            <li>
                                <a href="{{ route('profile.index',[$user_id]) }}" class="follower-user">
                                    <div class="chef-detail">
                                        @if($top_user->user_image != null)
                                            <img src="{{ asset('public/frontend/img/user_profiles/'.$top_user->user_image) }}" />
                                        @else
                                            <img src="{{ asset('public/frontend/img/no-profile-img.jpg') }}" />
                                        @endif
                                        <span class="chef-name">
                                            @if($top_user->is_username_active == 1)
                                                {{ $top_user->username }}
                                            @else
                                                {{ $top_user->name }} {{ ($top_user->lname != null) ? $top_user->lname : "" }}
                                            @endif
                                        </span>
                                    </div>
                                    <div class="icon-add-follow">
                                        <a href="#">
                                            <i class="fas fa-plus-circle"></i>
                                        </a>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>
        </form>
    </div>
</div>
<!-- end follow toggle -->
@section('script')
<script src="{{ asset('public/frontend/js/news-feed.js') }}"></script>
@endsection