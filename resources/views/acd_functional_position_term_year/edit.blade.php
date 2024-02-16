@extends('layouts._layout')
@section('pageTitle', 'Functional Position Term Year')
@section('content')

<?php

foreach ($query_edit as $data_edit) {

?>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Edit Jabatan Fungsional</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('master/functional_position_term_year?term_year='.$data_edit->Year_Id.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
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
          <div class="form-group">
            {!! Form::open(['url' => route('functional_position_term_year.update', $data_edit->Functional_Position_Term_Year_Id) , 'method' => 'put', 'class' => 'form']) !!}
            <div class="col-md-12">
              <input type="hidden" name="Term_Year_Id" value="{{$data_edit->Year_Id }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Jabatan', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="hidden" id="Functional_Position_Id" value="{{ $data_edit->Functional_Position_Id }}" class="form-control form-control-sm">
              <input type="text"  readonly value="{{ $data_edit->Functional_Position_Name }}" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            <?php
            if (!$data_edit->Faculty_Id == null) {
            ?>
            <div id="Fakultas">
              {!! Form::label('', 'Fakultas', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" readonly value="{{ $data_edit->Faculty_Name }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
            </div>
            </div>
            <?php } ?>
          </div>
          <div class="form-group">
            <?php
            if (!$data_edit->Department_Id == null) {
            ?>
            <div id="Department">
              {!! Form::label('', 'Program Studi', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" readonly value="{{ $data_edit->Department_Name }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
            </div>
            </div>
          <?php } ?>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Nama Jabatan', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select  name="Employee_Id" class="form-control form-control-sm">
                <option value="">Pilih Pejabat</option>
                @foreach ( $select_employee as $data )
                  <option <?php if($data_edit->Employee_Id == $data->Employee_Id){ echo "selected"; } ?> value="{{ $data->Employee_Id }}">{{ $data->First_Title }} {{ $data->Name }} {{ $data->Last_Title }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <br><center><button type="submit" class="btn btn-primary btn-flat">Simpan</button></center>

          {!! Form::close() !!}
      </div>
    </div>
  </div>

<!-- /.row -->

</section>

<!-- <script>
$(document).on("ready", function (event) {
    var FP = $("#Functional_Position_Id").val();
    if (FP == 1 || FP == 2 || FP == 3 || FP == 4 || FP == 11) {
        $("#Fakultas").hide();
        $("#Prodi").hide();
    }
    if (FP == 5 || FP == 6 || FP == 7 || FP == 8) {
        $("#Fakultas").show();
        $("#Prodi").hide();
    }
    if (FP == 9 || FP == 10) {
        $("#Fakultas").hide();
        $("#Prodi").show();
    }
});
</script> -->

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
