@extends('layouts._layout')
@section('pageTitle', 'KRS')
@section('content')

<?php


// $url = 'http://10.64.21.72:8080/webservice/rest/simpleserver.php?wsusername=a&wspassword=a';
// $xmlll = simplexml_load_file($url) or die("feed not loading");
// // $xml=simplexml_load_string($xmlll) or die("ErrorCannot create object");
// print_r($xmlll);
?>

<style>
tr:hover td{ 
   background-color: #ccc;
}
</style>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">KRS Per Paket</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('proses/krs_paket?class_program='.$class_program.'&term_year='.$term_year.'&department='.$department.'&curriculum='.$curriculum) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
        </div>
        <b>Tambah</b>
      </div>
    </div>
        <div class="bootstrap-admin-box-title right text-green">
         @if (count($errors) > 0)
            @foreach ( $errors->all() as $error )
                <p class="alert alert-danger">{{ $error }}</p>
            @endforeach
          @endif
          <!-- <b>Daftar Fakultas</b> -->
          {!! Form::open(['url' => route('krs_paket.create') , 'method' => 'GET', 'name' => 'form', 'role' => 'form', 'style' => 'margin:2%;']) !!}
          <div class="row">
            <input type="hidden" name="id" value="{{ $Offered_Course_id }}">
            <input type="hidden" name="department" value="{{ $department }}">
            <input type="hidden" name="term_year" value="{{ $term_year }}">
            <input type="hidden" name="class_program" value="{{ $class_program }}">
          </div>
          {!! Form::close() !!}
        </div>
      {!! Form::open(['url' => route('krs_paket.store') , 'method' => 'POST', 'role' => 'form']) !!}
        <input type="hidden" name="class_program" value="{{ $class_program  }}">
        <input type="hidden" name="department" value="{{ $department  }}">
        <input type="hidden" name="term_year" value="{{ $term_year  }}">
        <input type="hidden" name="curriculum" value="{{ $curriculum  }}">
        <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
		        <div  class="row col-md-7">
		        <label class="col-md-3">Paket :</label>
		        <select class="form-control form-control-sm col-md-4" name="paket">
                <option value="0">Pilih Paket</option>
                  <option value="1">Paket 1</option>
                  <option value="2">Paket 2</option>
                  <option value="3">Paket 3</option>
                  <option value="4">Paket 4</option>
                  <option value="5">Paket 5</option>
              </select>
		        </div>
         </div>
         <br>
        <div class="table-responsive">
        <table class="table table-striped table-font-sm">
          <thead class="thead-default thead-green">
              <tr>
                  <!-- <th class="col-sm-1">No</th> -->
                  <th width="10%"><input type="checkbox" id="all" /></th>
                  <th width="20%">Kode</th>
                  <th width="40%">Nama Matakuliah</th>
                  <th width="10%">Semester</th>
              </tr>
          </thead>
          <tbody>
            <?php
            $a = "1";
            foreach ($data_course as $data) {
            ?>
            <tr>
              {{-- <td>{{ $a }}</td> --}}
              <td><center><input type="checkbox" class="student" name="offered_course[]" value="{{ $data->Course_Id }}" /></center></td>
              <td><center>{{ $data->Course_Code }}</center></td>
              <td>{{ $data->Course_Name }}</td>
              <td>{{ $data->Study_Level_Id }}</td>
            </tr>
            <?php
            $a++;
            }
            ?>
          </tbody>
        </table>
      </div>
      <div align="center">
        <input type="submit" name="" value="Simpan" class="btn btn-primary">
      </div>
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
