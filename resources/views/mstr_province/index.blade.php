@extends('layouts._layout')
@section('pageTitle', 'Province')
@section('content')

<?php
$access = auth()->user()->akses();
          $acc = $access;
?>

<section class="content">

  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Provinsi</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <?php $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; ?>
          @if(in_array('province-CanAdd', $acc)) <a href="{{ url('master/province/create?page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-success btn-sm">Tambah data &nbsp;<i class="fa fa-plus"></i></a>@endif
        </div>
        <b>Provinsi</b>
      </div>
    </div>
    <br>
        <div class="bootstrap-admin-box-title right text-white">
          <!-- <b>Daftar Fakultas</b> -->
          {!! Form::open(['url' => route('province.index') , 'method' => 'GET', 'name' => 'form', 'class' => 'row text-green', 'style' => 'padding-top:5px;padding-left:15px;padding-right:15px;', 'role' => 'form']) !!}
          <div  class="row col-md-7">
          <label class="col-md-2">Pencarian :</label>
          <input type="text" name="search"  class="form-control form-control-sm col-md-8 col-sm-8" value="{{ $search }}" placeholder="Search">
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
          <!-- <input type="number" name="rowpage" class="form-control form-control-sm col-md-3" value="{{ $rowpage }}" placeholder="Row Page">&nbsp -->
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
                  <th width="15%">Kode Provinsi</th>
                  <th width="20%">Nama Provinsi</th>
                  <th width="15%">Akronim Provinsi</th>
                  <th width="10%">Nomor Urut</th>
                  <th width="15%">Nama Negara</th>
                  @if(in_array('province-CanEdit', $acc) || in_array('province-CanDelete', $acc))<th width="15%"><center><i class="fa fa-gear"></i></center></th>@endif
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
                  <td>{{ $data->Province_Code }}</td>
                  <td>{{ $data->Province_Name }}</td>
                  <td>{{ $data->Province_Acronym }}</td>
                  <td>{{ $data->Order_Id }}</td>
                  <td>{{ $data->Country_Name }}</td>
                  @if(in_array('province-CanEdit', $acc) || in_array('province-CanDelete', $acc))
                  <td align="center">
                      {!! Form::open(['url' => route('province.destroy', $data->Province_Id) , 'method' => 'delete', 'role' => 'form']) !!}
                      <!-- <a href="{{ url('master/province/'.$data->Province_Id.'?page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-success btn-sm">Detail</a> -->
                      @if(in_array('province-CanEdit', $acc)) <a href="{{ url('master/province/'.$data->Province_Id.'/edit?page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-info btn-sm">Edit</a>@endif
                      @if(in_array('province-CanDelete', $acc)) {!! Form::button('Hapus', ['type'=>'submit','class'=>'btn btn-danger btn-sm hapus','data-id'=>$data->Province_Id]) !!}@endif
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
                  url: "{{ url('') }}/master/province/" + id,
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
