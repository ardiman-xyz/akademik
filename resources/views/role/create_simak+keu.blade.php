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


<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Tambah Role</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('administrator/role?page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-danger btn-sm">Batal &nbsp;<i class="fa fa-close"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Tambah</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">

          @if (count($errors) > 0)
            @foreach ( $errors->all() as $error )
              @if ($error=="Berhasil Menambah Role")
                <p class="alert alert-success">{{ $error }}</p>
              @else
                <p class="alert alert-danger">{{ $error }}</p>
              @endif
            @endforeach
          @endif

          <div class="row">
            <div class="col-sm-6">
              {!! Form::open(['url' => route('role.store') , 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form']) !!}
              <div class="form-group">
                {!! Form::label('', 'Nama', ['class' => 'col-md-4 form-label']) !!}
                <div class="col-md-12">
                  <input type="text" name="nama" value="{{ old('nama') }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
                </div>
              </div>
              <div class="form-group">
                {!! Form::label('', 'Deskripsi', ['class' => 'col-md-4 form-label']) !!}
                <div class="col-md-12">
                  <input type="text" name="deskripsi" value="{{ old('deskripsi') }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
                </div>
                  <br><center><button type="submit" class="btn btn-primary btn-flat">Tambah</button></center>
              </div>
            </div>
            <div class="col-sm-6">
              {!! Form::label('', 'Otoritas', ['class' => 'col-md-4 form-label']) !!}
              <div>
                <div id="treeview_container" class="hummingbird-treeview well h-scroll-large">
                  <ul id="treeview" class="hummingbird-base checktree">
                    <li>
                      <i class="fa fa-plus"></i> <label> <input id="node-0" data-id="custom-0" type="checkbox"> SIMAKAD</label>
                      {{-- <ul style="display: block;"> --}}
                      <ul>
                        <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Administrator</label>
                          <ul>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1-1" data-id="custom-1-1" type="checkbox"> User</label>
                              <ul>
                                <li><i></i> <label> <input name="access[]" class="hummingbirdNoParent" type="checkbox" value="{{ $access->where('name', 'user-CanView')->first()->id }}"> {{ $access->where('name', 'user-CanView')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" class="hummingbirdNoParent" type="checkbox" value="{{ $access->where('name', 'user-CanAdd')->first()->id }}"> {{ $access->where('name', 'user-CanAdd')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" class="hummingbirdNoParent" type="checkbox" value="{{ $access->where('name', 'user-CanEdit')->first()->id }}"> {{ $access->where('name', 'user-CanEdit')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" class="hummingbirdNoParent" type="checkbox" value="{{ $access->where('name', 'user-CanDelete')->first()->id }}"> {{ $access->where('name', 'user-CanDelete')->first()->description }}</label></li>
                              </ul>
                              <li>
                                <i class="fa fa-plus"></i> <label> <input name="access[]" type="checkbox" value="{{ $access[4]->id }}"> {{ $access[4]->description }}</label>
                              </li>
                              <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Role</label>
                                <ul>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'role-CanView')->first()->id }}"> {{ $access->where('name', 'role-CanView')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'role-CanAdd')->first()->id }}"> {{ $access->where('name', 'role-CanAdd')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'role-CanEdit')->first()->id }}"> {{ $access->where('name', 'role-CanEdit')->first()->description }}</label></li>
                                  <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'role-CanDelete')->first()->id }}"> {{ $access->where('name', 'role-CanDelete')->first()->description }}</label></li>
                                </ul>
                              </li>
                          </li>
                        </ul>
                      </ul>
                      <ul>
                        <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Master</label>
                          <ul>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1-1" data-id="custom-1-1" type="checkbox"> Akademik</label>
                              <ul>
                                <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Fakultas</label>
                                  <ul>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'faculty-CanView')->first()->id }}"> {{ $access->where('name', 'faculty-CanView')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'faculty-CanAdd')->first()->id }}"> {{ $access->where('name', 'faculty-CanAdd')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'faculty-CanEdit')->first()->id }}"> {{ $access->where('name', 'faculty-CanEdit')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'faculty-CanDelete')->first()->id }}"> {{ $access->where('name', 'faculty-CanDelete')->first()->description }}</label></li>
                                  </ul>
                                </li>
                                <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Program Studi</label>
                                  <ul>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'department-CanView')->first()->id }}"> {{ $access->where('name', 'department-CanView')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'department-CanAdd')->first()->id }}"> {{ $access->where('name', 'department-CanAdd')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'department-CanEdit')->first()->id }}"> {{ $access->where('name', 'department-CanEdit')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'department-CanDelete')->first()->id }}"> {{ $access->where('name', 'department-CanDelete')->first()->description }}</label></li>
                                  </ul>
                                </li>
                                <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Program Kelas</label>
                                  <ul>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'class_program-CanView')->first()->id }}"> {{ $access->where('name', 'class_program-CanView')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'class_program-CanAdd')->first()->id }}"> {{ $access->where('name', 'class_program-CanAdd')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'class_program-CanEdit')->first()->id }}"> {{ $access->where('name', 'class_program-CanEdit')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'class_program-CanDelete')->first()->id }}"> {{ $access->where('name', 'class_program-CanDelete')->first()->description }}</label></li>
                                  </ul>
                                </li>
                                <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Prodi vs Program Kelas</label>
                                  <ul>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'department_class_program-CanView')->first()->id }}"> {{ $access->where('name', 'department_class_program-CanView')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'department_class_program-CanAdd')->first()->id }}"> {{ $access->where('name', 'department_class_program-CanAdd')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'department_class_program-CanDelete')->first()->id }}"> {{ $access->where('name', 'department_class_program-CanDelete')->first()->description }}</label></li>
                                  </ul>
                                </li>
                                <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Konsentrasi</label>
                                  <ul>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'concentration-CanView')->first()->id }}"> {{ $access->where('name', 'concentration-CanView')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'concentration-CanAdd')->first()->id }}"> {{ $access->where('name', 'concentration-CanAdd')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'concentration-CanEdit')->first()->id }}"> {{ $access->where('name', 'concentration-CanEdit')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'concentration-CanDelete')->first()->id }}"> {{ $access->where('name', 'concentration-CanDelete')->first()->description }}</label></li>
                                  </ul>
                                </li>
                                <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Jenjang Pendidikan</label>
                                  <ul>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'education_type-CanView')->first()->id }}"> {{ $access->where('name', 'education_type-CanView')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'education_type-CanAdd')->first()->id }}"> {{ $access->where('name', 'education_type-CanAdd')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'education_type-CanEdit')->first()->id }}"> {{ $access->where('name', 'education_type-CanEdit')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'education_type-CanDelete')->first()->id }}"> {{ $access->where('name', 'education_type-CanDelete')->first()->description }}</label></li>
                                  </ul>
                                </li>
                                <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Angkatan</label>
                                  <ul>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'entry_year-CanView')->first()->id }}"> {{ $access->where('name', 'entry_year-CanView')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'entry_year-CanAdd')->first()->id }}"> {{ $access->where('name', 'entry_year-CanAdd')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'entry_year-CanEdit')->first()->id }}"> {{ $access->where('name', 'entry_year-CanEdit')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'entry_year-CanDelete')->first()->id }}"> {{ $access->where('name', 'entry_year-CanDelete')->first()->description }}</label></li>
                                  </ul>
                                </li>
                                <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Semester Berlaku</label>
                                  <ul>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'term_year-CanView')->first()->id }}"> {{ $access->where('name', 'term_year-CanView')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'term_year-CanAdd')->first()->id }}"> {{ $access->where('name', 'term_year-CanAdd')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'term_year-CanEdit')->first()->id }}"> {{ $access->where('name', 'term_year-CanEdit')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'term_year-CanDelete')->first()->id }}"> {{ $access->where('name', 'term_year-CanDelete')->first()->description }}</label></li>
                                  </ul>
                                </li>
                                <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Group Sesi Jadwal</label>
                                  <ul>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'sched_session_group-CanView')->first()->id }}"> {{ $access->where('name', 'sched_session_group-CanView')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'sched_session_group-CanAdd')->first()->id }}"> {{ $access->where('name', 'sched_session_group-CanAdd')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'sched_session_group-CanEdit')->first()->id }}"> {{ $access->where('name', 'sched_session_group-CanEdit')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'sched_session_group-CanDelete')->first()->id }}"> {{ $access->where('name', 'sched_session_group-CanDelete')->first()->description }}</label></li>
                                  </ul>
                                </li>
                                <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Pegawai / Dosen</label>
                                  <ul>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'employee-CanView')->first()->id }}"> {{ $access->where('name', 'employee-CanView')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'employee-CanAdd')->first()->id }}"> {{ $access->where('name', 'employee-CanAdd')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'employee-CanEdit')->first()->id }}"> {{ $access->where('name', 'employee-CanEdit')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'employee-CanDelete')->first()->id }}"> {{ $access->where('name', 'employee-CanDelete')->first()->description }}</label></li>
                                  </ul>
                                </li>
                                <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Strata Pendidikan</label>
                                  <ul>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'education_program_type-CanView')->first()->id }}"> {{ $access->where('name', 'education_program_type-CanView')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'education_program_type-CanAdd')->first()->id }}"> {{ $access->where('name', 'education_program_type-CanAdd')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'education_program_type-CanEdit')->first()->id }}"> {{ $access->where('name', 'education_program_type-CanEdit')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'education_program_type-CanDelete')->first()->id }}"> {{ $access->where('name', 'education_program_type-CanDelete')->first()->description }}</label></li>
                                  </ul>
                                </li>
                                <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Nilai Huruf</label>
                                  <ul>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'grade_letter-CanView')->first()->id }}"> {{ $access->where('name', 'grade_letter-CanView')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'grade_letter-CanAdd')->first()->id }}"> {{ $access->where('name', 'grade_letter-CanAdd')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'grade_letter-CanEdit')->first()->id }}"> {{ $access->where('name', 'grade_letter-CanEdit')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'grade_letter-CanDelete')->first()->id }}"> {{ $access->where('name', 'grade_letter-CanDelete')->first()->description }}</label></li>
                                  </ul>
                                </li>
                                <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Jenis Matakuliah</label>
                                  <ul>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'course_type-CanView')->first()->id }}"> {{ $access->where('name', 'course_type-CanView')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'course_type-CanAdd')->first()->id }}"> {{ $access->where('name', 'course_type-CanAdd')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'course_type-CanEdit')->first()->id }}"> {{ $access->where('name', 'course_type-CanEdit')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'course_type-CanDelete')->first()->id }}"> {{ $access->where('name', 'course_type-CanDelete')->first()->description }}</label></li>
                                  </ul>
                                </li>
                                <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Kelompok Matakuliah</label>
                                  <ul>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'course_group-CanView')->first()->id }}"> {{ $access->where('name', 'course_group-CanView')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'course_group-CanAdd')->first()->id }}"> {{ $access->where('name', 'course_group-CanAdd')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'course_group-CanEdit')->first()->id }}"> {{ $access->where('name', 'course_group-CanEdit')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'course_group-CanDelete')->first()->id }}"> {{ $access->where('name', 'course_group-CanDelete')->first()->description }}</label></li>
                                  </ul>
                                </li>
                                <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Predikat Lulus</label>
                                  <ul>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'graduate_predicate-CanView')->first()->id }}"> {{ $access->where('name', 'graduate_predicate-CanView')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'graduate_predicate-CanAdd')->first()->id }}"> {{ $access->where('name', 'graduate_predicate-CanAdd')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'graduate_predicate-CanEdit')->first()->id }}"> {{ $access->where('name', 'graduate_predicate-CanEdit')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'graduate_predicate-CanDelete')->first()->id }}"> {{ $access->where('name', 'graduate_predicate-CanDelete')->first()->description }}</label></li>
                                  </ul>
                                </li>
                              </ul>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1-1" data-id="custom-1-1" type="checkbox"> Biodata</label>
                              <ul>
                                <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Agama</label>
                                  <ul>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'religion-CanView')->first()->id }}"> {{ $access->where('name', 'religion-CanView')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'religion-CanAdd')->first()->id }}"> {{ $access->where('name', 'religion-CanAdd')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'religion-CanEdit')->first()->id }}"> {{ $access->where('name', 'religion-CanEdit')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'religion-CanDelete')->first()->id }}"> {{ $access->where('name', 'religion-CanDelete')->first()->description }}</label></li>
                                  </ul>
                                </li>
                                <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Kewarganegaraan</label>
                                  <ul>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'citizenship-CanView')->first()->id }}"> {{ $access->where('name', 'citizenship-CanView')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'citizenship-CanAdd')->first()->id }}"> {{ $access->where('name', 'citizenship-CanAdd')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'citizenship-CanEdit')->first()->id }}"> {{ $access->where('name', 'citizenship-CanEdit')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'citizenship-CanDelete')->first()->id }}"> {{ $access->where('name', 'citizenship-CanDelete')->first()->description }}</label></li>
                                  </ul>
                                </li>
                                <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Golongan Darah</label>
                                  <ul>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'blood_type-CanView')->first()->id }}"> {{ $access->where('name', 'blood_type-CanView')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'blood_type-CanAdd')->first()->id }}"> {{ $access->where('name', 'blood_type-CanAdd')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'blood_type-CanEdit')->first()->id }}"> {{ $access->where('name', 'blood_type-CanEdit')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'blood_type-CanDelete')->first()->id }}"> {{ $access->where('name', 'blood_type-CanDelete')->first()->description }}</label></li>
                                  </ul>
                                </li>
                                <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Status Registrasi Mahasiswa</label>
                                  <ul>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'register_status-CanView')->first()->id }}"> {{ $access->where('name', 'register_status-CanView')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'register_status-CanAdd')->first()->id }}"> {{ $access->where('name', 'register_status-CanAdd')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'register_status-CanEdit')->first()->id }}"> {{ $access->where('name', 'register_status-CanEdit')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'register_status-CanDelete')->first()->id }}"> {{ $access->where('name', 'register_status-CanDelete')->first()->description }}</label></li>
                                  </ul>
                                </li>
                                <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Jurusan di SMA</label>
                                  <ul>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'high_school_major-CanView')->first()->id }}"> {{ $access->where('name', 'high_school_major-CanView')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'high_school_major-CanAdd')->first()->id }}"> {{ $access->where('name', 'high_school_major-CanAdd')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'high_school_major-CanEdit')->first()->id }}"> {{ $access->where('name', 'high_school_major-CanEdit')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'high_school_major-CanDelete')->first()->id }}"> {{ $access->where('name', 'high_school_major-CanDelete')->first()->description }}</label></li>
                                  </ul>
                                </li>
                              </ul>
                            </li>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1-1" data-id="custom-1-1" type="checkbox"> Organisasi</label>
                              <ul>
                                <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Gedung</label>
                                  <ul>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'building-CanView')->first()->id }}"> {{ $access->where('name', 'building-CanView')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'building-CanAdd')->first()->id }}"> {{ $access->where('name', 'building-CanAdd')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'building-CanEdit')->first()->id }}"> {{ $access->where('name', 'building-CanEdit')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'building-CanDelete')->first()->id }}"> {{ $access->where('name', 'building-CanDelete')->first()->description }}</label></li>
                                  </ul>
                                </li>
                                <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Ruang</label>
                                  <ul>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'room-CanView')->first()->id }}"> {{ $access->where('name', 'room-CanView')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'room-CanAdd')->first()->id }}"> {{ $access->where('name', 'room-CanAdd')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'room-CanEdit')->first()->id }}"> {{ $access->where('name', 'room-CanEdit')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'room-CanDelete')->first()->id }}"> {{ $access->where('name', 'room-CanDelete')->first()->description }}</label></li>
                                  </ul>
                                </li>
                                <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Jabatan</label>
                                  <ul>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'jabatan-CanView')->first()->id }}"> {{ $access->where('name', 'jabatan-CanView')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'jabatan-CanAdd')->first()->id }}"> {{ $access->where('name', 'jabatan-CanAdd')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'jabatan-CanEdit')->first()->id }}"> {{ $access->where('name', 'jabatan-CanEdit')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'jabatan-CanDelete')->first()->id }}"> {{ $access->where('name', 'jabatan-CanDelete')->first()->description }}</label></li>
                                  </ul>
                                </li>
                                <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Jabatan Struktural</label>
                                  <ul>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'functional_position_term_year-CanView')->first()->id }}"> {{ $access->where('name', 'functional_position_term_year-CanView')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'functional_position_term_year-CanAdd')->first()->id }}"> {{ $access->where('name', 'functional_position_term_year-CanAdd')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'functional_position_term_year-CanEdit')->first()->id }}"> {{ $access->where('name', 'functional_position_term_year-CanEdit')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'functional_position_term_year-CanDelete')->first()->id }}"> {{ $access->where('name', 'functional_position_term_year-CanDelete')->first()->description }}</label></li>
                                  </ul>
                                </li>
                              </ul>
                            </li>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1-1" data-id="custom-1-1" type="checkbox"> Kurikulum</label>
                              <ul>
                                <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Jenis Kurikulum</label>
                                  <ul>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'curriculum_type-CanView')->first()->id }}"> {{ $access->where('name', 'curriculum_type-CanView')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'curriculum_type-CanAdd')->first()->id }}"> {{ $access->where('name', 'curriculum_type-CanAdd')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'curriculum_type-CanEdit')->first()->id }}"> {{ $access->where('name', 'curriculum_type-CanEdit')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'curriculum_type-CanDelete')->first()->id }}"> {{ $access->where('name', 'curriculum_type-CanDelete')->first()->description }}</label></li>
                                  </ul>
                                </li>
                                <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Kurikulum</label>
                                  <ul>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'curriculum-CanView')->first()->id }}"> {{ $access->where('name', 'curriculum-CanView')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'curriculum-CanAdd')->first()->id }}"> {{ $access->where('name', 'curriculum-CanAdd')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'curriculum-CanEdit')->first()->id }}"> {{ $access->where('name', 'curriculum-CanEdit')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'curriculum-CanDelete')->first()->id }}"> {{ $access->where('name', 'curriculum-CanDelete')->first()->description }}</label></li>
                                  </ul>
                                </li>
                              </ul>
                            </li>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1-1" data-id="custom-1-1" type="checkbox"> Lokasi</label>
                              <ul>
                                <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Negara</label>
                                  <ul>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'country-CanView')->first()->id }}"> {{ $access->where('name', 'country-CanView')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'country-CanAdd')->first()->id }}"> {{ $access->where('name', 'country-CanAdd')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'country-CanEdit')->first()->id }}"> {{ $access->where('name', 'country-CanEdit')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'country-CanDelete')->first()->id }}"> {{ $access->where('name', 'country-CanDelete')->first()->description }}</label></li>
                                  </ul>
                                </li>
                                <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Provinsi</label>
                                  <ul>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'province-CanView')->first()->id }}"> {{ $access->where('name', 'province-CanView')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'province-CanAdd')->first()->id }}"> {{ $access->where('name', 'province-CanAdd')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'province-CanEdit')->first()->id }}"> {{ $access->where('name', 'province-CanEdit')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'province-CanDelete')->first()->id }}"> {{ $access->where('name', 'province-CanDelete')->first()->description }}</label></li>
                                  </ul>
                                </li>
                                <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Kota</label>
                                  <ul>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'city-CanView')->first()->id }}"> {{ $access->where('name', 'city-CanView')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'city-CanAdd')->first()->id }}"> {{ $access->where('name', 'city-CanAdd')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'city-CanEdit')->first()->id }}"> {{ $access->where('name', 'city-CanEdit')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'city-CanDelete')->first()->id }}"> {{ $access->where('name', 'city-CanDelete')->first()->description }}</label></li>
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
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'curriculum_applied-CanView')->first()->id }}"> {{ $access->where('name', 'curriculum_applied-CanView')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'curriculum_applied-CanAdd')->first()->id }}"> {{ $access->where('name', 'curriculum_applied-CanAdd')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'curriculum_applied-CanEdit')->first()->id }}"> {{ $access->where('name', 'curriculum_applied-CanEdit')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'curriculum_applied-CanDelete')->first()->id }}"> {{ $access->where('name', 'curriculum_applied-CanDelete')->first()->description }}</label></li>
                              </ul>
                            </li>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Kurikulum Angkatan</label>
                              <ul>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'curriculum_entry_year-CanView')->first()->id }}"> {{ $access->where('name', 'curriculum_entry_year-CanView')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'curriculum_entry_year-CanAdd')->first()->id }}"> {{ $access->where('name', 'curriculum_entry_year-CanAdd')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'curriculum_entry_year-CanEdit')->first()->id }}"> {{ $access->where('name', 'curriculum_entry_year-CanEdit')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'curriculum_entry_year-CanDelete')->first()->id }}"> {{ $access->where('name', 'curriculum_entry_year-CanDelete')->first()->description }}</label></li>
                              </ul>
                            </li>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Matakuliah</label>
                              <ul>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'course-CanView')->first()->id }}"> {{ $access->where('name', 'course-CanView')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'course-CanAdd')->first()->id }}"> {{ $access->where('name', 'course-CanAdd')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'course-CanEdit')->first()->id }}"> {{ $access->where('name', 'course-CanEdit')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'course-CanDelete')->first()->id }}"> {{ $access->where('name', 'course-CanDelete')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'course-CanExport')->first()->id }}"> {{ $access->where('name', 'course-CanExport')->first()->description }}</label></li>
                              </ul>
                            </li>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Matakuliah dan Kurikulum</label>
                              <ul>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'course_curriculum-CanView')->first()->id }}"> {{ $access->where('name', 'course_curriculum-CanView')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'course_curriculum-CanAdd')->first()->id }}"> {{ $access->where('name', 'course_curriculum-CanAdd')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'course_curriculum-CanEdit')->first()->id }}"> {{ $access->where('name', 'course_curriculum-CanEdit')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'course_curriculum-CanDelete')->first()->id }}"> {{ $access->where('name', 'course_curriculum-CanDelete')->first()->description }}</label></li>
                              </ul>
                            </li>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Matakuliah setara</label>
                              <ul>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'course_identic-CanView')->first()->id }}"> {{ $access->where('name', 'course_identic-CanView')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'course_identic-CanAdd')->first()->id }}"> {{ $access->where('name', 'course_identic-CanAdd')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'course_identic-CanEdit')->first()->id }}"> {{ $access->where('name', 'course_identic-CanEdit')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'course_identic-CanDelete')->first()->id }}"> {{ $access->where('name', 'course_identic-CanDelete')->first()->description }}</label></li>
                              </ul>
                            </li>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Grade Nilai</label>
                              <ul>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'grade_department-CanView')->first()->id }}"> {{ $access->where('name', 'grade_department-CanView')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'grade_department-CanAdd')->first()->id }}"> {{ $access->where('name', 'grade_department-CanAdd')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'grade_department-CanEdit')->first()->id }}"> {{ $access->where('name', 'grade_department-CanEdit')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'grade_department-CanDelete')->first()->id }}"> {{ $access->where('name', 'grade_department-CanDelete')->first()->description }}</label></li>
                              </ul>
                            </li>
                          </ul>
                        </li>
                        <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Setting</label>
                          <ul>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Data Mahasiswa</label>
                              <ul>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'student-CanView')->first()->id }}"> {{ $access->where('name', 'student-CanView')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'student-CanAdd')->first()->id }}"> {{ $access->where('name', 'student-CanAdd')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'student-CanEdit')->first()->id }}"> {{ $access->where('name', 'student-CanEdit')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'student-CanDelete')->first()->id }}"> {{ $access->where('name', 'student-CanDelete')->first()->description }}</label></li>
                              </ul>
                            </li>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Bimbingan Mahasiswa</label>
                              <ul>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'student_supervision-CanView')->first()->id }}"> {{ $access->where('name', 'student_supervision-CanView')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'student_supervision-CanAdd')->first()->id }}"> {{ $access->where('name', 'student_supervision-CanAdd')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'student_supervision-CanEdit')->first()->id }}"> {{ $access->where('name', 'student_supervision-CanEdit')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'student_supervision-CanDelete')->first()->id }}"> {{ $access->where('name', 'student_supervision-CanDelete')->first()->description }}</label></li>
                              </ul>
                            </li>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Password Mahasiswa</label>
                              <ul>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'student_password-CanView')->first()->id }}"> {{ $access->where('name', 'student_password-CanView')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'student_password-CanEdit')->first()->id }}"> {{ $access->where('name', 'student_password-CanEdit')->first()->description }}</label></li>
                              </ul>
                            </li>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Jadwal Pengisian</label>
                              <ul>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'event_sched-CanView')->first()->id }}"> {{ $access->where('name', 'event_sched-CanView')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'event_sched-CanAdd')->first()->id }}"> {{ $access->where('name', 'event_sched-CanAdd')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'event_sched-CanEdit')->first()->id }}"> {{ $access->where('name', 'event_sched-CanEdit')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'event_sched-CanDelete')->first()->id }}"> {{ $access->where('name', 'event_sched-CanDelete')->first()->description }}</label></li>
                              </ul>
                            </li>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Dosen Prodi</label>
                              <ul>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'department_lecturer-CanView')->first()->id }}"> {{ $access->where('name', 'department_lecturer-CanView')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'department_lecturer-CanAdd')->first()->id }}"> {{ $access->where('name', 'department_lecturer-CanAdd')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'department_lecturer-CanDelete')->first()->id }}"> {{ $access->where('name', 'department_lecturer-CanDelete')->first()->description }}</label></li>
                              </ul>
                            </li>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Matakuliah Ditawarkan</label>
                              <ul>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'offered_course-CanView')->first()->id }}"> {{ $access->where('name', 'offered_course-CanView')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'offered_course-CanAdd')->first()->id }}"> {{ $access->where('name', 'offered_course-CanAdd')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'offered_course-CanEditCapacity')->first()->id }}"> {{ $access->where('name', 'offered_course-CanEditCapacity')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'offered_course-CanEditEmployee')->first()->id }}"> {{ $access->where('name', 'offered_course-CanEditEmployee')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'offered_course-CanDelete')->first()->id }}"> {{ $access->where('name', 'offered_course-CanDelete')->first()->description }}</label></li>
                              </ul>
                            </li>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Sks Diijinkan</label>
                              <ul>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'allowed_sks-CanView')->first()->id }}"> {{ $access->where('name', 'allowed_sks-CanView')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'allowed_sks-CanAdd')->first()->id }}"> {{ $access->where('name', 'allowed_sks-CanAdd')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'allowed_sks-CanEdit')->first()->id }}"> {{ $access->where('name', 'allowed_sks-CanEdit')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'allowed_sks-CanDelete')->first()->id }}"> {{ $access->where('name', 'allowed_sks-CanDelete')->first()->description }}</label></li>
                              </ul>
                            </li>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Sesi Jadwal Kuliah</label>
                              <ul>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'sched_session-CanView')->first()->id }}"> {{ $access->where('name', 'sched_session-CanView')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'sched_session-CanAdd')->first()->id }}"> {{ $access->where('name', 'sched_session-CanAdd')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'sched_session-CanEdit')->first()->id }}"> {{ $access->where('name', 'sched_session-CanEdit')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'sched_session-CanDelete')->first()->id }}"> {{ $access->where('name', 'sched_session-CanDelete')->first()->description }}</label></li>
                              </ul>
                            </li>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Entry Jadwal Kuliah</label>
                              <ul>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'offered_course_sched-CanView')->first()->id }}"> {{ $access->where('name', 'offered_course_sched-CanView')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'offered_course_sched-CanEdit')->first()->id }}"> {{ $access->where('name', 'offered_course_sched-CanEdit')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'offered_course_sched-CanDelete')->first()->id }}"> {{ $access->where('name', 'offered_course_sched-CanDelete')->first()->description }}</label></li>
                              </ul>
                            </li>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Jadwal dan Peserta Ujian</label>
                              <ul>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'offered_course_exam-CanView')->first()->id }}"> {{ $access->where('name', 'offered_course_exam-CanView')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'offered_course_exam-CanViewPeserta')->first()->id }}"> {{ $access->where('name', 'offered_course_exam-CanViewPeserta')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'offered_course_exam-CanAdd')->first()->id }}"> {{ $access->where('name', 'offered_course_exam-CanAdd')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'offered_course_exam-CanAddPeserta')->first()->id }}"> {{ $access->where('name', 'offered_course_exam-CanAddPeserta')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'offered_course_exam-CanEdit')->first()->id }}"> {{ $access->where('name', 'offered_course_exam-CanEdit')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'offered_course_exam-CanDelete')->first()->id }}"> {{ $access->where('name', 'offered_course_exam-CanDelete')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'offered_course_exam-CanHapusPeserta')->first()->id }}"> {{ $access->where('name', 'offered_course_exam-CanHapusPeserta')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'offered_course_exam-CanExportPresensi')->first()->id }}"> {{ $access->where('name', 'offered_course_exam-CanExportPresensi')->first()->description }}</label></li>
                              </ul>
                            </li>
                          </ul>
                        </li>
                        <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Proses</label>
                          <ul>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">KRS Per Kelas Matakuliah</label>
                              <ul>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'krs_matakuliah-CanView')->first()->id }}"> {{ $access->where('name', 'krs_matakuliah-CanView')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'krs_matakuliah-CanAdd')->first()->id }}"> {{ $access->where('name', 'krs_matakuliah-CanAdd')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'krs_matakuliah-CanViewDetail')->first()->id }}"> {{ $access->where('name', 'krs_matakuliah-CanViewDetail')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'krs_matakuliah-CanDelete')->first()->id }}"> {{ $access->where('name', 'krs_matakuliah-CanDelete')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'krs_matakuliah-CanExport')->first()->id }}"> {{ $access->where('name', 'krs_matakuliah-CanExport')->first()->description }}</label></li>
                              </ul>
                            </li>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">KRS Per Mahasiswa</label>
                              <ul>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'krs_mahasiswa-CanView')->first()->id }}"> {{ $access->where('name', 'krs_mahasiswa-CanView')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'krs_mahasiswa-CanAdd')->first()->id }}"> {{ $access->where('name', 'krs_mahasiswa-CanAdd')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'krs_mahasiswa-CanEdit')->first()->id }}"> {{ $access->where('name', 'krs_mahasiswa-CanEdit')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'krs_mahasiswa-CanDelete')->first()->id }}"> {{ $access->where('name', 'krs_mahasiswa-CanDelete')->first()->description }}</label></li>
                              </ul>
                            </li>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Nilai Per Kelas Matakuliah</label>
                              <ul>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'khs_matakuliah-CanView')->first()->id }}"> {{ $access->where('name', 'khs_matakuliah-CanView')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'khs_matakuliah-CanViewDetail')->first()->id }}"> {{ $access->where('name', 'khs_matakuliah-CanViewDetail')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'khs_matakuliah-CanEditDetail')->first()->id }}"> {{ $access->where('name', 'khs_matakuliah-CanEditDetail')->first()->description }}</label></li>
                              </ul>
                            </li>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Nilai Per Mahasiswa</label>
                              <ul>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'khs_mahasiswa-CanView')->first()->id }}"> {{ $access->where('name', 'khs_mahasiswa-CanView')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'khs_mahasiswa-CanEdit')->first()->id }}"> {{ $access->where('name', 'khs_mahasiswa-CanEdit')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'khs_mahasiswa-CanExport')->first()->id }}"> {{ $access->where('name', 'khs_mahasiswa-CanExport')->first()->description }}</label></li>
                              </ul>
                            </li>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Nilai Mahasiswa Ekuivalensi</label>
                              <ul>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'transcript_equivalensi-CanView')->first()->id }}"> {{ $access->where('name', 'transcript_equivalensi-CanView')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'transcript_equivalensi-CanAdd')->first()->id }}"> {{ $access->where('name', 'transcript_equivalensi-CanAdd')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'transcript_equivalensi-CanEdit')->first()->id }}"> {{ $access->where('name', 'transcript_equivalensi-CanEdit')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'transcript_equivalensi-CanDelete')->first()->id }}"> {{ $access->where('name', 'transcript_equivalensi-CanDelete')->first()->description }}</label></li>
                              </ul>
                            </li>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Tugas Akhir</label>
                              <ul>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'tugas_akhir-CanView')->first()->id }}"> {{ $access->where('name', 'tugas_akhir-CanView')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'tugas_akhir-CanAdd')->first()->id }}"> {{ $access->where('name', 'tugas_akhir-CanAdd')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'tugas_akhir-CanEdit')->first()->id }}"> {{ $access->where('name', 'tugas_akhir-CanEdit')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'tugas_akhir-CanDelete')->first()->id }}"> {{ $access->where('name', 'tugas_akhir-CanDelete')->first()->description }}</label></li>
                              </ul>
                            </li>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Yudisium</label>
                              <ul>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'yudisium-CanView')->first()->id }}"> {{ $access->where('name', 'yudisium-CanView')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'create_yudisium-CanAdd')->first()->id }}"> {{ $access->where('name', 'create_yudisium-CanAdd')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'yudisium-CanDelete')->first()->id }}"> {{ $access->where('name', 'yudisium-CanDelete')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'yudisium-CanUpdateBeritaAcara')->first()->id }}"> {{ $access->where('name', 'yudisium-CanUpdateBeritaAcara')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'yudisium-CanUpdateSKL')->first()->id }}"> {{ $access->where('name', 'yudisium-CanUpdateSKL')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'yudisium-CanExport')->first()->id }}"> {{ $access->where('name', 'yudisium-CanExport')->first()->description }}</label></li>
                              </ul>
                            </li>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Periode Wisuda</label>
                              <ul>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'periodewisuda-CanView')->first()->id }}"> {{ $access->where('name', 'periodewisuda-CanView')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'periodewisuda-CanAdd')->first()->id }}"> {{ $access->where('name', 'periodewisuda-CanAdd')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'periodewisuda-CanEdit')->first()->id }}"> {{ $access->where('name', 'periodewisuda-CanEdit')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'periodewisuda-CanDelete')->first()->id }}"> {{ $access->where('name', 'periodewisuda-CanDelete')->first()->description }}</label></li>
                              </ul>
                            </li>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Wisuda</label>
                              <ul>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'wisuda-CanView')->first()->id }}"> {{ $access->where('name', 'wisuda-CanView')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'wisuda-CanAdd')->first()->id }}"> {{ $access->where('name', 'wisuda-CanAdd')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'wisuda-CanEdit')->first()->id }}"> {{ $access->where('name', 'wisuda-CanEdit')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'wisuda-CanDelete')->first()->id }}"> {{ $access->where('name', 'wisuda-CanDelete')->first()->description }}</label></li>
                              </ul>
                            </li>
                          </ul>
                        </li>
                        <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Cetak</label>
                          <ul>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Transkrip Sementara</label>
                              <ul>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'transcript_sementara-CanView')->first()->id }}"> {{ $access->where('name', 'transcript_sementara-CanView')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'transcript_sementara-CanExport')->first()->id }}"> {{ $access->where('name', 'transcript_sementara-CanExport')->first()->description }}</label></li>
                              </ul>
                            </li>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Transkrip Akhir</label>
                              <ul>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'transcript_akhir-CanView')->first()->id }}"> {{ $access->where('name', 'transcript_akhir-CanView')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'transcript_akhir-CanExport')->first()->id }}"> {{ $access->where('name', 'transcript_akhir-CanExport')->first()->description }}</label></li>
                              </ul>
                            </li>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Presensi Mahasiswa</label>
                              <ul>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'presensimhs-CanView')->first()->id }}"> {{ $access->where('name', 'presensimhs-CanView')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'presensimhs-CanExport')->first()->id }}"> {{ $access->where('name', 'transcript_akhir-CanExport')->first()->description }}</label></li>
                              </ul>
                            </li>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Jadwal dan Peserta Ujian</label>
                              <ul>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'jadwaldanpesertaujian-CanView')->first()->id }}"> {{ $access->where('name', 'jadwaldanpesertaujian-CanView')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'jadwaldanpesertaujian-CanExport')->first()->id }}"> {{ $access->where('name', 'jadwaldanpesertaujian-CanExport')->first()->description }}</label></li>
                              </ul>
                            </li>
                          </ul>
                        </li>
                        <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Laporan</label>
                          <ul>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Daftar Mahasiswa KRS</label>
                              <ul>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'laporan_daftar_mahasiswa_krs-CanView')->first()->id }}"> {{ $access->where('name', 'laporan_daftar_mahasiswa_krs-CanView')->first()->description }}</label></li>
                              </ul>
                            </li>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Histori Nilai Mahasiswa</label>
                              <ul>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'laporan_history_nilaimhs-CanView')->first()->id }}"> {{ $access->where('name', 'laporan_history_nilaimhs-CanView')->first()->description }}</label></li>
                              </ul>
                            </li>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Resume Mahasiswa Aktif KRS</label>
                              <ul>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'laporan_mhskrs-CanView')->first()->id }}"> {{ $access->where('name', 'laporan_mhskrs-CanView')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'laporan_mhskrs-CanExport')->first()->id }}"> {{ $access->where('name', 'laporan_mhskrs-CanExport')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="access[]" type="checkbox" value="{{ $access->where('name', 'showmhsnonaktif-CanViewnonaktif')->first()->id }}"> {{ $access->where('name', 'showmhsnonaktif-CanViewnonaktif')->first()->description }}</label></li>
                              </ul>
                            </li>
                          </ul>
                        </li>
                      </ul>
                    </li>


                    <li>
                      <i class="fa fa-plus"></i> <label> <input id="node-0" data-id="custom-0" type="checkbox"> KEUANGAN</label>
                      <ul>
                        <li>
                          <i class="fa fa-plus"></i> <label> <input id="node-0" data-id="custom-0" type="checkbox"> Master</label>
                          <ul>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Item Biaya</label>
                              <ul>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Item Biaya-CanView')->first()->id }}"> {{ $accesskeu->where('name', 'Item Biaya-CanView')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Item Biaya-CanAdd')->first()->id }}"> {{ $accesskeu->where('name', 'Item Biaya-CanAdd')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Item Biaya-CanEdit')->first()->id }}"> {{ $accesskeu->where('name', 'Item Biaya-CanEdit')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Item Biaya-CanDelete')->first()->id }}"> {{ $accesskeu->where('name', 'Item Biaya-CanDelete')->first()->description }}</label></li>
                              </ul>
                            </li>
                          </ul>
                        </li>
                        <li>
                          <i class="fa fa-plus"></i> <label> <input id="node-0" data-id="custom-0" type="checkbox"> Biaya Mata Kuliah</label>
                          <ul>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Set Jenis Matakuliah</label>
                              <ul>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Set Jenis Mata Kuliah-CanView')->first()->id }}"> {{ $accesskeu->where('name', 'Set Jenis Mata Kuliah-CanView')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Set Jenis Mata Kuliah-CanAdd')->first()->id }}"> {{ $accesskeu->where('name', 'Set Jenis Mata Kuliah-CanAdd')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Set Jenis Mata Kuliah-CanDelete')->first()->id }}"> {{ $accesskeu->where('name', 'Set Jenis Mata Kuliah-CanDelete')->first()->description }}</label></li>
                              </ul>
                            </li>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Biaya Per SKS</label>
                              <ul>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Biaya Per Sks-CanView')->first()->id }}"> {{ $accesskeu->where('name', 'Biaya Per Sks-CanView')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Biaya Per Sks-CanAdd')->first()->id }}"> {{ $accesskeu->where('name', 'Biaya Per Sks-CanAdd')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Biaya Per Sks-CanEdit')->first()->id }}"> {{ $accesskeu->where('name', 'Biaya Per Sks-CanEdit')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Biaya Per Sks-CanDelete')->first()->id }}"> {{ $accesskeu->where('name', 'Biaya Per Sks-CanDelete')->first()->description }}</label></li>
                              </ul>
                            </li>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Biaya Per Paket</label>
                              <ul>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Biaya Per Paket-CanView')->first()->id }}"> {{ $accesskeu->where('name', 'Biaya Per Paket-CanView')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Biaya Per Paket-CanAdd')->first()->id }}"> {{ $accesskeu->where('name', 'Biaya Per Paket-CanAdd')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Biaya Per Paket-CanEdit')->first()->id }}"> {{ $accesskeu->where('name', 'Biaya Per Paket-CanEdit')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Biaya Per Paket-CanDelete')->first()->id }}"> {{ $accesskeu->where('name', 'Biaya Per Paket-CanDelete')->first()->description }}</label></li>
                              </ul>
                            </li>
                          </ul>
                        </li>
                        <li>
                          <i class="fa fa-plus"></i> <label> <input id="node-0" data-id="custom-0" type="checkbox"> Biaya Registrasi</label>
                          <ul>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Set Biaya Registrasi</label>
                              <ul>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Set Biaya Registrasi-CanView')->first()->id }}"> {{ $accesskeu->where('name', 'Set Biaya Registrasi-CanView')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Set Biaya Registrasi-CanAdd')->first()->id }}"> {{ $accesskeu->where('name', 'Set Biaya Registrasi-CanAdd')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Set Biaya Registrasi-CanEdit')->first()->id }}"> {{ $accesskeu->where('name', 'Set Biaya Registrasi-CanEdit')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Set Biaya Registrasi-CanDelete')->first()->id }}"> {{ $accesskeu->where('name', 'Set Biaya Registrasi-CanDelete')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Set Biaya Registrasi-CanHitungTagihan')->first()->id }}"> {{ $accesskeu->where('name', 'Set Biaya Registrasi-CanHitungTagihan')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Set Biaya Registrasi-CanCopyData')->first()->id }}"> {{ $accesskeu->where('name', 'Set Biaya Registrasi-CanCopyData')->first()->description }}</label></li>
                              </ul>
                            </li>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Set Biaya Registrasi(Resume)</label>
                              <ul>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Set Biaya Registrasi Resume-CanView')->first()->id }}"> {{ $accesskeu->where('name', 'Set Biaya Registrasi Resume-CanView')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Set Biaya Registrasi Resume-CanAdd')->first()->id }}"> {{ $accesskeu->where('name', 'Set Biaya Registrasi Resume-CanAdd')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Set Biaya Registrasi Resume-CanEdit')->first()->id }}"> {{ $accesskeu->where('name', 'Set Biaya Registrasi Resume-CanEdit')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Set Biaya Registrasi Resume-CanDelete')->first()->id }}"> {{ $accesskeu->where('name', 'Set Biaya Registrasi Resume-CanDelete')->first()->description }}</label></li>
                              </ul>
                            </li>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Set Biaya Tambahan</label>
                              <ul>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Set Biaya Tambahan-CanView')->first()->id }}"> {{ $accesskeu->where('name', 'Set Biaya Tambahan-CanView')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Set Biaya Tambahan-CanAdd')->first()->id }}"> {{ $accesskeu->where('name', 'Set Biaya Tambahan-CanAdd')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Set Biaya Tambahan-CanEdit')->first()->id }}"> {{ $accesskeu->where('name', 'Set Biaya Tambahan-CanEdit')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Set Biaya Tambahan-CanDelete')->first()->id }}"> {{ $accesskeu->where('name', 'Set Biaya Tambahan-CanDelete')->first()->description }}</label></li>
                              </ul>
                            </li>
                          </ul>
                        </li>
                        <li>
                          <i class="fa fa-plus"></i> <label> <input id="node-0" data-id="custom-0" type="checkbox"> Pembayaran Mahasiswa</label>
                          <ul>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Set Biaya Registrasi Personal</label>
                              <ul>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Set Biaya Registrasi Personal-CanView')->first()->id }}"> {{ $accesskeu->where('name', 'Set Biaya Registrasi Personal-CanView')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Set Biaya Registrasi Personal-CanAdd')->first()->id }}"> {{ $accesskeu->where('name', 'Set Biaya Registrasi Personal-CanAdd')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Set Biaya Registrasi Personal-CanEdit')->first()->id }}"> {{ $accesskeu->where('name', 'Set Biaya Registrasi Personal-CanEdit')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Set Biaya Registrasi Personal-CanDelete')->first()->id }}"> {{ $accesskeu->where('name', 'Set Biaya Registrasi Personal-CanDelete')->first()->description }}</label></li>
                              </ul>
                            </li>
                          </ul>
                        </li>
                        <li>
                          <i class="fa fa-plus"></i> <label> <input id="node-0" data-id="custom-0" type="checkbox"> Retur</label>
                          <ul>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Set Aturan Pengembalian/Return</label>
                              <ul>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Set Aturan Pengembalian-CanView')->first()->id }}"> {{ $accesskeu->where('name', 'Set Aturan Pengembalian-CanView')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Set Aturan Pengembalian-CanCetak')->first()->id }}"> {{ $accesskeu->where('name', 'Set Aturan Pengembalian-CanCetak')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Set Aturan Pengembalian-CanEdit')->first()->id }}"> {{ $accesskeu->where('name', 'Set Aturan Pengembalian-CanEdit')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Set Aturan Pengembalian-CanDelete')->first()->id }}"> {{ $accesskeu->where('name', 'Set Aturan Pengembalian-CanDelete')->first()->description }}</label></li>
                              </ul>
                            </li>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Entry Pengembalian/Return</label>
                              <ul>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Set Aturan Pengembalian-CanAdd')->first()->id }}"> {{ $accesskeu->where('name', 'Set Aturan Pengembalian-CanAdd')->first()->description }}</label></li>
                              </ul>
                            </li>
                          </ul>
                        </li>
                        <li>
                          <i class="fa fa-plus"></i> <label> <input id="node-0" data-id="custom-0" type="checkbox"> Teller</label>
                          <ul>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Entry Pembayaran Mahasiswa</label>
                              <ul>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Entry Pembayaran Mahasiswa-CanView')->first()->id }}"> {{ $accesskeu->where('name', 'Entry Pembayaran Mahasiswa-CanView')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Entry Pembayaran Mahasiswa-CanViewDetail')->first()->id }}"> {{ $accesskeu->where('name', 'Entry Pembayaran Mahasiswa-CanViewDetail')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Entry Pembayaran Mahasiswa-CanEditTagihan')->first()->id }}"> {{ $accesskeu->where('name', 'Entry Pembayaran Mahasiswa-CanEditTagihan')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Entry Pembayaran Mahasiswa-CanBayaTagihan')->first()->id }}"> {{ $accesskeu->where('name', 'Entry Pembayaran Mahasiswa-CanBayaTagihan')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Entry Pembayaran Mahasiswa-CanPrintTagihan')->first()->id }}"> {{ $accesskeu->where('name', 'Entry Pembayaran Mahasiswa-CanPrintTagihan')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Entry Pembayaran Mahasiswa-CanBatalTagihan')->first()->id }}"> {{ $accesskeu->where('name', 'Entry Pembayaran Mahasiswa-CanBatalTagihan')->first()->description }}</label></li>
                                <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Riwayat Pembayaran Details-CanView')->first()->id }}"> {{ $accesskeu->where('name', 'Riwayat Pembayaran Details-CanView')->first()->description }}</label></li>
                              </ul>
                            </li>
                            <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Laporan</label>
                              <ul>
                                <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Laporan Pembayaran Prodi</label>
                                  <ul>
                                    <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Laporan Prodi-CanView')->first()->id }}"> {{ $accesskeu->where('name', 'Laporan Prodi-CanView')->first()->description }}</label></li>
                                  </ul>
                                </li>
                                <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Laporan Pembayaran Mahasiswa</label>
                                  <ul>
                                    <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Laporan Mahasiswa-CanView')->first()->id }}"> {{ $accesskeu->where('name', 'Laporan Mahasiswa-CanView')->first()->description }}</label></li>
                                  </ul>
                                </li>
                                <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Laporan Pembayaran Per Bank</label>
                                  <ul>
                                    <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Laporan Bank-CanView')->first()->id }}"> {{ $accesskeu->where('name', 'Laporan Bank-CanView')->first()->description }}</label></li>
                                  </ul>
                                </li>
                                <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox">Riwayat Pembayaran Mahasiswa</label>
                                  <ul>
                                    <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Riwayat Pembayaran Mahasiswa-CanView')->first()->id }}"> {{ $accesskeu->where('name', 'Riwayat Pembayaran Mahasiswa-CanView')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Riwayat Pembayaran Details Create-CanView')->first()->id }}"> {{ $accesskeu->where('name', 'Riwayat Pembayaran Details Create-CanView')->first()->description }}</label></li>
                                  </ul>
                                </li>
                                <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox"> Pembayaran Mahasiswa Per Item</label>
                                  <ul>
                                    <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Pembayaran Mahasiswa Item-CanView')->first()->id }}"> {{ $accesskeu->where('name', 'Pembayaran Mahasiswa Item-CanView')->first()->description }}</label></li>
                                  </ul>
                                </li>
                                <li><i class="fa fa-plus"></i> <label> <input id="node-0-1" data-id="custom-1" type="checkbox"> Laporan Tunggakan Mahasiswa</label>
                                  <ul>
                                    <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Laporan Tunggakan Mahasiswa-CanView')->first()->id }}"> {{ $accesskeu->where('name', 'Laporan Tunggakan Mahasiswa-CanView')->first()->description }}</label></li>
                                    <li><i></i> <label> <input name="accesskeu[]" type="checkbox" value="{{ $accesskeu->where('name', 'Laporan Tunggakan Mahasiswa-CanViewDetail')->first()->id }}"> {{ $accesskeu->where('name', 'Laporan Tunggakan Mahasiswa-CanViewDetail')->first()->description }}</label></li>
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
          {{-- <div class=" col-md-12 col-xs-12" style="padding:2%;">
            <div class="table-responsive">
            <table class="table table-striped table-font-sm">
                <thead class="thead-default thead-green">
                    <tr>
                        <th width="85%">SIMAKAD</th>
                        <th width="15%"><input type="checkbox" id="allsimak" /></th>
                    </tr>
                </thead>
                <tbody>
                  @foreach($access as $acc)
                    <tr>
                      <td>{{ $acc->description }}</td>
                      <td><center><input class="rolesimak" type="checkbox" name="access[]" value="{{ $acc->id }}"><center></td>
                    </tr>
                  @endforeach
                </tbody>
            </table>
            </div>
            <div class="table-responsive">
            <table class="table table-striped table-font-sm">
                <thead class="thead-default thead-green">
                    <tr>
                        <th width="85%">KEUANGAN</th>
                        <th width="15%"><input type="checkbox" id="allkeu" /></th>
                    </tr>
                </thead>
                <tbody>
                  @foreach($accesskeu as $acck)
                    <tr>
                      <td>{{ $acck->description }}</td>
                      <td><center><input class="rolekeu" type="checkbox" name="accesskeu[]" value="{{ $acck->id }}"><center></td>
                    </tr>
                  @endforeach
                </tbody>
            </table>
            </div>

          </div>
          <br><center><button type="submit" class="btn btn-primary btn-flat">Tambah</button></center> --}}

          {!! Form::close() !!}
      </div>
    </div>
  </div>


  <script type="text/javascript">
    $(document).ready(function () {
      $('#allsimak').click(function () {
          $('.rolesimak').prop("checked", this.checked);
      });
    });
    $(document).ready(function () {
      $('#allkeu').click(function () {
          $('.rolekeu').prop("checked", this.checked);
      });
    });
  </script>

  <script>
$("#treeview").hummingbird();
</script>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-36251023-1']);
  _gaq.push(['_setDomainName', 'jqueryscript.net']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</section>

@endsection
