@extends('layouts._layout')
@section('pageTitle', 'Student')
@section('content')

<?php

foreach ($query_edit as $data_edit) {

?>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Edit Nilai Ekuivalensi Mahasiswa</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('proses/transcript_equivalensi/'.$data_edit->Student_Id.'?department='.$department.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search.'&entry_year='.$entry_year) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
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

          {!! Form::open(['url' => route('transcript_equivalensi.update', $data_edit->Transcript_Id) , 'method' => 'put', 'class' => 'form', 'enctype' => 'multipart/form-data']) !!}
          <input type="hidden" name="department" value="{{ $department }}">
          <input type="hidden" name="Student_Id" value="{{ $data_edit->Student_Id }}">
          {{ csrf_field() }}
          {{ method_field('put') }}

          <div class="form-group">
            {!! Form::label('', 'Kode Matakuliah Transfer', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Course_Code_Transfer" value="{{ $data_edit->Course_Code_Transfer }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
            </div>
          </div>

          <div class="form-group">
            {!! Form::label('', 'Nama Matakuliah Transfer', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Course_Name_Transfer" value="{{ $data_edit->Course_Name_Transfer }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
            </div>
          </div>

          <div class="form-group">
            {!! Form::label('', 'SKS Transfer', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Sks_Transfer" value="{{ $data_edit->Sks_Transfer }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
            </div>
          </div>

          <div class="form-group">
            {!! Form::label('', 'Nilai Huruf Transfer', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Grade_Letter_Transfer" value="{{ $data_edit->Grade_Letter_Transfer }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
            </div>
          </div>
        </br>
          <hr>
        </br>


        <div class="form-group">
          {!! Form::label('', 'Matakuliah', ['class' => 'col-md-4 form-label']) !!}
          <div class="col-md-12">
            <select class="form-control form-control-sm col-md-12" name="Course_Id" id="Course_Id">
              @foreach ( $select_course_ as $course_ )
              <option value="{{ $course_->Course_Id }}">{{ $course_->Course_Name }}</option>
              @endforeach
              @foreach ( $select_course as $course )
                <option <?php if($data_edit->Course_Id == $course->Course_Id){ echo "selected"; } ?> value="{{ $course->Course_Id }}">{{ $course->Course_Name }}</option>
              @endforeach
            </select>
            <script>
              var select = new SlimSelect({
              select: '#Course_Id'
              })
              select.selected()
          </script>
          </div>
        </div>

          <div class="form-group">
            {!! Form::label('', 'SKS Transkrip', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Sks" value="{{ $data_edit->Sks }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
            </div>
          </div>

          <div class="form-group">
            {!! Form::label('', 'Nilai Huruf', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select class="form-control form-control-sm" name="Grade_Letter_Id">
                @foreach($select_grade_letter as $grade_letter)
                <option <?php if($data_edit->Grade_Letter_Id == $grade_letter->Grade_Letter_Id){ echo "selected"; } ?> value="{{ $grade_letter->Grade_Letter_Id }}">{{ $grade_letter->Grade_Letter }}</option>
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
