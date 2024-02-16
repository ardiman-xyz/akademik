@extends('layouts._layout')
@section('pageTitle', 'KRS')
@section('content')

<?php


// $url = 'http://10.64.21.72:8080/webservice/rest/simpleserver.php?wsusername=a&wspassword=a';
// $xmlll = simplexml_load_file($url) or die("feed not loading");
// // $xml=simplexml_load_string($xmlll) or die("Error: Cannot create object");
// print_r($xmlll);
?>
<?php
$access = auth()->user()->akses();
          $acc = $access;
?>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">KRS Per Paket</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          <div class="pull-right tombol-gandeng dua">
            @if(in_array('krs_paket-CanAdd', $acc))
            <a href="{{ url('proses/krs_paket/'.$id.'/create_datapeserta?class_program='.$class_program.'&term_year='.$term_year.'&department='.$department.'&curriculum='.$curriculum) }}" class="btn btn-success btn-sm">Tambah Peserta &nbsp;<i class="fa fa-plus"></i></a>@endif
            <!-- <a data-params="{{ $department }}"
                data-classprogram="{{ $class_program }}" 
                data-termyear="{{ $term_year }}"
                data-curriculum="{{ $curriculum }}"
                class="btn btn-success btn-sm" 
                id="tambahpeserta"  
                href="javascript:">Tambah Peserta</a> -->
            <a href="{{ url('proses/krs_paket?class_program='.$class_program.'&term_year='.$term_year.'&department='.$department.'&current_search='.$currentsearch.'&current_page='.$currentpage.'&current_rowpage='.$currentrowpage.'&curriculum='.$curriculum) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
          </div>
          <b>Index</b>
        </div>
      </div>
          {!! Form::open(['url' => route('krs_matakuliah.show',$Offered_Course_id) , 'method' => 'GET', 'name' => 'form', 'role' => 'form']) !!}
          <div class="">
            <div class="pull-left tombol-gandeng dua">
            </div>
          </div>
          <br>
          <div class="row">
            <label class="col-md-1">Pencarian:</label>
            <input type="text" name="search"  class="form-control form-control-sm col-md-4" value="{{ $search }}" placeholder="Search">&nbsp
            <label class="col-md-2" style="text-align:right;">Baris Per halaman :</label>
            <input type="number" name="rowpage"  class="form-control form-control-sm col-md-4" value="{{ $rowpage }}" placeholder="Baris Per halaman">&nbsp
            <input type="submit" name="" style="float:right;" class="btn btn-primary btn-sm" value="Submit">
          </div><hr>
          {!! Form::close() !!}
        </div>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        <br>
        <div class="row">
          <div class="col-sm-6">
            <div >
              <label  class="col-sm-5">Th Akademik/Semester</label>:
              <label  class="col-sm-5"> {{ $term_year }}</label>
            </div>
            <div>
              <label  class="col-sm-5">Program Studi</label>:
              <label  class="col-sm-5"> {{ $department }}</label>
            </div>
            <div>
              <label  class="col-sm-5">Kelas Program</label>:
              <label  class="col-sm-5"> {{ $class_program }}</label>
            </div>
            <div>
              <label  class="col-sm-5">Matakuliah</label>
            </div>
              <?php
                $a = "1";
                foreach ($data_offered_course as $data) {
              ?>
               <div>
              <label  class="col-sm-5"> </label>
                <label  class="col-sm-5"> {{ $data->Course_Name }}</label>
              </div>
              <?php
                }
              ?>
              <?php
                $a = "1";
                foreach ($detail_offered_course as $data) {
              ?>
              <?php
                }
              ?>
          </div>
        </div>
        @if (count($errors) > 0)
          @foreach ( $errors->all() as $error )
            <p class="alert alert-danger">{{ $error }}</p>
          @endforeach
        @endif
        <br>
        <div class="table-responsive">
            <table class="table table-striped table-font-sm">
              <thead class="thead-default thead-green">
                  <tr>
                      <!-- <th class="col-sm-1">No</th> -->
                      <th width="">NIM</th>
                      <th width="">Nama Mahasiswa</th>
                      <th width="">Matakuliah</th>
                      <th width="">Kelas</th>
                  </tr>
              </thead>
              <tbody>
                <?php
                $a = "1";
                foreach ($acd_student_krs as $data) {
                ?>
                <tr>
                  <td><center>{{ $data->Nim }}</center></td>
                  <td>{{ $data->Full_Name }}</td>
                  <td>{{ $data->Course_Name }}</td>
                  <td>{{ $data->Class_Name }}</td>
                </tr>
                <?php
                $a++;
                }
                ?>
              </tbody>
            </table>
          </div>
        <?php 
        // echo $query->render('vendor.pagination.bootstrap-4'); 
        ?>
      </div>
    </div>
  </div>



    <div id="tambahpeserta_form" class="w3-modal">
      <div class="w3-modal-content w3-animate-zoom w3-card-4">
      <header class="w3-container w3-teal"> 
          <span onclick="location.reload()" 
          class="w3-button w3-display-topright">&times;</span>
          <h4>Upload Silabus</h4>
      </header>
      <div class="w3-container">
      </br>
      <form id="frm-upload" action="{{ route('course_curriculum.store_silabus') }}" method="POST" enctype="multipart/form-data">
          <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            <input type="text" name="dept_from"  hidden >
            <input type="text" name="term_from" value=""  hidden >
            <input type="text" name="class_from" value=""  hidden >
            <input type="text" name="cur_from" value="" hidden  >
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
              <?php
                  }
              ?>
              <br>
            <div  class="row col-md-10">
              <label class="col-md-4"></label>
              <input type="submit"  value="Simpan" class="btn-success btn-sm form-control form-control-sm col-md-7">        
            </div>
            <div  class="row col-md-10">
              <label class="col-md-4"></label>
              <select class="form-control form-control-sm col-md-4" id="entry_from">
                <option value="0">Pilih Angkatan</option>
                @foreach ( $select_entry_year as $data )
                  <option value="{{ $data->Entry_Year_Id }}">{{ $data->Entry_Year_Code }}</option>
                @endforeach
              </select>&nbsp       
            </div>
            </form>
            </div>
          </br>

      </div>
      </div>
      <table id="eaea">
      </table>
  </div>
