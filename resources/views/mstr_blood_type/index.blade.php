@extends('layouts._layout')
@section('pageTitle', 'Blood Type')
@section('content')

<?php
$access = auth()->user()->akses();
          $acc = $access;
?>

<section class="content">

  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Golongan Darah</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <?php $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; ?>
          @if(in_array('blood_type-CanAdd', $acc)) <a href="{{ url('master/blood_type/create?page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-success btn-sm">Tambah data &nbsp;<i class="fa fa-plus"></i></a>@endif
        </div>
        <b>Index</b>
      </div>
    </div>
    {{-- <br>
        <div class="bootstrap-admin-box-title right text-green">
          <!-- <b>Daftar Fakultas</b> -->
          {!! Form::open(['url' => route('blood_type.index') , 'method' => 'GET', 'name' => 'form', 'class' => 'row', 'style' => 'padding-top:5px;padding-left:15px;padding-right:15px;', 'role' => 'form']) !!}
          <label class="col-md-1">Pencarian:</label>
          <input type="text" name="search"  class="form-control form-control-sm col-md-4" value="{{ $search }}" placeholder="Search">&nbsp
          <input type="submit" name="" class="btn btn-primary btn-sm" value="Cari">&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
          {!! Form::close() !!}
        </div> --}}
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
                  {{-- <th width="5%">No</th> --}}
                  <th width="20%">Kode Golongan Darah</th>
                  <th width="25%">Nama Golongan Darah</th>
                  <th width="30%">Order Id</th>
                  <th width="15%"><center><i class="fa fa-gear"></i></center></th>
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
                  {{-- <td>{{ $a }}</td> --}}
                  <td>{{ $data->Blood_Code }}</td>
                  <td>{{ $data->Blood_Type }}</td>
                  <td>{{ $data->Order_Id }}</td>
                  @if(in_array('blood_type-CanAdd', $acc) || in_array('blood_type-CanDelete', $acc))
                  <td align="center">
                      {!! Form::open(['url' => route('blood_type.destroy', $data->Blood_Type_Id) , 'method' => 'delete', 'role' => 'form']) !!}
                      {{-- <a href="{{ url('master/blood_type/'.$data->Blood_Type_Id.'?page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-success btn-sm">Detail</a> --}}
                      @if(in_array('blood_type-CanEdit', $acc))<a href="{{ url('master/blood_type/'.$data->Blood_Type_Id.'/edit?page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-info btn-sm">Edit</a>@endif
                      @if(in_array('blood_type-CanDelete', $acc)){!! Form::button('Hapus', ['type'=>'submit','class'=>'btn btn-danger btn-sm hapus','data-id'=>$data->Blood_Type_Id]) !!}@endif
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
                  url: "{{ url('') }}/master/blood_type/" + id,
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
