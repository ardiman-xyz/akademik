@extends('layouts._layout')
@section('pageTitle', 'Department Class Program')
@section('content')

<?php
$access = auth()->user()->akses();
          $acc = $access;
?>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Prodi VS Program Kelas</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          <b>Prodi VS Program Kelas</b>
        </div>
      </div>
      <br>
          <!-- <b>Daftar Program Kelas</b> -->
          {!! Form::open(['url' => route('department_class_program.index',$fakultas) , 'method' => 'GET', 'name' => 'form', 'role' => 'form']) !!}
          <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            <div  class="row col-md-7">
            <label class="col-md-3">Fakultas :</label>
            <select class="form-control form-control-sm col-md-9" name="fakultas" id="pilih">
              <option value="0">Pilih Fakultas</option>
              @foreach ( $select_fakultas as $data )
                <option <?php if($fakultas == $data->Faculty_Id){ echo "selected"; } ?> value="{{ $data->Faculty_Id }}">{{ $data->Faculty_Name }}</option>
              @endforeach
            </select>
            <script type="text/javascript">
                $(document).ready(function () {
                  $("#pilih").change(function () {
                    var url = {!! json_encode(url('/')) !!};
                    var id = $("#pilih").val();
                    window.location = url+"/master/department_class_program/"+id;
                  });
                });
            </script>
              <?php $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; ?>
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
              <option <?php if($rowpage == "10"){ echo "selected"; } ?> value="">10 Baris</option>
              <option <?php if($rowpage == "20"){ echo "selected"; } ?> value="20">20 Baris</option>
              <option <?php if($rowpage == "50"){ echo "selected"; } ?> value="50">50 Baris</option>
              <option <?php if($rowpage == "100"){ echo "selected"; } ?> value="100">100 Baris</option>
              <option <?php if($rowpage == "1000000"){ echo "selected"; } ?> value="1000000">semua</option>
            </select>
          </div><br>
          {!! Form::close() !!}
        </div>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
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
                  <th width="5%">No</th>
                  <th width="22%">Kode Program Studi</th>
                  <th width="23%">Nama Program Studi</th>
                  <th width="22%">Program Kelas</th>
                  @if(in_array('department_class_program-CanAdd', $acc) || in_array('department_class_program-CanDelete', $acc))
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
                  <td>{{ $data->Department_Code }}</td>
                  <td>{{ $data->Department_Name }}</td>
                  <td>
                  <?php
                    $dat = DB::table('mstr_class_program')
                                ->join('mstr_department_class_program','mstr_class_program.Class_Prog_Id','=','mstr_department_class_program.Class_Prog_Id')
                                ->join('mstr_department','mstr_department_class_program.Department_Id','=','mstr_department.Department_Id')
                                ->where('mstr_department.Department_Id', $data->Department_Id)->get();
                    foreach ($dat as $ta) {
                      echo $ta->Class_Program_Name."&nbsp | &nbsp";
                    }
                  ?>
                  </td>
                  @if(in_array('department_class_program-CanAdd', $acc) || in_array('department_class_program-CanDelete', $acc))
                  <td align="center">
                      <a href="{{ url('master/department_class_program/'.$data->Department_Id.'/edit/'.$fakultas.'?page='.$page.'&rowpage='.$rowpage) }}" class="btn btn-success btn-sm">Ubah Data</a>
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
  <!-- <script type="text/javascript">
     $(document).ready(function () {
     $(".hapus").click(function(e) {
       var url = "{{ url('modal/class_program') }}"
           $("#detail").load(url);
           $("#detail").modal('show',{backdrop: 'true'});
        });
      });
  </script> -->

<!-- /.row -->
<!-- <script>
    $(document).ready(function () {
        $('#dataTables-example').dataTable();
    });
</script> -->
</section>
@endsection
