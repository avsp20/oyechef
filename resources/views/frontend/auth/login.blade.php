@extends('frontend.auth.auth-master')

@section('content')
<form class="" method="POST" action="{{ route('login.submit') }}">
  @csrf
    <div class="form-title">
        <h2>Sign In</h2>
        <p>Don’t have an account? <a href="{{ route('register') }}" class="form-link fw-500"> Sign up</a></p>
    </div>
    <div class="form-content">
        <div class="form-left">
            <div class="form-group">
              <label for="email" class="form-label">Email<span class="text-danger"> *</span></label>
              <input type="text" name="email" class="form-control" id="email" placeholder="Enter your email" value="{{ old('email') }}">
              <span class="text-danger">{{ $errors->first('email') }}</span>
            </div>
            <div class="form-group">
              <label for="password" class="form-label">Password<span class="text-danger"> *</span></label>
              <input type="password" name="password" class="form-control" id="password" placeholder="**********" value="{{ old('password') }}">
              <span class="text-danger">{{ $errors->first('password') }}</span>
            </div>
            <div class="form-group form-row">
                 <div class="form-col form-check">
                  <input type="checkbox" class="form-check-input" id="exampleCheck1" name="rememberme">
                  <label class="form-check-label" for="exampleCheck1">Remember me</label>
                </div>
                <div class="form-col">
                  <a href="{{ route('front.password.request') }}" class="forgotpass-link"> Forgot password?</a>
                </div>
            </div>
            <div class="form-btn">
              <button type="submit" class="btn btn-submit">Sign In</button>
            </div>
        </div>
        <div class="form-right">
            <div class="social-btn-wrapper">
               <div class="social-btn btn-fb" onClick="window.open('{{ url('auth/facebook') }}','_self');">
                    <div class="btn-icon">
                        <svg width="13" height="25" viewBox="0 0 13 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.30798 4.7594H12.5283V0H8.74272V0.0171644C4.15581 0.179702 3.21571 2.75936 3.13285 5.46877H3.12342V7.84538H0V12.5063H3.12342V25H7.83059V12.5063H11.6865L12.4314 7.84538H7.83213V6.40949C7.83213 5.49382 8.44113 4.7594 9.30798 4.7594Z" fill="#3B5998"/>
                        </svg>
                    </div>
                    <span>Continue with Facebook</span>
                </div>
                <div class="social-btn btn-google" onClick="window.open('{{ url('auth/google') }}','_self');">
                    <div class="btn-icon">
                        <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M24.4688 12.7813C24.4688 11.9584 24.3958 11.1772 24.2708 10.4167H12.5V15.1147H19.2396C18.9375 16.6563 18.0521 17.9584 16.7396 18.8438V21.9688H20.7604C23.1146 19.7917 24.4688 16.5834 24.4688 12.7813Z" fill="#4285F4"/>
                            <path d="M12.5 25.0001C15.875 25.0001 18.6979 23.8751 20.7604 21.9688L16.7396 18.8438C15.6146 19.5938 14.1875 20.0522 12.5 20.0522C9.23958 20.0522 6.47917 17.8542 5.48958 14.8855H1.34375V18.1042C3.39583 22.1876 7.61458 25.0001 12.5 25.0001Z" fill="#34A853"/>
                            <path d="M5.48958 14.8853C5.22917 14.1353 5.09375 13.3332 5.09375 12.4999C5.09375 11.6666 5.23958 10.8645 5.48958 10.1145V6.89575H1.34375C0.489582 8.58325 0 10.4791 0 12.4999C0 14.5207 0.489582 16.4166 1.34375 18.1041L5.48958 14.8853Z" fill="#FBBC05"/>
                            <path d="M12.5 4.94792C14.3438 4.94792 15.9896 5.58334 17.2917 6.82292L20.8542 3.26042C18.6979 1.23958 15.875 0 12.5 0C7.61458 0 3.39583 2.8125 1.34375 6.89584L5.48958 10.1146C6.47917 7.14584 9.23958 4.94792 12.5 4.94792Z" fill="#EA4335"/>
                        </svg>
                   </div>
                   <span>Continue with Google</span>
                </div>
            </div>
        </div>
    </div>
    <div class="form-bottom">
        <p>
          This site is protected by reCAPTCHA Enterprise and the Google
          <a href="{{ route('front.privacy-policy') }}" target="_blank" class="form-link">Privacy Policy</a> And <a href="{{ route('front.terms-and-conditions') }}" target="_blank" class="form-link">Terms of Use</a> apply
        </p>
    </div>
</form>
@section('script')
<script type="text/javascript">
    $(document).ready(function(){
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
