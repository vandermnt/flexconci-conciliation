  @extends('layouts.authLayout')
  @section('content')
  <!-- Log In page -->
  <div class="container">
      <div class="row vh-100 ">
          <div class="col-12 align-self-center">
              <div class="auth-page">
                  <div class="card auth-card shadow-lg">
                      <div class="card-body">
                          <div class="px-3">
                              <div class="mb-12" style="text-align:center">
                                  <a class="logo logo-admin">
                                      <span><img src="{{ URL::asset('assets/images/logoconci.png')}}" height="45" class="my-3"></span>
                                  </a>
                              </div>
                              <div class="text-center auth-logo-text">
                                  <h4 class="mt-0 mb-3 mt-3">Redefinir senha</h4>
                              </div>
                              <form method="POST" action="{{ route('password.update') }}">
                                  @csrf
                                  <input type="hidden" name="token" value="{{ $token }}">
                                  <div class="form-group">
                                      <label for="username">E-mail</label>
                                      <div class="input-group mb-3">
                                          <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="USUARIO" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                                          @error('email')
                                          <span class="invalid-feedback" role="alert">
                                              <strong>{{ $message }}</strong>
                                          </span>
                                          @enderror

                                      </div>
                                  </div>
                                  <div class="form-group">
                                      <label for="username">Senha</label>
                                      <div class="input-group mb-3">
                                          <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="SENHA" required autocomplete="new-password">

                                          @error('password')
                                          <span class="invalid-feedback" role="alert">
                                              <strong>{{ $message }}</strong>
                                          </span>
                                          @enderror

                                      </div>
                                  </div>
                                  <!--end form-group-->


                                  <!-- <div class="form-group">
                                              <label for="username">Confirmar Senha</label>
                                              <div class="input-group mb-3">

                                  <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">


                                              </div>
                                          </div>end form-group -->

                                  <div class="form-group mb-0 row">
                                      <div class="col-12 mt-2">
                                          <button class="btn btn-round btn-block waves-effect waves-light" style="background: #2D5275; color: white" type="submit">Redefinir Senha<i class="fas fa-sign-in-alt ml-1"></i></button>
                                      </div>
                                  </div>
                              </form>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>

  @endsection
