@extends('layouts._layout')
@section('pageTitle', 'Home')
@section('content')

<!-- Main content -->
<?php
try {
  DB::connection()->getPdo();
} catch (\Exception $e) {
  ?>
  <div class="col-sm-12 alert alert-danger">
    <b>Sambungan Gagal !</b><br>
    Tidak dapat tersambung ke database, pesan error :<br>
    <?php echo $e->getMessage(); ?>
  </div>
  <?php
}
?>
<!-- Main Section -->
<section class="main-section">
    <!-- Add Your Content Inside -->
    <div class="content">
        <!-- Remove This Before You Start -->
        <h1>Anak IT -  Login</h1>
        <hr>
        @if(\Session::has('alert'))
            <div class="alert alert-danger">
                <div>{{Session::get('alert')}}</div>
            </div>
        @endif
        @if(\Session::has('alert-success'))
            <div class="alert alert-success">
                <div>{{Session::get('alert-success')}}</div>
            </div>
        @endif
        <form action="{{ url('/loginPost') }}" method="post">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email">
            </div>
            <div class="form-group">
                <label for="alamat">Password:</label>
                <input type="password" class="form-control" id="password" name="password"></input>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-md btn-primary">Login</button>
                <a href="{{url('register')}}" class="btn btn-md btn-warning">Register</a>
            </div>
        </form>
    </div>
    <!-- /.content -->
</section>
<!-- /.main-section -->
@endsection
