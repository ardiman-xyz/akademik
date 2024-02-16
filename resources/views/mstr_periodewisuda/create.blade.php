@extends('layouts._layout')
@section('pageTitle', 'Country')
@section('content')
<section class="content">

  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Tambah Periode Wisuda</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('proses/periodewisuda?page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Tambah</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">

          @if (count($errors) > 0)
            @foreach ( $errors->all() as $error )
              @if ($error=="Berhasil Menambah Periode Wisuda")
                <p class="alert alert-success">{{ $error }}</p>
              @else
                <p class="alert alert-danger">{{ $error }}</p>
              @endif
            @endforeach
          @endif
          {!! Form::open(['url' => route('periodewisuda.store') , 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form']) !!}
          <div class="form-group">
            {!! Form::label('', 'Nama Periode Wisuda', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Period_Name" value="{{ old('Country_Name') }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Tempat Wisuda', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Graduation_Place" value="{{ old('Graduation_Place') }}" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Tanggal Wisuda', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <?php
                $date = strtotime(old('Graduation_Date'));
                $Graduation_Date = date('Y-m-d', $date);
              ?>
              <input type="date" name="Graduation_Date" value="{{ old('$Graduation_Date') }}" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Tanggal Maksimal Yudisium', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <?php
                $date = strtotime(old('End_Date_Yudisium'));
                $End_Date_Yudisium = date('Y-m-d', $date);
              ?>
              <input type="date" name="Yudisium_Date" value="{{ old('$End_Date_Yudisium') }}" class="form-control form-control-sm">
            </div>
          </div>
          <br><center><button type="submit" class="btn btn-primary btn-flat">Tambah</button></center>

          {!! Form::close() !!}
      </div>
    </div>
  </div>



<!-- /.row -->

</section>
<!-- <script type="text/javascript">
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
  $("form").submit(function() {
    $("input").removeAttr("disabled");
  });
</script> -->
@endsection
