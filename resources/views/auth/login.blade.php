@extends('auth.auth')

@section('content')
<body class="hold-transition login-page">
    <div class="login-box">
      <!-- /.login-logo -->
      <div class="card">
        <div class="card-body login-card-body p-3">
            <div class="d-flex justify-content-center mb-2">
                <img class="" src="{{ asset('dist/img/laroscell.png')}}" alt="Logo" height="50%" width="70%" borde-radius="50%">
            </div>
            <h4>Login</h4>
          <p class="text-left">Masukkan username dan password anda</p>
            @if (Session::has('error'))
                <div class="mb-2 bg-danger p-2 text-center text-white rounded swalDefaultError d-flex justify-content-center" role="alert">
                {{ Session::get('error') }}
                </div>
            @endif
          <form action="{{ route('login.action') }}" method="post">
            @csrf
            <div class="input-group mb-3">
              <input type="text" class="form-control" name="username" placeholder="Masukkan username" required>
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-user"></span>
                </div>
              </div>
            </div>
            <div class="input-group mb-3">
              <input id="MyPass" type="password" class="form-control" name="password" placeholder="Masukkan password" required>
              <div class="input-group-append">
                <div class="input-group-text">
                  <span class="fas fa-lock"></span>
                </div>
              </div>
            </div>
            <div class="col-8">
                <div class="icheck-primary">
                    <input type="checkbox" id="remember" onclick="ShowHidden()">
                    <label for="remember">
                        Tampilkan password
                    </label>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </div>
          </form>
        </div>
        <!-- /.login-card-body -->
      </div>
    </div>
    <!-- /.login-box -->
    <script>
        function ShowHidden() {
            var x = document.getElementById("MyPass");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
    </script>
@endsection
