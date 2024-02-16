@extends('layouts._layout')
@section('pageTitle', 'Student Supervision')
@section('content')

<?php
$access = auth()->user()->akses();
          $acc = $access;
?>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Bimbingan DPA</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          <?php $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; ?>
          @if($department != null && $department != 0)
          <div class="pull-right tombol-gandeng dua">
            @if(in_array('student_supervision-CanAdd', $acc)) <a href="{{ url('setting/student_supervision/create/?department_id='.$department.'&current_page='.$page.'&current_rowpage='.$rowpage.'&current_search='.$search ) }}" class="btn btn-success btn-sm">Tambah data &nbsp;<i class="fa fa-plus"></i></a>@endif
          </div>
          @endif
          <b>Bimbingan DPA</b>
        </div>
      </div>
      <br>
          {!! Form::open(['url' => route('student_supervision.index') , 'method' => 'GET', 'name' => 'form', 'role' => 'form']) !!}
          <div class="row text-green"  style="padding-top:5px;padding-left:15px;padding-right:15px;">
          <div  class="row col-md-7">
            <label class="col-md-3" >Program Studi :</label>
            <select class="form-control form-control-sm col-md-9" name="department" id="pilih" onchange="form.submit()">
              <option value="0">Pilih Program Studi</option>
              @foreach ( $select_Department_Id as $data )
              <option <?php if($department == $data->Department_Id){ echo "selected"; } ?> value="{{ $data->Department_Id }}">{{ $data->Department_Name }}</option>
              @endforeach
            </select>
          </div>
          </div>

          <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            <div  class="row col-md-7">
            <label class="col-md-3">Pencarian :</label>
            <input type="text" name="search"  class="form-control form-control-sm col-md-7 col-sm-7" value="{{ $search }}" placeholder="Search">
            <input type="submit" name="" class="btn btn-primary btn-sm col-md-2 col-sm-2" value="Cari">
            </div>
            <div  class="row col-md-5">
            <label class="col-md-5">Baris per halamam :</label>
            <select class="form-control form-control-sm col-md-7" name="rowpage" onchange="form.submit()">
              <option <?php if($rowpage == 10){ echo "selected"; } ?> value="10">10</option>
              <option <?php if($rowpage == 20){ echo "selected"; } ?> value="20">20</option>
              <option <?php if($rowpage == 50){ echo "selected"; } ?> value="50">50</option>
              <option <?php if($rowpage == 100){ echo "selected"; } ?> value="100">100</option>
              <option <?php if($rowpage == 200){ echo "selected"; } ?> value="200">200</option>
              <option <?php if($rowpage == "1000000"){ echo "selected"; } ?> value="1000000">semua</option>
            </select>
            </div>
          </div><br>
          {!! Form::close() !!}
        </div>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        @if (count($errors) > 0)
          @foreach ( $errors->all() as $error )
            <p class="alert alert-danger">{{ $error }}</p>
          @endforeach
        @endif
        <div class="table-responsive">
        <table class="table table-striped table-font-sm" style="font-size:70%;">
          <thead class="thead-default thead-green">
              <tr>
                  <!-- <th class="col-sm-1">No</th> -->
                  <th width="15%">NIP</th>
                  <th width="24%">Dosen</th>
                  <th width="12%">Jumlah Bimbingan</th>
                  <th width="12%">Jumlah Lulus</th>
                  <th width="12%">Jumlah Belum Lulus</th>
                  @if(in_array('student_supervision-CanEdit', $acc))
                  <th width="15%"><center><i class="fa fa-gear"></i></center></th>
                  @endif
              </tr>
          </thead>
          <tbody>
            <?php

            function encode($string) {
              $key = 5;
              $result = '';
              for($i=0, $k= strlen($string); $i<$k; $i++) {
                $char = substr($string, $i, 1);
                $keychar = substr($key, ($i % strlen($key))-1, 1);
                $char = chr(ord($char)+ord($keychar));
                $result .= $char;
              }
              return base64_encode($result);
            }

            
            $a = "1";
            foreach ($query as $data) {
              // $prodi = DB::table('prodi')->join('prodi_user','prodi_user.id_prodi','=','prodi.id_prodi')->join('users','users.id','=','prodi_user.id_user')->where('users.id',$data->id)->get();
              // $prod = auth()->user()->Prodi();
              // if (count($prod)==0) {
              ?>
              <tr>
                <?php
                $nip = "";
                 if($data->Nip==null){
                  $nip = '-';
                }else{
                  $nip = $data->Nip;
                } ?>
                  <td style="padding-left:1%;">{{ $nip }}</td>
                  <td>{{ $data->First_Title }} {{ $data->Name }} {{ $data->Last_Title }}</td>
                  <td>{{ $data->jumlah_bimbingan }}</td>
                  <td>{{ $data->jumlah_lulus }}</td>
                  <td><?php echo $data->jumlah_bimbingan - $data->jumlah_lulus; ?></td>
                  @if(in_array('student_supervision-CanEdit', $acc))
                  <td align="center">
                    <a data-params="{{ $department }}"
                            data-employeeid="{{ $data->Employee_Id }}" 
                            class="btn btn-success btn-sm" 
                            id="edit_dosen"  
                            href="javascript:">Edit Dosen</a>
                    <a href="{{ url('setting/student_supervision/create/?department_id='.$department.'&employee_id='.$data->Employee_Id.'&current_page='.$page.'&current_rowpage='.$rowpage.'&current_search='.$search ) }}" class="btn btn-info btn-sm">Edit</a>
                    <button  onclick="window.open('https://simdosen.umk.ac.id/exportDpaWali/0?e={{encode($data->Email_Corporate)}}')" class="export btn btn-warning btn-sm">Export</button>
                  </td>
                  @endif
              </tr>
              <?php
              $a++;
            }
            ?>
          </tbody>
        </table>
        </div>
        <?php echo $query->render('vendor.pagination.bootstrap-4'); ?>
      </div>
    </div>
  </div>

  <div id="ubahpembayaran" class="w3-modal">
      <div class="w3-modal-content w3-animate-zoom w3-card-4">
      <header class="w3-container w3-teal"> 
          <span onclick="location.reload()" 
          class="w3-button w3-display-topright">&times;</span>
          <h4>Ubah Dosen Bimbingan DPA</h4>
      </header>
      <div class="w3-container">
      </br>
      <!-- <form id="frm-upload" action="{{ route('course_curriculum.store_silabus') }}" method="POST" enctype="multipart/form-data"> -->
          <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            <div  class="row col-md-10">
              <label class="col-md-4">Dosen</label>
              <select class="form-control form-control-sm col-md-7" name="employeeid_new" id="pilih">
                <option value="0">Pilih Dosen</option>
                @foreach ( $emp_employee as $data )
                  <option value="{{ $data->Employee_Id }}">{{ $data->First_Title }} {{ $data->Name }} {{ $data->Last_Title }}</option>
                @endforeach
             </select>           
            </div>
            <div  class="row col-md-10">
              <input type="text" name="department"  hidden >
              <input type="text" name="employeeid_old" hidden >
            </div>

            <div  class="row col-md-10">
              <label class="col-md-4"></label>
              <button class="btn-success btn-sm form-control form-control-sm col-md-7" id="btnubahdosen" name="btnubahdosen">Ubah</button>
            </div>
            <!-- </form> -->
            </div>
          </br>

      </div>
      </div>
  </div>
