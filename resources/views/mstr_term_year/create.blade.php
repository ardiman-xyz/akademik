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
          <a href="{{ url('master/term_year/?entry_year='.$entry_year.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Tambah</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">

          @if (count($errors) > 0)
            @foreach ( $errors->all() as $error )
              @if ($error=="Berhasil Menambah Program Studi")
                <p class="alert alert-success">{{ $error }}</p>
              @else
                <p class="alert alert-danger">{{ $error }}</p>
              @endif
            @endforeach
          @endif
          {!! Form::open(['url' => route('term_year.store') , 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form']) !!}
          <input type="hidden" name="entry_year" min="1" value="{{ $entry_year }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control">
          <div class="row" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            {!! Form::label('', 'Tahun', ['class' => 'col-md-2 form-label']) !!}
            <label class="form-control-sm col-md-4" for="">{{ $entry_year }}</label>
          </div><br>
          <div class="row" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            {!! Form::label('', 'Semester', ['class' => 'col-md-2 form-label']) !!}
            <select class="form-control form-control-sm col-md-4" name="term">
              @foreach ( $term as $data )
                <option  <?php if(old('term') == $data->Term_Id ){ echo "selected"; } ?> value="{{ $data->Term_Id }}">{{ $data->Term_Name }}</option>
              @endforeach
            </select>
          </div><br>
          <div class="row" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            {!! Form::label('', 'Tanggal Mulai', ['class' => 'col-md-2 form-label']) !!}
            <input type="date" name="Start_Date" value="" class="form-control form-control-sm col-md-4">
          </div><br>
          <div class="row" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            {!! Form::label('', 'Tanggal Selesai', ['class' => 'col-md-2 form-label']) !!}
            <input type="date" name="End_Date" value="" class="form-control form-control-sm col-md-4">
          </div><br>
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
