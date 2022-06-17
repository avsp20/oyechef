@if(count($errors) > 0)
    @php
        $style = "display: block;";
    @endphp
@else
    @php
        $style = "display: none;";
    @endphp
@endif
<div class="main-content-body add-post-toggle" style="{{ $style }}">
    <h4 class="toggle-title d-block d-sm-none">
        Add Post
    </h4>
    <div class="add-post-form-wrapper">
        <form action="{{ route('news-feed.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="nf-user">
                @if(Auth::guard('users')->check())
                    <div class="nf-user-img">
                        @if(Auth::guard('users')->user()->user_meta != null && Auth::guard('users')->user()->user_meta->user_image != null)
                            <img src="{{ asset('public/frontend/img/user_profiles/'.Auth::guard('users')->user()->user_meta->user_image) }}">
                        @else
                            <img src="{{ asset('public/frontend/img/no-profile-img.jpg') }}">
                        @endif
                    </div>
                    <div class="nf-user-name">
                        {{ ucfirst(Auth::guard('users')->user()->name) }} {{ (Auth::guard('users')->user()->user_meta != null) ? ' '.ucfirst(Auth::guard('users')->user()->user_meta->lname) : "" }}
                    </div>
                @else
                    <div class="nf-user-name">
                        Please do login to add your news feeds.
                    </div>
                @endif
            </div>
            <div class="form-group">
                <textarea class="form-control" name="content" id="addtextlink" rows="3" placeholder="Add text or link here"></textarea>
                <span class="text-danger float-start">{{ $errors->first('content') }}</span>
            </div>
            <div class="form-group custom-file-upload">
                <div class="file video-file">
                    <label for="input-file">
                        <div class="vd-file"></div>
                      <img src="{{ asset('public/frontend/img/icons/image-gallery.png') }}" width="40" id="feed_image">
                      Add photos and videos
                    </label>
                    <input id="input-file" type="file" name="file" onchange="readURL(this);">
                </div>
                <span class="text-danger float-start">{{ $errors->first('file') }}</span>
            </div>
            <button type="submit" class="btn btn-add-post mt-3">POST</button>
        </form>
    </div>
</div>