@extends('frontend.menu-master')
@section('content')
<form action="{{ route('front.edit-profile') }}" class="new-form myprofile" method="POST" enctype="multipart/form-data">
  @csrf
  <div class="row profile-row">
    <div class="profile-left">
        <div class="form-group">
          <label for="profileimage" class="form-label">Profile Picture </label>
          <br>
          <div class="fileupload-wrapper">
            <div class="browse-btn">
              @if($user->image != null)
                <img src="{{ asset('public/frontend/img/user_profiles/'.$user->image) }}" id="user_img">
              @else
                <img src="{{ asset('public/frontend/img/no-profile-img.jpg') }}" id="user_img">
              @endif
            </div>

            <div  class="edit-img">
            <i class="fas fa-edit"></i>
            </div>
          <input type="file" name="profileimage" onchange="readURL(this);">
          </div>

        </div>
    </div>
    <div class="profile-right">
      <div class="row">
        <div class="col">
          <div class="form-group">
            <label for="fname" class="form-label">First Name<span class="text-danger"> *</span></label>
             <input type="text" class="form-control" name="fname" placeholder="John" value="{{ old('fname',$user->name) }}">
             <span class="text-danger">{{ $errors->first('fname') }}</span>
             </div>
        </div>
        <div class="col">
          <div class="form-group">
            <label for="lname" class="form-label">Last Name<span class="text-danger"> *</span></label>
             <input type="text" class="form-control" name="lname" placeholder="Doe" value="{{ old('lname',$user->last_name) }}">
             <span class="text-danger">{{ $errors->first('lname') }}</span>
             </div>
        </div>
      </div>
      <div class="row">
        <div class="col">
          <div class="form-group">
            <label for="email" class="form-label">Email Address<span class="text-danger"> *</span></label>
             <input type="email" class="form-control" name="email" placeholder="Tom.kitchen@gmail.com" value="{{ old('email',$user->email) }}">
             <span class="text-danger">{{ $errors->first('email') }}</span>
             </div>
        </div>
        <div class="col">
          <div class="form-group">
            <label for="cno" class="form-label">Contact Number<span class="text-danger"> *</span></label>
             <input type="text" class="form-control {{ ($user->mobile == null) ? 'empty-input' : '' }}" name="phone" placeholder="645-445-4644" value="{{ old('phone',$user->mobile) }}">
             <span class="text-danger">{{ $errors->first('phone') }}</span>
             </div>
        </div>
      </div>
      <div class="row">
        <div class="col">
          <div class="form-group">
            <label for="foodname" class="form-label">Address<span class="text-danger"> *</span></label>
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
