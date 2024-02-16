@extends('layouts._layout')
@section('pageTitle', 'Entry Year')
@section('content')

<?php
$access = auth()->user()->akses();
          $acc = $access;
?>

<section class="content">

  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Angkatan</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
      <div class="pull-right tombol-gandeng dua">
          <?php $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; ?>
          <!-- <a href="{{ url('master/entry_year/create?page='.$page.'&rowpage='.$rowpage) }}" class="btn btn-success" style="font-size:medium">Tambah data &nbsp;<i class="glyphicon icon-plus-sign"></i></a> -->
          {!! Form::open(['url' => route('entry_year.store') , 'method' => 'POST', 'name' => 'form', 'class' => 'pull-right tombol-gandeng dua', 'role' => 'form']) !!}
            @if(in_array('entry_year-CanAdd', $acc))<button style="font-size:14px;" type="submit" class="btn btn-success btn-sm" name="">Tambah data <i class="fa fa-plus"></i></button>@endif
          {!! Form::close() !!}
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Angkatan</b>
        </div>
      </div>
      <br>
        <div class="bootstrap-admin-box-title right text-green">
          <!-- <b>Daftar Angkatan</b> -->
          {!! Form::open(['url' => route('entry_year.index') , 'method' => 'GET', 'name' => 'forms', 'class' => 'row text-green', 'style' => 'padding-top:5px;padding-left:15px;padding-right:15px;', 'role' => 'form']) !!}
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
          {!! Form::close() !!}
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        @if (count($errors) > 0)
          @foreach ( $errors->all() as $error )
            @if ($error=="Berhasil Menambah Angkatan")
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
                  <!-- <th class="col-sm-1">No</th> -->
                  <th width="5%">No</th>
                  <th width="40%">Kode Angkatan</th>
                  <th width="40%">Nama Angkatan</th>
                  @if(in_array('entry_year-CanDelete', $acc))<th width="15%"><center><i class="fa fa-gear"></i></center></th>@endif
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
                  <td>{{ $data->Entry_Year_Code }}</td>
                  <td>{{ $data->Entry_Year_Name }}</td>
                  @if(in_array('entry_year-CanDelete', $acc))<td align="center">
                      {!! Form::open(['url' => route('entry_year.destroy', $data->Entry_Year_Id) , 'method' => 'delete', 'role' => 'form']) !!}
                      {!! Form::button('Hapus', ['type'=>'submit','class'=>'btn btn-danger btn-sm hapus','data-id'=>$data->Entry_Year_Id]) !!}
                      {!! Form::close() !!}
                  </td>@endif
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

  <script type='text/javascript'>
function formsubmit()
{
    document.forms.submit();
}

</script>

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
                  url: "{{ url('') }}/master/entry_year/" + id,
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
       var url = "{{ url('modal/entry_year') }}"
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
