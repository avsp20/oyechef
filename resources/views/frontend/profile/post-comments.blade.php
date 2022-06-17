@php
    use App\Http\Controllers\Frontend\CommonController as Common;
@endphp
<!-- Modal -->
<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
        <div class="modal-header">
            <div class="label-wrapper">
                <h4>Comments</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="comment-box-wrapper">
                <div class="user-img">
                    @if(Auth::guard('users')->check())
                        @if(Auth::guard('users')->user()->user_meta->user_image != null)
                            <img src="{{ asset('public/frontend/img/user_profiles/'.Auth::guard('users')->user()->user_meta->user_image) }}">
                        @else
                            <img src="{{ asset('public/frontend/img/user-img.png') }}">
                        @endif
                    @else
                        <img src="{{ asset('public/frontend/img/user-img.png') }}">
                    @endif
                </div>
                <div class="comment-box">
                    <form id="comment_form" method="POST" action="{{ route('front.add-comment') }}">
                        @csrf
                        @php
                            $post_id = Common::encrypt($post_comments->id);
                        @endphp
                        <input type="hidden" name="feed" id="feed" value="{{ $post_id }}">
                        <textarea class="form-control" id="comment" name="comment" placeholder="Add a comment">{{ old(
                        'comment') }}</textarea>
                        <span class="text-danger message-error"></span>
                        <button type="submit" class="btn btn-sm text-primary btn-cmnt-post">POST</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal-body">
            @if(!empty($post_comments))
                <ul class="comment-list">
                    @if(count($post_comments->post_comments) > 0)
                        @foreach($post_comments->post_comments as $comment)
                            @php
                                $comment_id = Common::encrypt($comment->id);
                            @endphp
                            <li class="comment" id="feed_id_{{ $comment->id }}">
                                @if($comment->user->user_meta != null) 
                                <div class="user">
                                    @if($comment->user->user_meta->user_image != null)
                                        <img src="{{ asset('public/frontend/img/user_profiles/'.$comment->user->user_meta->user_image) }}">
                                    @else
                                        <img src="{{ asset('public/frontend/img/user-img.png') }}">
                                    @endif
                                </div>
                                @endif
                                <div class="content">
                                    @if($comment->user->user_meta != null) 
                                        @if($comment->user->user_meta->is_username_active == 1)
                                            <h4>{{ $comment->user->user_meta->username }}</h4>
                                        @else
                                            <h4>{{ $comment->user->name }} {{ $comment->user->user_meta->lname }}</h4>
                                        @endif
                                    @else
                                        <h4>{{ $comment->user->name }}</h4>
                                    @endif
                                    <div id="comment_desc_{{ $comment->id }}" style="display: none;">
                                        <input type="text" name="comment" class="form-control" id="comment_{{ $comment->id }}" value="{{ $comment->comment }}" readonly="">
                                        <a href="javascript:void(0)" data-id="{{ $comment->id }}" onclick="editUserComment('{{ $comment->id }}')" class="icon-edit" id=""><i class="fas fa-edit"></i></a>
                                        <a href="javascript:void(0)" data-id="{{ $comment->id }}" class="icon-cancel icon-delete" onclick="cancelComment({{ $comment->id }})"><i class="fas fa-times-circle"></i></a>
                                    </div>
                                    <p class="cmt-desc_{{ $comment->id }}">{{ $comment->comment }}</p>
                                </div>
                                @if(Auth::guard('users')->check())
                                    @if(Auth::guard('users')->user()->id == $comment->user_id)
                                        <div class="icon-delete action-btn">
                                            <a href="javascript:void(0)" onclick="deleteFeedComment('{{ $comment_id }}')" class="icon-delete">
                                                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"><g>
                                                    <path class="st0" d="M9,0C4,0,0,4,0,9c0,5,4,9,9,9s9-4,9-9C18,4,14,0,9,0z M14.1,9.9c0,0.3-0.2,0.5-0.5,0.5H4.4   c-0.3,0-0.5-0.2-0.5-0.5V8.1c0-0.3,0.2-0.5,0.5-0.5h9.2c0.3,0,0.5,0.2,0.5,0.5V9.9z" fill="#606060"></path></g>
                                                </svg>
                                            </a>
                                        </div>
                                        {{--<div class="action-btn">
                                            <a href="javascript:void(0)" data-id="{{ $comment->id }}" onclick="editComments({{ $comment->id }})" class="icon-edit" id="edit-cmt_{{ $comment->id }}"><i class="fas fa-edit"></i></a>
                                            <a href="javascript:void(0)" onclick="deleteFeedComment('{{ $comment_id }}')" class="icon-delete"><i class="fas fa-times-circle"></i></a>
                                        </div>--}}
                                    @endif
                                @endif
                            </li>
                        @endforeach
                    @else
                        <div class="no-cmt">Be the first to comment.</div>
                    @endif
                </ul>
            @endif
        </div>

    </div>
</div>
<script type="text/javascript">
    $('#comment_form').on('submit', function(e) {
        e.preventDefault(); 
        var message = $('#comment').val();
        var post_id = $('#feed').val();
        var action = $("#comment_form").attr('action');
        var method = $("#comment_form").attr('method');
        if(message.length > 0){
            let myform = document.getElementById("comment_form");
            let fd = new FormData(myform);
            $.ajax({
                url: action,
                data: fd,
                cache: false,
                processData: false,
                contentType: false,
                type: method,
                success: function (response) {
                    if(response.status == 1){
                        $(".no-cmt").empty();
                        if(response.data.user.user_meta != null){
                            var user_img = '{{asset("public/frontend/img/user-img.png")}}';
                            if(response.data.user.user_meta.user_image != null){
                                var user_img = '{{asset("public/frontend/img/user_profiles")}}/'+response.data.user.user_meta.user_image;
                            }
                        }
                        $(".comment-list").append('<li class="comment" id="' + response.data.id + '">'+
                            '<div class="user">'+
                                '<img src="' + user_img + '">'+
                            '</div>'+
                            '<div class="content">'+
                                '<h4>' + response.data.user.name + ' ' + response.data.user.user_meta.lname + '</h4>'+
                                '<p>' + response.data.comment + '</p>'+
                            '</div>'+
                        '</li>');
                        new Noty({
                            theme: ' alert alert-success alert-styled-left p-0 bg-green',
                            text: response.success,
                            type: "success",
                        }).show();
                        setTimeout(function(){ 
                            window.location.reload();
                        }, 3000);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) 
                {
                    if(jqXHR.responseJSON.status == 0){
                        new Noty({
                            theme: ' alert alert-danger alert-styled-left p-0 bg-red',
                            text: jqXHR.responseJSON.error,
                            type: "danger",
                        }).show();
                    }
                }
            });
        }else{
            $(".message-error").text('Please add your comment.');
        }
    });
</script>