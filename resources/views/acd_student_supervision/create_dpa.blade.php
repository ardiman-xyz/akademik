@extends('layouts._layout')
@section('pageTitle', 'Student Supervision')
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
      <h3 class="text-white">Bimbingan DPA</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          <div class="panel-heading tombol-gandeng dua">
            <?php $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; ?>
            <div class="pull-right tombol-gandeng dua">
              <a href="{{ url('setting/student_supervision/create?department_id='.$department_id.'&employee_id='.$employee_id.'&page='.$page.'&rowpage='.$rowpage.'&current_page='.$current_page.'&current_rowpage='.$current_rowpage.'&current_search='.$current_search) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
            </div>
            <b>Create</b>
          </div>
        </div>
      </div>
      <br>
          {!! Form::open(['url' => route('student_supervision.create_dpa') , 'method' => 'GET', 'name' => 'form', 'role' => 'form']) !!}
          <div class="row text-green"  style="padding-top:5px;padding-left:15px;padding-right:15px;">
            <div  class="row col-md-7">
              <label class="col-md-3" >Tahun Angkatan :</label>
              <select class="form-control form-control-sm col-md-9" name="entry_year_id" id="pilih" onchange="form.submit()">
                <option value="0">Pilih Tahun Angkatan</option>
                @foreach ( $entry_year as $data )
                  <option <?php if($entry_year_id == $data->Entry_Year_Id){ echo "selected"; } ?> value="{{ $data->Entry_Year_Id }}">{{ $data->Entry_Year_Code }}</option>
                @endforeach
              </select>
            </div>
          </div><div class="row text-green"  style="padding-top:5px;padding-left:15px;padding-right:15px;">
            <div  class="row col-md-7">
              <label class="col-md-3" >Semua Prodi :</label>
              <select class="form-control form-control-sm col-md-9" name="semua_prodi" id="pilih" onchange="form.submit()">
                <option value="">Tidak</option>
                <option value="semua" <?php if($request->semua_prodi == 'semua'){ echo "selected"; } ?>>Ya</option>
              </select>
            </div>
          </div>

            <input type="hidden" name="search" value="{{ $search }}">
            <input type="hidden" name="page" value="{{ $page }}">
            <input type="hidden" name="rowpage" value="{{ $rowpage }}">
            <input type="hidden" name="current_search" value="{{ $current_search }}">
            <input type="hidden" name="current_page" value="{{ $current_page }}">
            <input type="hidden" name="current_rowpage" value="{{ $current_rowpage }}">
            <input type="hidden" name="employee_id" value="{{ $employee_id }}">
            <input type="hidden" name="department_id" value="{{ $department_id }}">

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
            @if ($error=="Berhasil Menambah Bimbingan DPA")
              <p class="alert alert-success">{{ $error }}</p>
            @else
              <p class="alert alert-danger">{{ $error }}</p>
            @endif
          @endforeach
        @endif
        <br>
        <div class="table-responsive">
        {!! Form::open(['url' => route('student_supervision.store') , 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form']) !!}
        <input type="hidden" name="Employee_Id" value="{{ $employee_id }}">
        <table class="table table-striped table-font-sm" >
          <thead class="thead-default thead-green">
              <tr>
                  <!-- <th class="col-sm-1">No</th> -->
                  <th width="10%"><input type="checkbox" onchange="Change(this);" name="" value=""> </th>
                  <th width="25%">NIM</th>
                  <th width="50%">Nama Mahasiswa</th>
                  <!-- <th width="15%"><center><i class="fa fa-gear"></i></center></th> -->
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
                  <td><center><input type="checkbox" name="Student_Id[]" value="{{ $data->Student_Id }}" class="checkbox"></center></td>
                  <td style="padding-left:1%;">{{ $data->Nim }}</td>
                  <td>{{ $data->Full_Name }}</td>
                  <!-- <td>

                  </td> -->


              </tr>
              <?php
              $a++;
            }
            ?>
          </tbody>
        </table>
        <br><center><button type="submit" class="btn btn-primary btn-flat">Tambah</button></center>
        {!! Form::close() !!}
        </div>
        <?php
        // echo $query->render('vendor.pagination.bootstrap-4');
        ?>
      </div>
    </div>
  </div>
<script type="text/javascript">
function Change(checkbox) {
    var id = $(checkbox).val();
    if(checkbox.checked == true){
      list = document.getElementsByClassName("checkbox"+id);
      for (index = 0; index < list.length; ++index) {
        // list[index].setAttribute("disabled","disabled");
        list[index].checked = true;
        // list[index].setAttribute("checked","checked");
      }
    }else {
      list = document.getElementsByClassName("checkbox"+id);
      for (index = 0; index < list.length; ++index) {
        // list[index].removeAttribute("disabled");
        list[index].checked = false;
        // list[index].removeAttribute("checked");
      }
    }
}
</script>
</section>
@endsection
