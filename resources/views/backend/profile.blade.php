@extends('backend.master')

@section('breadcrumb')
    <div class="page-header page-header-default">
        <div class="page-header-content">
            <div class="page-title">
                <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold"></span>Edit Profile</h4>
            </div>
        </div>
    </div>
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        @if (count($errors) > 0)
            <div class="alert alert-danger alert-styled-left alert-bordered">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="text-center">
            @if (session('status'))
            <div class="alert alert-success alert-styled-left alert-arrow-left alert-bordered">
                <p>{{ session('status') }}</p>
            </div>
            @endif
        </div>
        <!-- Basic layout-->
        <form class="form-horizontal" action="{{ route('admin.update-profile', [$user->id]) }}" method="POST" enctype="multipart/form-data">
            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h5 class="panel-title">Profile</h5>
                <a class="heading-elements-toggle"><i class="icon-more"></i></a></div>

                <div class="panel-body">
                    <div class="form-group">
                        <label class="control-label col-lg-2 text-right">First Name<span class="text-danger"> *</span></label>
                        <div class="col-lg-10">
                        <input type="text" value="{{ old('fname',$user->name) }}" name="name" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-2 text-right">Last Name<span class="text-danger"> *</span></label>
                        <div class="col-lg-10">
                            <input type="text" value="{{ old('lname',$user->lname) }}" name="lname" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-2 text-right">Email<!-- <span class="text-danger"> *</span> --></label>
                        <div class="col-lg-10">
                            <label class="form-control">{{ $user->email }}</label>
                            {{--<input type="text" value="{{ old('email',$user->email) }}" name="email" class="form-control">--}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-2 text-right">Phone<span class="text-danger"> *</span></label>
                        <div class="col-lg-10">
                            <input type="text" value="{{ old('phone',$user->phone) }}" name="phone" id="phone" class="form-control" >
                        </div>
                    </div>
                    @csrf
                    <div class="form-group">
                        <label class="control-label col-lg-2 text-right">Country</label>
                        <div class="col-lg-10">
                            <select id="country" name="country" class="select-size-sm option-required" data-placeholder="Select Country">
                                <option value="">Select Country</option>
                                @if(count($countries) > 0)
                                @foreach ($countries as $country)
                                <option {{ $user->country==$country->id ? 'selected' : ''}} value="{{$country->id}}">{{$country->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-2 text-right">State</label>
                        <div class="col-lg-10">
                            <select id="state" name="state" class="select-size-sm option-required" data-placeholder="Select State">
                                <option value="">Select State</option>
                                @if(count($states) > 0)
                                    @foreach ($states as $state)
                                        <option {{ $user->state==$state->id ? 'selected' : ''}} value="{{$state->id}}">{{$state->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-2 text-right">City</label>
                        <div class="col-lg-10">
                            <select id="city" name="city" class="select-size-sm option-required" data-placeholder="Select City">
                                <option value="">Select City</option>
                                @if(count($cities) > 0)
                                    @foreach ($cities as $city)
                                        <option {{ $user->city==$city->id ? 'selected' : ''}} value="{{$city->id}}">{{$city->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-2 text-right">Street Address</label>
                        <div class="col-lg-10">
                            <textarea name="address" class="form-control" id="" cols="1" rows="3" maxlength="200">{{ old('address',$user->address) }}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-2 text-right">Zipcode</label>
                        <div class="col-lg-10">
                            <input type="text" value="{{ old('zipcode',$user->zipcode) }}" name="zipcode" class="form-control" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-2 text-right">Profile Image</label>
                        <div class="col-lg-10">
                            <input type="file" name="user_image" id="user_image" class="form-control">
                            @if($user->user_image != null)
                                <img src="{{ asset('public/backend/images/user').'/'.$user->user_image}}" alt="Profile Image" class="img-fluid mb-2" style="width: 90px; border-radius: 50%">
                            @endif
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-success">Update <i class="icon-arrow-right14"></i></button>
                    </div>
                </div>
            </div>
        </form>
        <!-- /basic layout -->
    </div>
</div>
@endsection
@section('script')
<script type="text/javascript" src="{{ asset('public/backend/js/pages/form_select2.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
<script>
    $('#country').change(function(){
        var cid = $(this).val();
        if(cid){
        $.ajax({
           type:"get",
           url:"{{ url('/') }}/state/"+cid,
           success:function(res)
           {
                if(res)
                {
                    $("#state").empty();
                    $("#city").empty();
                    $("#state").prop('disabled',false);
                    if(res.length ==0){
                        $("#state").append('<option value="">This country has no state</option>');
                        $("#state").prop('disabled',false);
                    }
                    else{
                        $("#state").append('<option>Select State</option>');
                       $.each(res,function(key,value){
                            $("#state").append('<option value="'+key+'">'+value+'</option>');
                        });
                    }
                }
           }

        });
        }
    });
    $('#state').change(function(){
        var sid = $(this).val();
        if(sid){
        $.ajax({
           type:"get",
           url:"{{ url('/') }}/city/"+sid,
           success:function(res)
           {
                if(res)
                {
                    $("#city").empty();
                    $("#city").prop('disabled',false);
                    if(res.length ==0){
                        $("#city").append('<option value="">This state has no city</option>');
                        $("#city").prop('disabled',true);
                    }
                    else{
                        $("#city").append('<option>Select City</option>');
                        $.each(res,function(key,value){
                            $("#city").append('<option value="'+key+'">'+value+'</option>');
                        });
                    }
                }
           }

        });
        }
    });
    $('#phone').mask('(999) 999-9999', {placeholder: "(999) 999-9999"});
</script>
@endsection
