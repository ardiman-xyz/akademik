@extends('layouts._layout')
@section('pageTitle', 'Offered Course Exam')
@section('content')

<?php


// $url = 'http://10.64.21.72:8080/webservice/rest/simpleserver.php?wsusername=a&wspassword=a';
// $xmlll = simplexml_load_file($url) or die("feed not loading");
// // $xml=simplexml_load_string($xmlll) or die("Error: Cannot create object");
// print_r($xmlll);
?>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Jadwal dan Peserta Ujian</h3>
    </div>
  </div>

  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('setting/offered_course_exam/'.$Offered_Course_Exam_Id.'/peserta?class_program='.$class_program.'&term_year='.$term_year.'&department='.$department.'&currentsearch='.$currentsearch.'&currentpage='.$currentpage.'&currentrowpage='.$currentrowpage) }}" class="btn btn-danger" style="font-size:medium">Kembali &nbsp;<i class="fa fa-reply"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Daftar Peserta</b>
        </div>
      </div>

      <div class="panel-heading">
        <div class="bootstrap-admin-box-title right text-purple">
          <!-- <b>Daftar Fakultas</b> -->
          {!! Form::open(['url' => route('offered_course_exam.create_peserta') , 'method' => 'GET', 'name' => 'form', 'role' => 'form', 'style' => 'margin:2%;']) !!}
          <div class="row">
            <input type="hidden" name="id" value="{{ $Offered_Course_Exam_Id }}">
            <input type="hidden" name="offered_course_id" value="{{ $offered_course_id }}">
            <input type="hidden" name="department" value="{{ $department }}">
            <input type="hidden" name="term_year" value="{{ $term_year }}">
            <input type="hidden" name="class_program" value="{{ $class_program }}">
            <input type="hidden" name="currentpage" value="{{ $currentpage }}">
            <input type="hidden" name="currentsearch" value="{{ $currentsearch }}">
            <input type="hidden" name="currentrowpage" value="{{ $currentrowpage }}">



            <!-- <label class="col-md-2" >Department :</label> -->
            <select class="form-control col-md-4" name="entry_year" onchange="document.form.submit();">
              <option value="0">Pilih Angkatan</option>
              @foreach ( $select_entry_year as $data )
                <option <?php if($Entry_Year_Id == $data->Entry_Year_Id){ echo "selected"; } ?>  value="{{ $data->Entry_Year_Id }}">{{ $data->Entry_Year_Code }}</option>
              @endforeach
            </select>&nbsp


          </div>

          {!! Form::close() !!}
        </div>
      </div>
      {!! Form::open(['url' => route('offered_course_exam.store_peserta') , 'method' => 'POST', 'role' => 'form']) !!}
      <input type="hidden" name="Offered_Course_Exam_Id" value="{{ $Offered_Course_Exam_Id  }}">
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        @if (count($errors) > 0)
          @foreach ( $errors->all() as $error )
            @if ($error=="Berhasil Menambah Peserta Ujian")
              <p class="alert alert-success">{{ $error }}</p>
            @else
              <p class="alert alert-danger">{{ $error }}</p>
            @endif
          @endforeach
        @endif
        <input type="hidden" name="Offered_Course_Exam_Id" value="{{ $Offered_Course_Exam_Id }}">
        <div class="table-responsive">
        <table class="table table-striped table-font-sm">
          <thead class="thead-default thead-purple">
              <tr>
                  <!-- <th class="col-sm-1">No</th> -->
                  <th width="10%"><input type="checkbox" id="all" /></th>
                  <th width="40%">NIM</th>
                  <th width="50%">Nama Mahasiswa</th>
              </tr>
          </thead>
          <tbody>
            <?php
            foreach ($query as $data) {
            ?>
            <tr>
              <td><center><input type="checkbox" class="student" name="Student_Id[]" value="{{ $data->Student_Id }}" /></center></td>
              <td><center>{{ $data->Nim }}</center></td>
              <td>{{ $data->Full_Name }}</td>
            </tr>
            <?php
            }
            ?>
          </tbody>
        </table>
      </div>
      <input type="submit" name="" value="Simpan" class="btn btn-primary">
    </div>
    {!! Form::close() !!}
  </div>
  <script type="text/javascript">
    $(document).ready(function () {
      $('#all').click(function () {
          $('.student').prop("checked", this.checked);
      });
    });
  </script>

</section>
@endsection
