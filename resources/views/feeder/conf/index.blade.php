@extends('layouts._layout')
@section('pageTitle', 'Feeder - Configure WSDL')
@section('content')


<?php
$access = auth()->user()->akses();
          $acc = $access;
?>

<section class="content">

  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Feeder - Konfigurasi WSDL</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          <b>Konfigurasi WSDL</b>
        </div>
      </div>
    </div>
    <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        <div style="padding:30px;">
            @if(session('status') != null || session('status') != '')
                <div class="container">{{ session('status')}}</div>
                <br>
            @endif
            {!! Form::open(['url' => route('feeder.conf.save') , 'method' => 'post', 'class' => 'form']) !!}
            <div class="row col-md-12">
                <label class="col-md-4" for="">URL WSDL Feeder</label>
                <label class="col-md-1" for="">:</label>
            <div class="col-md-7"><input name="url_wsdl" value="{{$data->url_wsdl}}" class="form-control col-md-12" type="text"></div>
            </div>
            <div class="row col-md-12">
                <label class="col-md-4" for="">User Feeder</label>
                <label class="col-md-1" for="">:</label>
                <div class="col-md-7"><input name="user_wsdl" value="{{$data->user_wsdl}}" class="form-control col-md-12" type="text"></div>
            </div>
            <div class="row col-md-12">
                <label class="col-md-4" for="">Password Feeder</label>
                <label class="col-md-1" for="">:</label>
                <div class="col-md-7"><input name="pass_wsdl" value="{{$data->pass_wsdl}}" class="form-control col-md-12" type="password"></div>
            </div>
            <div class="row col-md-12">
                <label class="col-md-4" for=""><b>Aktivasi Sinkronisasi Feeder</b></label>
                <label class="col-md-1" for="">:</label>
                <div class="col-md-7">
                    <select name="activate_synchronization" class="form-control col-md-12">
                        <option <?php if($data->activate_synchronization == 1){ echo 'selected'; } ?> value="1">Ya</option>
                        <option <?php if($data->activate_synchronization == 0){ echo 'selected'; } ?> value="0">Tidak</option>
                    </select>
                </div>
            </div>
            <div class="row col-md-12">
                <label class="col-md-4" for=""><b>Default Tahun Akademik Aktif Feeder</b></label>
                <label class="col-md-1" for="">:</label>
                <div class="col-md-7"><input name="default_term_year" value="{{$data->default_term_year}}" class="form-control col-md-12" type="text"></div>
            </div>
            <div class="row col-md-12">
                <label class="col-md-4" for=""><b>Realisasi Jml Pertemuan Dosen Sesuai Presensi Setelah Tahun Akademik</b></label>
                <label class="col-md-1" for="">:</label>
                <div class="col-md-7"><input name="realization_term_year" value="{{$data->realization_term_year}}" class="form-control col-md-12" type="text"></div>
            </div>
            <div class="row col-md-12">
                <label class="col-md-4" for=""><b>Separator / Karakter Pemisah Export Data Csv</b></label>
                <label class="col-md-1" for="">:</label>
                <div class="col-md-7"><input name="separator_export" value="{{$data->separator_export}}" class="form-control col-md-12" type="text"></div>
            </div>
            <br>
            <center><button class="btn btn-primary" type="submit">SIMPAN PERUBAHAN</button></center>
            {!! Form::close() !!}
        </div>
    </div>
  </div>

  <script>
  </script>

</section>


@endsection