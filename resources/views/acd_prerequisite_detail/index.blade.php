@extends('layouts._layout')
@section('pageTitle', 'Prasyarat')
@section('content')

<?php
$access = auth()->user()->akses();
          $acc = $access;
?>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Prasyarat</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          @if($class_program != null && $class_program != 0 && $department != null && $department != 0 && $curriculum != null && $curriculum != 0)
          <div class="pull-right tombol-gandeng dua">
            <?php $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; ?>
            <a href="{{ url('parameter/prasyarat/create?class_program='.$class_program.'&department='.$department.'&curriculum='.$curriculum.'&semester='.$semester.'&course_id='.$course_id.'&current_page='.$page.'&current_rowpage='.$rowpage.'&current_search='.$search ) }}" class="btn btn-success btn-sm">Tambah data &nbsp;<i class="fa fa-plus"></i></a>
            <a href="{{ url('parameter/course_curriculum?class_program='.$class_program.'&curriculum='.$curriculum.'&semester='.$semester.'&department='.$department.'&page='.$cekpage.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>

          </div>
          @endif
          <b>Prasyarat</b>
        </div>
      </div>
          <br>
        </div>
        <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
          <div  class="row col-md-7">
          <label class="col-md-3">Program Studi :</label>
          <label class="form-control-sm col-md-7 col-sm-7">{{ $departmentpra->Department_Name }}</label>
          </div>
          <div  class="row col-md-7">
          <label class="col-md-3">Kurikulum :</label>
          <label class="form-control-sm col-md-7 col-sm-7">{{ $curprasyarat->Curriculum_Name }}</label>
          </div>
          <div  class="row col-md-7">
          <label class="col-md-3">Matakuliah :</label>
          <label class="form-control-sm col-md-7 col-sm-7">{{ $coursepra->Course_Name }}</label>
          </div>
        </div><br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        @if (count($errors) > 0)
            @foreach ( $errors->all() as $error )
              @if ($error=="Berhasil Menambah Prasyarat" || $error=="Berhasil Menyimpan Perubahan")
                <p class="alert alert-success">{{ $error }}</p>
              @else
                <p class="alert alert-danger">{{ $error }}</p>
              @endif
            @endforeach
          @endif
        <div class="table-responsive">
        <table class="table table-striped table-font-sm">
          <thead class="thead-default thead-green">
              <tr>
                  <th width="5%">No</th>
                  <th width="10%">Jenis Prasyarat</th>
                  <th width="10%">Kode Matakuliah</th>
                  <th width="10%">Nama Matakuliah</th>
                  <th width="5%">Nilai minimum</th>
                  <th width="5%">Value</th>
                  {{-- @if(in_array('course_curriculum-CanEdit', $acc) || in_array('course_curriculum-CanDelete', $acc)) --}}
                  <th width="13%"><center><i class="fa fa-gear"></i></center></th>
                  {{-- @endif --}}
              </tr>
          </thead>
          <tbody>
            <?php
            $a = "1";
            foreach ($prerequisite_detail as $data) {
              // $prodi = DB::table('prodi')->join('prodi_user','prodi_user.id_prodi','=','prodi.id_prodi')->join('users','users.id','=','prodi_user.id_user')->where('users.id',$data->id)->get();
              // $prod = auth()->user()->Prodi();
              // if (count($prod)==0) {
              ?>
              <tr>
                  <th>{{$a}}</th>
                  <td><center>{{ $data->Prerequisite_Type_Name }}</td>
                  <td><center>{{ $data->Course_Code}}</td>
                  <td><center>{{ $data->Course_Name }}</td>
                  <td><center>{{ $data->Grade_Letter }}</td>
                  <td><center>{{ $data->Value }}</td>
                  @if(in_array('course_curriculum-CanEdit', $acc) || in_array('course_curriculum-CanDelete', $acc))
                  <td align="center">
                      {!! Form::open(['url' => route('course_curriculum.destroy', $data->Prerequisite_Detail_Id) , 'method' => 'delete', 'role' => 'form']) !!}
                      <a href="{{ url('parameter/prasyarat/'.$data->Prerequisite_Detail_Id.'/edit'.'?class_program='.$class_program.'&department='.$department.'&curriculum='.$curriculum.'&semester='.$semester.'&page='.$page.'&course_id='.$course_id.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-info btn-sm">Edit</a>
                      {!! Form::button('Hapus', ['type'=>'submit','class'=>'btn btn-danger btn-sm hapus','data-id'=>$data->Prerequisite_Detail_Id]) !!}
                        {{-- <a href="{{ url('parameter/course_curriculum/'.$data->Course_Cur_Id.'/edit'.'?class_program='.$class_program.'&department='.$department.'&curriculum='.$curriculum.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-warning btn-sm">Prasyarat</a> --}}
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
        <?php echo $prerequisite_detail->render('vendor.pagination.bootstrap-4'); ?>
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
                  url: "{{ url('') }}/parameter/prasyarat/" + id,
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
