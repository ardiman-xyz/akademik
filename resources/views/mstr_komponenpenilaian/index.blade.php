@extends('layouts._layout')
@section('pageTitle', 'Komponen Penilaian')
@section('content')

<?php
$access = auth()->user()->akses();
          $acc = $access;
?>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Komponen Penilaian</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          <?php $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; ?>
          <div class="pull-right tombol-gandeng dua">
            @if(in_array('grade_department-CanAdd', $acc)) <a href="{{ url('parameter/komponen_penilaian/create/?&term_year='.$term_year.'&department='.$department.'&rowpage='.$rowpage.'&search='.$search ) }}" class="btn btn-success btn-sm">Tambah data &nbsp;<i class="fa fa-plus"></i></a>@endif
          </div>
          <b>Komponen Penilaian</b>
        </div>
      </div>
      <br>
          {!! Form::open(['url' => route('komponen_penilaian.index') , 'method' => 'GET', 'name' => 'form', 'role' => 'form']) !!}
          <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            <div class="row col-md-7">
              <label class="col-md-3">Tahun Semester :</label>
              <select class="form-control form-control-sm col-md-9" name="term_year"  onchange="document.form.submit();">
                <option value="0">Pilih Tahun Semester</option>
                @foreach ( $select_term_year as $data )
                  <option <?php if($term_year == $data->Term_Year_Id){ echo "selected"; } ?> value="{{ $data->Term_Year_Id }}">{{ $data->Term_Year_Name }}</option>
                @endforeach
              </select>
              </div>
          </div><br>
          <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            <div class="row col-md-7">
              <label class="col-md-3">Prodi :</label>
              <select class="form-control form-control-sm col-md-9" name="department"  onchange="document.form.submit();">
                <option value="0">Pilih Prodi</option>
                @foreach ( $select_Department_Id as $data )
                  <option <?php if($department == $data->Department_Id){ echo "selected"; } ?> value="{{ $data->Department_Id }}">{{ $data->Department_Name }}</option>
                @endforeach
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
                  <th width="15%">Nama Komponen</th>
                  <th width="15%">Bobot</th>
                  <!-- @if(in_array('grade_department-CanEdit', $acc) || in_array('grade_department-CanDelete', $acc)) -->
                  <th width="15%"><center><i class="fa fa-gear"></i></center></th>
                  <!-- @endif -->
              </tr>
          </thead>
          <tbody>
            <?php
            $a = "1";
            foreach ($query as $data) {
              ?>
              <tr>
                  <!-- <th></th> -->
                  <td>{{$a++}}</td>
                  <td>{{$data->Item_Name}}</td>
                  <td><center>{{$data->Bobot}}</td>
                  <!-- @if(in_array('grade_department-CanEdit', $acc) || in_array('grade_department-CanDelete', $acc)) -->
                  <td align="center">
                      {!! Form::open(['url' => route('komponen_penilaian.destroy', $data->Student_Khs_Item_Bobot_Id) , 'method' => 'delete', 'role' => 'form']) !!}
                      <a href="{{ url('parameter/komponen_penilaian/create/?&term_year='.$term_year.'&department='.$department.'page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-info btn-sm">Edit</a>
                      {!! Form::button('Hapus', ['type'=>'submit','class'=>'btn btn-danger btn-sm hapus','data-id'=>$data->Student_Khs_Item_Bobot_Id]) !!}
                      {!! Form::close() !!}
                  </td>
                  <!-- @endif -->
              </tr>
              <?php
              $a++;
            }
            ?>
            <tr>
                <!-- <th></th> -->
                <td bgcolor="#5F9EA0" colspan="2"><center>Total</center></td>
                <td bgcolor="#5F9EA0"><center>{{ $bobot->Tbobot }} %</center></td>
            </tr>
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
                url: "{{ url('') }}/parameter/komponen_penilaian/" + id,
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
