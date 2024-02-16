@extends('layouts._layout')
@section('pageTitle', 'Building')
@section('content')
<?php

foreach ($query as $data_edit) {

?>
<section class="content">

  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Detail Gedung</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('master/building?page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Detail</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
          <div class="row col-md-12 col-xs-12">
            {!! Form::label('', 'Kode Gedung', ['class' => 'col-md-4 col-xs-12']) !!}:
            <label for="" class="col-md-4 col-xs-12">{{$data_edit->Building_Code}}</label>
          </div>
          <div class="row col-md-12 col-xs-12">
            {!! Form::label('', 'Nama Gedung', ['class' => 'col-md-4 col-xs-12']) !!}:
            <label for="" class="col-md-4 col-xs-12">{{$data_edit->Building_Name}}</label>
          </div>
          </div>
      </div>
    </div>
  </div>



<!-- /.row -->

</section>
<?php } ?>
@endsection