<script>

$(document).on('click', '#tambahpeserta', function (e) {
    document.getElementById("tambahpeserta_form").style.display = "block";
    var department = $(this).data('params'),
        classprogram = $(this).data('classprogram'),
        termyear = $(this).data('termyear'),
        curriculum = $(this).data('curriculum');
        // console.log(department);
        $("[name='dept_from']").val(department);
        $("[name='term_from']").val(classprogram);
        $("[name='class_from']").val(termyear);
        $("[name='cur_from']").val(curriculum);

        if(curriculum == ''){

        }else{

        }
});

$("#entry_from").change(function(){
  var department = $("[name='dept_from']").val(),
      termyear = $("[name='term_from']").val(),
      classprogram = $("[name='class_from']").val(),
      curriculum = $("[name='cur_from']").val();
      entryyear = $("[id='entry_from']").val();
  alert([department,termyear,classprogram,curriculum,entryyear]);
});


$(document).on('click', '.hapus', function (e) {
    e.preventDefault();
    var id = $(this).data('id');

  //  console.log(id);
    swal({
      title: 'Data Akan Dihapus',
        text: "Klik hapus untuk menghapus data",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Hapus',
        cancelButtonText: 'cancel!',
        confirmButtonClass: 'btn btn-success',
        cancelButtonClass: 'btn btn-danger',
        buttonsStyling: true
      }, function(isConfirm) {
    if (isConfirm) {
            $.ajax({
                url: "{{ url('') }}/proses/krs_matakuliah/" + id,
                type: "DELETE",
                dataType: "json",
                data: {
                  "_token": "{{ csrf_token() }}"
                },
                success: function (data) {
                  swal2();
                },
                error: function(){
                  swal1();
                }
            });
            // $("#hapus").submit();
          }
        });
});
  function swal1() {
    swal({
      title: 'Data masih digunakan',
        type: 'error',
        showCancelButton: false,
        cancelButtonColor: '#d33',
        cancelButtonText: 'cancel!',
        cancelButtonClass: 'btn btn-danger',
      });
  }
  function swal2() {
    swal({
      title: 'Data telah dihapus',
      type: 'success', showConfirmButton:false,
      });
      window.location.reload();
  }
        </script>
</section>
@endsection
