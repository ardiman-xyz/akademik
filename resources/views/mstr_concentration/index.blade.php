@extends('layouts._layout')
@section('pageTitle', 'Concentration')
@section('content')

<?php
$access = auth()->user()->akses();
          $acc = $access;
?>

<section class="content">

  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Konsentrasi</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          <div class="pull-right tombol-gandeng dua">
            @if($fakultas != null && $fakultas != 0 && $department != null && $department != 0)
            <div>
              <?php $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; ?>
              @if(in_array('concentration-CanAdd', $acc)) <a href="{{ url('master/concentration/create/'.$fakultas.'?department='.$department.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search ) }}" class="btn btn-success btn-sm">Tambah data &nbsp;<i class="fa fa-plus"></i></a>@endif
            </div>
            @endif
          </div>
          <b>Konsentrasi</b>
        </div>
      </div>
          <!-- <b>Daftar Departemen</b> -->
          {!! Form::open(['url' => route('concentration.index',$fakultas) , 'method' => 'GET', 'name' => 'form', 'role' => 'form', 'style' => 'margin:2%;']) !!}
          <div class="row text-green">
            <div class="row col-md-7">
            <label class="col-md-3">Departemen :</label>
            <select class="form-control form-control-sm col-md-7" name="fakultas" id="pilih">
              <option value="0">Pilih Departemen</option>
              @foreach ( $select_fakultas as $data )
                <option <?php if($fakultas == $data->Faculty_Id){ echo "selected"; } ?> value="{{ $data->Faculty_Id }}">{{ $data->Faculty_Name }}</option>
              @endforeach
            </select>
            <div class="col-md-2">
            </div>
          </div>
          <div class="row col-md-5">
            <label class="col-md-5">Program Studi :</label>
            <select class="form-control form-control-sm col-md-7" name="department" id="pilihdepartment" onchange="document.form.submit();">
              <option value="0">Pilih Program Studi</option>
              @foreach ( $select_department as $data )
                <option <?php if($department == $data->Department_Id){ echo "selected"; } ?>  value="{{ $data->Department_Id }}">{{ $data->Department_Name }}</option>
              @endforeach
            </select>
            <script type="text/javascript">
                $(document).ready(function () {
                  $("#pilih").change(function () {
                    var url = {!! json_encode(url('/')) !!};
                    var id = $("#pilih").val();
                    window.location = url+"/master/concentration/"+id;
                  });
                });
            </script>
          </div>
          </div>
          <br>
          <div class="row text-green">
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
        <div class="table-responsive">
        <table class="table table-striped table-font-sm">
          <thead class="thead-default thead-green">
              <tr>
                  <!-- <th class="col-sm-1">No</th> -->
                  <th width="3%">No</th>
                  <th width="7%">Kode Konsentrasi</th>
                  <th width="10%">Nama Konsentrasi</th>
                  <th width="10%">Nama Konsentrasi (Eng)</th>
                  <th width="10%">Akronim Konsentrasi</th>
                  <th width="10%">Nama Program Studi</th>
                  @if(in_array('concentration-CanEdit', $acc) || in_array('concentration-CanDelete', $acc))
                  <th width="10%"><center><i class="fa fa-gear"></i></center></th>
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
                  <td>{{ $data->Concentration_Code }}</td>
                  <td>{{ $data->Concentration_Name }}</td>
                  <td>{{ $data->Concentration_Name_Eng }}</td>
                  <td>{{ $data->Concentration_Acronym }}</td>
                  <td>{{ $data->Department_Name }}</td>
                  @if(in_array('concentration-CanEdit', $acc) || in_array('concentration-CanDelete', $acc))
                  <td align="center">
                      {!! Form::open(['url' => route('concentration.destroy', $data->Concentration_Id) , 'method' => 'delete', 'role' => 'form']) !!}
                      @if(in_array('concentration-CanEdit', $acc))<a href="{{ url('master/concentration/'.$data->Concentration_Id.'/edit/'.$fakultas.'?department='.$department.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-info btn-sm">Edit</a>@endif
                      @if(in_array('concentration-CanDelete', $acc)){!! Form::button('Hapus', ['type'=>'submit','class'=>'btn btn-danger btn-sm hapus','data-id'=>$data->Concentration_Id]) !!}@endif
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
                  url: "{{ url('') }}/master/concentration/" + id,
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
