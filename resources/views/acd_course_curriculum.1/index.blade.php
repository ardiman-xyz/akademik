@extends('layouts._layout')
@section('pageTitle', 'Course Curriculum')
@section('content')

<?php
$access = auth()->user()->akses();
          $acc = $access;
?>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Matakuliah Kurikulum</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          @if($class_program != null && $class_program != 0 && $department != null && $department != 0 && $curriculum != null && $curriculum != 0)
          <div class="pull-right tombol-gandeng dua">
            <?php $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; ?>
            @if(in_array('course_curriculum-CanAdd', $acc)) <a href="{{ url('parameter/course_curriculum/create?class_program='.$class_program.'&department='.$department.'&curriculum='.$curriculum.'&semester='.$semester.'&current_page='.$page.'&current_rowpage='.$rowpage.'&current_search='.$search ) }}" class="btn btn-success btn-sm">Tambah data &nbsp;<i class="fa fa-plus"></i></a>@endif
          </div>
          @endif
          <b>Matakuliah Kurikulum</b>
        </div>
      </div>
          <!-- <b>Daftar Fakultas</b> -->
          {!! Form::open(['url' => route('course_curriculum.index') , 'method' => 'GET', 'name' => 'form', 'role' => 'form', 'style' => 'margin:2%;']) !!}
          <div class="row  col-md-12 text-green">
            <label class="col-md-2">Program Studi :</label>
            <select class="form-control form-control-sm col-md-4" name="department"  onchange="document.form.submit();">
              <option value="">Pilih Program Studi</option>
              @foreach ( $select_department as $data )
                <option <?php if($department == $data->Department_Id){ echo "selected"; } ?> value="{{ $data->Department_Id }}">{{ $data->Department_Name }}</option>
              @endforeach
            </select>
            <label class="col-md-2">Program Kelas :</label>
            <select class="form-control form-control-sm col-md-4" name="class_program"  onchange="document.form.submit();">
              <option value="">Pilih Program Kelas</option>
              @foreach ( $select_class_program as $data )
                <option <?php if($class_program == $data->Class_Prog_Id){ echo "selected"; } ?> value="{{ $data->Class_Prog_Id }}">{{ $data->Class_Program_Name }}</option>
              @endforeach
            </select>
          </div>
          <div class="row  col-md-12 text-green">
            <label class="col-md-2" >Kurikulum :</label>
            <select class="form-control form-control-sm col-md-4" name="curriculum" onchange="document.form.submit();">
              <option value="">Pilih Kurikulum</option>
              @foreach ( $select_curriculum as $data )
                <option <?php if($curriculum == $data->Curriculum_Id){ echo "selected"; } ?>  value="{{ $data->Curriculum_Id }}">{{ $data->Curriculum_Name }}</option>
              @endforeach
            </select>
            <label class="col-md-2" >Semester :</label>
            <select class="form-control form-control-sm col-md-4" name="semester" onchange="document.form.submit();">
              <option value="">Pilih Semester</option>
              @foreach ( $select_semester as $data )
                <option <?php if($semester == $data->Study_Level_Id){ echo "selected"; } ?>  value="{{ $data->Study_Level_Id }}">{{ $data->Level_Name }}</option>
              @endforeach
            </select>
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
                  <!-- <th class="col-sm-1">No</th> -->
                  <th width="7%">Kode Matakuliah</th>
                  <th width="7%">Nama Matakuliah</th>
                  <th width="5%">SKS</th>
                  <th width="5%">Transkrip</th>
                  <th width="5%">SKS Transkrip</th>
                  <th width="5%">Sifat</th>
                  <th width="7%">Nama Kelompok</th>
                  <th width="5%">SMT</th>
                  <!-- <th width="5%">Sub SMT</th> -->
                  <th width="5%">Jenis Kurikulum</th>
                  @if(in_array('course_curriculum-CanEdit', $acc) || in_array('course_curriculum-CanDelete', $acc))
                  <th width="13%"><center><i class="fa fa-gear"></i></center></th>
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
              <tr <?php if($data->Applied_Sks == null || $data->Study_Level_Id == null || $data->Transcript_Sks == null) { echo "style='color:red;'"; } ?>>
                  <!-- <th></th> -->
                  <td><center>{{ $data->Course_Code }}</td>
                  <td><center>{{ $data->Course_Name }}</td>
                  <td><center>{{ $data->Applied_Sks }}</td>
                  <td><center>
                    @if($data->Is_For_Transcript == true)
                    <label>Ya</label>
                    @else
                    <label>Tidak</label>
                    @endif
                  </td>
                  <td><center>{{ $data->Transcript_Sks }}</td>
                  <td><center>
                    @if($data->	Is_Required == true)
                    <label>Wajib</label>
                    @else
                    <label>Pilihan</label>
                    @endif
                  </td>
                  <td><center>{{ $data->Name_Of_Group }}</td>
                  <td><center>{{ $data->Study_Level_Code }}</td>
                  <!-- <td><center>{{ $data->Study_Level_Sub }}</td> -->
                  <td><center>{{ $data->Curriculum_Type_Name }}</td>
                  @if(in_array('course_curriculum-CanEdit', $acc) || in_array('course_curriculum-CanDelete', $acc))
                  <td align="center">
                      {!! Form::open(['url' => route('course_curriculum.destroy', $data->Course_Cur_Id) , 'method' => 'delete', 'role' => 'form']) !!}
                      @if(in_array('course_curriculum-CanEdit', $acc)) <a href="{{ url('parameter/course_curriculum/'.$data->Course_Cur_Id.'/edit'.'?class_program='.$class_program.'&department='.$department.'&curriculum='.$curriculum.'&semester='.$semester.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-info btn-sm">Edit</a>@endif
                      @if(in_array('course_curriculum-CanDelete', $acc)) {!! Form::button('Hapus', ['type'=>'submit','class'=>'btn btn-danger btn-sm hapus','data-id'=>$data->Course_Cur_Id]) !!}@endif
                        <a href="{{ url('parameter/prasyarat?class_program='.$class_program.'&department='.$department.'&curriculum='.$curriculum.'&semester='.$semester.'&page='.$page.'&course_id='.$data->Course_Id.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-warning btn-sm">Prasyarat</a>
                        <a href="{{ url('parameter/prasyarat?class_program='.$class_program.'&department='.$department.'&curriculum='.$curriculum.'&semester='.$semester.'&page='.$page.'&course_id='.$data->Course_Id.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-warning btn-sm">Silabus</a>
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
                  url: "{{ url('') }}/parameter/course_curriculum/" + id,
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
  <!-- <script type="text/javascript">
     $(document).ready(function () {
     $(".hapus").click(function(e) {
       var url = "{{ url('modal/faculty') }}"
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
