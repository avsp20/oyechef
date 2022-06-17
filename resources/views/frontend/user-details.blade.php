@php
  use App\Http\Controllers\Frontend\CommonController as Common;
  $username = Common::getUsername();
@endphp
<div class="u-info">
    <h4 class="u-name">
        {{ $username }}
    </h4>
    <div class="u-contact">
        <ul>
            <li>
                <a class="active" href="tel:@if(request()->segment(1) == 'my-account') {{ Auth::guard('users')->user()->user_meta->phone }} @else {{ $user_details->user_meta->phone }} @endif"><i class="fas fa-phone-alt"></i>
                    <div class="u-contact-hover">
                        <i class="fas fa-mobile-alt"></i> @if(request()->segment(1) == 'my-account') {{ Auth::guard('users')->user()->user_meta->phone }} @else {{ $user_details->user_meta->phone }} @endif
                    </div>
                </a>
            </li>
            <li>
                <a @if(preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"])) href="sms://@if(request()->segment(1) == 'my-account') {{ Auth::guard('users')->user()->user_meta->phone }} @else {{ $user_details->user_meta->phone }} @endif" @else href="tel:@if(request()->segment(1) == 'my-account') {{ Auth::guard('users')->user()->user_meta->phone }} @else {{ $user_details->user_meta->phone }} @endif" @endif>
                    <i class="fas fa-comment-alt"></i>
                    <div class="u-contact-hover">

                        <i class="far fa-envelope"></i> @if(request()->segment(1) == 'my-account') {{ Auth::guard('users')->user()->user_meta->phone }} @else {{ $user_details->user_meta->phone }} @endif
                    </div>
                </a>
            </li>
            <li class="rating-li"><a><i class="fas fa-star" @if($user_ratings > 0) style="color: #F6B22C;" @endif></i>
                    <div class="u-contact-hover">
                        <label class="d-block">Rating</label>
                        <span class="star menu-rating">
                            @if(request()->segment(1) == 'edit-menu' || request()->segment(1) == 'my-account')
                                @if(Auth::guard('users')->check())
                                    <span>Cannot rate your own account.</span>
                                @endif
                            @endif
                            @if(request()->segment(1) == 'menu')
                                @if(Auth::guard('users')->check())
                                    @if(request()->route('id') == Common::encrypt(Auth::guard('users')->user()->id))
                                        <span>Cannot rate your own account.</span>
                                    @else
                                    @if($user_ratings > 0)
                                        @for($i = 0; $i < 5; $i++)
                                            @if (floor($user_ratings) - $i >= 1)
                                                <i class="fas fa-star common-star" id="star_{{ $i + 1 }}" onclick="giveRatings('{{ request()->route("id") }}',this)"></i>
                                            @elseif ($user_ratings - $i > 0)
                                                <i class="fas fa-star-half-alt common-star" id="star_{{ $i + 1 }}" onclick="giveRatings('{{ request()->route("id") }}',this)"></i>
                                            @else
                                                <i class="fas fa-star common-star empty-star" id="star_{{ $i + 1 }}" onclick="giveRatings('{{ request()->route("id") }}',this)"></i>
                                            @endif
                                        @endfor
                                    @else
                                        @for($i = 0; $i < 5; $i++)
                                            <i class="fas fa-star empty-star common-star" id="star_{{ $i+1 }}" onclick="giveRatings('{{ request()->route("id") }}',this)"></i>
                                        @endfor
                                    @endif
                                @endif
                            @else
                                @if($user_ratings > 0)
                                    @for($i = 0; $i < 5; $i++)
                                        @if (floor($user_ratings) - $i >= 1)
                                            <i class="fas fa-star common-star" id="star_{{ $i + 1 }}" onclick="giveRatings('{{ request()->route("id") }}',this)"></i>
                                        @elseif ($user_ratings - $i > 0)
                                            <i class="fas fa-star-half-alt common-star" id="star_{{ $i + 1 }}" onclick="giveRatings('{{ request()->route("id") }}',this)"></i>
                                        @else
                                            <i class="fas fa-star common-star empty-star" id="star_{{ $i + 1 }}" onclick="giveRatings('{{ request()->route("id") }}',this)"></i>
                                        @endif
                                    @endfor
                                @else
                                    @for($i = 0; $i < 5; $i++)
                                        <i class="fas fa-star empty-star common-star" id="star_{{ $i+1 }}" onclick="giveRatings('{{ request()->route("id") }}',this)"></i>
                                    @endfor
                                @endif
                            @endif
                        @endif
                      </span>
                        <!-- <span class="star">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star empty-star"></i>
                        </span> -->
                    </div>
                </a>
            </li>
            @php
                $user_id = request()->route('id');
            @endphp
            @if(request()->segment(1) == 'menu')
                @if(Auth::guard('users')->check())
                    @php
                      $userId = Common::encrypt(Auth::guard('users')->user()->id);
                    @endphp
                        @if($user_id == $userId)
                            <li class="favorite-li">
                                <a href="#">
                                  <i class="fas fa-heart"></i>
                                  <div class="u-favorite-hover">
                                    <label class="d-block">FAVORITES</label>
                                    <span>Cannot add your own account to favorites.</span>
                                  </div>
                                </a>
                            </li>
                        @else
                        <li>
                            <a href="{{ route('front.add-to-favorites',[$user_id]) }}">
                              <i class="fas fa-heart" @if($fav_user == 1) style="color: #FF000C;" @endif></i>
                            </a>
                        </li>
                    @endif
                @else
                    <li>
                      <a href="{{ route('front.add-to-favorites',[$user_id]) }}">
                        <i class="fas fa-heart" @if($fav_user == 1) style="color: #FF000C;" @endif></i>
                      </a>
                    </li>
                @endif
            @elseif(request()->segment(1) == 'edit-menu' || request()->segment(1) == 'my-account')
                <li class="favorite-li">
                    <a href="#">
                        <i class="fas fa-heart"></i>
                        <div class="u-favorite-hover">
                            <label class="d-block">FAVORITES</label>
                            @if(request()->segment(1) == 'edit-menu' || request()->segment(1) == 'my-account')
                                @if(Auth::guard('users')->check())
                                    <span>Cannot add your own account to favorites.</span>
                                @endif
                            @endif
                        </div>
                    </a>
                </li>
            @else
                <li style="pointer-events: none;">
                    <a href="#">
                      <i class="fas fa-heart"></i>
                    </a>
                </li>
            @endif
            <!-- <li class="follow-li">
                <a href="#">
                    <i class="fas fa-heart"></i>
                    <div class="u-contact-hover">
                        <label class="d-block">favourite Chef</label>
                    </div>
                </a>
            </li> -->
            <li class="share-li">
                @if(request()->segment(1) == 'my-account' || request()->segment(1) == 'edit-menu')
                    @if(Auth::guard('users')->check())
                        @php
                            $share_uid = Common::encrypt(Auth::guard('users')->user()->id);
                        @endphp
                    @endif
                @else
                    @php
                        $share_uid = request()->route('id');
                    @endphp
                @endif
                <a class="d-block">
                    <i class="fas fa-share-alt"></i>
                </a>
                <div class="u-share-hover">
                    <label class="d-block">Share</label>
                    <div class="u-share-hover-inner">
                        <a target="_blank" href="https://api.whatsapp.com/send?text={{urlencode(route('front.menu',[$share_uid])) }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                        <a target="_blank" href="https://twitter.com/share?url={{ urlencode(route('front.menu',[$share_uid])) }}"><i class="fab fa-twitter" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Twitter"></i></a>
                        <a target="_blank" href="https://www.facebook.com/sharer.php?u={{urlencode(route('front.menu',[$share_uid])) }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Facebook"><i class="fab fa-facebook"></i></a>
                    </div>
                </div>

            </li>
        </ul>
    </div>
</div>
