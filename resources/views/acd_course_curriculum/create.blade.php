@extends('layouts._layout')
@section('pageTitle', 'Course Curriculum')
@section('content')

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Tambah Matakuliah Kurikulum</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a  href="{{ url('parameter/course_curriculum?department='.$department.'&class_program='.$class_program.'&curriculum='.$curriculum.'&semester='.$semester.'&page='.$current_page.'&rowpage='.$current_rowpage.'&search='.$current_search) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Tambah {{ $departmentname }} semester {{ $semester }}</b>
        </div>
      </div>
          <!-- <b>Daftar Fakultas</b> -->
          {!! Form::open(['url' => route('course_curriculum.create') , 'method' => 'GET', 'name' => 'form', 'role' => 'form', 'style' => 'margin:2%;']) !!}
          <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            <input type="hidden" name="department" value="{{ $department }}">
            <input type="hidden" name="curriculum" value="{{ $curriculum }}">
            <input type="hidden" name="semester" value="{{ $semester }}">
            <input type="hidden" name="class_program" value="{{ $class_program }}">

            <input type="hidden" name="current_page" value="{{ $current_page }}">
            <input type="hidden" name="current_rowpage" value="{{ $current_rowpage }}">
            <input type="hidden" name="current_search" value="{{ $current_search }}">

            <label>Pencarian:&nbsp</label>
            <input type="text" name="search"  class="form-control form-control-sm col-md-4" value="{{ $search }}" placeholder="Search">&nbsp&nbsp
            <label >Baris Per halaman :&nbsp</label>
            <input type="number" name="rowpage"  class="form-control form-control-sm col-md-4" value="{{ $rowpage }}" placeholder="Baris Per halaman">
            &nbsp
            <input type="submit" name="" style="float:right;" class="btn btn-primary btn-sm" value="Submit">

          </div>
          {!! Form::close() !!}
        </div>
      {!! Form::open(['url' => route('course_curriculum.store') , 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form']) !!}
      <input type="hidden" name="Class_Prog_Id" min="1" value="{{ $class_program }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control">
      <input type="hidden" name="Department_Id" min="1" value="{{ $department }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control">
      <input type="hidden" name="Curriculum_Id" min="1" value="{{ $curriculum }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control">
      <input type="hidden" name="Study_Level_Id" min="1" value="{{ $semester }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control">
      <input type="hidden" name="cc" value="{{ $ccc }}">

      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        @if (count($errors) > 0)
          @foreach ( $errors->all() as $error )
            @if ($error=="Berhasil Menambah Matakuliah Kurikulum")
              <p class="alert alert-success">{{ $error }}</p>
            @else
              <p class="alert alert-danger">{{ $error }}</p>
            @endif
          @endforeach
        @endif
        @if ($notif != null)
            <p class="alert alert-danger">{{ $notif }}</p>
        @endif
        <div class="table-responsive">
        <table class="table table-striped table-font-sm">
          <thead class="thead-default thead-green">
              <tr>
                  <!-- <th class="col-sm-1">No</th> -->
                  <th width="10%">Pilih</th>
                  <th width="45%">Kode Matakuliah</th>
                  <th width="45%">Nama Matakuliah</th>
              </tr>
          </thead>
          <tbody>
            <?php
            $a = "1";
            foreach ($course as $data) {

              ?>
              <tr>

                  <td>
                    <center><input type="checkbox" name="Course_Id[]" value="{{ $data->Course_Id }}"></center>
                  </td>
                  <td>{{ $data->Course_Code }}</td>
                  <td>{{ $data->Course_Name }}</td>
              </tr>
              <?php
              $a++;
            }
            ?>
          </tbody>
        </table>
        <br>
        <div align="center">
          <button type="submit" class="btn btn-primary btn-flat">Tambah</button><br>
        </div>
        </div>
        <?php echo $course->render('vendor.pagination.bootstrap-4'); ?>
      </div>
      {!! Form::close() !!}
    </div>
  </div>

</section>
@endsection
