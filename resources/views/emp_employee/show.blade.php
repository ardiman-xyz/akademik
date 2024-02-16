@extends('layouts._layout')
@section('pageTitle', 'Building')
@section('content')
<?php

foreach ($query as $data_edit) {

?>
<section class="content">


  <div class="container">
    <div class="title-laporan">
      <h3>Detail Gedung</h3>
    </div>
    <hr>
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-purple">
        <div class="pull-right">
          <a href="{{ url('master/building?page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-danger" style="font-size:medium">Kembali &nbsp;<i class="glyphicon icon-plus-sign"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Detail</b>
        </div>
      </div>
      <hr>
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
