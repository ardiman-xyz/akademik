@extends('layouts._layout')
@section('pageTitle', 'Grade Department')
@section('content')

<?php

foreach ($query_edit as $data_edit) {

?>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Edit Grade Nilai</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('parameter/beban_mengajar') }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Edit</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">

          @if (count($errors) > 0)
            @foreach ( $errors->all() as $error )
              @if ($error=="Berhasil Menyimpan Perubahan")
                <p class="alert alert-success">{{ $error }}</p>
              @else
                <p class="alert alert-danger">{{ $error }}</p>
              @endif
            @endforeach
          @endif
          {!! Form::open(['url' => route('beban_mengajar.update', $data_edit->Lecturer_Work_Load_Id) , 'method' => 'put', 'class' => 'form']) !!}
          <div class="form-group">
            {!! Form::label('', 'Tahun/Semester', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" readonly name="Term_Year_Id" value="{{ $data_edit->Term_Year_Id }}" class="form-control form-control-sm">
            </div>
          </div>
          
          <div class="form-group">
            {!! Form::label('', 'Beban Mengajar', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Beban_Mengajar" value="{{ $data_edit->Sks }}" class="form-control form-control-sm">
            </div>
          </div>
          <br><center><button type="submit" class="btn btn-primary btn-flat">Simpan</button></center>

          {!! Form::close() !!}
      </div>
    </div>
  </div>

<!-- /.row -->

</section>

<?php
}
?>
@endsection
