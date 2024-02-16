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
          <a href="{{ url('proses/krs_paket/'.$paket.'?class_program='.$class_program.'&term_year='.$term_year.'&department='.$department.'&curriculum='.$curriculum) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
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
        </div>
            {!! Form::open(['url' => route('krs_paket.create_datapeserta',$paket) , 'method' => 'GET', 'name' => 'form', 'role' => 'form', 'style' => 'margin:2%;']) !!}
            <input type="text" name="department" value="{{$department}}" hidden >
            <input type="text" name="class_program" value="{{$class_program}}" hidden >
            <input type="text" name="term_year" value="{{$term_year}}" hidden >
            <input type="text" name="curriculum" value="{{$curriculum}}" hidden >
        <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            
            <div  class="row col-md-10">
              <label class="col-md-4">Matakuliah</label>                       
            </div>
              <?php
                  $a = "1";
                  foreach ($data_offered_course as $data) {
              ?>
              <div  class="row col-md-10">
                <label  class="col-md-4"> </label>
                <input type="text" name="" id=""  value="{{ $data->Course_Name }}" readonly class="form-control form-control-sm col-md-7">
              </div>
              <div  class="row col-md-10">
                <label  class="col-md-4"> </label>
                <input type="text" hidden name="course_id[]" id=""  value="{{ $data->Course_Id }}" readonly class="form-control form-control-sm col-md-7">
              </div>
              <?php
                $a++;
                  }
              ?>
              <br>
            <div  class="row col-md-10">
              <label class="col-md-4">Tahun Angkatan</label>
              <select class="form-control form-control-sm col-md-4" name="entry_year" onchange="document.form.submit();">
                <option value="">Pilih Angkatan</option>
                @foreach ( $select_entry_year as $data )
                  <option <?php if($entry_year == $data->Entry_Year_Id){ echo "selected"; } ?>  value="{{ $data->Entry_Year_Id }}">{{ $data->Entry_Year_Code }}</option>
                @endforeach
              </select>&nbsp      
            </div>
          {!! Form::close() !!}
      <div class="table-responsive">
      <br>
      <div  class="row col-md-10">
        <label class="col-md-4">Mahasiswa</label>                       
      </div>
        <table class="table table-striped table-font-sm">
          <thead class="thead-default thead-green">
              <tr>
                  <!-- <th class="col-sm-1">No</th> -->
                  <th width="10%"><input type="checkbox" id="all" /></th>
                  <th width="40%">NIM</th>
                  <th width="50%">Nama Mahasiswa</th>
              </tr>
          </thead>
          <tbody>
            <?php
            $a = "1";
            foreach ($data_mhs as $data) {
            ?>
            <tr>
              <td><center><input type="checkbox" class="student" onchange="cek_cekbox({{ $data->Student_Id }});" name="Student_Id" id="Student_Id" value="{{ $data->Student_Id }}" /></center></td>
              <td><center>{{ $data->Nim }}</center></td>
              <td>{{ $data->Full_Name }}</td>
            </tr>
            <?php
            $a++;
            }
            ?>
          </tbody>
        </table>
      </div>

      <div class="table-responsive">
      <div  class="row col-md-10">
        <label class="col-md-4">Kelas</label>                       
      </div>
        <table class="table table-striped table-font-sm">
          <thead class="thead-default thead-green">
              <tr>
                  <!-- <th class="col-sm-1">No</th> -->
                  <th width="10%"</th>
                  <th width="15%">Kode MK</th>
                  <th width="25%">Nama Matakuliah</th>
                  <th width="5%">Kelas</th>
                  <th width="10%">Kapasitas</th>
                  <th width="10%">Peserta</th>
                  <th width="10%">Sisa Kuota</th>
              </tr>
          </thead>
          <tbody>
            <?php
            $a = "1";
            foreach ($data_kelas as $data) {
            ?>
            <tr>
              <td><center><input type="checkbox" class="off_id" onchange="cek_cekbox2({{ $data->Offered_Course_id }});" name="Offered_Course_id" value="{{ $data->Offered_Course_id }}" /></center></td>
              <td>{{ $data->Course_Code }}</td>
              <td>{{ $data->Course_Name }}</td>
              <td>{{ $data->Class_Name }}</td>
              <td>{{ $data->Class_Capacity }}</td>
              <td <?php if($data->jml_peserta == 0){ echo "style='color:#f78383;'"; } ?> >{{ $data->jml_peserta }}</td>
              <td>{{ $data->Class_Capacity - $data->jml_peserta }}</td>
            </tr>
            <?php
            $a++;
            }
            ?>
          </tbody>
        </table>
      </div>
      <div class="form-group">
                    <div class="col-md-8 offset-md-5">
                      <a class="btn btn-success btn-sm" id="insertpeserta"  href="javascript:">Tambah Peserta</a>
                    </div>
                </div>
  </div>
  <script type="text/javascript">
    $(document).ready(function () {
      $('#all').click(function () {
          $('.student').prop("checked", this.checked);
      });
    });

    function cek_cekbox(studentid){
      // alert(studentid);
    }
    function cek_cekbox2(Offered_Course_id){
      // alert(Offered_Course_id);
    }

    $("#insertpeserta").click(function(e){
      var department = $("[name='department']").val(),
          class_program = $("[name='class_program']").val(),
          term_year = $("[name='term_year']").val(),
          curriculum = $("[name='curriculum']").val(),
          course_id = $("[name='course_id']").val(),
          entry_year = $("[name='entry_year']").val();
      var Student_Id = [];
          $("input[name='Student_Id']:checked").each(function() {
            Student_Id.push($(this).val());
          });
      var Offered_Course_id = [];
          $("input[name='Offered_Course_id']:checked").each(function() {
            Offered_Course_id.push($(this).val());
          });
      console.log(Offered_Course_id);
      if (Offered_Course_id == "") {
          swal('Perhatian', "field harus diisi atau Belum cek nilai", 'warning');
      } else {
          $.ajax({
              headers: {
                  "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
              },

              url:'{{route('krs_paket.update_datapeserta')}}',
              type: "POST",
              data: {
                    department :department,
                    class_program : class_program,
                    term_year : term_year,
                    curriculum : curriculum,
                    course_id : course_id,
                    entry_year : entry_year,
                    Student_Id : Student_Id,
                    Offered_Course_id : Offered_Course_id
              },

              success: function (res) {
                console.log(res.message);
                swal({
                  title: res.message,
                    showCancelButton: false,
                    cancelButtonColor: '#d33',
                    confirmButtonText: "Oke",
                    closeOnConfirm: false,
                  },function (isConfirm) {
                      if (isConfirm) {
                              window.location.reload(true) // submitting the form when user press yes
                      }
                  });
              },
              error: function (xhr, ajaxOptions, thrownError) {
                  swal({
                          title: thrownError,
                          text: 'Error !! ' + xhr.status+7,
                          type: "error",
                          confirmButtonColor: "#02991a",
                          confirmButtonText: "Refresh Serkarang",
                          cancelButtonText: "Tidak, Batalkan!",
                          closeOnConfirm: false,
                      },
                      function (isConfirm) {
                          if (isConfirm) {
                                  window.location.reload(true) // submitting the form when user press yes
                          }
                      });
              }
          });
      }
  });
  </script>

</section>
@endsection
