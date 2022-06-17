@php
    use App\Http\Controllers\Frontend\CommonController as Common;
@endphp
@foreach($feeds as $feed)
    @php
        $feed_id = Common::encrypt($feed->id);
        $user_id = Common::encrypt($feed->user_id);
    @endphp
    <div class="grid-item feed-post" data-id="{{ $feed->id }}">
        <div class="news-feed-widget">
            <div class="news-feed-top">
                <div class="nf-user" onclick="window.open('{{ route("profile.index",[$user_id]) }}','_self'); ">
                    <div class="nf-user-img">
                        @if($feed->user->user_meta != null)
                            @if($feed->user->user_meta->user_image != null)
                                <img src="{{ asset('public/frontend/img/user_profiles/'.$feed->user->user_meta->user_image) }}" id="user_img">
                            @else
                                <img src="{{ asset('public/frontend/img/no-profile-img.jpg') }}" id="user_img">
                            @endif
                        @else
                            <img src="{{ asset('public/frontend/img/no-profile-img.jpg') }}" id="user_img">
                        @endif
                    </div>
                    <div class="nf-user-name">
                        @if($feed->user->user_meta != null) 
                            @if($feed->user->user_meta->is_username_active == 1)
                                {{ $feed->user->user_meta->username }}
                            @else
                                {{ $feed->user->name }} {{ ($feed->user->user_meta->lname != null) ? $feed->user->user_meta->lname : "" }}
                            @endif
                        @else
                            {{ $feed->user->name }}
                        @endif
                    </div>
                </div>
                <div class="nf-text @if($feed->file == null){{ "news-feed-img" }}@endif">
                    @if($feed->content != null && $feed->content_url != null)
                        @if($feed->content != null)
                            <p class="de-feed-text">
                                {{ $feed->content }}
                            </p>
                        @endif
                        @if(strpos($feed->content_url, 'youtube') > 0)
                        <div class="iframe-wrapper">
                            <iframe src="{{ $feed->content_url }}" width="100%" height="100%"></iframe>
                        </div>
                        @else
                            <a href="{{ $feed->content_url }}">
                                <div class="newsfeed-links" @if($feed->meta_description != null) style="padding: 10px;" @endif>
                                    @if($feed->meta_image != null)
                                        <div class="newsfeed-img">
                                            <img src="{{ $feed->meta_image }}">
                                        </div>
                                    @endif
                                    <div class="newsfeed-cont">
                                        {{--<p>{{ $feed->content_url }}</p>--}}
                                        <h4>{{ $feed->meta_title }}</h4>
                                        @if(strlen($feed->meta_description) > 100) 
                                            <p>{{ substr(strip_tags($feed->meta_description), 0, 100) . "..." }}</p>
                                        @else 
                                            <p>{{ strip_tags($feed->meta_description) }}</p>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @endif
                    @else
                        {{ $feed->content }}
                    @endif
                </div>
            </div>
            @if($feed->file != null)
                <div class="news-feed-img">
                    @if(str_contains($feed->file_type, 'video/'))
                        <video controls>
                          <source src="{{ asset('public/frontend/img/feeds/'.$feed->file) }}" type="{{ $feed->file_type }}">
                        </video>
                    @elseif(str_contains($feed->file_type, 'image/'))
                        <img src="{{ asset('public/frontend/img/feeds/'.$feed->file) }}" id="user_img">
                    @endif
                </div>
            @endif
            <div class="news-feed-bottom">
                <div class="nf-social">
                    <div class="nf-follow">
                        <a href="{{ route('front.follow-feed',[$user_id]) }}">
                            @if(Auth::guard('users')->check())
                                @if(count($feed->user->followers) > 0)
                                    <i class="fas fa-heart" style="color: #FF000C;"></i>
                                @else
                                    <i class="far fa-heart"></i>
                                @endif
                            @else
                                <i class="far fa-heart"></i>
                            @endif
                                <span>{{ $feed->user_following_count }}</span>
                        </a>
                    </div>
                    <div class="nf-like">
                        <a onclick="likeUserFeed('{{ $feed_id }}')" href="javascript:void(0)">
                            @if(Auth::guard('users')->check())
                                @if($feed->user_like != null)
                                    <i class="far fa-thumbs-up like-icon" id="like_feed_{{ $feed->id }}"></i> <span class="like_feed_cnt_{{ $feed->id }}">{{ $feed->likes_count }}</span>
                                @else
                                    <i class="far fa-thumbs-up" id="like_feed_{{ $feed->id }}"></i> <span class="like_feed_cnt_{{ $feed->id }}">{{ $feed->likes_count }}</span>
                                @endif
                            @else
                                <i class="far fa-thumbs-up"></i><span>{{ $feed->likes_count }}</span>
                            @endif
                        </a>
                    </div>
                    <div class="nf-share">
                        <a class="share-post">
                            <i class="fas fa-share-alt"></i>

                            <div class="u-share-hover">
                                <label class="d-block">Share</label>
                                <div class="u-share-hover-inner">
                                    <a href="https://api.whatsapp.com/send?text={{urlencode(route('profile.index',[$user_id])) }}" target="_blank" data-bs-toggle="tooltip" data-bs-placement="bottom" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                                    {{--<a href="https://twitter.com/intent/tweet?text={{ $uname }}&url={{ urlencode(route('profile.index',[$user_id])) }}" target="_blank"><i class="fab fa-twitter" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Twitter"></i></a>--}}
                                    <a href="https://twitter.com/share?url={{ urlencode(route('profile.index',[$user_id])) }}" class="ml-8" target="_blank"><i class="fab fa-twitter" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Twitter"></i></a>
                                    <a href="https://www.facebook.com/sharer.php?u={{urlencode(route('profile.index',[$user_id])) }}" class="ml-8" target="_blank" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Facebook"><i class="fab fa-facebook"></i></a>
                                </div>
                            </div>
                        </a>

                    </div>
                </div>
                <div class="nf-comment">
                    <!-- data-bs-toggle="modal" data-bs-target="#commentmodal" -->
                    <a href="javascript:void(0)" onclick="showComments('{{ $feed_id }}')"> <span class="cmt-cnt">{{ $feed->post_comments_count }}</span> Comments</a>
                </div>
            </div>
        </div>
    </div>
@endforeach