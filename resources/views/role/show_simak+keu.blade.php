@extends('layouts._layout')
@section('pageTitle', 'Role')
@section('content')
  <style>
    body { background-color:#fafafa;}
    .stylish-input-group .input-group-addon{
        background: white !important;
    }
    .stylish-input-group .form-control{
        //border-right:0;
        box-shadow:0 0 0;
        border-color:#ccc;
    }
    .stylish-input-group button{
        border:0;
        background:transparent;
    }

    .h-scroll {
        background-color: #fcfdfd;
        height: 260px;
        overflow-y: scroll;
    }
    ul {
      list-style-type: none;
      margin: 3px;
    }
    ul.checktree li:before {
      height: 1em;
      width: 12px;
      border-bottom: 1px dashed;
      content: "";
      display: inline-block;
      top: -0.3em;
    }
    ul.checktree li { border-left: 1px dashed; }
    ul.checktree li:last-child:before { border-left: 1px dashed; }
    ul.checktree li:last-child { border-left: none; }
  </style>
<?php

foreach ($query as $data_edit) {

  $acce = array();
  foreach ($access as $value) {
    $acce[] = $value->name;
  }
  $accekeu = array();
  foreach ($accesskeu as $value) {
    $accekeu[] = $value->id;
  }
?>
<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Detail Role</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('administrator/role?page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Detail</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">

          @if (count($errors) > 0)
            @foreach ( $errors->all() as $error )
              <p class="alert alert-danger">{{ $error }}</p>
            @endforeach
          @endif
          <div class="row col-md-12 col-xs-12">
            {!! Form::label('', 'Nama', ['class' => 'col-md-4 col-xs-12']) !!}:
            <label for="" class="col-md-4 col-xs-12">{{$data_edit->name}}</label>
          </div>
          <div class="row col-md-12 col-xs-12">
            {!! Form::label('', 'Deskripsi', ['class' => 'col-md-4 col-xs-12']) !!}:
            <label for="" class="col-md-4 col-xs-12">{{$data_edit->description}}</label>
          </div>
          <div class="row col-md-12 col-xs-12">
          {!! Form::label('', 'Otoritas', ['class' => 'col-md-4 col-xs-12']) !!}:
          </div>
          <div class=" col-md-12 col-xs-12" style="padding:2%;">
            <div>
              <div id="treeview_container" class="hummingbird-treeview well h-scroll-large">
                <ul id="treeview" class="hummingbird-base checktree">
                  <li>
                    <i class="fa fa-plus"></i> <label> <input id="simakad" data-id="custom-0" type="checkbox"> SIMAKAD</label>
                    {{-- <ul style="display: block;"> --}}
                    <ul>
                      <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Administrator</label>
                        <ul>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1-1" data-id="custom-1-1" type="checkbox"> User</label>
                            <ul>
                              <li><i></i> <label> <input name="access[]" id="user-CanView" class="hummingbirdNoParent" type="checkbox" value="{{ $accesses->where('name', 'user-CanView')->first()->id }}"> {{ $accesses->where('name', 'user-CanView')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" id="user-CanAdd" class="hummingbirdNoParent" type="checkbox" value="{{ $accesses->where('name', 'user-CanAdd')->first()->id }}"> {{ $accesses->where('name', 'user-CanAdd')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" id="user-CanEdit" class="hummingbirdNoParent" type="checkbox" value="{{ $accesses->where('name', 'user-CanEdit')->first()->id }}"> {{ $accesses->where('name', 'user-CanEdit')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" id="user-CanDelete" class="hummingbirdNoParent" type="checkbox" value="{{ $accesses->where('name', 'user-CanDelete')->first()->id }}"> {{ $accesses->where('name', 'user-CanDelete')->first()->description }}</label></li>
                            </ul>
                          </li>
                          <li><i class="fa fa-plus"></i> <label> <input name="access[]" id="ubahpassword-CanView" class="" type="checkbox" value="{{ $accesses->where('name', 'ubahpassword-CanView')->first()->id }}"> {{ $accesses->where('name', 'ubahpassword-CanView')->first()->description }}</label>
                          </li>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Role</label>
                            <ul>
                              <li><i></i> <label> <input name="access[]" id="role-CanView" type="checkbox" value="{{ $accesses->where('name', 'role-CanView')->first()->id }}"> {{ $accesses->where('name', 'role-CanView')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" id="role-CanAdd" type="checkbox" value="{{ $accesses->where('name', 'role-CanAdd')->first()->id }}"> {{ $accesses->where('name', 'role-CanAdd')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" id="role-CanEdit" type="checkbox" value="{{ $accesses->where('name', 'role-CanEdit')->first()->id }}"> {{ $accesses->where('name', 'role-CanEdit')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" id="role-CanDelete" type="checkbox" value="{{ $accesses->where('name', 'role-CanDelete')->first()->id }}"> {{ $accesses->where('name', 'role-CanDelete')->first()->description }}</label></li>
                            </ul>
                          </li>
                      </ul>
                    </li>
                      <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Master</label>
                        <ul>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1-1" data-id="custom-1-1" type="checkbox"> Akademik</label>
                            <ul>
                              <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Fakultas</label>
                                <ul>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="faculty-CanView" value="{{ $accesses->where('name', 'faculty-CanView')->first()->id }}"> {{ $accesses->where('name', 'faculty-CanView')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="faculty-CanAdd" value="{{ $accesses->where('name', 'faculty-CanAdd')->first()->id }}"> {{ $accesses->where('name', 'faculty-CanAdd')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="faculty-CanEdit" value="{{ $accesses->where('name', 'faculty-CanEdit')->first()->id }}"> {{ $accesses->where('name', 'faculty-CanEdit')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="faculty-CanDelete" value="{{ $accesses->where('name', 'faculty-CanDelete')->first()->id }}"> {{ $accesses->where('name', 'faculty-CanDelete')->first()->description }}</label></li>
                                </ul>
                              </li>
                              <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Program Studi</label>
                                <ul>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="department-CanView" value="{{ $accesses->where('name', 'department-CanView')->first()->id }}"> {{ $accesses->where('name', 'department-CanView')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="department-CanAdd" value="{{ $accesses->where('name', 'department-CanAdd')->first()->id }}"> {{ $accesses->where('name', 'department-CanAdd')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="department-CanEdit" value="{{ $accesses->where('name', 'department-CanEdit')->first()->id }}"> {{ $accesses->where('name', 'department-CanEdit')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="department-CanDelete" value="{{ $accesses->where('name', 'department-CanDelete')->first()->id }}"> {{ $accesses->where('name', 'department-CanDelete')->first()->description }}</label></li>
                                </ul>
                              </li>
                              <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Program Kelas</label>
                                <ul>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="class_program-CanView" value="{{ $accesses->where('name', 'class_program-CanView')->first()->id }}"> {{ $accesses->where('name', 'class_program-CanView')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="class_program-CanAdd" value="{{ $accesses->where('name', 'class_program-CanAdd')->first()->id }}"> {{ $accesses->where('name', 'class_program-CanAdd')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="class_program-CanEdit" value="{{ $accesses->where('name', 'class_program-CanEdit')->first()->id }}"> {{ $accesses->where('name', 'class_program-CanEdit')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="class_program-CanDelete" value="{{ $accesses->where('name', 'class_program-CanDelete')->first()->id }}"> {{ $accesses->where('name', 'class_program-CanDelete')->first()->description }}</label></li>
                                </ul>
                              </li>
                              <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Prodi vs Program Kelas</label>
                                <ul>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="department_class_program-CanView" value="{{ $accesses->where('name', 'department_class_program-CanView')->first()->id }}"> {{ $accesses->where('name', 'department_class_program-CanView')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="department_class_program-CanAdd" value="{{ $accesses->where('name', 'department_class_program-CanAdd')->first()->id }}"> {{ $accesses->where('name', 'department_class_program-CanAdd')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="department_class_program-CanDelete" value="{{ $accesses->where('name', 'department_class_program-CanDelete')->first()->id }}"> {{ $accesses->where('name', 'department_class_program-CanDelete')->first()->description }}</label></li>
                                </ul>
                              </li>
                              <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Konsentrasi</label>
                                <ul>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="concentration-CanView" value="{{ $accesses->where('name', 'concentration-CanView')->first()->id }}"> {{ $accesses->where('name', 'concentration-CanView')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="concentration-CanAdd" value="{{ $accesses->where('name', 'concentration-CanAdd')->first()->id }}"> {{ $accesses->where('name', 'concentration-CanAdd')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="concentration-CanEdit" value="{{ $accesses->where('name', 'concentration-CanEdit')->first()->id }}"> {{ $accesses->where('name', 'concentration-CanEdit')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="concentration-CanDelete" value="{{ $accesses->where('name', 'concentration-CanDelete')->first()->id }}"> {{ $accesses->where('name', 'concentration-CanDelete')->first()->description }}</label></li>
                                </ul>
                              </li>
                              <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Jenjang Pendidikan</label>
                                <ul>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="education_type-CanView" value="{{ $accesses->where('name', 'education_type-CanView')->first()->id }}"> {{ $accesses->where('name', 'education_type-CanView')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="education_type-CanAdd" value="{{ $accesses->where('name', 'education_type-CanAdd')->first()->id }}"> {{ $accesses->where('name', 'education_type-CanAdd')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="education_type-CanEdit" value="{{ $accesses->where('name', 'education_type-CanEdit')->first()->id }}"> {{ $accesses->where('name', 'education_type-CanEdit')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="education_type-CanDelete" value="{{ $accesses->where('name', 'education_type-CanDelete')->first()->id }}"> {{ $accesses->where('name', 'education_type-CanDelete')->first()->description }}</label></li>
                                </ul>
                              </li>
                              <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Angkatan</label>
                                <ul>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="entry_year-CanView" value="{{ $accesses->where('name', 'entry_year-CanView')->first()->id }}"> {{ $accesses->where('name', 'entry_year-CanView')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="entry_year-CanAdd" value="{{ $accesses->where('name', 'entry_year-CanAdd')->first()->id }}"> {{ $accesses->where('name', 'entry_year-CanAdd')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="entry_year-CanEdit" value="{{ $accesses->where('name', 'entry_year-CanEdit')->first()->id }}"> {{ $accesses->where('name', 'entry_year-CanEdit')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="entry_year-CanDelete" value="{{ $accesses->where('name', 'entry_year-CanDelete')->first()->id }}"> {{ $accesses->where('name', 'entry_year-CanDelete')->first()->description }}</label></li>
                                </ul>
                              </li>
                              <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Semester Berlaku</label>
                                <ul>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="term_year-CanView" value="{{ $accesses->where('name', 'term_year-CanView')->first()->id }}"> {{ $accesses->where('name', 'term_year-CanView')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="term_year-CanAdd" value="{{ $accesses->where('name', 'term_year-CanAdd')->first()->id }}"> {{ $accesses->where('name', 'term_year-CanAdd')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="term_year-CanEdit" value="{{ $accesses->where('name', 'term_year-CanEdit')->first()->id }}"> {{ $accesses->where('name', 'term_year-CanEdit')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="term_year-CanDelete" value="{{ $accesses->where('name', 'term_year-CanDelete')->first()->id }}"> {{ $accesses->where('name', 'term_year-CanDelete')->first()->description }}</label></li>
                                </ul>
                              </li>
                              <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Group Sesi Jadwal</label>
                                <ul>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="sched_session_group-CanView" value="{{ $accesses->where('name', 'sched_session_group-CanView')->first()->id }}"> {{ $accesses->where('name', 'sched_session_group-CanView')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="sched_session_group-CanAdd" value="{{ $accesses->where('name', 'sched_session_group-CanAdd')->first()->id }}"> {{ $accesses->where('name', 'sched_session_group-CanAdd')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="sched_session_group-CanEdit" value="{{ $accesses->where('name', 'sched_session_group-CanEdit')->first()->id }}"> {{ $accesses->where('name', 'sched_session_group-CanEdit')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="sched_session_group-CanDelete" value="{{ $accesses->where('name', 'sched_session_group-CanDelete')->first()->id }}"> {{ $accesses->where('name', 'sched_session_group-CanDelete')->first()->description }}</label></li>
                                </ul>
                              </li>
                              <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Pegawai / Dosen</label>
                                <ul>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="employee-CanView" value="{{ $accesses->where('name', 'employee-CanView')->first()->id }}"> {{ $accesses->where('name', 'employee-CanView')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="employee-CanView" value="{{ $accesses->where('name', 'employee-CanAdd')->first()->id }}"> {{ $accesses->where('name', 'employee-CanAdd')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="employee-CanView" value="{{ $accesses->where('name', 'employee-CanEdit')->first()->id }}"> {{ $accesses->where('name', 'employee-CanEdit')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="employee-CanView" value="{{ $accesses->where('name', 'employee-CanDelete')->first()->id }}"> {{ $accesses->where('name', 'employee-CanDelete')->first()->description }}</label></li>
                                </ul>
                              </li>
                              <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Strata Pendidikan</label>
                                <ul>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="education_program_type-CanView" value="{{ $accesses->where('name', 'education_program_type-CanView')->first()->id }}"> {{ $accesses->where('name', 'education_program_type-CanView')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="education_program_type-CanAdd" value="{{ $accesses->where('name', 'education_program_type-CanAdd')->first()->id }}"> {{ $accesses->where('name', 'education_program_type-CanAdd')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="education_program_type-CanEdit" value="{{ $accesses->where('name', 'education_program_type-CanEdit')->first()->id }}"> {{ $accesses->where('name', 'education_program_type-CanEdit')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="education_program_type-CanDelete" value="{{ $accesses->where('name', 'education_program_type-CanDelete')->first()->id }}"> {{ $accesses->where('name', 'education_program_type-CanDelete')->first()->description }}</label></li>
                                </ul>
                              </li>
                              <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Nilai Huruf</label>
                                <ul>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="grade_letter-CanView" value="{{ $accesses->where('name', 'grade_letter-CanView')->first()->id }}"> {{ $accesses->where('name', 'grade_letter-CanView')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="grade_letter-CanAdd" value="{{ $accesses->where('name', 'grade_letter-CanAdd')->first()->id }}"> {{ $accesses->where('name', 'grade_letter-CanAdd')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="grade_letter-CanEdit" value="{{ $accesses->where('name', 'grade_letter-CanEdit')->first()->id }}"> {{ $accesses->where('name', 'grade_letter-CanEdit')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="grade_letter-CanDelete" value="{{ $accesses->where('name', 'grade_letter-CanDelete')->first()->id }}"> {{ $accesses->where('name', 'grade_letter-CanDelete')->first()->description }}</label></li>
                                </ul>
                              </li>
                              <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Jenis Matakuliah</label>
                                <ul>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="course_type-CanView" value="{{ $accesses->where('name', 'course_type-CanView')->first()->id }}"> {{ $accesses->where('name', 'course_type-CanView')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="course_type-CanAdd" value="{{ $accesses->where('name', 'course_type-CanAdd')->first()->id }}"> {{ $accesses->where('name', 'course_type-CanAdd')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="course_type-CanEdit" value="{{ $accesses->where('name', 'course_type-CanEdit')->first()->id }}"> {{ $accesses->where('name', 'course_type-CanEdit')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="course_type-CanDelete" value="{{ $accesses->where('name', 'course_type-CanDelete')->first()->id }}"> {{ $accesses->where('name', 'course_type-CanDelete')->first()->description }}</label></li>
                                </ul>
                              </li>
                              <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Kelompok Matakuliah</label>
                                <ul>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="course_group-CanView" value="{{ $accesses->where('name', 'course_group-CanView')->first()->id }}"> {{ $accesses->where('name', 'course_group-CanView')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="course_group-CanAdd" value="{{ $accesses->where('name', 'course_group-CanAdd')->first()->id }}"> {{ $accesses->where('name', 'course_group-CanAdd')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="course_group-CanEdit" value="{{ $accesses->where('name', 'course_group-CanEdit')->first()->id }}"> {{ $accesses->where('name', 'course_group-CanEdit')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="course_group-CanDelete" value="{{ $accesses->where('name', 'course_group-CanDelete')->first()->id }}"> {{ $accesses->where('name', 'course_group-CanDelete')->first()->description }}</label></li>
                                </ul>
                              </li>
                              <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Predikat Lulus</label>
                                <ul>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="graduate_predicate-CanView" value="{{ $accesses->where('name', 'graduate_predicate-CanView')->first()->id }}"> {{ $accesses->where('name', 'graduate_predicate-CanView')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="graduate_predicate-CanAdd" value="{{ $accesses->where('name', 'graduate_predicate-CanAdd')->first()->id }}"> {{ $accesses->where('name', 'graduate_predicate-CanAdd')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="graduate_predicate-CanEdit" value="{{ $accesses->where('name', 'graduate_predicate-CanEdit')->first()->id }}"> {{ $accesses->where('name', 'graduate_predicate-CanEdit')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="graduate_predicate-CanDelete" value="{{ $accesses->where('name', 'graduate_predicate-CanDelete')->first()->id }}"> {{ $accesses->where('name', 'graduate_predicate-CanDelete')->first()->description }}</label></li>
                                </ul>
                              </li>
                            </ul>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1-1" data-id="custom-1-1" type="checkbox"> Biodata</label>
                            <ul>
                              <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Agama</label>
                                <ul>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="religion-CanView" value="{{ $accesses->where('name', 'religion-CanView')->first()->id }}"> {{ $accesses->where('name', 'religion-CanView')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="religion-CanAdd" value="{{ $accesses->where('name', 'religion-CanAdd')->first()->id }}"> {{ $accesses->where('name', 'religion-CanAdd')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="religion-CanEdit" value="{{ $accesses->where('name', 'religion-CanEdit')->first()->id }}"> {{ $accesses->where('name', 'religion-CanEdit')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="religion-CanDelete" value="{{ $accesses->where('name', 'religion-CanDelete')->first()->id }}"> {{ $accesses->where('name', 'religion-CanDelete')->first()->description }}</label></li>
                                </ul>
                              </li>
                              <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Kewarganegaraan</label>
                                <ul>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="citizenship-CanView" value="{{ $accesses->where('name', 'citizenship-CanView')->first()->id }}"> {{ $accesses->where('name', 'citizenship-CanView')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="citizenship-CanAdd" value="{{ $accesses->where('name', 'citizenship-CanAdd')->first()->id }}"> {{ $accesses->where('name', 'citizenship-CanAdd')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="citizenship-CanEdit" value="{{ $accesses->where('name', 'citizenship-CanEdit')->first()->id }}"> {{ $accesses->where('name', 'citizenship-CanEdit')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="citizenship-CanDelete" value="{{ $accesses->where('name', 'citizenship-CanDelete')->first()->id }}"> {{ $accesses->where('name', 'citizenship-CanDelete')->first()->description }}</label></li>
                                </ul>
                              </li>
                              <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Golongan Darah</label>
                                <ul>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="blood_type-CanView" value="{{ $accesses->where('name', 'blood_type-CanView')->first()->id }}"> {{ $accesses->where('name', 'blood_type-CanView')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="blood_type-CanAdd" value="{{ $accesses->where('name', 'blood_type-CanAdd')->first()->id }}"> {{ $accesses->where('name', 'blood_type-CanAdd')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="blood_type-CanEdit" value="{{ $accesses->where('name', 'blood_type-CanEdit')->first()->id }}"> {{ $accesses->where('name', 'blood_type-CanEdit')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="blood_type-CanDelete" value="{{ $accesses->where('name', 'blood_type-CanDelete')->first()->id }}"> {{ $accesses->where('name', 'blood_type-CanDelete')->first()->description }}</label></li>
                                </ul>
                              </li>
                              <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Status Dafter Mahasiswa</label>
                                <ul>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="register_status-CanView" value="{{ $accesses->where('name', 'register_status-CanView')->first()->id }}"> {{ $accesses->where('name', 'register_status-CanView')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="register_status-CanAdd" value="{{ $accesses->where('name', 'register_status-CanAdd')->first()->id }}"> {{ $accesses->where('name', 'register_status-CanAdd')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="register_status-CanEdit" value="{{ $accesses->where('name', 'register_status-CanEdit')->first()->id }}"> {{ $accesses->where('name', 'register_status-CanEdit')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="register_status-CanDelete" value="{{ $accesses->where('name', 'register_status-CanDelete')->first()->id }}"> {{ $accesses->where('name', 'register_status-CanDelete')->first()->description }}</label></li>
                                </ul>
                              </li>
                              <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Jurusan di SMA</label>
                                <ul>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="high_school_major-CanView" value="{{ $accesses->where('name', 'high_school_major-CanView')->first()->id }}"> {{ $accesses->where('name', 'high_school_major-CanView')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="high_school_major-CanAdd" value="{{ $accesses->where('name', 'high_school_major-CanAdd')->first()->id }}"> {{ $accesses->where('name', 'high_school_major-CanAdd')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="high_school_major-CanEdit" value="{{ $accesses->where('name', 'high_school_major-CanEdit')->first()->id }}"> {{ $accesses->where('name', 'high_school_major-CanEdit')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="high_school_major-CanDelete" value="{{ $accesses->where('name', 'high_school_major-CanDelete')->first()->id }}"> {{ $accesses->where('name', 'high_school_major-CanDelete')->first()->description }}</label></li>
                                </ul>
                              </li>
                            </ul>
                          </li>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1-1" data-id="custom-1-1" type="checkbox"> Organisasi</label>
                            <ul>
                              <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Gedung</label>
                                <ul>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="building-CanView" value="{{ $accesses->where('name', 'building-CanView')->first()->id }}"> {{ $accesses->where('name', 'building-CanView')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="building-CanAdd" value="{{ $accesses->where('name', 'building-CanAdd')->first()->id }}"> {{ $accesses->where('name', 'building-CanAdd')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="building-CanEdit" value="{{ $accesses->where('name', 'building-CanEdit')->first()->id }}"> {{ $accesses->where('name', 'building-CanEdit')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="building-CanDelete" value="{{ $accesses->where('name', 'building-CanDelete')->first()->id }}"> {{ $accesses->where('name', 'building-CanDelete')->first()->description }}</label></li>
                                </ul>
                              </li>
                              <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Ruang</label>
                                <ul>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="room-CanView" value="{{ $accesses->where('name', 'room-CanView')->first()->id }}"> {{ $accesses->where('name', 'room-CanView')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="room-CanAdd" value="{{ $accesses->where('name', 'room-CanAdd')->first()->id }}"> {{ $accesses->where('name', 'room-CanAdd')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="room-CanEdit" value="{{ $accesses->where('name', 'room-CanEdit')->first()->id }}"> {{ $accesses->where('name', 'room-CanEdit')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="room-CanDelete" value="{{ $accesses->where('name', 'room-CanDelete')->first()->id }}"> {{ $accesses->where('name', 'room-CanDelete')->first()->description }}</label></li>
                                </ul>
                              </li>
                              <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Jabatan Struktural</label>
                                <ul>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="functional_position_term_year-CanView" value="{{ $accesses->where('name', 'functional_position_term_year-CanView')->first()->id }}"> {{ $accesses->where('name', 'functional_position_term_year-CanView')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="functional_position_term_year-CanAdd" value="{{ $accesses->where('name', 'functional_position_term_year-CanAdd')->first()->id }}"> {{ $accesses->where('name', 'functional_position_term_year-CanAdd')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="functional_position_term_year-CanEdit" value="{{ $accesses->where('name', 'functional_position_term_year-CanEdit')->first()->id }}"> {{ $accesses->where('name', 'functional_position_term_year-CanEdit')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="functional_position_term_year-CanDelete" value="{{ $accesses->where('name', 'functional_position_term_year-CanDelete')->first()->id }}"> {{ $accesses->where('name', 'functional_position_term_year-CanDelete')->first()->description }}</label></li>
                                </ul>
                              </li>
                            </ul>
                          </li>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1-1" data-id="custom-1-1" type="checkbox"> Kurikulum</label>
                            <ul>
                              <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Jenis Kurikulum</label>
                                <ul>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="curriculum_type-CanView" value="{{ $accesses->where('name', 'curriculum_type-CanView')->first()->id }}"> {{ $accesses->where('name', 'curriculum_type-CanView')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="curriculum_type-CanAdd" value="{{ $accesses->where('name', 'curriculum_type-CanAdd')->first()->id }}"> {{ $accesses->where('name', 'curriculum_type-CanAdd')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="curriculum_type-CanEdit" value="{{ $accesses->where('name', 'curriculum_type-CanEdit')->first()->id }}"> {{ $accesses->where('name', 'curriculum_type-CanEdit')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="curriculum_type-CanDelete" value="{{ $accesses->where('name', 'curriculum_type-CanDelete')->first()->id }}"> {{ $accesses->where('name', 'curriculum_type-CanDelete')->first()->description }}</label></li>
                                </ul>
                              </li>
                              <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Kurikulum</label>
                                <ul>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="curriculum-CanView" value="{{ $accesses->where('name', 'curriculum-CanView')->first()->id }}"> {{ $accesses->where('name', 'curriculum-CanView')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="curriculum-CanAdd" value="{{ $accesses->where('name', 'curriculum-CanAdd')->first()->id }}"> {{ $accesses->where('name', 'curriculum-CanAdd')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="curriculum-CanEdit" value="{{ $accesses->where('name', 'curriculum-CanEdit')->first()->id }}"> {{ $accesses->where('name', 'curriculum-CanEdit')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="curriculum-CanDelete" value="{{ $accesses->where('name', 'curriculum-CanDelete')->first()->id }}"> {{ $accesses->where('name', 'curriculum-CanDelete')->first()->description }}</label></li>
                                </ul>
                              </li>
                            </ul>
                          </li>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1-1" data-id="custom-1-1" type="checkbox"> Lokasi</label>
                            <ul>
                              <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Negara</label>
                                <ul>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="country-CanView" value="{{ $accesses->where('name', 'country-CanView')->first()->id }}"> {{ $accesses->where('name', 'country-CanView')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="country-CanAdd" value="{{ $accesses->where('name', 'country-CanAdd')->first()->id }}"> {{ $accesses->where('name', 'country-CanAdd')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="country-CanEdit" value="{{ $accesses->where('name', 'country-CanEdit')->first()->id }}"> {{ $accesses->where('name', 'country-CanEdit')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="country-CanDelete" value="{{ $accesses->where('name', 'country-CanDelete')->first()->id }}"> {{ $accesses->where('name', 'country-CanDelete')->first()->description }}</label></li>
                                </ul>
                              </li>
                              <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Provinsi</label>
                                <ul>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="province-CanView" value="{{ $accesses->where('name', 'province-CanView')->first()->id }}"> {{ $accesses->where('name', 'province-CanView')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="province-CanAdd" value="{{ $accesses->where('name', 'province-CanAdd')->first()->id }}"> {{ $accesses->where('name', 'province-CanAdd')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="province-CanEdit" value="{{ $accesses->where('name', 'province-CanEdit')->first()->id }}"> {{ $accesses->where('name', 'province-CanEdit')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="province-CanDelete" value="{{ $accesses->where('name', 'province-CanDelete')->first()->id }}"> {{ $accesses->where('name', 'province-CanDelete')->first()->description }}</label></li>
                                </ul>
                              </li>
                              <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Kota</label>
                                <ul>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="city-CanView" value="{{ $accesses->where('name', 'city-CanView')->first()->id }}"> {{ $accesses->where('name', 'city-CanView')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="city-CanAdd" value="{{ $accesses->where('name', 'city-CanAdd')->first()->id }}"> {{ $accesses->where('name', 'city-CanAdd')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="city-CanEdit" value="{{ $accesses->where('name', 'city-CanEdit')->first()->id }}"> {{ $accesses->where('name', 'city-CanEdit')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" id="city-CanDelete" value="{{ $accesses->where('name', 'city-CanDelete')->first()->id }}"> {{ $accesses->where('name', 'city-CanDelete')->first()->description }}</label></li>
                                </ul>
                              </li>
                            </ul>
                          </li>
                        </li>
                      </ul>
                      <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Parameter</label>
                        <ul>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Kurikulum Prodi</label>
                            <ul>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="curriculum_applied-CanView" value="{{ $accesses->where('name', 'curriculum_applied-CanView')->first()->id }}"> {{ $accesses->where('name', 'curriculum_applied-CanView')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="curriculum_applied-CanAdd" value="{{ $accesses->where('name', 'curriculum_applied-CanAdd')->first()->id }}"> {{ $accesses->where('name', 'curriculum_applied-CanAdd')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="curriculum_applied-CanEdit" value="{{ $accesses->where('name', 'curriculum_applied-CanEdit')->first()->id }}"> {{ $accesses->where('name', 'curriculum_applied-CanEdit')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="curriculum_applied-CanDelete" value="{{ $accesses->where('name', 'curriculum_applied-CanDelete')->first()->id }}"> {{ $accesses->where('name', 'curriculum_applied-CanDelete')->first()->description }}</label></li>
                            </ul>
                          </li>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Kurikulum Angkatan</label>
                            <ul>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="curriculum_entry_year-CanView" value="{{ $accesses->where('name', 'curriculum_entry_year-CanView')->first()->id }}"> {{ $accesses->where('name', 'curriculum_entry_year-CanView')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="curriculum_entry_year-CanAdd" value="{{ $accesses->where('name', 'curriculum_entry_year-CanAdd')->first()->id }}"> {{ $accesses->where('name', 'curriculum_entry_year-CanAdd')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="curriculum_entry_year-CanEdit" value="{{ $accesses->where('name', 'curriculum_entry_year-CanEdit')->first()->id }}"> {{ $accesses->where('name', 'curriculum_entry_year-CanEdit')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="curriculum_entry_year-CanDelete" value="{{ $accesses->where('name', 'curriculum_entry_year-CanDelete')->first()->id }}"> {{ $accesses->where('name', 'curriculum_entry_year-CanDelete')->first()->description }}</label></li>
                            </ul>
                          </li>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Matakuliah</label>
                            <ul>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="course-CanView" value="{{ $accesses->where('name', 'course-CanView')->first()->id }}"> {{ $accesses->where('name', 'course-CanView')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="course-CanAdd" value="{{ $accesses->where('name', 'course-CanAdd')->first()->id }}"> {{ $accesses->where('name', 'course-CanAdd')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="course-CanEdit" value="{{ $accesses->where('name', 'course-CanEdit')->first()->id }}"> {{ $accesses->where('name', 'course-CanEdit')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="course-CanDelete" value="{{ $accesses->where('name', 'course-CanDelete')->first()->id }}"> {{ $accesses->where('name', 'course-CanDelete')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="course-CanExport" value="{{ $accesses->where('name', 'course-CanExport')->first()->id }}"> {{ $accesses->where('name', 'course-CanExport')->first()->description }}</label></li>
                            </ul>
                          </li>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Matakuliah dan Kurikulum</label>
                            <ul>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="course_curriculum-CanView" value="{{ $accesses->where('name', 'course_curriculum-CanView')->first()->id }}"> {{ $accesses->where('name', 'course_curriculum-CanView')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="course_curriculum-CanAdd" value="{{ $accesses->where('name', 'course_curriculum-CanAdd')->first()->id }}"> {{ $accesses->where('name', 'course_curriculum-CanAdd')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="course_curriculum-CanEdit" value="{{ $accesses->where('name', 'course_curriculum-CanEdit')->first()->id }}"> {{ $accesses->where('name', 'course_curriculum-CanEdit')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="course_curriculum-CanDelete" value="{{ $accesses->where('name', 'course_curriculum-CanDelete')->first()->id }}"> {{ $accesses->where('name', 'course_curriculum-CanDelete')->first()->description }}</label></li>
                            </ul>
                          </li>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Matakuliah setara</label>
                            <ul>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="course_identic-CanView" value="{{ $accesses->where('name', 'course_identic-CanView')->first()->id }}"> {{ $accesses->where('name', 'course_identic-CanView')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="course_identic-CanAdd" value="{{ $accesses->where('name', 'course_identic-CanAdd')->first()->id }}"> {{ $accesses->where('name', 'course_identic-CanAdd')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="course_identic-CanEdit" value="{{ $accesses->where('name', 'course_identic-CanEdit')->first()->id }}"> {{ $accesses->where('name', 'course_identic-CanEdit')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="course_identic-CanDelete" value="{{ $accesses->where('name', 'course_identic-CanDelete')->first()->id }}"> {{ $accesses->where('name', 'course_identic-CanDelete')->first()->description }}</label></li>
                            </ul>
                          </li>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Grade Nilai</label>
                            <ul>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="grade_department-CanView" value="{{ $accesses->where('name', 'grade_department-CanView')->first()->id }}"> {{ $accesses->where('name', 'grade_department-CanView')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="grade_department-CanAdd" value="{{ $accesses->where('name', 'grade_department-CanAdd')->first()->id }}"> {{ $accesses->where('name', 'grade_department-CanAdd')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="grade_department-CanEdit" value="{{ $accesses->where('name', 'grade_department-CanEdit')->first()->id }}"> {{ $accesses->where('name', 'grade_department-CanEdit')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="grade_department-CanDelete" value="{{ $accesses->where('name', 'grade_department-CanDelete')->first()->id }}"> {{ $accesses->where('name', 'grade_department-CanDelete')->first()->description }}</label></li>
                            </ul>
                          </li>
                        </ul>
                      </li>
                      <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Setting</label>
                        <ul>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Data Mahasiswa</label>
                            <ul>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="student-CanView" value="{{ $accesses->where('name', 'student-CanView')->first()->id }}"> {{ $accesses->where('name', 'student-CanView')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="student-CanAdd" value="{{ $accesses->where('name', 'student-CanAdd')->first()->id }}"> {{ $accesses->where('name', 'student-CanAdd')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="student-CanEdit" value="{{ $accesses->where('name', 'student-CanEdit')->first()->id }}"> {{ $accesses->where('name', 'student-CanEdit')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="student-CanDelete" value="{{ $accesses->where('name', 'student-CanDelete')->first()->id }}"> {{ $accesses->where('name', 'student-CanDelete')->first()->description }}</label></li>
                            </ul>
                          </li>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Bimbingan Mahasiswa</label>
                            <ul>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="student_supervision-CanView" value="{{ $accesses->where('name', 'student_supervision-CanView')->first()->id }}"> {{ $accesses->where('name', 'student_supervision-CanView')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="student_supervision-CanAdd" value="{{ $accesses->where('name', 'student_supervision-CanAdd')->first()->id }}"> {{ $accesses->where('name', 'student_supervision-CanAdd')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="student_supervision-CanEdit" value="{{ $accesses->where('name', 'student_supervision-CanEdit')->first()->id }}"> {{ $accesses->where('name', 'student_supervision-CanEdit')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="student_supervision-CanDelete" value="{{ $accesses->where('name', 'student_supervision-CanDelete')->first()->id }}"> {{ $accesses->where('name', 'student_supervision-CanDelete')->first()->description }}</label></li>
                            </ul>
                          </li>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Password Mahasiswa</label>
                            <ul>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="student_password-CanView" value="{{ $accesses->where('name', 'student_password-CanView')->first()->id }}"> {{ $accesses->where('name', 'student_password-CanView')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="student_password-CanEdit" value="{{ $accesses->where('name', 'student_password-CanEdit')->first()->id }}"> {{ $accesses->where('name', 'student_password-CanEdit')->first()->description }}</label></li>
                            </ul>
                          </li>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Jadwal Pengisian</label>
                            <ul>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="event_sched-CanView" value="{{ $accesses->where('name', 'event_sched-CanView')->first()->id }}"> {{ $accesses->where('name', 'event_sched-CanView')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="event_sched-CanAdd" value="{{ $accesses->where('name', 'event_sched-CanAdd')->first()->id }}"> {{ $accesses->where('name', 'event_sched-CanAdd')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="event_sched-CanEdit" value="{{ $accesses->where('name', 'event_sched-CanEdit')->first()->id }}"> {{ $accesses->where('name', 'event_sched-CanEdit')->first()->description }}</label></li>
                            </ul>
                          </li>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Dosen Prodi</label>
                            <ul>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="department_lecturer-CanView" value="{{ $accesses->where('name', 'department_lecturer-CanView')->first()->id }}"> {{ $accesses->where('name', 'department_lecturer-CanView')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="department_lecturer-CanAdd" value="{{ $accesses->where('name', 'department_lecturer-CanAdd')->first()->id }}"> {{ $accesses->where('name', 'department_lecturer-CanAdd')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="department_lecturer-CanDelete" value="{{ $accesses->where('name', 'department_lecturer-CanDelete')->first()->id }}"> {{ $accesses->where('name', 'department_lecturer-CanDelete')->first()->description }}</label></li>
                            </ul>
                          </li>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Matakuliah Ditawarkan</label>
                            <ul>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="offered_course-CanView" value="{{ $accesses->where('name', 'offered_course-CanView')->first()->id }}"> {{ $accesses->where('name', 'offered_course-CanView')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="offered_course-CanAdd" value="{{ $accesses->where('name', 'offered_course-CanAdd')->first()->id }}"> {{ $accesses->where('name', 'offered_course-CanAdd')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="offered_course-CanEditCapacity" value="{{ $accesses->where('name', 'offered_course-CanEditCapacity')->first()->id }}"> {{ $accesses->where('name', 'offered_course-CanEditCapacity')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="offered_course-CanEditEmployee" value="{{ $accesses->where('name', 'offered_course-CanEditEmployee')->first()->id }}"> {{ $accesses->where('name', 'offered_course-CanEditEmployee')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="offered_course-CanDelete" value="{{ $accesses->where('name', 'offered_course-CanDelete')->first()->id }}"> {{ $accesses->where('name', 'offered_course-CanDelete')->first()->description }}</label></li>
                            </ul>
                          </li>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Sks Diijinkan</label>
                            <ul>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="allowed_sks-CanView" value="{{ $accesses->where('name', 'allowed_sks-CanView')->first()->id }}"> {{ $accesses->where('name', 'allowed_sks-CanView')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="allowed_sks-CanAdd" value="{{ $accesses->where('name', 'allowed_sks-CanAdd')->first()->id }}"> {{ $accesses->where('name', 'allowed_sks-CanAdd')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="allowed_sks-CanEdit" value="{{ $accesses->where('name', 'allowed_sks-CanEdit')->first()->id }}"> {{ $accesses->where('name', 'allowed_sks-CanEdit')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="allowed_sks-CanDelete" value="{{ $accesses->where('name', 'allowed_sks-CanDelete')->first()->id }}"> {{ $accesses->where('name', 'allowed_sks-CanDelete')->first()->description }}</label></li>
                            </ul>
                          </li>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Sesi Jadwal Kuliah</label>
                            <ul>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="sched_session-CanView" value="{{ $accesses->where('name', 'sched_session-CanView')->first()->id }}"> {{ $accesses->where('name', 'sched_session-CanView')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="sched_session-CanAdd" value="{{ $accesses->where('name', 'sched_session-CanAdd')->first()->id }}"> {{ $accesses->where('name', 'sched_session-CanAdd')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="sched_session-CanEdit" value="{{ $accesses->where('name', 'sched_session-CanEdit')->first()->id }}"> {{ $accesses->where('name', 'sched_session-CanEdit')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="sched_session-CanDelete" value="{{ $accesses->where('name', 'sched_session-CanDelete')->first()->id }}"> {{ $accesses->where('name', 'sched_session-CanDelete')->first()->description }}</label></li>
                            </ul>
                          </li>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Entry Jadwal Kuliah</label>
                            <ul>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="offered_course_sched-CanView" value="{{ $accesses->where('name', 'offered_course_sched-CanView')->first()->id }}"> {{ $accesses->where('name', 'offered_course_sched-CanView')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="offered_course_sched-CanEdit" value="{{ $accesses->where('name', 'offered_course_sched-CanEdit')->first()->id }}"> {{ $accesses->where('name', 'offered_course_sched-CanEdit')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="offered_course_sched-CanDelete" value="{{ $accesses->where('name', 'offered_course_sched-CanDelete')->first()->id }}"> {{ $accesses->where('name', 'offered_course_sched-CanDelete')->first()->description }}</label></li>
                            </ul>
                          </li>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Jadwal dan Peserta Ujian</label>
                            <ul>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="offered_course_exam-CanView" value="{{ $accesses->where('name', 'offered_course_exam-CanView')->first()->id }}"> {{ $accesses->where('name', 'offered_course_exam-CanView')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="offered_course_exam-CanViewPeserta" value="{{ $accesses->where('name', 'offered_course_exam-CanViewPeserta')->first()->id }}"> {{ $accesses->where('name', 'offered_course_exam-CanViewPeserta')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="offered_course_exam-CanAdd" value="{{ $accesses->where('name', 'offered_course_exam-CanAdd')->first()->id }}"> {{ $accesses->where('name', 'offered_course_exam-CanAdd')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="offered_course_exam-CanAddPeserta" value="{{ $accesses->where('name', 'offered_course_exam-CanAddPeserta')->first()->id }}"> {{ $accesses->where('name', 'offered_course_exam-CanAddPeserta')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="offered_course_exam-CanEdit" value="{{ $accesses->where('name', 'offered_course_exam-CanEdit')->first()->id }}"> {{ $accesses->where('name', 'offered_course_exam-CanEdit')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="offered_course_exam-CanDelete" value="{{ $accesses->where('name', 'offered_course_exam-CanDelete')->first()->id }}"> {{ $accesses->where('name', 'offered_course_exam-CanDelete')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="offered_course_exam-CanHapusPeserta" value="{{ $accesses->where('name', 'offered_course_exam-CanHapusPeserta')->first()->id }}"> {{ $accesses->where('name', 'offered_course_exam-CanHapusPeserta')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="offered_course_exam-CanExportPresensi" value="{{ $accesses->where('name', 'offered_course_exam-CanExportPresensi')->first()->id }}"> {{ $accesses->where('name', 'offered_course_exam-CanExportPresensi')->first()->description }}</label></li>
                            </ul>
                          </li>
                        </ul>
                      </li>
                      <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Proses</label>
                        <ul>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">KRS Per Kelas Matakuliah</label>
                            <ul>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="krs_matakuliah-CanView" value="{{ $accesses->where('name', 'krs_matakuliah-CanView')->first()->id }}"> {{ $accesses->where('name', 'krs_matakuliah-CanView')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="krs_matakuliah-CanAdd" value="{{ $accesses->where('name', 'krs_matakuliah-CanAdd')->first()->id }}"> {{ $accesses->where('name', 'krs_matakuliah-CanAdd')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="krs_matakuliah-CanViewDetail" value="{{ $accesses->where('name', 'krs_matakuliah-CanViewDetail')->first()->id }}"> {{ $accesses->where('name', 'krs_matakuliah-CanViewDetail')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="krs_matakuliah-CanDelete" value="{{ $accesses->where('name', 'krs_matakuliah-CanDelete')->first()->id }}"> {{ $accesses->where('name', 'krs_matakuliah-CanDelete')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="krs_matakuliah-CanExport" value="{{ $accesses->where('name', 'krs_matakuliah-CanExport')->first()->id }}"> {{ $accesses->where('name', 'krs_matakuliah-CanExport')->first()->description }}</label></li>
                            </ul>
                          </li>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">KRS Per Mahasiswa</label>
                            <ul>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="krs_mahasiswa-CanView" value="{{ $accesses->where('name', 'krs_mahasiswa-CanView')->first()->id }}"> {{ $accesses->where('name', 'krs_mahasiswa-CanView')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="krs_mahasiswa-CanAdd" value="{{ $accesses->where('name', 'krs_mahasiswa-CanAdd')->first()->id }}"> {{ $accesses->where('name', 'krs_mahasiswa-CanAdd')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="krs_mahasiswa-CanEdit" value="{{ $accesses->where('name', 'krs_mahasiswa-CanEdit')->first()->id }}"> {{ $accesses->where('name', 'krs_mahasiswa-CanEdit')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="krs_mahasiswa-CanDelete" value="{{ $accesses->where('name', 'krs_mahasiswa-CanDelete')->first()->id }}"> {{ $accesses->where('name', 'krs_mahasiswa-CanDelete')->first()->description }}</label></li>
                            </ul>
                          </li>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Nilai Per Kelas Matakuliah</label>
                            <ul>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="khs_matakuliah-CanView" value="{{ $accesses->where('name', 'khs_matakuliah-CanView')->first()->id }}"> {{ $accesses->where('name', 'khs_matakuliah-CanView')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="khs_matakuliah-CanViewDetail" value="{{ $accesses->where('name', 'khs_matakuliah-CanViewDetail')->first()->id }}"> {{ $accesses->where('name', 'khs_matakuliah-CanViewDetail')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="khs_matakuliah-CanEditDetail" value="{{ $accesses->where('name', 'khs_matakuliah-CanEditDetail')->first()->id }}"> {{ $accesses->where('name', 'khs_matakuliah-CanEditDetail')->first()->description }}</label></li>
                            </ul>
                          </li>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Nilai Per Mahasiswa</label>
                            <ul>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="khs_mahasiswa-CanView" value="{{ $accesses->where('name', 'khs_mahasiswa-CanView')->first()->id }}"> {{ $accesses->where('name', 'khs_mahasiswa-CanView')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="khs_mahasiswa-CanEdit" value="{{ $accesses->where('name', 'khs_mahasiswa-CanEdit')->first()->id }}"> {{ $accesses->where('name', 'khs_mahasiswa-CanEdit')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="khs_mahasiswa-CanExport" value="{{ $accesses->where('name', 'khs_mahasiswa-CanExport')->first()->id }}"> {{ $accesses->where('name', 'khs_mahasiswa-CanExport')->first()->description }}</label></li>
                            </ul>
                          </li>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Nilai Mahasiswa Ekuivalensi</label>
                            <ul>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="transcript_equivalensi-CanView" value="{{ $accesses->where('name', 'transcript_equivalensi-CanView')->first()->id }}"> {{ $accesses->where('name', 'transcript_equivalensi-CanView')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="transcript_equivalensi-CanAdd" value="{{ $accesses->where('name', 'transcript_equivalensi-CanAdd')->first()->id }}"> {{ $accesses->where('name', 'transcript_equivalensi-CanAdd')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="transcript_equivalensi-CanEdit" value="{{ $accesses->where('name', 'transcript_equivalensi-CanEdit')->first()->id }}"> {{ $accesses->where('name', 'transcript_equivalensi-CanEdit')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="transcript_equivalensi-CanDelete" value="{{ $accesses->where('name', 'transcript_equivalensi-CanDelete')->first()->id }}"> {{ $accesses->where('name', 'transcript_equivalensi-CanDelete')->first()->description }}</label></li>
                            </ul>
                          </li>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Tugas Akhir</label>
                            <ul>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="tugas_akhir-CanView" value="{{ $accesses->where('name', 'tugas_akhir-CanView')->first()->id }}"> {{ $accesses->where('name', 'tugas_akhir-CanView')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="tugas_akhir-CanAdd" value="{{ $accesses->where('name', 'tugas_akhir-CanAdd')->first()->id }}"> {{ $accesses->where('name', 'tugas_akhir-CanAdd')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="tugas_akhir-CanEdit" value="{{ $accesses->where('name', 'tugas_akhir-CanEdit')->first()->id }}"> {{ $accesses->where('name', 'tugas_akhir-CanEdit')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="tugas_akhir-CanDelete" value="{{ $accesses->where('name', 'tugas_akhir-CanDelete')->first()->id }}"> {{ $accesses->where('name', 'tugas_akhir-CanDelete')->first()->description }}</label></li>
                            </ul>
                          </li>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Yudisium</label>
                            <ul>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="yudisium-CanView" value="{{ $accesses->where('name', 'yudisium-CanView')->first()->id }}"> {{ $accesses->where('name', 'yudisium-CanView')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="create_yudisium-CanAdd" value="{{ $accesses->where('name', 'create_yudisium-CanAdd')->first()->id }}"> {{ $accesses->where('name', 'create_yudisium-CanAdd')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="yudisium-CanDelete" value="{{ $accesses->where('name', 'yudisium-CanDelete')->first()->id }}"> {{ $accesses->where('name', 'yudisium-CanDelete')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="yudisium-CanUpdateBeritaAcara" value="{{ $accesses->where('name', 'yudisium-CanUpdateBeritaAcara')->first()->id }}"> {{ $accesses->where('name', 'yudisium-CanUpdateBeritaAcara')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="yudisium-CanUpdateSKL" value="{{ $accesses->where('name', 'yudisium-CanUpdateSKL')->first()->id }}"> {{ $accesses->where('name', 'yudisium-CanUpdateSKL')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="yudisium-CanExport" value="{{ $accesses->where('name', 'yudisium-CanExport')->first()->id }}"> {{ $accesses->where('name', 'yudisium-CanExport')->first()->description }}</label></li>
                            </ul>
                          </li>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Wisuda</label>
                            <ul>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="wisuda-CanView" value="{{ $accesses->where('name', 'wisuda-CanView')->first()->id }}"> {{ $accesses->where('name', 'wisuda-CanView')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="wisuda-CanAdd" value="{{ $accesses->where('name', 'wisuda-CanAdd')->first()->id }}"> {{ $accesses->where('name', 'wisuda-CanAdd')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="wisuda-CanEdit" value="{{ $accesses->where('name', 'wisuda-CanEdit')->first()->id }}"> {{ $accesses->where('name', 'wisuda-CanEdit')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="wisuda-CanDelete" value="{{ $accesses->where('name', 'wisuda-CanDelete')->first()->id }}"> {{ $accesses->where('name', 'wisuda-CanDelete')->first()->description }}</label></li>
                            </ul>
                          </li>
                        </ul>
                      </li>
                      <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Cetak</label>
                        <ul>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Transkrip Sementara</label>
                            <ul>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="transcript_sementara-CanView" value="{{ $accesses->where('name', 'transcript_sementara-CanView')->first()->id }}"> {{ $accesses->where('name', 'transcript_sementara-CanView')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="transcript_sementara-CanExport" value="{{ $accesses->where('name', 'transcript_sementara-CanExport')->first()->id }}"> {{ $accesses->where('name', 'transcript_sementara-CanExport')->first()->description }}</label></li>
                            </ul>
                          </li>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Transkrip Akhir</label>
                            <ul>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="transcript_akhir-CanView" value="{{ $accesses->where('name', 'transcript_akhir-CanView')->first()->id }}"> {{ $accesses->where('name', 'transcript_akhir-CanView')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="transcript_akhir-CanExport" value="{{ $accesses->where('name', 'transcript_akhir-CanExport')->first()->id }}"> {{ $accesses->where('name', 'transcript_akhir-CanExport')->first()->description }}</label></li>
                            </ul>
                          </li>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Presensi Mahasiswa</label>
                            <ul>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="presensimhs-CanView" value="{{ $accesses->where('name', 'presensimhs-CanView')->first()->id }}"> {{ $accesses->where('name', 'presensimhs-CanView')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="presensimhs-CanExport" value="{{ $accesses->where('name', 'presensimhs-CanExport')->first()->id }}"> {{ $accesses->where('name', 'presensimhs-CanExport')->first()->description }}</label></li>
                            </ul>
                          </li>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Jadwal dan Peserta Ujian</label>
                            <ul>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="jadwaldanpesertaujian-CanView" value="{{ $accesses->where('name', 'jadwaldanpesertaujian-CanView')->first()->id }}"> {{ $accesses->where('name', 'jadwaldanpesertaujian-CanView')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="jadwaldanpesertaujian-CanExport" value="{{ $accesses->where('name', 'jadwaldanpesertaujian-CanExport')->first()->id }}"> {{ $accesses->where('name', 'jadwaldanpesertaujian-CanExport')->first()->description }}</label></li>
                            </ul>
                          </li>
                        </ul>
                      </li>
                      <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Laporan</label>
                        <ul>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Daftar Mahasiswa KRS</label>
                            <ul>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="laporan_daftar_mahasiswa_krs-CanView" value="{{ $accesses->where('name', 'laporan_daftar_mahasiswa_krs-CanView')->first()->id }}"> {{ $accesses->where('name', 'laporan_daftar_mahasiswa_krs-CanView')->first()->description }}</label></li>
                            </ul>
                          </li>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Histori Nilai Mahasiswa</label>
                            <ul>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="laporan_history_nilaimhs-CanView" value="{{ $accesses->where('name', 'laporan_history_nilaimhs-CanView')->first()->id }}"> {{ $accesses->where('name', 'laporan_history_nilaimhs-CanView')->first()->description }}</label></li>
                            </ul>
                          </li>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Resume Mahasiswa Aktif KRS</label>
                            <ul>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="laporan_mhskrs-CanView" value="{{ $accesses->where('name', 'laporan_mhskrs-CanView')->first()->id }}"> {{ $accesses->where('name', 'laporan_mhskrs-CanView')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="laporan_mhskrs-CanExport" value="{{ $accesses->where('name', 'laporan_mhskrs-CanExport')->first()->id }}"> {{ $accesses->where('name', 'laporan_mhskrs-CanExport')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="access[]" type="checkbox" id="showmhsnonaktif-CanViewnonaktif" value="{{ $accesses->where('name', 'showmhsnonaktif-CanViewnonaktif')->first()->id }}"> {{ $accesses->where('name', 'showmhsnonaktif-CanViewnonaktif')->first()->description }}</label></li>
                            </ul>
                          </li>
                        </ul>
                      </li>
                    </ul>
                  </li>


                  <li>
                    <i class="fa fa-plus"></i> <label> <input id="keuangan" data-id="custom-0" type="checkbox"> KEUANGAN</label>
                    <ul>
                      <li>
                        <i class="fa fa-plus"></i> <label> <input id="node-0" data-id="custom-0" type="checkbox"> Master</label>
                        <ul>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Item Biaya</label>
                            <ul>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Item Biaya-CanView')->first()->id }}" value="{{ $accesseskeu->where('name', 'Item Biaya-CanView')->first()->id }}"> {{ $accesseskeu->where('name', 'Item Biaya-CanView')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Item Biaya-CanAdd')->first()->id }}" value="{{ $accesseskeu->where('name', 'Item Biaya-CanAdd')->first()->id }}"> {{ $accesseskeu->where('name', 'Item Biaya-CanAdd')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Item Biaya-CanEdit')->first()->id }}" value="{{ $accesseskeu->where('name', 'Item Biaya-CanEdit')->first()->id }}"> {{ $accesseskeu->where('name', 'Item Biaya-CanEdit')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Item Biaya-CanDelete')->first()->id }}" value="{{ $accesseskeu->where('name', 'Item Biaya-CanDelete')->first()->id }}"> {{ $accesseskeu->where('name', 'Item Biaya-CanDelete')->first()->description }}</label></li>
                            </ul>
                          </li>
                        </ul>
                      </li>
                      <li>
                        <i class="fa fa-plus"></i> <label> <input id="node-0" data-id="custom-0" type="checkbox"> Biaya Mata Kuliah</label>
                        <ul>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Set Jenis Matakuliah</label>
                            <ul>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Set Jenis Mata Kuliah-CanView')->first()->id }}" value="{{ $accesseskeu->where('name', 'Set Jenis Mata Kuliah-CanView')->first()->id }}"> {{ $accesseskeu->where('name', 'Set Jenis Mata Kuliah-CanView')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Set Jenis Mata Kuliah-CanAdd')->first()->id }}" value="{{ $accesseskeu->where('name', 'Set Jenis Mata Kuliah-CanAdd')->first()->id }}"> {{ $accesseskeu->where('name', 'Set Jenis Mata Kuliah-CanAdd')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Set Jenis Mata Kuliah-CanDelete')->first()->id }}" value="{{ $accesseskeu->where('name', 'Set Jenis Mata Kuliah-CanDelete')->first()->id }}"> {{ $accesseskeu->where('name', 'Set Jenis Mata Kuliah-CanDelete')->first()->description }}</label></li>
                            </ul>
                          </li>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Biaya Per SKS</label>
                            <ul>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Biaya Per Sks-CanView')->first()->id }}" value="{{ $accesseskeu->where('name', 'Biaya Per Sks-CanView')->first()->id }}"> {{ $accesseskeu->where('name', 'Biaya Per Sks-CanView')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Biaya Per Sks-CanAdd')->first()->id }}" value="{{ $accesseskeu->where('name', 'Biaya Per Sks-CanAdd')->first()->id }}"> {{ $accesseskeu->where('name', 'Biaya Per Sks-CanAdd')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Biaya Per Sks-CanEdit')->first()->id }}" value="{{ $accesseskeu->where('name', 'Biaya Per Sks-CanEdit')->first()->id }}"> {{ $accesseskeu->where('name', 'Biaya Per Sks-CanEdit')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Biaya Per Sks-CanDelete')->first()->id }}" value="{{ $accesseskeu->where('name', 'Biaya Per Sks-CanDelete')->first()->id }}"> {{ $accesseskeu->where('name', 'Biaya Per Sks-CanDelete')->first()->description }}</label></li>
                            </ul>
                          </li>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Biaya Per Paket</label>
                            <ul>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Biaya Per Paket-CanView')->first()->id }}" value="{{ $accesseskeu->where('name', 'Biaya Per Paket-CanView')->first()->id }}"> {{ $accesseskeu->where('name', 'Biaya Per Paket-CanView')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Biaya Per Paket-CanAdd')->first()->id }}" value="{{ $accesseskeu->where('name', 'Biaya Per Paket-CanAdd')->first()->id }}"> {{ $accesseskeu->where('name', 'Biaya Per Paket-CanAdd')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Biaya Per Paket-CanEdit')->first()->id }}" value="{{ $accesseskeu->where('name', 'Biaya Per Paket-CanEdit')->first()->id }}"> {{ $accesseskeu->where('name', 'Biaya Per Paket-CanEdit')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Biaya Per Paket-CanDelete')->first()->id }}" value="{{ $accesseskeu->where('name', 'Biaya Per Paket-CanDelete')->first()->id }}"> {{ $accesseskeu->where('name', 'Biaya Per Paket-CanDelete')->first()->description }}</label></li>
                            </ul>
                          </li>
                        </ul>
                      </li>
                      <li>
                        <i class="fa fa-plus"></i> <label> <input id="node-0" data-id="custom-0" type="checkbox"> Biaya Registrasi</label>
                        <ul>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Set Biaya Registrasi</label>
                            <ul>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Set Biaya Registrasi-CanView')->first()->id }}" value="{{ $accesseskeu->where('name', 'Set Biaya Registrasi-CanView')->first()->id }}"> {{ $accesseskeu->where('name', 'Set Biaya Registrasi-CanView')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Set Biaya Registrasi-CanAdd')->first()->id }}" value="{{ $accesseskeu->where('name', 'Set Biaya Registrasi-CanAdd')->first()->id }}"> {{ $accesseskeu->where('name', 'Set Biaya Registrasi-CanAdd')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Set Biaya Registrasi-CanEdit')->first()->id }}" value="{{ $accesseskeu->where('name', 'Set Biaya Registrasi-CanEdit')->first()->id }}"> {{ $accesseskeu->where('name', 'Set Biaya Registrasi-CanEdit')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Set Biaya Registrasi-CanDelete')->first()->id }}" value="{{ $accesseskeu->where('name', 'Set Biaya Registrasi-CanDelete')->first()->id }}"> {{ $accesseskeu->where('name', 'Set Biaya Registrasi-CanDelete')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Set Biaya Registrasi-CanHitungTagihan')->first()->id }}" value="{{ $accesseskeu->where('name', 'Set Biaya Registrasi-CanHitungTagihan')->first()->id }}"> {{ $accesseskeu->where('name', 'Set Biaya Registrasi-CanHitungTagihan')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Set Biaya Registrasi-CanCopyData')->first()->id }}" value="{{ $accesseskeu->where('name', 'Set Biaya Registrasi-CanCopyData')->first()->id }}"> {{ $accesseskeu->where('name', 'Set Biaya Registrasi-CanCopyData')->first()->description }}</label></li>
                            </ul>
                          </li>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Set Biaya Registrasi(Resume)</label>
                            <ul>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Set Biaya Registrasi Resume-CanView')->first()->id }}" value="{{ $accesseskeu->where('name', 'Set Biaya Registrasi Resume-CanView')->first()->id }}"> {{ $accesseskeu->where('name', 'Set Biaya Registrasi Resume-CanView')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Set Biaya Registrasi Resume-CanAdd')->first()->id }}" value="{{ $accesseskeu->where('name', 'Set Biaya Registrasi Resume-CanAdd')->first()->id }}"> {{ $accesseskeu->where('name', 'Set Biaya Registrasi Resume-CanAdd')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Set Biaya Registrasi Resume-CanEdit')->first()->id }}" value="{{ $accesseskeu->where('name', 'Set Biaya Registrasi Resume-CanEdit')->first()->id }}"> {{ $accesseskeu->where('name', 'Set Biaya Registrasi Resume-CanEdit')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Set Biaya Registrasi Resume-CanDelete')->first()->id }}" value="{{ $accesseskeu->where('name', 'Set Biaya Registrasi Resume-CanDelete')->first()->id }}"> {{ $accesseskeu->where('name', 'Set Biaya Registrasi Resume-CanDelete')->first()->description }}</label></li>
                            </ul>
                          </li>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Set Biaya Tambahan</label>
                            <ul>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Set Biaya Tambahan-CanView')->first()->id }}" value="{{ $accesseskeu->where('name', 'Set Biaya Tambahan-CanView')->first()->id }}"> {{ $accesseskeu->where('name', 'Set Biaya Tambahan-CanView')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Set Biaya Tambahan-CanAdd')->first()->id }}" value="{{ $accesseskeu->where('name', 'Set Biaya Tambahan-CanAdd')->first()->id }}"> {{ $accesseskeu->where('name', 'Set Biaya Tambahan-CanAdd')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Set Biaya Tambahan-CanEdit')->first()->id }}" value="{{ $accesseskeu->where('name', 'Set Biaya Tambahan-CanEdit')->first()->id }}"> {{ $accesseskeu->where('name', 'Set Biaya Tambahan-CanEdit')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Set Biaya Tambahan-CanDelete')->first()->id }}" value="{{ $accesseskeu->where('name', 'Set Biaya Tambahan-CanDelete')->first()->id }}"> {{ $accesseskeu->where('name', 'Set Biaya Tambahan-CanDelete')->first()->description }}</label></li>
                            </ul>
                          </li>
                        </ul>
                      </li>
                      <li>
                        <i class="fa fa-plus"></i> <label> <input id="node-0" data-id="custom-0" type="checkbox"> Pembayaran Mahasiswa</label>
                        <ul>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Set Biaya Registrasi Personal</label>
                            <ul>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Set Biaya Registrasi Personal-CanView')->first()->id }}" value="{{ $accesseskeu->where('name', 'Set Biaya Registrasi Personal-CanView')->first()->id }}"> {{ $accesseskeu->where('name', 'Set Biaya Registrasi Personal-CanView')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Set Biaya Registrasi Personal-CanAdd')->first()->id }}" value="{{ $accesseskeu->where('name', 'Set Biaya Registrasi Personal-CanAdd')->first()->id }}"> {{ $accesseskeu->where('name', 'Set Biaya Registrasi Personal-CanAdd')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Set Biaya Registrasi Personal-CanEdit')->first()->id }}" value="{{ $accesseskeu->where('name', 'Set Biaya Registrasi Personal-CanEdit')->first()->id }}"> {{ $accesseskeu->where('name', 'Set Biaya Registrasi Personal-CanEdit')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Set Biaya Registrasi Personal-CanDelete')->first()->id }}" value="{{ $accesseskeu->where('name', 'Set Biaya Registrasi Personal-CanDelete')->first()->id }}"> {{ $accesseskeu->where('name', 'Set Biaya Registrasi Personal-CanDelete')->first()->description }}</label></li>
                            </ul>
                          </li>
                        </ul>
                      </li>
                      <li>
                        <i class="fa fa-plus"></i> <label> <input id="node-0" data-id="custom-0" type="checkbox"> Retur</label>
                        <ul>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Set Aturan Pengembalian/Return</label>
                            <ul>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Set Aturan Pengembalian-CanView')->first()->id }}" value="{{ $accesseskeu->where('name', 'Set Aturan Pengembalian-CanView')->first()->id }}"> {{ $accesseskeu->where('name', 'Set Aturan Pengembalian-CanView')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Set Aturan Pengembalian-CanCetak')->first()->id }}" value="{{ $accesseskeu->where('name', 'Set Aturan Pengembalian-CanCetak')->first()->id }}"> {{ $accesseskeu->where('name', 'Set Aturan Pengembalian-CanCetak')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Set Aturan Pengembalian-CanEdit')->first()->id }}" value="{{ $accesseskeu->where('name', 'Set Aturan Pengembalian-CanEdit')->first()->id }}"> {{ $accesseskeu->where('name', 'Set Aturan Pengembalian-CanEdit')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Set Aturan Pengembalian-CanDelete')->first()->id }}" value="{{ $accesseskeu->where('name', 'Set Aturan Pengembalian-CanDelete')->first()->id }}"> {{ $accesseskeu->where('name', 'Set Aturan Pengembalian-CanDelete')->first()->description }}</label></li>
                            </ul>
                          </li>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Entry Pengembalian/Return</label>
                            <ul>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Set Aturan Pengembalian-CanAdd')->first()->id }}" value="{{ $accesseskeu->where('name', 'Set Aturan Pengembalian-CanAdd')->first()->id }}"> {{ $accesseskeu->where('name', 'Set Aturan Pengembalian-CanAdd')->first()->description }}</label></li>
                            </ul>
                          </li>
                        </ul>
                      </li>
                      <li>
                        <i class="fa fa-plus"></i> <label> <input id="node-0" data-id="custom-0" type="checkbox"> Teller</label>
                        <ul>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Entry Pembayaran Mahasiswa</label>
                            <ul>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Entry Pembayaran Mahasiswa-CanView')->first()->id }}" value="{{ $accesseskeu->where('name', 'Entry Pembayaran Mahasiswa-CanView')->first()->id }}"> {{ $accesseskeu->where('name', 'Entry Pembayaran Mahasiswa-CanView')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Entry Pembayaran Mahasiswa-CanViewDetail')->first()->id }}" value="{{ $accesseskeu->where('name', 'Entry Pembayaran Mahasiswa-CanViewDetail')->first()->id }}"> {{ $accesseskeu->where('name', 'Entry Pembayaran Mahasiswa-CanViewDetail')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Entry Pembayaran Mahasiswa-CanEditTagihan')->first()->id }}" value="{{ $accesseskeu->where('name', 'Entry Pembayaran Mahasiswa-CanEditTagihan')->first()->id }}"> {{ $accesseskeu->where('name', 'Entry Pembayaran Mahasiswa-CanEditTagihan')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Entry Pembayaran Mahasiswa-CanBayaTagihan')->first()->id }}" value="{{ $accesseskeu->where('name', 'Entry Pembayaran Mahasiswa-CanBayaTagihan')->first()->id }}"> {{ $accesseskeu->where('name', 'Entry Pembayaran Mahasiswa-CanBayaTagihan')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Entry Pembayaran Mahasiswa-CanPrintTagihan')->first()->id }}" value="{{ $accesseskeu->where('name', 'Entry Pembayaran Mahasiswa-CanPrintTagihan')->first()->id }}"> {{ $accesseskeu->where('name', 'Entry Pembayaran Mahasiswa-CanPrintTagihan')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Entry Pembayaran Mahasiswa-CanBatalTagihan')->first()->id }}" value="{{ $accesseskeu->where('name', 'Entry Pembayaran Mahasiswa-CanBatalTagihan')->first()->id }}"> {{ $accesseskeu->where('name', 'Entry Pembayaran Mahasiswa-CanBatalTagihan')->first()->description }}</label></li>
                              <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Riwayat Pembayaran Details-CanView')->first()->id }}" value="{{ $accesseskeu->where('name', 'Riwayat Pembayaran Details-CanView')->first()->id }}"> {{ $accesseskeu->where('name', 'Riwayat Pembayaran Details-CanView')->first()->description }}</label></li>
                            </ul>
                          </li>
                          <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Laporan</label>
                            <ul>
                              <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Laporan Pembayaran Prodi</label>
                                <ul>
                                  <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Laporan Prodi-CanView')->first()->id }}" value="{{ $accesseskeu->where('name', 'Laporan Prodi-CanView')->first()->id }}"> {{ $accesseskeu->where('name', 'Laporan Prodi-CanView')->first()->description }}</label></li>
                                </ul>
                              </li>
                              <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Laporan Pembayaran Mahasiswa</label>
                                <ul>
                                  <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Laporan Mahasiswa-CanView')->first()->id }}" value="{{ $accesseskeu->where('name', 'Laporan Mahasiswa-CanView')->first()->id }}"> {{ $accesseskeu->where('name', 'Laporan Mahasiswa-CanView')->first()->description }}</label></li>
                                </ul>
                              </li>
                              <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Laporan Pembayaran Per Bank</label>
                                <ul>
                                  <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Laporan Bank-CanView')->first()->id }}" value="{{ $accesseskeu->where('name', 'Laporan Bank-CanView')->first()->id }}"> {{ $accesseskeu->where('name', 'Laporan Bank-CanView')->first()->description }}</label></li>
                                </ul>
                              </li>
                              <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Riwayat Pembayaran Mahasiswa</label>
                                <ul>
                                  <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Riwayat Pembayaran Mahasiswa-CanView')->first()->id }}" value="{{ $accesseskeu->where('name', 'Riwayat Pembayaran Mahasiswa-CanView')->first()->id }}"> {{ $accesseskeu->where('name', 'Riwayat Pembayaran Mahasiswa-CanView')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Riwayat Pembayaran Details Create-CanView')->first()->id }}" value="{{ $accesseskeu->where('name', 'Riwayat Pembayaran Details Create-CanView')->first()->id }}"> Export Riwayat Pembayaran Detail</label></li>
                                </ul>
                              </li>
                              <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox"> Pembayaran Mahasiswa Per Item</label>
                                <ul>
                                  <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Pembayaran Mahasiswa Item-CanView')->first()->id }}" value="{{ $accesseskeu->where('name', 'Pembayaran Mahasiswa Item-CanView')->first()->id }}"> {{ $accesseskeu->where('name', 'Pembayaran Mahasiswa Item-CanView')->first()->description }}</label></li>
                                </ul>
                              </li>
                              <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox"> Laporan Tunggakan Mahasiswa</label>
                                <ul>
                                  <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Laporan Tunggakan Mahasiswa-CanView')->first()->id }}" value="{{ $accesseskeu->where('name', 'Laporan Tunggakan Mahasiswa-CanView')->first()->id }}"> {{ $accesseskeu->where('name', 'Laporan Tunggakan Mahasiswa-CanView')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="accesskeu[]" type="checkbox" id="{{ $accesseskeu->where('name', 'Laporan Tunggakan Mahasiswa-CanViewDetail')->first()->id }}" value="{{ $accesseskeu->where('name', 'Laporan Tunggakan Mahasiswa-CanViewDetail')->first()->id }}"> {{ $accesseskeu->where('name', 'Laporan Tunggakan Mahasiswa-CanViewDetail')->first()->description }}</label></li>
                                </ul>
                              </li>
                            </ul>
                          </li>
                        </ul>
                      </li>
                    </ul>
                  </li>
                  <li>
                    <i class="fa fa-plus"></i> <label> <input id="node-0" data-id="custom-0" type="checkbox"> KEPEGAWAIAN</label>
                    <ul>
                    </ul>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          </div>
      </div>
    </div>
  </div>



<!-- /.row -->
<?php $accee = json_encode($acce);?>
<?php $acceekeu = json_encode($accekeu);?>
<script>
function contains(arr, element) {
    for (var i = 0; i < arr.length; i++) {
        if (arr[i] === element) {
            return true;
        }
    }
    return false;
}

function contains(arr2, element) {
    for (var i = 0; i < arr2.length; i++) {
        if (arr2[i] === element) {
            return true;
        }
    }
    return false;
}

$(document).ready(function () {
  $("#treeview").hummingbird();
  var arr = <?php echo $accee; ?>;
  var arr2 = <?php echo $acceekeu; ?>;

  arr.forEach(function(entry) {
    if (contains(arr,entry) == true) {
      $("#treeview").hummingbird("disableNode",{attr:"id",name: entry,state:true});
    }
  });

  arr2.forEach(function(entry) {
    if (contains(arr2,entry) == true) {
      $("#treeview").hummingbird("disableNode",{attr:"id",name: entry,state:true});
    }
  });
  // $("#treeview").hummingbird("checkNode",{attr:"id",name: "node-0-1-2-1",state:true});
  // $("#treeview").hummingbird("checkNode",{attr:"id",name: "node-0-1-2-2",state:true});

});
</script>
</section>
<?php } ?>
@endsection
