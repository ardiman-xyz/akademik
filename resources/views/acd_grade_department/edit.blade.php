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
          <a href="{{ url('parameter/grade_department?department='.$data_edit->Department_Id.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search.'&term_year='.$term_year) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
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
          {!! Form::open(['url' => route('grade_department.update', $data_edit->Grade_Department_Id) , 'method' => 'put', 'class' => 'form']) !!}
          <!-- <div class="form-group">
            {!! Form::label('', 'Program Studi', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text"  readonly value="{{ $data_edit->Department_Name }}" class="form-control form-control-sm">
              <input type="text" name="department_id" hidden  readonly value="{{ $data_edit->Department_Id }}" class="form-control form-control-sm">
            </div>
          </div> -->
          <input type="text" hidden name="entry_year" value="{{ $request->entry_year }}"class="form-control form-control-sm">
          <div class="form-group">
            {!! Form::label('', 'Jenis Matakuliah', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select class="form-control form-control-sm" name="Grade_Letter_Id">
                <option value="">Pilih Jenis Matakuliah</option>
                @foreach ( $select_grade_letter as $data )
                  <option <?php if($data_edit->Grade_Letter_Id == $data->Grade_Letter_Id){ echo "selected"; } ?> value="{{ $data->Grade_Letter_Id }}">{{ $data->Grade_Letter }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Bobot', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Weight_Value" max="4" value="{{ $data_edit->Weight_Value }}" class="form-control form-control-sm">
              <input type="text" hidden name="term_year" value="{{ $term_year }}" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Predikat', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Predicate" value="{{ $data_edit->Predicate }}" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Predikat (English)', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Predicate_Eng" value="{{ $data_edit->Predicate_Eng }}" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Batas Atas', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Scale_Numeric_Max" value="{{ $data_edit->Scale_Numeric_Max }}" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Batas Bawah', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Scale_Numeric_Min" value="{{ $data_edit->Scale_Numeric_Min }}" class="form-control form-control-sm">
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
