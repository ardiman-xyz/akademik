@extends('layouts._layout')
@section('pageTitle', 'Sched Session')
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
      <h3 class="text-white">Sesi Jadwal Kuliah </h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          @if($Sched_Type_Id != null && $Sched_Type_Id != 0 && $Sched_Session_Group_Id != null && $Sched_Session_Group_Id != 0)
          &nbsp
          <div class="pull-right tombol-gandeng dua">
            <?php $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; ?>
            @if(in_array('sched_session-CanAdd', $acc)) <a href="{{ url('setting/sched_session/create?sched_session_group_id='.$Sched_Session_Group_Id.'&sched_type_id='.$Sched_Type_Id.'&term_year='.$request->term_year ) }}" class="btn btn-success btn-sm">Tambah data &nbsp;<i class="fa fa-plus"></i></a>@endif
          </div>
          @endif
          <!-- <b>Daftar Fakultas</b> -->
          <b>Sesi Jadwal Kuliah</b>
        </div>
      </div>
      <br>
          {!! Form::open(['url' => route('sched_session.index') , 'method' => 'GET', 'name' => 'form', 'role' => 'form', 'style' => 'padding-top:5px;padding-left:15px;padding-right:15px;']) !!}
          <div class="row text-green">
            <label class="col-md-1">Grup Sesi:</label>
            <select class="form-control form-control-sm col-md-3" name="sched_session_group_id"  onchange="document.form.submit();">
              <option value="0">Pilih Grup Sesi</option>
              @foreach ( $select_sched_session_group as $data )
                <option <?php if($Sched_Session_Group_Id == $data->Sched_Session_Group_Id){ echo "selected"; } ?> value="{{ $data->Sched_Session_Group_Id }}">{{ $data->Sched_Session_Group_Name }}</option>
              @endforeach
            </select>
            <label class="col-md-1" >Type :</label>
            <select class="form-control form-control-sm col-md-3" name="sched_type_id" onchange="document.form.submit();">
              <option value="0">Pilih Type</option>
              @foreach ( $select_sched_type as $data )
                <option <?php if($Sched_Type_Id == $data->Sched_Type_Id){ echo "selected"; } ?>  value="{{ $data->Sched_Type_Id }}">{{ $data->Sched_Type_Name }}</option>
              @endforeach
            </select>
            <label class="col-md-1" >Semester :</label>
            <select class="form-control form-control-sm col-md-3" name="term_year" onchange="document.form.submit();">
              @foreach ( $select_term_year as $data )
                <option <?php if($request->term_year == $data->Term_Year_Id){ echo "selected"; } ?>  value="{{ $data->Term_Year_Id }}">{{ $data->Term_Year_Name }}</option>
              @endforeach
            </select>
          </div>
          <br>
          {!! Form::close() !!}
        </div>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        @if (count($errors) > 0)
          @foreach ( $errors->all() as $error )
            <p class="alert alert-danger">{{ $error }}</p>
          @endforeach
        @endif
        <div class="table-responsive">
        <table class="table table-striped table-font-sm">
          <thead class="thead-default thead-green">
              <tr>
                  <th class=""><div style="width:80px;">Hari</div></th>
                  <?php
                  $a = "1";
                  $property_types = array();
                  foreach ($order as $data) {
                    if ( in_array($data->Order_Id, $property_types) ) {
                        continue;
                    }
                    $property_types[] = $data->Order_Id;
                  ?>
                  <th><center><div style="width:80px;"><center>Sesi {{ $data->Order_Id }}</center></div></th>
                  <?php

                  }
                  ?>

                  <!-- <th width="10%"><center><i class="fa fa-gear"></i></center></th> -->
              </tr>
          </thead>
          <tbody>
              @foreach($query as $value)
              <tr>
                <td>
                  {{ $value->Day_Name }}
                </td>
                <?php
                $a = "1";
                $property_types = array();
                foreach ($order as $data) {
                  if ( in_array($data->Order_Id, $property_types) ) {
                      continue;
                  }
                  $property_types[] = $data->Order_Id;

                  $jadwal = DB::table('acd_sched_session')
                  ->where('Sched_Session_Group_Id', $value->Sched_Session_Group_Id)
                  ->where('Sched_Type_Id', $value->Sched_Type_Id)
                  ->where('Term_Year_Id', $request->term_year)
                  ->where('Day_Id', $value->Day_Id)
                  ->where('Order_Id', $data->Order_Id)
                  ->first();
                  if ($jadwal != null) {
                    ?>
                    <td><center>
                      @if(in_array('sched_session-CanEdit', $acc)) <a  style="text-decoration:none;" href="{{ url('setting/sched_session/'.$jadwal->Sched_Session_Id.'/edit' ) }}">{{ $jadwal->Time_Start }} - {{ $jadwal->Time_End }}</a>
                      @else {{ $jadwal->Time_Start }} - {{ $jadwal->Time_End }} @endif
                      {!! Form::open(['url' => route('sched_session.destroy', $jadwal->Sched_Session_Id)  , 'method' => 'DELETE','class'=>'hapus','data-id'=>$jadwal->Sched_Session_Id , 'role' => 'form']) !!}
                      @if(in_array('sched_session-CanDelete', $acc))<button style="background:none!important; color:inherit; border:none; padding:0!important; font: inherit; cursor: pointer;"><i class="fa fa-close" style="cursor:pointer; color:red;"></i></button>@endif
                      {!! Form::close() !!}
                    </center></td>
                    <?php
                  }else {
                    ?>
                    <td></td>
                    <?php
                  }

                }
                ?>
              </tr>

              @endforeach

          </tbody>
        </table>
        </div>
      </div>
    </div>
  </div>

  <script>
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
                  url: "{{ url('') }}/setting/sched_session/" + id,
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
