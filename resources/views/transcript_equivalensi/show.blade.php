@extends('layouts._layout')
@section('pageTitle', 'Transkrip Equivalensi')
@section('content')


<?php
$access = auth()->user()->akses();
          $acc = $access;
?>
<section class="content">

  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Nilai Mahasiswa Equivalensi</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          <?php $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; ?>
          <div class="pull-right tombol-gandeng dua">
            @if(in_array('transcript_equivalensi-CanAdd', $acc))<a href="{{ url('proses/transcript_equivalensi/create/?student_id='.$Student_Id.'&department='.$department.'&entry_year='.$entry_year.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search ) }}" class="btn btn-success btn-sm">Tambah data &nbsp;<i class="fa fa-plus"></i></a>@endif
            <!-- @if(in_array('transcript_equivalensi-CanAdd', $acc))<a href="#" onclick="menukosong()" class="btn btn-success btn-sm">Tambah data &nbsp;<i class="fa fa-plus"></i></a>@endif -->
            <a href="{{ url('proses/transcript_equivalensi/?department='.$department.'&entry_year='.$entry_year.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-danger btn-sm" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
          </div>
          <b>Index</b>
        </div>
      </div>
      <br>
      <div class="col-md-12">
        <div class="row">
          <label class="col-md-3" for="">NIM</label>
          <label class="col-md-9" for="">{{ $data->Nim }}</label>
        </div>
        <div class="row">
          <label class="col-md-3" for="">Nama Mahasiswa</label>
          <label class="col-md-9" for="">{{ $data->Full_Name }}</label>
        </div>
      </div>
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
                    <th width="4%" rowspan="1"> </th>
                    <th width="40%" colspan="4">Matakuliah Asal</th>
                    <th width="40%" colspan="4">Matakuliah {{env('NAME_AKRONIM')}}</th>
                    @if(in_array('transcript_equivalensi-CanEdit', $acc) || in_array('transcript_equivalensi-CanDelete', $acc))
                    <th width="16%" rowspan="2"><center><i class="fa fa-gear"></i></center></th>
                    @endif
                </tr>
                <tr>
                    <th>No.</th>
                    <th>Kode Matakuliah</th>
                    <th>Nama Matakuliah</th>
                    <th>SKS</th>
                    <th>Nilai Huruf</th>
                    <th>Kode Matakuliah</th>
                    <th>Nama Matakuliah</th>
                    <th>SKS</th>
                    <th>Nilai Huruf</th>
                </tr>
            </thead>
            <tbody>
              <?php
              $a = "1";
              foreach ($query as $data) {
                ?>
                <tr>
                    <!-- <th></th> -->
                    <td>{{$a}}</td>
                    <td>{{$data->Course_Code_Transfer}}</td>
                    <td>{{$data->Course_Name_Transfer}}</td>
                    <td>{{ $data->Sks_Transfer }}</td>
                    <td>{{$data->Grade_Letter_Transfer}}</td>
                    <td>{{$data->Course_Code}}</td>
                    <td>{{$data->Course_Name}}</td>
                    <td>{{$data->Sks}}</td>
                    <td>{{$data->Grade_Letter}}</td>
                    @if(in_array('transcript_equivalensi-CanEdit', $acc) || in_array('transcript_equivalensi-CanDelete', $acc))
                    <td>
                        {!! Form::open(['url' => route('transcript_equivalensi.destroy', $data->Transcript_Id) , 'method' => 'delete', 'role' => 'form']) !!}
                        @if(in_array('transcript_equivalensi-CanEdit', $acc))<a href="{{ url('proses/transcript_equivalensi/'.$data->Transcript_Id.'/edit?department='.$department.'&entry_year='.$entry_year.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-info btn-sm">Edit</a>@endif
                        <!-- @if(in_array('transcript_equivalensi-CanEdit', $acc))<a href="#" onclick="menukosong()" class="btn btn-info btn-sm">Edit</a>@endif -->
                        @if(in_array('transcript_equivalensi-CanDelete', $acc)){!! Form::button('Hapus', ['type'=>'submit','class'=>'btn btn-danger btn-sm hapus','data-id'=>$data->Transcript_Id]) !!}@endif
                        <!-- @if(in_array('transcript_equivalensi-CanDelete', $acc))<a href="#" onclick="menukosong()" class="btn btn-danger btn-sm">Hapus</a>@endif -->
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

    <script>
    function menukosong(){
        swal('Maaf...!', 'Halaman Masih Dalam Pengerjaan / Perbaikan' , 'warning');
    }
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
                    url: "{{ url('') }}/proses/transcript_equivalensi/" + id,
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
