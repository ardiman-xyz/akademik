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
<section class="content">
  <br>
  <center>
    <font style=" font-size: 40px">Selamat Datangs</font><br>
      <b>
        <font style=" font-size: 30px"><?php echo Auth::user()->name;?></font>
      </b>
      <br><br><br>
      <img src="img/logo_univ.png" alt="" class="logo-side" >
      <br><br><br>
      <h1>SISTEM INFORMASI AKADEMIK</h1>
      <h3>{{env('NAME_UNIV')}}</h3>
    </center>
</section>
<!-- /.content -->
@endsection
