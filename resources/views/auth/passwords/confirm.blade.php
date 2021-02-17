@extends('layouts.auth')

@section('content')
<p class="login-box-msg">  {{ __('Please confirm your password before continuing.') }}</p>


                    <form method="POST" action="{{ route('password.confirm') }}">
                        @csrf

                        <div class="input-group mb-3">
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password"  placeholder="{{ __('Password') }}">


                            <div class="input-group-append">
                              <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                              </div>
                            </div>
                            @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                          </div>
                          <div class="row">
                            <div class="col-6">
                                <button type="submit" class="btn btn-danger btn-md">   {{ __('Confirm Password') }}
                                </button>
                            </div>
                            <!-- /.col -->
                            <div class="col-6">
                                @if (Route::has('password.request'))
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    <small>{{ __('Forgot Your Password?') }}</small>
                                </a>
                            @endif
                            </div>
                            <!-- /.col -->
                          </div>

                    </form>

@endsection
