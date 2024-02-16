@extends('layouts._layout')
@section('pageTitle', 'Berkas')
@section('content')

<!-- Main content -->
<?php
try {
  DB::connection()->getPdo();
} catch (\Exception $e) {
  ?>
  <div class="col-sm-12 alert alert-danger"><center>
    <b>Sambungan Gagal !</b><br>
    Tidak dapat tersambung ke database, pesan error :<br>
    <?php echo $e->getMessage(); ?>
  </div>
  <?php
}
?>
<section class="content" style="color:#333;">
  <br><br><br>
  <div class="row">
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6"  style="text-align:right">

        <br><br><br>
        <br><br>
        <h3>SISTEM INFORMASI AKADEMIK</h3>
       <h5>Sekolah Tinggi Teknologi Nasional (STTNAS) Yogyakarta</h5>
       <h5><i>Center of Excellence</i></h5>
     </center>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
      <!-- <h2 class="center">Login</h2>
      <div class="container container-login-white">
        <form action="/action_page.php">
          <div class="form-group">
            <label for="username">Username:</label>
            <input type="username" class="form-control" id="username">
          </div>
          <div class="form-group">
            <label for="pwd">Password:</label>
            <input type="password" class="form-control" id="pwd">
          </div>
          <div class="form-check">
            <label class="form-check-label">
              <input class="form-check-input" type="checkbox"> Remember me
            </label>
          </div>
          <button type="submit" class="btn btn-primary">Submit</button>
        </form>
      </div> -->

      <div class=" container-login-transparant-border-right">
        <div style="margin-left:20px;">
          <h3>Login Pengguna</h3>
          Hubungi admin Univ untuk mendapatkan hak akses <br><br>
        </div>
          <form class="form-horizontal" method="POST" action="{{ route('login') }}">
              {{ csrf_field() }}

              <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                  <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                  <div class="col-md-12">
                      <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" autofocus>

                      @if ($errors->has('email'))
                          <span class="help-block">
                              <strong>{{ $errors->first('email') }}</strong>
                          </span>
                      @endif
                  </div>
              </div>

              <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                  <label for="password" class="col-md-4 control-label">Password</label>

                  <div class="col-md-12">
                      <input id="password" type="password" class="form-control" name="password" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')">

                      @if ($errors->has('password'))
                          <span class="help-block">
                              <strong>{{ $errors->first('password') }}</strong>
                          </span>
                      @endif
                  </div>
              </div>
              <div class="form-check">
                <label class="form-check-label">
                  <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                </label>
              </div>
              <p></p>
              <div class="form-group">
                  <div class="col-md-8 col-md-offset-4">
                      <button type="submit" class="btn btn-success">
                          Login
                      </button>
                  </div>
              </div>
          </form>
      </div>
    </div>
  </div>
</section>
<!-- /.content -->
@endsection
