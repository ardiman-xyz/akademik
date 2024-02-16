@extends('layouts._layout')
@section('pageTitle', 'Offered Course Sched')
@section('content')

<?php

foreach ($query_edit as $data_edit) {

?>

<script src="https://cdn.datatables.net/fixedcolumns/3.2.6/js/dataTables.fixedColumns.min.js"></script>

<style>
  tr:hover {
    background: #C0C0C0 !important;
}

th, td { white-space: nowrap; }
.khusus a{display:block;width:40px}


/* .fixed_header thead{
   display: block;
} */

.fixed_header tbody{
  /* display:block; */
  width: 100%;
  overflow: auto;
  height: 300px;
}

div.dataTables_wrapper {
        height: 400px;
        margin: 0 auto;
    }
.w3-modal {
padding-top: 10px !important;
}

</style>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Edit Entry Jadwal Kuliah</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('setting/offered_course_sched?class_program='.$class_program.'&term_year='.$term_year.'&department='.$department.'&curriculum='.$curriculum.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search.'&curriculum='.$curriculum) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Edit Jadwal</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
          @if (count($errors) > 0)
            @foreach ( $errors->all() as $error )
              @if ($error=="Berhasil Menambah Jadwal Kuliah")
                <p class="alert alert-success">{{ $error }}</p>
              @else
                <p class="alert alert-danger">{{ $error }}</p>
              @endif
            @endforeach
          @endif
          <div class="form-group">
            {!! Form::open(['url' => route('offered_course_sched.store') , 'method' => 'POST', 'class' => 'form']) !!}
            <div class="col-md-12">
              <input type="hidden" name="Offered_Course_id" value="{{ $data_edit->Offered_Course_id }}">
            </div>
          </div>
          <div class="row text-green">
            <label class="col-md-3">Kode Matakuliah:</label>
            <input type="text" name="Course_Code"  value="{{ $data_edit->Course_Code }}" readonly class="form-control form-control-sm col-md-3">
          </div>
          <div class="row text-green">
            <label class="col-md-3">Nama Matakuliah:</label>
            <input type="text" name="Course_Name" value="{{ $data_edit->Course_Name }}" readonly class="form-control form-control-sm col-md-3">
          </div>
          <div class="row text-green">
            <label class="col-md-3">SKS:</label>
            <input type="text" name="Course_Name" value="{{ $data_edit->Applied_Sks }}" readonly class="form-control form-control-sm col-md-3">
          </div>
          <div class="row text-green">
            <label class="col-md-3">Kelas:</label>
            <input type="text" name="Class_Id" min="1" value="{{ $data_edit->Class_Name }}" readonly class="form-control form-control-sm col-md-3">
          </div>
          <div class="row text-green">
            <label class="col-md-3">Kapasitas Kelas Matakuliah:</label>
              <input type="text" name="Class_Id" min="1" value="{{ $data_edit->Class_Capacity }}" readonly class="form-control form-control-sm col-md-3">
          </div>

          <div class="row text-green">
              <label class="col-md-3">Dosen Pengampu:</label>
            @if($dosen2 == 0)
                <input type="text" name="dosen" min="1" value="Dosen pengampu belum diatur" readonly class="form-control form-control-sm col-md-3">                
              @else
              <?php
              foreach($dosen as $dsn){
                ?>
                  <input type="text" name="dosen" min="1" value="{{ $dsn->First_Title }} {{ $dsn->Name }} {{ $dsn->Last_Title }}" readonly class="form-control form-control-sm col-md-3">                
              <?php
              }
              ?>
              @endif
          </div>
          <div class="row text-green">
            <label class="col-md-3">Grup Sesi:</label>
            <select class="form-control form-control-sm col-md-3" name="sched_session_group_id"  onchange="group();">
              <option value="0">Pilih Grup Sesi</option>
              @foreach ( $select_sched_session_group as $data )
                <option <?php if($Sched_Session_Group_Id == $data->Sched_Session_Group_Id){ echo "selected"; } ?> value="{{ $data->Sched_Session_Group_Id }}">{{ $data->Sched_Session_Group_Name }}</option>
              @endforeach
            </select>
          </div>
          <div class="row text-green">
            <label class="col-md-3" >Type :</label>
            <select class="form-control form-control-sm col-md-3" name="sched_type_id" onchange="type_k();">
              <option value="0">Pilih Type</option>
              @foreach ( $select_sched_type as $data )
                <option <?php if($Sched_Type_Id == $data->Sched_Type_Id){ echo "selected"; } ?>  value="{{ $data->Sched_Type_Id }}">{{ $data->Sched_Type_Name }}</option>
              @endforeach
            </select>
          </div>
          {!! Form::close() !!}
          <br>
          <center><button type="" class="btn btn-primary btn-flat" onclick="myFunction()">Tambah</button></center>
          <br>
          <center>
          <div class="table-responsive col-md-10">
          <table class="table table-striped table-font-sm">
            <thead class="thead-default thead-green">
                <tr>
                    <!-- <th class="col-sm-1">No</th> -->
                    <th width="45%">Sesi</th>
                    <th width="45%">Ruang</th>
                    <th width="10%"><center><i class="fa fa-gear"></i></center></th>
                </tr>
            </thead>
            <tbody>
              <?php
              $a = "1";
              foreach ($query as $data) {

                ?>
                <tr>
                    <td><center>{{ $data->Description }}</center></td>
                    <td><center>{{ $data->Room_Name }}</center></td>
                    <td>
                        <center>
                        {!! Form::open(['url' => route('offered_course_sched.destroy', $data->Offered_Course_Sched_id) , 'method' => 'delete', 'role' => 'form']) !!}
                        {!! Form::button('Hapus', ['type'=>'submit','class'=>'btn btn-danger btn-sm hapus','data-id'=>$data->Offered_Course_Sched_id]) !!}
                        {!! Form::close() !!}
                        </center>
                    </td>
                </tr>
                <?php
                $a++;
              }
              ?>
            </tbody>
          </table>
          </div>
        </center>
      </div>
    </div>
  </div>

<div id="ubahpembayaran" class="w3-modal">
      <div class="w3-modal-content w3-animate-zoom w3-card-4">
      <header class="w3-container w3-teal"> 
          <span onclick="location.reload()" 
          class="w3-button w3-display-topright">&times;</span>
          <h4>Detail Jadwal</h4>
      </header>
      <div class="w3-container">
      
          <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            <div  class="row col-md-7">
              <label class="col-md-4">Kode Matakuliah</label>
              <input type="text" name="Course_Code" id="Course_Code"  value="{{ $data_edit->Course_Code }}" readonly class="form-control form-control-sm col-md-7">
            </div>
            <div  class="row col-md-7">
              <label class="col-md-4">Matakuliah</label>
              <input type="text" name="Course_Id" id="Course_Id"  value="{{ $data_edit->Course_Name }}" readonly class="form-control form-control-sm col-md-7">
            </div>
            <div  class="row col-md-7">
              <label class="col-md-4">SKS</label>
              <input type="text" name="Course_Id" id="Course_Id"  value="{{ $data_edit->Applied_Sks }}" readonly class="form-control form-control-sm col-md-7">
            </div>
            <div  class="row col-md-7">
              <label class="col-md-4">Kelas</label>
              <input type="text" name="Class_Id" id="Class_Id"  value="{{ $data_edit->Class_Name }}" readonly class="form-control form-control-sm col-md-7">
            </div>
            <div  class="row col-md-7">
              <label class="col-md-4">Kapasitas Kelas Matakuliah</label>
              <input type="text" name="Class_Id" id="Class_Capacity"  value="{{ $data_edit->Class_Capacity }}" readonly class="form-control form-control-sm col-md-7">
            </div>
          </div>
        
          
          <form action="" method="post" style="display:inline">
              {!! csrf_field() !!}
              <div class="table-responsive">
                             <table id="example" class="table-font-sm stripe row-border order-column" style="width:10%;">
                  <thead class="thead-default thead-green">
                      <tr>
			<th rowspan="2" class=""><div style="width:80px;">Ruang</div></th>
                          <?php
                            foreach ($th['hari'] as $hari) {
                              $th['sched'] = DB::table('acd_sched_session')
                                          ->where('Day_Id',$hari->Day_Id)
                                          ->where('acd_sched_session.Sched_Type_Id', $Sched_Type_Id)
                                          ->orderBy('acd_sched_session.Day_Id', 'asc')
                                          ->orderBy('acd_sched_session.Order_Id', 'asc')->get();
                                $ht = count($th['sched'])
                                ?>
                                <th colspan="{{$ht}}" style="border:0.5px solid white"><?php echo $hari->Day_Name; ?></th>
                                <?php
                            }
                        ?>
                      </tr>
                      <tr>
			
                          <?php
                            foreach ($th['sesi_hari'] as $sesi_hari) {
                                foreach ($sesi_hari as $sesi_haris) {
                                    ?>
                                    <th style="border:0.5px solid white"><?php echo $sesi_haris; ?></th>
                                    <?php
                                }
                            }
                        ?>
                      </tr>
                  </thead>
                  <tbody>
                  @foreach ($th['data'][0]['ruangs'] as $item)
                      <tr>
                            <td>
                                {{$item['Room_Name']}} ({{$item['Capacity']}})
                            </td>

                      <?php
                        foreach ($item['kelas'] as $kelas) {
                          
                      ?>      
                      <td>
                      <?php 
                      if(count($kelas['r_k']) > 0){ 
                      ?> 
                            <a style="width: 32px;" class="badge khusus" href="javascript:"> {{$kelas['r_k'][0]->Course_Name}} / </br>{{$kelas['r_k'][0]->Course_Code}} / {{$kelas['r_k'][0]->Class_Name}} </a>
                      <?php 
                      } else { ?>
                            <input type="text" hidden name="aa" id="aa" value="{{$offered_course_id}}">
                            <input type="text" hidden name="bb" id="bb" value="{{$kelas['Sched_Session_Id']}}">
                            <input type="text" hidden name="cc" id="cc" value="{{$kelas['Room_Id']}}">
                            <input type="text" hidden name="dd" id="dd" value="{{$kelas['Day_Id']}}">
                            <a data-params="{{$kelas['Sched_Session_Id']}}|{{$kelas['Room_Id']}}|{{$kelas['Room_Name']}}|{{$kelas['Description']}}|{{ $data_edit->Class_Capacity }}|{{$kelas['Capacity']}}" 
                               data-ku="cekdata"
                               class="badge badge-secondary" 
                               id="pilih_jdw"  
                               href="javascript:">Pilih</a>
                    <?php
                      } 
                      ?>
                      </td>
                      <?php
                        
                      }
                      ?>      
                      </tr>
                  @endforeach
                  </tbody>
                </table>


                </br>
                </br>
                </div>
          </form>
      </div>
      </div>
  </div>

  <script>

function group() {
  var sched_type_id = $("[name='sched_session_group_id']").val(),
      offered_course_id = $("[name='Offered_Course_id']").val(),
      class_program = {{$class_program}},
      department = {{$department}},
      term_year = {{$term_year}},
      curriculum = {{$curriculum}},
      url = '{{ url('') }}';
  window.open(url+'/setting/offered_course_sched/create?offered_course_id='+offered_course_id+'&sched_session_group_id='+sched_type_id+'&class_program='+class_program+'&department='+department+'&term_year='+term_year+'&curriculum='+curriculum, '_self');
}
function type_k() {
  var sched_type = $("[name='sched_type_id']").val(),
      sched_type_id = $("[name='sched_session_group_id']").val(),
      offered_course_id = $("[name='Offered_Course_id']").val(),
      class_program = {{$class_program}},
      department = {{$department}},
      term_year = {{$term_year}},
      curriculum = {{$curriculum}},
      url = '{{ url('') }}';
  window.open(url+'/setting/offered_course_sched/create?offered_course_id='+offered_course_id+'&sched_session_group_id='+sched_type_id+'&sched_type_id='+sched_type+'&class_program='+class_program+'&department='+department+'&term_year='+term_year+'&curriculum='+curriculum, '_self');
}
function myFunction() {
  document.getElementById("ubahpembayaran").style.display = "block";
}

$(document).ready(function() {  
  var table = $('#example').DataTable({
    "scrollY": "370px",
    "scrollX": true,
    "scrollCollapse": true,
    searching: false, 
    paging: false, 
    info: false,
    fixedColumns:   {
            leftColumns: 1
        }
  });

  $(document).on('click', '#pilih_jdw', function (e) {
      e.preventDefault(); 
      var data = $(this).data('params').split('|');
      // var data2 = $(this).data('ku');
      // console.log(data2);  
      // alert(data[0] + data[1]);
      var ssi = data[0],
          ri = data[1],
          rn = data[2],
          dc = data[3],
          cc = data[4],
          cr = data[5];

      var offered_course_id = $("[name='aa']").val();      
      var Sched_Session_Id = $("[name='bb']").val();
      var Room_Id = $("[name='cc']").val();
      var Day_Id = $("[name='dd']").val();
      swal({
        title: rn+' '+dc,
          text: "Kapasitas Kelas Matakuliah " +cc+ '\n Kapasitas Ruang '+cr,
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Oke',
          cancelButtonText: 'cancel!',
          confirmButtonClass: 'btn btn-success',
          cancelButtonClass: 'btn btn-danger',
          buttonsStyling: true
        }, function(isConfirm) {
      if (isConfirm) {
              $.ajax({
                  url: '{{route('offered_course_sched.store_detail')}}',
                  type: "post",
                  dataType: "json",
                  data: {
                    Room_Id: ri,
                    offered_course_id : offered_course_id,
                    Sched_Session_Id : ssi,                    
                    "_token": "{{ csrf_token() }}",
                    
                  },
                  success: function (res) {
                    swal3();
                  },
                  error: function(xhr, ajaxOptions, thrownError){
                    swal({
                                    title: thrownError,
                                    text: 'Error !! ' + xhr.status,
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
              // $("#hapus").submit();
            }
          });
  });
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
                  url: "{{ url('') }}/setting/offered_course_sched/" + id,
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
    function swal3() {
      swal({
        title: 'Data telah Ditambahkan',
        type: 'success', showConfirmButton:true,
        });
        window.location.reload();
    }
</script>

<!-- /.row -->

</section>

<?php
}
?>


@endsection
