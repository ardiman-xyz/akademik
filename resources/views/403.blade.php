@extends('layouts._layout')
@section('pageTitle', 'Home')
@section('content')

<!-- Main content -->

<style>
.back{
  background-color:#b7b5b5;
}
</style>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Tidak ada hak akses</h3>
    </div>
  </div>

  <br>

  {{-- <div class="container-fluid-title back">
    <div>
      <h3 class="text-black">Anda tidak memiliki hak akses pada halaman ini.</h3>
    </div>
  </div> --}}
    <div class="back">
  <center></br>
    {{-- <img src="{{ url('img/403_forbidden.png')}}" alt="gambar kosong"> --}}
    <h3>Anda tidak memiliki hak akses pada halaman ini.</h3>
    <h3>Silakan hubungi Admin untuk meminta hak akses.</h3>
    <br>
    {{-- <a href="{{ URL::previous() }}">Kembali ke halaman sebelumnya</a> --}}
  </center>
</div>
</section>
<!-- /.content -->
@endsection
