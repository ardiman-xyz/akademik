@extends('layouts._layout')
@section('pageTitle', 'Opstion Remidi')
@section('content')


  <?php

  foreach ($query_edit as $data_edit) {

  ?>


<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Edit Option Remidi</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('setting/short_term?faculty='.$fakultas->Faculty_Id.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Edit</b>
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

        {!! Form::open(['url' => route('short_term.update', $department) , 'method' => 'put', 'class' => 'form']) !!}

        <div class="form-group">
          {!! Form::label('', 'Nama Prodi', ['class' => 'col-md-4 form-label']) !!}
          <div class="col-md-12">
            <input type="hidden" name="Department_Id" value="{{$data_edit->Department_Id }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
            <input type="text" readonly value="{{ $data_edit->Department_Name }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
          </div>
        </div>
        <div class="form-group">
          {!! Form::label('', 'Status Mahasiswa', ['class' => 'col-md-4 form-label']) !!}
          <div class="col-md-12">
            <select class="form-control form-control-sm" name="aktif">
              <option value="">--- ---</option>
              <option <?php if($data_edit->Is_Active_Student == 1){ echo "selected "; } ?> value="1">Aktif</option>
              <option <?php if($data_edit->Is_Active_Student == 2){ echo "selected "; } ?> value="2">Tidak Aktif</option>
            </select>
          </div>
        </div>
        <div class="form-group">
          {!! Form::label('', 'Matakuliah Max', ['class' => 'col-md-4 form-label']) !!}
          <div class="col-md-12">
            <input type="text" name="coursemax" value="{{ $data_edit->Course_Limit }}" class="form-control form-control-sm">
          </div>
        </div>
        <!-- <div class="form-group">
          {!! Form::label('', 'Aturan Ambil', ['class' => 'col-md-4 form-label']) !!}
          <div class="col-md-12">
            <select class="form-control form-control-sm" name="aturan_ambil">
              @foreach ( $Taking_Rule as $data )
                <option <?php if($data_edit->Taking_Rule_Id == $data->Taking_Rule_Id){ echo "selected "; } ?> value="{{ $data->Taking_Rule_Id }}">{{ $data->Taking_Rule_Name }}</option>
              @endforeach
            </select>
          </div>
        </div> -->
        <div class="form-group">
          {!! Form::label('', 'Semua Tahun ?', ['class' => 'col-md-4 form-label']) !!}
          <div class="col-md-12">
            <select class="form-control form-control-sm" name="tahun_ambil">
              <option value="">--- ---</option>
              <option <?php if($data_edit->Is_All_Year == 1){ echo "selected "; } ?> value="1">Semua tahun</option>
              <option  <?php if($data_edit->Is_All_Year == 0){ echo "selected "; } ?> value="0">Tahun Yang Bersangkutan</option>
            </select>
          </div>
        </div>
        <!-- <div class="form-group">
          {!! Form::label('', 'Nilai Minimum', ['class' => 'col-md-4 form-label']) !!}
          <div class="col-md-12">
            <select class="form-control form-control-sm" name="nilai_minimum">
              <option value="">--- ---</option>
              @foreach ( $grade as $data )
                <option <?php if($data_edit->Grade_Letter_Minimum_Id == $data->Grade_Letter_Id){ echo "selected "; } ?> value="{{ $data->Grade_Letter_Id }}">{{ $data->Grade_Letter }}</option>
              @endforeach
            </select>
          </div>
        </div> -->
        <br><center><button type="submit" class="btn btn-primary btn-flat">Simpan</button></center>

        {!! Form::close() !!}
      </div>

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
