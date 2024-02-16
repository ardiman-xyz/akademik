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
      <h3 class="text-white">Grade Nilai</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          <?php $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; ?>
          @if($request->entry_year != null && $request->entry_year != 0)
          <div class="pull-right tombol-gandeng dua">
          <!-- <a href="{{ url('/parameter/grade_department/create/copydata?department='.$department.'&term_year='.$term_year) }}" class="btn btn-success btn-sm">Copy data &nbsp;<i class="fa fa-plus"></i></a> -->
            @if(in_array('grade_department-CanAdd', $acc)) <a href="{{ url('parameter/grade_department/create/update_department?department='.$department.'&entry_year='.$request->entry_year.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search ) }}" class="btn btn-success btn-sm">Update Semua Prodi &nbsp;<i class="fa fa-download"></i></a>@endif
            @if(in_array('grade_department-CanAdd', $acc)) <a href="{{ url('parameter/grade_department/create/?department='.$department.'&entry_year='.$request->entry_year.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search ) }}" class="btn btn-success btn-sm">Tambah data &nbsp;<i class="fa fa-plus"></i></a>@endif
          </div>
          @endif
          <b>Grade Nilai</b>
        </div>
      </div>
      <br>
          {!! Form::open(['url' => route('grade_department.index') , 'method' => 'GET', 'name' => 'form', 'role' => 'form']) !!}
          <div class="row text-green"  style="padding-top:5px;padding-left:15px;padding-right:15px;">
          <!-- <div  class="row col-md-7">
            <label class="col-md-3" >Program Studi :</label>
            <select class="form-control form-control-sm col-md-9" name="department" id="pilih" onchange="form.submit()">
              <option value="0">Pilih Program Studi</option>
              @foreach ( $select_Department_Id as $data )
              <option <?php if($department == $data->Department_Id){ echo "selected"; } ?> value="{{ $data->Department_Id }}">{{ $data->Department_Name }}</option>
              @endforeach
            </select>
          </div> -->
          <div  class="row col-md-7">
            <label class="col-md-3">Tahun Angkatan :</label>
            <select class="form-control form-control-sm col-md-9" name="entry_year"  onchange="document.form.submit();">
              <option value="0">Pilih Tahun Angkatan</option>
              @foreach ( $select_entry_year as $data )
                <option <?php if($request->entry_year == $data->Entry_Year_Id){ echo "selected"; } ?> value="{{ $data->Entry_Year_Id }}">{{ $data->Entry_Year_Name }}</option>
              @endforeach
            </select>
          </div>
          <!-- <div  class="row col-md-7">
            <label class="col-md-3">Tahun Semester :</label>
            <select class="form-control form-control-sm col-md-9" name="term_year"  onchange="document.form.submit();">
              <option value="0">Pilih Tahun Semester</option>
              @foreach ( $select_term_year as $data )
                <option <?php if($term_year == $data->Term_Year_Id){ echo "selected"; } ?> value="{{ $data->Term_Year_Id }}">{{ $data->Term_Year_Name }}</option>
              @endforeach
            </select>
          </div> -->
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
              @if ($error=="Berhasil Menambah Grade Nilai" || $error=="Berhasil Menyimpan Perubahan")
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
                  <th width="15%">Nilai Huruf</th>
                  <th width="15%">Bobot</th>
                  <th width="15%">Predikat</th>
                  <th width="15%">Predikat (English)</th>
                  <th width="15%">Batas Atas</th>
                  <th width="10%">Batas Bawah</th>
                  @if(in_array('grade_department-CanEdit', $acc) || in_array('grade_department-CanDelete', $acc))
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
                  <td>{{ $data->Grade_Letter }}</td>
                  <td>{{ $data->Weight_Value }}</td>
                  <td>{{ $data->Predicate }}</td>
                  <td>{{ $data->Predicate_Eng }}</td>
                  <td>{{ $data->Scale_Numeric_Max }}</td>
                  <td>{{ $data->Scale_Numeric_Min }}</td>
                  @if(in_array('grade_department-CanEdit', $acc) || in_array('grade_department-CanDelete', $acc))
                  <td align="center">
                      {!! Form::open(['url' => route('grade_department.destroy', $data->Grade_Department_Id) , 'method' => 'delete', 'role' => 'form']) !!}
                      @if(in_array('grade_department-CanEdit', $acc)) <a href="{{ url('parameter/grade_department/'.$data->Grade_Department_Id.'/edit?page='.$page.'&rowpage='.$rowpage.'&search='.$search.'&entry_year='.$request->entry_year) }}" class="btn btn-info btn-sm">Edit</a>@endif
                      @if(in_array('grade_department-CanDelete', $acc)) {!! Form::button('Hapus', ['type'=>'submit','class'=>'btn btn-danger btn-sm hapus','data-id'=>$data->Grade_Department_Id]) !!}@endif
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
                url: "{{ url('') }}/parameter/grade_department/" + id,
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
