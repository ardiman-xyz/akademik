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
        <b>Peserta Matakulian Pertemuan ke- {{$pertemuan->Meeting_Order}}</b>
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
      <br>
      <div class="table-responsive">
      {!! Form::open(['url' => route('schedreal.storepeserta') , 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form']) !!}
      <input type="hidden" name="Sched_Real_Id" value="{{ $schedreal }}">
      <table class="table table-striped table-font-sm">
        <thead class="thead-default thead-green">
            <tr>
                <th width="15%">NIM</th>
                <th width="75%">Nama</th>
                <th width="5%">Hadir @if(in_array('schedreal-CanEditPeserta', $acc))<input disable type="checkbox" id="hadirall" />@endif</th>
                <th width="5%">Alpha @if(in_array('schedreal-CanEditPeserta', $acc))<input disable type="checkbox" id="tidakhadirall" />@endif</th>
                <th width="5%">Sakit @if(in_array('schedreal-CanEditPeserta', $acc))<input disable type="checkbox" id="sakitall" />@endif</th>
                <th width="5%">Ijin @if(in_array('schedreal-CanEditPeserta', $acc))<input disable type="checkbox" id="ijinall" />@endif</th>
                <th width="5%">Mbkm @if(in_array('schedreal-CanEditPeserta', $acc))<input disable type="checkbox" id="mbkmall" />@endif</th>
              </th>                
            </tr>
        </thead>
        <tbody>
          @foreach($datas as $data)
            <tr>
              <td>{{ $data->Nim }}</td>
              <td>{{ $data->Full_Name }}</td>
              <td><center>
              <input <?php if(!in_array('schedreal-CanEditPeserta', $acc)){echo"disable";} ?> <?php if(in_array($data->StudentId,$datapeserta)){ echo "checked"; } ?> type="checkbox" class="student" name="Student_Id[]" value="{{ $data->StudentId }}">
              </center></td>
              <td><center>
              <input <?php if(!in_array('schedreal-CanEditPeserta', $acc)){echo"disable";} ?> <?php if(in_array($data->StudentId,$datapesertatidakhadir)){ echo "checked"; } ?> type="checkbox" class="studenttidakhadir" name="tidakhadirStudent_Id[]" value="{{ $data->StudentId }}">
              </center></td>
              <td><center>
              <input <?php if(!in_array('schedreal-CanEditPeserta', $acc)){echo"disable";} ?> <?php if(in_array($data->StudentId,$datapesertasakit)){ echo "checked"; } ?> type="checkbox" class="studentsakit" name="sakitStudent_Id[]" value="{{ $data->StudentId }}">
              </center></td>
              <td><center>
              <input <?php if(!in_array('schedreal-CanEditPeserta', $acc)){echo"disable";} ?> <?php if(in_array($data->StudentId,$datapesertaijin)){ echo "checked"; } ?> type="checkbox" class="studentijin" name="ijinStudent_Id[]" value="{{ $data->StudentId }}">
              </center></td>
              <td><center>
              <input <?php if(!in_array('schedreal-CanEditPeserta', $acc)){echo"disable";} ?> <?php if(in_array($data->StudentId,$datapesertambkm)){ echo "checked"; } ?> type="checkbox" class="studentmbkm" name="mbkmStudent_Id[]" value="{{ $data->StudentId }}">
              </center></td>
            </tr>
          @endforeach
        </tbody>
      </table>
      @if(in_array('schedreal-CanEditPeserta', $acc))
      <input type="submit" class="btn btn-info" value="Simpan">
      @endif
      {!! Form::close() !!}
      </div>

    </div>
  </div>
</div>


<script>
$(document).ready(function () {
      $('#hadirall').click(function () {
          $('.student').prop("checked", this.checked);
      });
      $('#tidakhadirall').click(function () {
          $('.studenttidakhadir').prop("checked", this.checked);
      });
      $('#mbkmall').click(function () {
          $('.studentmbkm').prop("checked", this.checked);
      });
    });
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