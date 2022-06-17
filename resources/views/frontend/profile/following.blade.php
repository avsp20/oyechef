@php
    use App\Http\Controllers\Frontend\CommonController as Common;
@endphp
<div class="main-content-body followers-toggle  d-md-none" style="display: none;">
    <h4 class="toggle-title d-block d-sm-none">
        Following
    </h4>
    @if(count($following) > 0)
        <ul class="favorite-cheflist following-chef">
            @foreach($following as $follow)
                @php
                    $follow_id = Common::encrypt($follow->id);
                    $user_id = Common::encrypt($follow->user->id);
                @endphp
                <li>
                    <div class="chef-detail" onClick="window.open('{{ route('profile.index',[$user_id])}}','_self');">
                        @if($follow->user->user_meta != null)
                            @if($follow->user->user_meta->user_image != null)
                                <img src="{{ asset('public/frontend/img/user_profiles/'.$follow->user->user_meta->user_image) }}" />
                            @else
                                <img src="{{ asset('public/frontend/img/no-profile-img.jpg') }}" />
                            @endif
                            @if($follow->user->user_meta->is_username_active == 1)
                                <span class="chef-name">{{ $follow->user->user_meta->username }}</span>
                            @else
                                <span class="chef-name">{{ $follow->user->name }} {{ ($follow->user->user_meta->lname != null) ? $follow->user->user_meta->lname : "" }}</span>
                            @endif
                        @else
                            <span class="chef-name">{{ $follow->user->name }}</span>
                        @endif
                    </div>
                    @if($profile == 1)
                    <div class="icon-delete">
                        <a href="javascript:void(0)" onclick="deleteFollowingUser('{{$follow_id}}')">
                            <svg width="15" height="15" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"><g>
                                <path class="st0" d="M9,0C4,0,0,4,0,9c0,5,4,9,9,9s9-4,9-9C18,4,14,0,9,0z M14.1,9.9c0,0.3-0.2,0.5-0.5,0.5H4.4   c-0.3,0-0.5-0.2-0.5-0.5V8.1c0-0.3,0.2-0.5,0.5-0.5h9.2c0.3,0,0.5,0.2,0.5,0.5V9.9z" fill="#606060"/></g>
                            </svg>
                        </a>
                    </div>
                    @endif
                </li>
            @endforeach
        </ul>
    @else
        <div class="text-center">No following added.</div>
    @endif
    </ul>
</div>