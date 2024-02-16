@extends('layouts._layout')
@section('pageTitle', 'Term Year')
@section('content')
<section class="content">


  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Tambah Semester Berlaku</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('master/term_year/?entry_year='.$dat->Year_Id.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-danger btn-sm">Batal &nbsp;<i class="fa fa-close"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Tambah</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">

          @if (count($errors) > 0)
            @foreach ( $errors->all() as $error )
              <p class="alert alert-danger">{{ $error }}</p>
            @endforeach
          @endif
          {!! Form::open(['url' => route('term_year.update',$dat->Term_Year_Id) , 'method' => 'put', 'class' => 'form-horizontal', 'role' => 'form']) !!}
          <input type="hidden" name="entry_year" min="1" value="{{ $dat->Year_Id }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control">
          <div class="row" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            {!! Form::label('', 'Tahun', ['class' => 'col-md-2 form-label']) !!}
            <label class="form-control-sm col-md-4" for="">{{ $dat->Year_Id }}</label>
          </div><br>
          <div class="row" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            {!! Form::label('', 'Semester', ['class' => 'col-md-2 form-label']) !!}
            <label class="form-control-sm col-md-4" for="">{{ $dat->Term_Name }}</label>
          </div><br>
          <?php
          $start = explode(" ",$dat->Start_Date);
          $s_date = $start[0];
          $end = explode(" ",$dat->End_Date);
          $e_date = $end[0];
          ?>
          <div class="row" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            {!! Form::label('', 'Tanggal Mulai', ['class' => 'col-md-2 form-label']) !!}
            <input type="date" name="Start_Date" value="{{ $s_date }}" class="form-control form-control-sm col-md-4">
          </div><br>
          <div class="row" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            {!! Form::label('', 'Tanggal Selesai', ['class' => 'col-md-2 form-label']) !!}
            <input type="date" name="End_Date" value="{{ $e_date }}" class="form-control form-control-sm col-md-4">
          </div><br>
          <br><center><button type="submit" class="btn btn-primary btn-flat">Simpan</button></center>

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
