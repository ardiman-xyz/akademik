@extends('layouts._layout')
@section('pageTitle', 'Department')
@section('content')

<?php

foreach ($query_edit as $data_edit) {

?>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Edit Program Studi</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('master/announcement') }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Edit</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
          <?php $Post_Start_Date = Date("Y-m-d",strtotime($data_edit->Post_Start_Date)); ?>
          <?php $Post_End_Date = Date("Y-m-d",strtotime($data_edit->Post_End_Date)); ?>
          @if (count($errors) > 0)
            @foreach ( $errors->all() as $error )
              @if ($error=="Berhasil Menyimpan Perubahan")
                <p class="alert alert-success">{{ $error }}</p>
              @else
                <p class="alert alert-danger">{{ $error }}</p>
              @endif
            @endforeach
          @endif
          {!! Form::open(['url' => route('announcement.update', $data_edit->Announcement_Id) , 'method' => 'put', 'class' => 'form','enctype'=>'multipart/form-data']) !!}
          <div class="form-group">
            {!! Form::label('', 'Prodi', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select class="form-control form-control-sm" name="prodi">
              <option <?php if($data_edit->Department_Id == null){ echo "selected"; } ?> value='0' >Semua Prodi</option>
                @foreach ( $prodi as $data )
                  <option  <?php if($data_edit->Department_Id == $data->Department_Id){ echo "selected"; } ?> value="{{ $data->Department_Id }}">{{ $data->Department_Name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Aplikasi', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select id="Aplikasi" class="form-control form-control-sm col-md-12" name="Aplikasi[]" multiple>
                <option value="Admin">Admin</option>
                <option value="Mahasiswa">Mahasiswa</option>
                <option value="Dosen">Dosen</option>
              </select>
              <script type="text/javascript">
                var select = new SlimSelect({
                select: '#Aplikasi'
                })
                select.selected()
              </script>
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Name', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Announcement_Name" min="1" value="{{ $data_edit->Announcement_Name }}"  class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Pesan', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Message" min="1" value="{{ $data_edit->Message }}"  class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Start Date', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="date" name="Post_Start_Date" value="{{ $Post_Start_Date }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Start Date', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="date" name="Post_End_Date" value="{{ $Post_End_Date }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'File', ['class' => 'col-md-4 form-label']) !!}
            @if($data_edit->File_Upload != null)
            <div class="col-md-12">
              <a href="{{route('getfile')}}?name={{$data_edit->File_Upload}}" target="_blank">{{ $data_edit->File_Upload }}</a>
            </div>
            @endif
            <div class="col-md-12">
              <input type="file" name="file" id="file" class="form-control" accept=".jpg,.jpeg,.pdf,.png"><br>
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
