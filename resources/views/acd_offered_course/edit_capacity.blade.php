@extends('layouts._layout')
@section('pageTitle', 'Offered Course')

@section('content')

<?php

foreach ($query_edit as $data_edit) {

?>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Edit Matakuliah Ditawarkan</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('setting/offered_course?term_year='.$term_year.'&class_program='.$class_program.'&department='.$department.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search.'&curriculum='.$curriculum) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Edit kapasitas</b>
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
          {!! Form::open(['url' => route('offered_course.update_capacity', $data_edit->Offered_Course_id) , 'method' => 'put', 'class' => 'form']) !!}
          <input type="text" name="department" value="{{$department}}" hidden >
          <input type="text" name="term_year" value="{{$term_year}}" hidden >
          <input type="text" name="class_program" value="{{$class_program}}" hidden >
          <input type="text" name="curriculum" value="{{$curriculum}}" hidden >
          <div class="form-group">
            {!! Form::label('', 'Kode Matakuliah', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text"  value="{{ $data_edit->Course_Code }}" readonly class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Nama Matakuliah', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text"  value="{{ $data_edit->Course_Name }}" readonly class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Kelas yang ditawarkan', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text"  value="{{ $data_edit->Class_Name }}" readonly class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Kapasitas', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Class_Capacity"  value="{{ $data_edit->Class_Capacity }}" class="form-control form-control-sm">
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
