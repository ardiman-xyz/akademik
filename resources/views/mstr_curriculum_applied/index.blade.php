@extends('layouts._layout')
@section('pageTitle', 'Curriculum Applied')
@section('content')

<?php
$access = auth()->user()->akses();
          $acc = $access;
?>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Kurikulum Prodi</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
          <?php $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; ?>
          @if($department != null && $department != 0)
          <div class="pull-right tombol-gandeng dua">
            @if(in_array('curriculum_applied-CanAdd', $acc)) <a href="{{ url('parameter/curriculum_applied/create/?department='.$department.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search ) }}" class="btn btn-success btn-sm">Tambah data &nbsp;<i class="fa fa-plus"></i></a>@endif
          </div>
          @endif
        <b>Kurikulum Prodi</b>
      </div>
    </div>
    <br>
        <div class="bootstrap-admin-box-title right">
          <!-- <b>Daftar Fakultas</b> -->
          {!! Form::open(['url' => route('curriculum_applied.index')   , 'method' => 'GET', 'name' => 'form', 'role' => 'form', 'style' => 'padding-top:5px;padding-left:15px;padding-right:15px;']) !!}


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
        <table class="table table-striped table-font-sm table-sm" >
          <thead class="thead-default thead-green">
              <tr>
                  <!-- <th class="col-sm-1">No</th> -->
                  <th width="5%">No</th>
                  <th width="15%">Nama Kurikulum</th>
                  <th width="15%">Nama Program Kelas</th>
                  <th width="15%">SKS Wajib</th>
                  <th width="10%">SKS Pilihan</th>
                  <th width="10%">IPK Minimal</th>
                  <th width="10%">Min SKS Lulus</th>

                  <!-- <th width="10%">Gedung</th> -->
                  @if(in_array('curriculum_applied-CanEdit', $acc) || in_array('curriculum_applied-CanDelete', $acc))
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
                  <td>{{ $data->Curriculum_Name }}</td>
                  <td>{{ $data->Class_Program_Name }}</td>
                  <td>{{ $data->Total_Sks_Core }}</td>
                  <td>{{ $data->Total_Sks_Elective }}</td>
                  <td>{{ $data->Min_Cum_Gpa }}</td>
                  <td>{{ $data->Sks_Completion }}</td>
                  @if(in_array('curriculum_applied-CanEdit', $acc) || in_array('curriculum_applied-CanDelete', $acc))
                  <td align="center">
                    {!! Form::open(['url' => route('curriculum_applied.destroy', $data->Curiculum_Applied_Id) , 'method' => 'delete', 'role' => 'form']) !!}
                    <table class="table-action">
                      <tr class="table-action">
                        @if(in_array('curriculum_applied-CanEdit', $acc))<td class="table-action">
                          <a href="{{ url('parameter/curriculum_applied/'.$data->Curiculum_Applied_Id.'/edit?page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-info btn-sm">Edit</a>
                        </td>@endif
                        @if(in_array('curriculum_applied-CanDelete', $acc))<td class="table-action">
                          {!! Form::button('Hapus', ['type'=>'submit','class'=>'btn btn-danger btn-sm hapus','data-id'=>$data->Curiculum_Applied_Id]) !!}
                        </td>@endif
                      </tr>
                    </table>
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
                  url: "{{ url('') }}/parameter/curriculum_applied/" + id,
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
