@extends('layouts._layout')
@section('pageTitle', 'Curriculum Applied')
@section('content')

<?php

foreach ($query_edit as $data_edit) {

?>

<section class="content">
    <div class="container-fluid-title">
      <div class="title-laporan">
        <h3 class="text-white">Edit Kurikulum Prodi</h3>
      </div>
    </div>
    <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('parameter/curriculum_applied?department='.$data_edit->Department_Id.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
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
          {!! Form::open(['url' => route('curriculum_applied.update', $data_edit->Curiculum_Applied_Id) , 'method' => 'put', 'class' => 'form']) !!}
          <div class="form-group">
            {!! Form::label('', 'Program Studi', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text"  readonly value="{{ $data_edit->Department_Name }}" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Kurikulum', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select class="form-control form-control-sm" name="Curriculum_Id">
                <option value="">Pilih Kurikulum</option>
                @foreach ( $select_curriculum as $data )
                  <option <?php if($data_edit->Curriculum_Id == $data->Curriculum_Id){ echo "selected"; } ?> value="{{ $data->Curriculum_Id }}">{{ $data->Curriculum_Name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Program Kelas', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select class="form-control form-control-sm" name="Class_Prog_Id">
                <option value="">Pilih Program Kelas</option>
                @foreach ( $select_class_program as $data )
                  <option <?php if($data_edit->Class_Prog_Id == $data->Class_Prog_Id){ echo "selected"; } ?> value="{{ $data->Class_Prog_Id }}">{{ $data->Class_Program_Name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'SKS Wajib', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" value="{{ $data_edit->Total_Sks_Core }}" name="Total_Sks_Core"  class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'SKS Pilihan', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" value="{{ $data_edit->Total_Sks_Elective }}" name="Total_Sks_Elective"  class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Min GPA Lulus', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" value="{{ $data_edit->Min_Cum_Gpa }}" name="Min_Cum_Gpa"  class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Min SKS Lulus', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" value="{{ $data_edit->Sks_Completion }}" name="Sks_Completion"  class="form-control form-control-sm">
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
