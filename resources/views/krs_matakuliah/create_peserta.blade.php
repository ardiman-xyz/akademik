@extends('layouts._layout')
@section('pageTitle', 'KRS')
@section('content')

<?php


// $url = 'http://10.64.21.72:8080/webservice/rest/simpleserver.php?wsusername=a&wspassword=a';
// $xmlll = simplexml_load_file($url) or die("feed not loading");
// // $xml=simplexml_load_string($xmlll) or die("ErrorCannot create object");
// print_r($xmlll);
?>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">KRS Per Matakuliah</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('proses/krs_matakuliah/'.$Offered_Course_id.'?class_program='.$class_program.'&term_year='.$term_year.'&department='.$department.'&current_search='.$currentsearch.'&current_page='.$currentpage.'&curren_trowpage='.$currentrowpage) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
        </div>
        <b>Tambah</b>
      </div>
    </div>
    <br>
        <div class="bootstrap-admin-box-title right text-green">
          <!-- <b>Daftar Fakultas</b> -->
          {!! Form::open(['url' => route('krs_matakuliah.create') , 'method' => 'GET', 'name' => 'form', 'role' => 'form', 'style' => 'margin:2%;']) !!}
          <div class="row">
            <input type="hidden" name="id" value="{{ $Offered_Course_id }}">
            <input type="hidden" name="department" value="{{ $department }}">
            <input type="hidden" name="term_year" value="{{ $term_year }}">
            <input type="hidden" name="class_program" value="{{ $class_program }}">
            <input type="hidden" name="currentpage" value="{{ $currentpage }}">
            <input type="hidden" name="currentsearch" value="{{ $currentsearch }}">
            <input type="hidden" name="currentrowpage" value="{{ $currentrowpage }}">

            <!-- <label class="col-md-2" >Department :</label> -->
            <select class="form-control form-control-sm col-md-4" name="entry_year" onchange="document.form.submit();">
              <option value="0">Pilih Angkatan</option>
              @foreach ( $select_entry_year as $data )
                <option <?php if($Entry_Year_Id == $data->Entry_Year_Id){ echo "selected"; } ?>  value="{{ $data->Entry_Year_Id }}">{{ $data->Entry_Year_Code }}</option>
              @endforeach
            </select>&nbsp
          </div>

          {!! Form::close() !!}
        </div>
      {!! Form::open(['url' => route('krs_matakuliah.store') , 'method' => 'POST', 'role' => 'form']) !!}
      <input type="hidden" name="Offered_Course_id" value="{{ $Offered_Course->Offered_Course_id  }}">
      <input type="hidden" name="Term_Year_Id" value="{{ $Offered_Course->Term_Year_Id  }}">
      <input type="hidden" name="Department_Id" value="{{ $Offered_Course->Department_Id  }}">
      <input type="hidden" name="Entry_Year_Id" value="{{ $entry_year  }}">
      <input type="hidden" name="Course_Id" value="{{ $Offered_Course->Course_Id  }}">
      <input type="hidden" name="Class_Prog_Id" value="{{ $Offered_Course->Class_Prog_Id  }}">
      <input type="hidden" name="Class_Id" value="{{ $Offered_Course->Class_Id  }}">

      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        <div class="row">
          <div class="col-sm-6">
            <div >
              <label  class="col-sm-5">Th Akademik/Semester <font style="float:right;">:</font></label>
              <label  class="col-sm-5">{{ $Offered_Course->Term_Year_Name }}</label>
            </div>
            <div>
              <label  class="col-sm-5">Program Studi <font style="float:right;">:</font></label>
              <label  class="col-sm-5">{{ $Offered_Course->Department_Name }}</label>
            </div>
            <div>
              <label  class="col-sm-5">Kelas Program <font style="float:right;">:</font></label>
              <label  class="col-sm-5">{{ $Offered_Course->Class_Program_Name }}</label>
            </div>
            <div>
              <label  class="col-sm-5">Matakuliah <font style="float:right;">:</font></label>
              <label  class="col-sm-5">{{ $Offered_Course->Course_Name }}</label>
            </div>
            <div>
              <label  class="col-sm-5">Kelas <font style="float:right;">:</font></label>
              <label  class="col-sm-5">{{ $Offered_Course->Class_Name }}</label>
            </div>
          </div>
          <div class="col-sm-6">
            <div>
              <label  class="col-sm-5">Kapasitas <font style="float:right;">:</font></label>
              <label  class="col-sm-5">{{ $Offered_Course->Class_Capacity }}</label>
            </div>
            <div>
              <label  class="col-sm-5">Terdaftar <font style="float:right;">:</font></label>
              <label  class="col-sm-5">{{ $Offered_Course->jml_peserta }}</label>
            </div>
            <div>
              <label  class="col-sm-5">Sisa <font style="float:right;">:</font></label>
              <label  class="col-sm-5">{{ $Offered_Course->Class_Capacity - $Offered_Course->jml_peserta }}</label>
            </div>
            <div>
              <label  class="col-sm-5">Biaya <font style="float:right;">:</font></label>
              <label  class="col-sm-5">
                @if($getofferedcoursekrs != null)
                @foreach($getofferedcoursekrs as $getoffkrs)
                  <input type="text" name="biaya" hidden value="{{ $getoffkrs->amount }}">
                {{ $getoffkrs->amount }}
                @endforeach
                @endif
              </label>
            </div>
            <div>
              <label  class="col-sm-5">SKS <font style="float:right;">:</font></label>
              <label  class="col-sm-5">
                @if($getofferedcoursekrs != null)
                @foreach($getofferedcoursekrs as $getoffkrs)
                 <input type="text" name="krsnya" hidden value="{{ $getoffkrs->applied_sks }}">
                {{ $getoffkrs->applied_sks }}
                @endforeach
                @endif
              </label>
            </div>
          </div>
        </div>
        @if(Session::get('success') == true)
            @if (count($errors) > 0)
              @foreach ( $errors->all() as $error )
                <p class="alert alert-success">{{ $error }}</p>
              @endforeach
            @endif
        @else
          @if (count($errors) > 0)
            @foreach ( $errors->all() as $error )
              <p class="alert alert-danger">{{ $error }}</p>
            @endforeach
          @endif
        @endif
        <input type="hidden" name="Offered_Course_id" value="{{ $Offered_Course_id }}">
        <div class="table-responsive">
        <table class="table table-striped table-font-sm">
          <thead class="thead-default thead-green">
              <tr>
                  <th class="1%">No</th>
                  <th width="10%"><input type="checkbox" id="all" /></th>
                  <th width="20%">NIM</th>
                  <th width="40%">Nama Mahasiswa</th>
                  <th width="20%">Kelas Program</th>
                  <th width="20%">Agama</th>
              </tr>
          </thead>
          <tbody>
            <?php
            $a = "1";
            foreach ($query as $data) {
            ?>
            <tr>
              <td>{{ $a }}</td>
              <td><center><input type="checkbox" class="student" name="Student_Id[]" value="{{ $data->Student_Id }}" /></center></td>
              <td><center>{{ $data->Nim }}</center></td>
              <td>{{ $data->Full_Name }}</td>
              <td>{{ $data->Class_Program_Name }}</td>
              <td>{{ $data->Religion_Name }}</td>
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
