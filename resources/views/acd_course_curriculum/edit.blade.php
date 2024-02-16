@extends('layouts._layout')
@section('pageTitle', 'Course Curriculum')
@section('content')

<?php

foreach ($query_edit as $data_edit) {

?>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Edit Matakuliah Kurikulum</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('parameter/course_curriculum?class_program='.$class_program.'&curriculum='.$curriculum.'&semester='.$semester.'&department='.$department.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
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
          {!! Form::open(['url' => route('course_curriculum.update', $data_edit->Course_Cur_Id) , 'method' => 'put', 'class' => 'form']) !!}
          <div class="form-group">
            {!! Form::label('', 'Kode Matakuliah', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Course_Code"  value="{{ $data_edit->Course_Code }}" readonly class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Nama Matakuliah Kurikulum', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Course_Name" value="{{ $data_edit->Course_Name }}" readonly class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'SKS', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Applied_Sks" min="1" value="{{ $data_edit->Applied_Sks }}" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Transkrip', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select  class="form-control form-control-sm" name="Is_For_Transcript">
                <option <?php if($data_edit->Is_For_Transcript == True){ echo "selected"; } ?> value="1">Ya</option>
                <option <?php if($data_edit->Is_For_Transcript == False){ echo "selected"; } ?> value="0">Tidak</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'SKS Transkrip', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Transcript_Sks" min="1" value="{{ $data_edit->Transcript_Sks }}"  class="form-control form-control-sm">
            </div>
          </div>
          <input type="text" name="Is_Required" hidden value="1">
          <!-- <div class="form-group">
            {!! Form::label('', 'Sifat', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select  class="form-control form-control-sm" name="Is_Required">
                <option <?php if($data_edit->Is_Required == True){ echo "selected"; } ?> value="1">Wajib</option>
                <option <?php if($data_edit->Is_Required == False){ echo "selected"; } ?> value="0">Pilihan</option>
              </select>
            </div>
          </div> -->
          <input type="text" name="Course_Group_Id" hidden value="15">
          <!-- <div class="form-group">
            {!! Form::label('', 'Nama kelompok MK', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select  class="form-control form-control-sm" name="Course_Group_Id">
                @foreach ( $select_course_group as $data )
                  <option <?php if($data_edit->Course_Group_Id == $data->Course_Group_Id){ echo "selected"; } ?> value="{{ $data->Course_Group_Id }}">{{ $data->Name_Of_Group }}</option>
                @endforeach
              </select>
            </div>
          </div> -->
          <div class="form-group">
            {!! Form::label('', 'SMT', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select  class="form-control form-control-sm" name="Study_Level_Id">
                @foreach ( $select_study_level as $data )
                  <option <?php if($data_edit->Study_Level_Id == $data->Study_Level_Id){ echo "selected"; } ?> value="{{ $data->Study_Level_Id }}">{{ $data->Study_Level_Code }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <!-- <div class="form-group">
            {!! Form::label('', 'Sub SMT', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Study_Level_Sub" min="1" value="{{ $data_edit->Study_Level_Sub }}" class="form-control form-control-sm">
            </div>
          </div> -->
          <div class="form-group">
            {!! Form::label('', 'Jenis Kurikulum', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select class="form-control form-control-sm" name="Curriculum_Type_Id">
                @foreach ( $select_curriculum_type as $data )
                  <option <?php if($data_edit->Curriculum_Type_Id == $data->Curriculum_Type_Id){ echo "selected"; } ?> value="{{ $data->Curriculum_Type_Id }}">{{ $data->Curriculum_Type_Name }}</option>
                @endforeach
              </select>
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
