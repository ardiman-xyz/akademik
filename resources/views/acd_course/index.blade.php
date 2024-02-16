@extends('layouts._layout')
@section('pageTitle', 'Course')
@section('content')

<?php
$access = auth()->user()->akses();
          $acc = $access;
?>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Matakuliah</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          <?php $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; ?>
          @if($department != null && $department != 0)
          <div class="pull-right tombol-gandeng dua">
            @if(in_array('course-CanAdd', $acc)) <a href="{{ url('parameter/course/create/?department='.$department.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search ) }}" class="btn btn-success btn-sm">Tambah data &nbsp;<i class="fa fa-plus"></i></a>@endif
          </div>
          @endif
          <b>Matakuliah</b>
        </div>
      </div>
      <br>
          <!-- <b>Daftar Fakultas</b> -->
          {!! Form::open(['url' => route('course.index') , 'method' => 'GET', 'name' => 'form', 'role' => 'form']) !!}
          <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            <div class="row col-md-7">
              <label class="col-md-3">Program Studi : &nbsp</label>
              <select class="form-control form-control-sm col-md-9" name="department" id="pilih" onchange="form.submit()">
                <option value="0">Pilih Program Studi</option>
                @foreach ( $select_Department_Id as $data )
                  <option <?php if($department == $data->Department_Id){ echo "selected"; } ?> value="{{ $data->Department_Id }}">{{ $data->Department_Name }}</option>
                @endforeach
              </select>
              <div class="col-md-2">
              </div>
            </div>
          </div>
          <br>
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


      @if($department != null && $department != 0)
      <br>
        <a href="{{ url('parameter/course/export/pdf?department='.$department.'&search='.$search ) }}" target="_blank" class="btn btn-warning btn-sm" style="float:right; font-size:medium;margin-right:5px;">Export PDF &nbsp;<i class="fa fa-print"></i></a> &nbsp;&nbsp;
        <a href="{{ url('parameter/course/exportexcel/exportexcel?department='.$department.'&search='.$search) }}" target="_blank" class="btn btn-primary btn-sm" style="float:right; font-size:medium;margin-right:5px;">Export Excel &nbsp;<i class="fa fa-print"></i></a> &nbsp;&nbsp;
        <a href="{{ url('parameter/course/import_export/import_export?department='.$department ) }}" class="btn btn-success btn-sm" style="float:right; font-size:medium;margin-right:5px;">Import Excel &nbsp;<i class="fa fa-print"></i></a> &nbsp;&nbsp;
        <input type='button' id='download_contoh' onclick='download_contoh()' value='Download Contoh' class='k-button' style="float:right; font-size:medium;margin-right:5px;">
      @endif
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        @if (count($errors) > 0)
            @foreach ( $errors->all() as $error )
              @if ($error=="Berhasil Memasukkan Data Ke Database")
                <p class="alert alert-success">{{ $error }}</p>
              @else 
                <p class="alert alert-danger">{{ $error }}</p>
              @endif
            @endforeach
          @endif
        <br>
        <label style="font-size:10pt;">Jumlah Matakuliah : {{$datac}}</label>
        <div class="table-responsive">
        <table id="tbl" class="table table-striped table-font-sm">
          <thead class="thead-default thead-green">
              <tr>
                  <th width="5%">No</th>
                  <th width="15%">Kode Matakuliah</th>
                  <th width="15%">Nama Matakuliah</th>
                  <th width="15%">Nama Matakuliah (English)</th>
                  <th width="10%">Jenis Matakuliah</th>
                  <th width="10%">Prodi</th>

                  @if(in_array('course-CanEdit', $acc) || (in_array('course-CanDelete', $acc)))
                  <th width="15%"><center><i class="fa fa-gear"></i></center></th>
                  @endif
              </tr>
          </thead>
          <tbody>
            <?php
            $a = "1";
            foreach ($query as $data) {
              // $prodi = DB::table('prodi')->join('prodi_user','prodi_user.id_prodi','=','prodi.id_prodi')->join('users','users.id','=','prodi_user.id_user')->where('users.id',$data->id)->get();
              // $prod = auth()->user()->Prodi();
              // if (count($prod)==0) {
              ?>
              <tr>
                  <!-- <th></th> -->
                  <td>{{ $a }}</td>
                  <td>{{ $data->Course_Code }}</td>
                  <td>{{ $data->Course_Name }}</td>
                  <td>{{ $data->Course_Name_Eng }}</td>
                  <td>{{ $data->Course_Type_Name }}</td>
                  <td>{{ $data->Department_Name }}</td>
                  @if(in_array('course-CanEdit', $acc) || (in_array('course-CanDelete', $acc)))
                  <td align="center">
                      {!! Form::open(['url' => route('course.destroy', $data->Course_Id) , 'method' => 'delete', 'role' => 'form']) !!}
                      @if(in_array('course-CanEdit', $acc))<a href="{{ url('parameter/course/'.$data->Course_Id.'/edit?page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-info btn-sm">Edit</a>@endif
                      @if(in_array('course-CanDelete', $acc)){!! Form::button('Hapus', ['type'=>'submit','class'=>'btn btn-danger btn-sm hapus','data-id'=>$data->Course_Id]) !!}@endif
                      {!! Form::close() !!}
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
  <script type="text/javascript">
     $(document).ready(function () {
      $('#tbl').dataTable({
        paging:false,
        info:false
      });
      });
  </script>

<!-- /.row -->
<!-- <script>
    $(document).ready(function () {
        $('#dataTables-example').dataTable();
    });
</script> -->
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
        buttonsStyling: true,
        closeOnConfirm: false
      }, function(isConfirm) {
    if (isConfirm) {
            $.ajax({
                url: "{{ url('') }}/parameter/course/" + id,
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

  function download_contoh() {
    console.log('downlaod');
    // window.open("https://www.w3schools.com","_blank");
    window.open("<?php echo env('APP_URL')?>{{ 'panduan/course.xlsx' }}");
  }
  </script>
</section>
@endsection
