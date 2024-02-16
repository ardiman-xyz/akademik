@extends('layouts._layout')
@section('pageTitle', 'Grade Department')
@section('content')

<?php
$access = auth()->user()->akses();
          $acc = $access;
?>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Beban Mengajar Dosen</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          <?php $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; ?>
          <div class="pull-right tombol-gandeng dua">
            @if(in_array('beban_mengajar-CanAdd', $acc)) <a href="{{ url('parameter/beban_mengajar/create/?&term_year='.$term_year.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search ) }}" class="btn btn-success btn-sm">Tambah data &nbsp;<i class="fa fa-plus"></i></a>@endif
          </div>
          <b>Grade Nilai</b>
        </div>
      </div>
      <br>
          {!! Form::open(['url' => route('beban_mengajar.index',$term_year) , 'method' => 'GET', 'name' => 'form', 'role' => 'form', 'style' => 'padding-top:5px;padding-left:15px;padding-right:15px;']) !!}
          <div class="row text-green"  style="padding-top:5px;padding-left:15px;padding-right:15px;">
            <div class="row col-md-7">
            <label class="col-md-3">Tahun Semester :</label>
            <select class="form-control form-control-sm col-md-9" name="term_year"  onchange="document.form.submit();">
              <option value="0">Pilih Tahun Semester</option>
              @foreach ( $select_term_year as $data )
                <option <?php if($term_year == $data->Term_Year_Id){ echo "selected"; } ?> value="{{ $data->Term_Year_Id }}">{{ $data->Term_Year_Name }}</option>
              @endforeach
            </select>
            <div class="col-md-2">
            </div>
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
              @if ($error=="Berhasil Menyimpan Perubahan")
                <p class="alert alert-success">{{ $error }}</p>
              @elseif($error=="Berhasil Menambah Beban Mengajar")
                <p class="alert alert-success">{{ $error }}</p>
              @else 
                <p class="alert alert-danger">{{ $error }}</p>
              @endif
            @endforeach
          @endif
        <div class="table-responsive">
        <table id="tbl" class="table table-striped table-font-sm">
          <thead class="thead-default thead-green">
              <tr>
                  <th width="15%">No</th>
                  <th width="15%">Tahun/Semester</th>
                  <th width="15%">Employee_Id</th>
                  <th width="15%">Beban Sks</th>
                  <!-- <th width="15%">Predikat (English)</th>
                  <th width="15%">Batas Atas</th>
                  <th width="10%">Batas Bawah</th> -->
                  @if(in_array('beban_mengajar-CanEdit', $acc) || in_array('beban_mengajar-CanDelete', $acc))
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
                  <td>{{$a}}</td>
                  <td>{{$data->Term_Year_Name}}</td>
                  <td>{{$data->First_Title}} {{$data->Name}} {{$data->Last_Title}}</td>
                  <td>{{$data->Sks}}</td>
                  @if(in_array('beban_mengajar-CanEdit', $acc) || in_array('beban_mengajar-CanDelete', $acc))
                  <td align="center">
                      {!! Form::open(['url' => route('beban_mengajar.destroy', $data->Lecturer_Work_Load_Id) , 'method' => 'delete', 'role' => 'form']) !!}
                      @if(in_array('beban_mengajar-CanEdit', $acc)) <a href="{{ url('parameter/beban_mengajar/'.$data->Lecturer_Work_Load_Id.'/edit?page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-info btn-sm">Edit</a>@endif
                      @if(in_array('beban_mengajar-CanDelete', $acc)) {!! Form::button('Hapus', ['type'=>'submit','class'=>'btn btn-danger btn-sm hapus','data-id'=>$data->Lecturer_Work_Load_Id]) !!}@endif
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
                url: "{{ url('') }}/parameter/beban_mengajar/" + id,
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
</section>
@endsection
