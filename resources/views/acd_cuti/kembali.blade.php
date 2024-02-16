@extends('layouts._layout')
@section('pageTitle', 'Aktif Kembali')
@section('content')

<?php
$access = auth()->user()->akses();
          $acc = $access;
?>

<section class="content">

  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Mahasiswa Aktif Kembali</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <?php $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; ?>
          
            {{-- @if(in_array('cuti-CanAdd', $acc)) <a href="{{ url('proses/cuti/create?page='.$page.'&rowpage='.$rowpage.'&search='.$search.'&term_year='.$term_year) }}" class="btn btn-success btn-sm">Tambah data &nbsp;<i class="fa fa-plus"></i></a> @endif --}}
          
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Mahasiswa Aktif Kembali</b>
        </div>
      </div>
      <br>
          <!-- <b>Daftar Fakultas</b> -->
          {!! Form::open(['url' => route('kembali') , 'method' => 'GET', 'name' => 'form', 'role' => 'form']) !!}
          <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            <div  class="row col-md-7">
              <label class="col-md-3">Tahun / Semester :</label>
            <select class="form-control form-control-sm col-md-7" name="term_year"  onchange="document.form.submit();">
                <option value="0">Pilih Tahun / Semester</option>
                @foreach ( $select_term_year as $data )
                  <option <?php if($term_year == $data->Term_Year_Id){ echo "selected"; } ?>  value="{{ $data->Term_Year_Id }}">{{ $data->Term_Year_Name }}</option>
                @endforeach
              </select>&nbsp
          </div>
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
              <option <?php if($rowpage == "10"){ echo "selected"; } ?> value="">10 Baris</option>
              <option <?php if($rowpage == "20"){ echo "selected"; } ?> value="20">20 Baris</option>
              <option <?php if($rowpage == "50"){ echo "selected"; } ?> value="50">50 Baris</option>
              <option <?php if($rowpage == "100"){ echo "selected"; } ?> value="100">100 Baris</option>
              <option <?php if($rowpage == "1000000"){ echo "selected"; } ?> value="1000000">semua</option>
            </select>
          </div><br>
        </div><br>
        {!! Form::close() !!}
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
                {{-- <th width="5%">No</th> --}}
                <th width="10%">Nim</th>
                <th width="10%">Mahasiswa</th>
                <th width="10%">SK Cuti</th>
                <th width="10%">File SK Cuti</th>
                <th width="15%">SK Aktif Kembali</th>
                <th width="10%">tanggal SK Aktif Kembali</th>
                <th width="15%">File SK Aktif Kembali</th>
                @if(in_array('cuti-CanDelete', $acc))
                <th width="5%"><center><i class="fa fa-gear"></i></center></th>
                @endif
              </tr>
          </thead>
          <tbody>
            <?php
            $a = "1";
            foreach ($cuti as $data) {
              // $prodi = DB::table('prodi')->join('prodi_user','prodi_user.id_prodi','=','prodi.id_prodi')->join('users','users.id','=','prodi_user.id_user')->where('users.id',$data->id)->get();
              // $prod = auth()->user()->Prodi();
              // if (count($prod)==0) {
              ?>
              <tr>
                  <!-- <th></th> -->
                  {{-- <td>{{ $a }}</td> --}}
                  <td>{{ $data->Nim }}</td>
                  <td><center>{{ $data->Full_Name }}</center></td>
                  <td><center>{{ $data->Sk_Number }}</center></td>
                  <td><center><a href="{{ url('') }}/storage/{{ $data->File }}" target="_blank">{{ $data->File }}</a> </center></td>
                  <td><center>{{ $data->Sk_Number_Active }}</center></td>
                  <td><center>{{ $data->Sk_Date_Active }}</center></td>
                  <td><center><a href="{{ url('') }}/storage/{{ $data->File_Active }}" target="_blank">{{ $data->File_Active }}</a> </center></td>
                  @if(in_array('cuti-CanDelete', $acc))
                  <td align="center">
                  <a href="{{ url('proses/kembali/'.$data->Student_Vacation_Id.'/edit?&page='.$page.'&rowpage='.$rowpage.'&search='.$search.'&term_year='.$term_year) }}" class="btn btn-info btn-sm">Edit</a>
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
        <?php echo $cuti->render('vendor.pagination.bootstrap-4'); ?>
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
                  url: "{{ url('') }}/proses/cuti/" + id,
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
