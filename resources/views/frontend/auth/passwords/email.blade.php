@extends('frontend.auth.auth-master')

@section('content')
<form class="" action="{{ route('password.email') }}" method="POST">
  @csrf
  <div class="form-title">
    <h2>Forgot Password</h2>
    <p>Don’t worry! Just fill in your email and we’ll help you reset your password.</p>
  </div>
  <div class="form-content">
    <div class="form-left">
      <div class="form-group">
        <label for="email" class="form-label">Email</label>
        <input type="text" name="email" class="form-control" id="email" placeholder="Enter your email">
        <span class="text-danger">{{ $errors->first('email') }}</span>
      </div>
      <div class="form-btn">
        <button type="submit" class="btn btn-submit w-100">Send password reset link</button>
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