<style>
  @media (min-width:993px){.w3-modal-content{width:50%}.w3-hide-large{display:none!important}.w3-sidebar.w3-collapse{display:block!important}}
</style>
  <script type="text/javascript">
    $(document).on('click', '#edit_dosen', function (e) {
    document.getElementById("ubahpembayaran").style.display = "block";
    var department = $(this).data('params'),
        employeeid_old = $(this).data('employeeid');
        // console.log($(this).data);

        $("[name='department']").val(department);
        $("[name='employeeid_old']").val(employeeid_old);
    });

    $(".export").click(function(){
      location.reload();
    })

    $("#btnubahdosen").click(function(e){
      var department =  $("[name='department']").val();
      var employeeid_old =  $("[name='employeeid_old']").val();
      var employeeid_new =  $("[name='employeeid_new']").val();
      console.log(employeeid_new);
      if (employeeid_new == 0) {
          swal('Perhatian', "Kolom Belum DIisi", 'warning');
      } else {
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },

            url: '{{route('student_supervision.update_dosen')}}',
            type: "POST",
            data: {
                  department :department,
                  employeeid_old : employeeid_old,
                  employeeid_new : employeeid_new
            },
            success: function (res) {
                location.reload();
                document.getElementById("ubahpembayaran").style.display = "none";
                // updateWindows.close();
                // $('#grid2').data("kendoGrid").dataSource.read();
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
