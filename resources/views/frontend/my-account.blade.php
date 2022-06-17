@extends('frontend.profile.profile-master')
@section('content')
<form action="{{ route('front.edit-profile') }}" class="new-form myprofile" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="menu-page-top">
        <div class="row">
            <div class="col-12">
                <div class="subheader-col">
                    <div class="user">
                        <div class="fileupload-wrapper">
                            <div class="profile-main">
                                <div class="profile-img">
                                    @if($user->image != null)
                                        <img src="{{ asset('public/frontend/img/user_profiles/'.$user->image) }}" id="user_img">
                                    @else
                                        <img src="{{ asset('public/frontend/img/no-profile-img.jpg') }}" id="user_img">
                                    @endif
                                    <a href="{{ route('front.my-account') }}" class="profile-setting">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </div>
                            <input type="file" name="profileimage" onchange="readURL(this);">
                        </div>
                        @include('frontend.user-details')
                    </div>
                </div>
            </div>
        </div>
        @include('frontend.banner')
    </div>
    <div class="row profile-row">
        <div class="col-12">
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="fname" class="form-label">First Name<span class="text-danger">
                                *</span></label>
                        <input type="text" class="form-control" name="fname" placeholder="John" value="{{ old('fname',$user->name) }}">
                        <span class="text-danger">{{ $errors->first('fname') }}</span>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="lname" class="form-label">Last Name<span class="text-danger">
                                *</span></label>
                        <input type="text" class="form-control" name="lname" placeholder="Doe" value="{{ old('lname',$user->last_name) }}">
                        <span class="text-danger">{{ $errors->first('lname') }}</span>
                    </div>
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col">
                    <div class="form-group">
                        <label for="uname" class="form-label">User Name</label>
                        <input type="text" class="form-control" name="username" placeholder="John123" value="{{ old('username',$user->username) }}">
                        <span class="text-danger">{{ $errors->first('username') }}</span>
                    </div>
                </div>
                <div class="col">
                    <div class="form-check apply-user-name">
                        <input class="form-check-input" type="checkbox" name="is_username_active" id="is_username_active" @if(old('is_username_active') == "on") checked @endif {{ ($user->is_username_active == 1) ? "checked" : "" }}>
                        <label class="form-check-label" for="is_username_active">
                        Apply User Name
                        </label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address<span
                                class="text-danger"> *</span></label>
                        <input type="email" class="form-control" name="email" placeholder="Tom.kitchen@gmail.com" value="{{ old('email',$user->email) }}">
                        <span class="text-danger">{{ $errors->first('email') }}</span>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="cno" class="form-label">Contact Number<span class="text-danger">
                                *</span></label>
                        <input type="text" class="form-control {{ ($user->mobile == null) ? 'empty-input' : '' }}" name="phone" placeholder="645-445-4644" value="{{ old('phone',$user->mobile) }}">
                        <span class="text-danger">{{ $errors->first('phone') }}</span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="foodname" class="form-label">Address<span class="text-danger">
                                *</span></label>
                        <input type="text" class="form-control {{ ($user->mobile == null) ? 'empty-input' : '' }}" name="address" id="autocomplete_address" placeholder="3562 Sampson Street, Hicksville,  New York, 11854" value="{{ old('address',$user->street_address) }}">
                        <span class="text-danger">{{ $errors->first('address') }}</span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group btn-group">
                        <button class="editmenu-btn btn-solid" type="submit">
                            SAVE
                        </button>
                        <a href="{{ route('front.my-account') }}" class="ml-20">
                            <button class="editmenu-btn btn-solid-alt">
                                CANCEL
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@section('script')
<script src="{{ asset('public/frontend/js/menu.js') }}"></script>
<script src="https://maps.google.com/maps/api/js?key=AIzaSyBnCj3IWhjiuan4RaQdzjOWQWvDEt4pKxk&libraries=places"></script>
<script type="text/javascript">
    $(document).ready(function(){
      @if(Session::has('level'))
          new Noty({
              text: "{{ session('content') }}",
              type: "{{ session('level') }}"
          }).show();
      @endif
    });
    function readURL(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#user_img').attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
      }
    }
    google.maps.event.addDomListener(window, 'load', initialize);
    function initialize() {
      var input = document.getElementById('autocomplete_address');
      var autocomplete = new google.maps.places.Autocomplete(input);
    }
</script>
@endsection
@endsection
