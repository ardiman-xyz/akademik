@extends('layouts._layout')
@section('pageTitle', 'Pertemuan Kuliah')
@section('content')

  <?php
  $access = auth()->user()->akses();
  $acc = $access;
  
  $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; 
?>

<section class="content">

<div class="container-fluid-title">
  <div class="title-laporan">
    <h3 class="text-white">PESERTA Pertemuan Kuliah</h3>
  </div>
</div>
<div class="container">
  <div class="panel panel-default bootstrap-admin-no-table-panel">
    <div class="panel-heading-green">
      <div class="pull-right tombol-gandeng dua">
          <?php $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; ?>
          <a href="{{ url('proses/schedreal/'.$id) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
      </div>
      <div class="bootstrap-admin-box-title right text-white">
        <b>PESERTA</b>
      </div>
    </div>
    <br>
    <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        @if(Session::get('success') == true)
            @if (count($errors) > 0)
              @foreach ( $errors->all() as $error )
                <p class="alert alert-success">{{ $error }}</p>
              @endforeach
            @endif
        @else
          @if (count($errors) > 0)
            @foreach ( $errors->all() as $error )
              <p class="alert alert-danger">{{ $error }}</p>
            @endforeach
          @endif
        @endif

        <div class="row col-md-12 col-xs-12">
          {!! Form::label('', 'Jumlah Pertemuan', ['class' => 'col-md-2 col-xs-12']) !!}:
          <label for="" class="col-md-4 col-xs-12">{{ $totalpertemuan }}</label>
        </div>
      <div class="table-responsive">
      {!! Form::open(['url' => route('schedreal.storepeserta') , 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form']) !!}
      
      <table class="table table-striped table-font-sm">
        <thead class="thead-default thead-green">
            <tr>
                <th width="20%">NIM</th>
                <th width="40%">Nama</th>
                <th width="20%">Kehadiran</th>
                <th width="20%">% Kehadiran Mahasiswa</th>
            </tr>
        </thead>
        <tbody>
          @foreach($datas as $data)
            <tr>
              <td>{{ $data['Nim'] }}</td>
              <td>{{ $data['Full_Name'] }}</td>
              <td><center>{{ $data['Jumlah'] }}</center></td>
              <td><center>{{ $data['Persen'] }}</center></td>
            </tr>
          @endforeach
        </tbody>
      </table>
      {!! Form::close() !!}
      </div>

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
                url: "{{ url('') }}/proses/schedreal/" + id,
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