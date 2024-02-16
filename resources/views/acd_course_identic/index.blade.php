@extends('layouts._layout')
@section('pageTitle', 'Course Identic')
@section('content')

<?php
$access = auth()->user()->akses();
          $acc = $access;
?>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Matakuliah Setara</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          <?php $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; ?>
          @if($department != null && $department != 0)
          <div class="pull-right tombol-gandeng dua">
            @if(in_array('course_identic-CanAdd', $acc)) <a href="{{ url('parameter/course_identic/create/?department='.$department.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search ) }}" class="btn btn-success btn-sm">Tambah data &nbsp;<i class="fa fa-plus"></i></a>@endif
          </div>
          @endif
          {!! Form::open(['url' => route('course_identic.index') , 'method' => 'GET', 'name' => 'form', 'role' => 'form']) !!}
          <b>Matakuliah Setara</b>
        </div>
      </div>
      <br>
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
        <table class="table table-striped table-font-sm">
          <thead class="thead-default thead-green">
              <tr>
                  <!-- <th class="col-sm-1">No</th> -->
                  <th width="10%">ID</th>
                  <th width="40%">Matakuliah Setara</th>
                  <th width="40%">Matakuliah</th>
                  @if(in_array('course_identic-CanEdit', $acc) || in_array('course_identic-CanDelete', $acc))
                  <th width="10%"><center><i class="fa fa-gear"></i></center></th>
                  @endif
              </tr>
          </thead>
          <tbody>
            <?php
            $a = "1";
            // $property_types = array();
            foreach($query as $filter_result){
                // if ( in_array($filter_result->Course_Identic_Id, $property_types) ) {
                //     continue;
                // }
                // $property_types[] = $filter_result->Course_Identic_Id;
              // $prodi = DB::table('prodi')->join('prodi_user','prodi_user.id_prodi','=','prodi.id_prodi')->join('users','users.id','=','prodi_user.id_user')->where('users.id',$data->id)->get();
              // $prod = auth()->user()->Prodi();
              // if (count($prod)==0) {
              ?>
              <tr>
                  <!-- <th></th> -->
                  <td>{{ $filter_result->Course_Identic_Id }}</td>
                  <td>{{ $filter_result->Identic_Name }}</td>
                  <td>
                    <?php
                      $matkul = explode('|', $filter_result->matakuliah);
                      $kodematkul = explode('|', $filter_result->kodematakuliah);
                      $n = 0;
                      foreach ($matkul as $key) {
                        // if ($key->Course_Identic_Id == $filter_result->Course_Identic_Id) {
                          // if ($key->Course_Id != null) {
                            echo "<div class='btn btn-sm' style='background:#3ac98d; color:#fff; cursor:default; margin:1px;'>( ".$kodematkul[$n]." ) ".$key."</div>";
                          // }
                        // }
                        $n++;
                      }
                    ?>
                  </td>
                  @if(in_array('course_identic-CanEdit', $acc) || in_array('course_identic-CanDelete', $acc))
                  <td align="center">
                      {!! Form::open([ 'route' => ['course_identic.destroy', $filter_result->Course_Identic_Id] , 'method' => 'delete', 'role' => 'form']) !!}
                      @if(in_array('course_identic-CanEdit', $acc))<a href="{{ url('parameter/course_identic/'.$filter_result->Course_Identic_Id.'/edit?page='.$page.'&rowpage='.$rowpage.'&search='.$search.'&department='.$department) }}" class="btn btn-info btn-sm">Edit</a>@endif
                      @if(in_array('course_identic-CanDelete', $acc)){!! Form::button('Hapus', ['type'=>'submit','class'=>'btn btn-danger btn-sm hapus','data-id'=>$filter_result->Course_Identic_Id]) !!}@endif
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
                  url: "{{ url('') }}/parameter/course_identic/" + id,
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
