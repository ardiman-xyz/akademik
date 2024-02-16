@extends('layouts._layout')
@section('pageTitle', 'Religion')
@section('content')

<?php
$access = auth()->user()->akses();
          $acc = $access;
?>

<section class="content">

  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Agama</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <?php $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; ?>
          @if(in_array('religion-CanAdd', $acc)) <a href="{{ url('master/religion/create?page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-success btn-sm">Tambah data &nbsp;<i class="fa fa-plus"></i></a>@endif
        </div>
        <b>Agama</b>
      </div>
    </div>
    <br>
        <div class="bootstrap-admin-box-title right text-white">
          <!-- <b>Daftar Fakultas</b> -->
          {!! Form::open(['url' => route('religion.index') , 'method' => 'GET', 'name' => 'form', 'class' => 'row text-green', 'style' => 'padding-top:5px;padding-left:15px;padding-right:15px;', 'role' => 'form']) !!}
          <div  class="row col-md-7">
          <label class="col-md-3">Pencarian :</label>
          <input type="text" name="search"  class="form-control form-control-sm col-md-7 col-sm-7" value="{{ $search }}" placeholder="Search">
          <input type="submit" name="" class="btn btn-primary btn-sm col-md-2 col-sm-2" value="Cari">
          </div>

          {!! Form::close() !!}
        </div>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        @if (count($errors) > 0)
          @foreach ( $errors->all() as $error )
            <p id="message" class="alert alert-danger">{{ $error }}</p>
          @endforeach
        @endif
        <br>
        <div class="table-responsive">
        <table class="table table-striped table-font-sm">
          <thead class="thead-default thead-green">
              <tr>
                  <!-- <th class="col-sm-1">No</th> -->
                  {{-- <th width="5%">No</th> --}}
                  <th width="20%">Kode Agama</th>
                  <th width="25%">Nama Agama</th>
                  <th width="30%">No Urut</th>
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
                  <td>{{ $data->Religion_Code }}</td>
                  <td>{{ $data->Religion_Name }}</td>
                  <td>{{ $data->Order_Id }}</td>
                  <td align="center">
                      {{-- {!! Form::button('Hapus', ['type'=>'submit','class'=>'btn btn-danger btn-sm hapus','data-id'=>$data->Religion_Id]) !!} --}}
                      {!! Form::open(['url' => route('religion.destroy', $data->Religion_Id) , 'id' => 'hapus', 'method' => 'delete', 'role' => 'form']) !!}
                      @if(in_array('religion-CanEdit', $acc)) <a href="{{ url('master/religion/'.$data->Religion_Id.'/edit?page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-info btn-sm">Edit</a> @endif
                      @if(in_array('religion-CanDelete', $acc)) {!! Form::button('Hapus', ['type'=>'submit','class'=>'btn btn-danger btn-sm hapus','data-id'=>$data->Religion_Id]) !!} @endif
                      {!! Form::close() !!}
                  </td>
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
                  url: "{{ url('') }}/master/religion/" + id,
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
