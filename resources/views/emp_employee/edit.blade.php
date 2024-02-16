@extends('layouts._layout')
@section('pageTitle', 'Employee')
@section('content')

<?php

foreach ($query_edit as $data_edit) {

?>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Edit Pegawai / Dosen</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('master/employee?page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Edit</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">

          @if (count($errors) > 0)
            @foreach ( $errors->all() as $error )
              @if ($error=="Berhasil Menyimpan Perubahan")
                <p class="alert alert-success">{{ $error }}</p>
              @else
                <p class="alert alert-danger">{{ $error }}</p>
              @endif
            @endforeach
          @endif
          {!! Form::open(['url' => route('employee.update', $data_edit->Employee_Id) , 'method' => 'PUT', 'class' => 'form-horizontal', 'role' => 'form']) !!}
          <div class="form-group">
            {!! Form::label('', 'NIK', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Nik" value="{{ $data_edit->Nik }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'NIP', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Nip" value="{{ $data_edit->Nip }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Nama Pegawai / Dosen', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Name" value="{{ $data_edit->Name }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Gelar Depan', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="First_Title" value="{{ $data_edit->First_Title }}" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Gelar Belakang', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Last_Title" value="{{ $data_edit->Last_Title }}" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Status', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select class="form-control form-control-sm" name="Employee_Status_Id">
                @foreach ( $select_status as $data )
                  <option  <?php if( $data->Employee_Status_Id  == $data_edit->Employee_Status_Id ){ echo "selected"; } ?> value="{{ $data->Employee_Status_Id }}">{{ $data->Description }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Email', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="email" name="Email_Corporate" value="{{ $data_edit->Email_Corporate }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
            </div>
          </div>
          <br><center><button type="submit" class="btn btn-primary btn-flat">Simpan</button></center>

          {!! Form::close() !!}
      </div>
    </div>
  </div>

<!-- /.row -->

</section>
<!-- <script type="text/javascript">

$('[data-onload]').each(function(){
    eval($(this).data('onload'));
});

function handleChange(checkbox) {
    if(checkbox.checked == true){
      list = document.getElementsByClassName("fakultas");
      for (index = 0; index < list.length; ++index) {
        list[index].setAttribute("disabled","disabled");
        list[index].checked = false;
      }
    }else {
      list = document.getElementsByClassName("fakultas");
      for (index = 0; index < list.length; ++index) {
        list[index].removeAttribute("disabled");
      }
    }
}
  function Change(checkbox) {
      var id = $(checkbox).val();
      if(checkbox.checked == true){
        list = document.getElementsByClassName("prodi"+id);
        for (index = 0; index < list.length; ++index) {
          // list[index].setAttribute("disabled","disabled");
          list[index].checked = true;
          // list[index].setAttribute("checked","checked");
        }
      }else {
        list = document.getElementsByClassName("prodi"+id);
        for (index = 0; index < list.length; ++index) {
          // list[index].removeAttribute("disabled");
          list[index].checked = false;
          // list[index].removeAttribute("checked");
        }
      }
  }
  function ubah(id) {
      if($('.prodi'+id+':checked').length == $('.prodi'+id+'').length){
        list = document.getElementsByClassName("fac"+id);
        for (index = 0; index < list.length; ++index) {
          list[index].checked = true;
        }
      }else {
        list = document.getElementsByClassName("fac"+id);
        for (index = 0; index < list.length; ++index) {
          list[index].checked = false;
        }
      }
  }
</script> -->
<?php
}
?>
@endsection
