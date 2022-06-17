@extends('frontend.auth.auth-master')

@section('content')
<form class="" action="{{ route('password.update') }}" method="POST">
  @csrf
  <div class="form-title">
    <h2>Reset Password</h2>
    <p>Don’t worry! Just fill in your new password and we’ll help you reset your password.</p>
  </div>
  <div class="form-content">
    <div class="form-left">
        <input type="hidden" name="email" value="{{ Request::get('email') }}">
      <div class="form-group">
        <label for="email" class="form-label">New Password</label>
        <input type="password" name="password" class="form-control" id="password" placeholder="Enter your password">
        <span class="text-danger">{{ $errors->first('password') }}</span>
      </div>
      <div class="form-group">
        <label for="email" class="form-label">Confirm Password</label>
        <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Enter your confirm password">
        <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
      </div>
      <div class="form-btn">
        <button type="submit" class="btn btn-submit w-100">Reset Password</button>
      </div>
    </div>
  </div>
</form>
@section('script')
<script type="text/javascript">
    $(document).ready(function(){
      console.log('{{ Session::has("level") }}')
      @if(Session::has('level'))
          new Noty({
              text: "{{ session('content') }}",
              type: "{{ session('level') }}"
          }).show();
      @endif
    });
</script>
@endsection
@endsection
