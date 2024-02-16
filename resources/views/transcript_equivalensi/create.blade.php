@extends('layouts._layout')
@section('pageTitle', 'Equivalency')
@section('content')
<section class="content">


  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Tambah Nilai Mahasiswa Equivalensi</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('proses/transcript_equivalensi/'.$data->Student_Id.'?department='.$department.'&entry_year='.$entry_year.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Tambah</b>
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
          {!! Form::open(['url' => route('transcript_equivalensi.store') , 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form']) !!}
          {{ csrf_field() }}
          {{ method_field('post') }}

          <input type="hidden" name="Student_Id" value="{{ $data->Student_Id }}">
          <input type="hidden" name="department" value="{{ $data->Department_Id }}">
          <input type="hidden" name="entry_year" value="{{ $data->Entry_Year_Id }}">

          <div class="col-md-12">
            <div class="row">
              <label class="col-md-3" for="">NIM</label>
              <label class="col-md-9" for="">{{ $data->Nim }}</label>
            </div>
            <div class="row">
              <label class="col-md-3" for="">Nama Mahasiswa</label>
              <label class="col-md-9" for="">{{ $data->Full_Name }}</label>
            </div>
          </div>
  <br>

          <div class="form-group">
            {!! Form::label('', 'Kode Matakuliah Transfer', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" min="0" name="Course_Code_Transfer" value="{{ old('Course_Code_Transfer') }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Nama Matakuliah Transfer', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Course_Name_Transfer" value="{{ old('Room_Name') }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'SKS Transfer', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="number" min="0" name="Sks_Transfer" value="{{ old('Description') }}" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Nilai Huruf Transfer', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Grade_Letter_Transfer" min='1' value="{{ old('Capacity') }}" class="form-control form-control-sm" >
            </div>
          </div>
          <hr>

          <div class="form-group">
            {!! Form::label('', 'Matakuliah', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select id="select" class="form-control form-control-sm" name="Course_Id">
                @foreach($select_course as $course)
                <option value="{{ $course->Course_Id }}">{{ $course->Course_Code }} {{ $course->Course_Name }}</option>
                @endforeach
              </select>
              <script type="text/javascript">
                var select = new SlimSelect({
                placeholder: 'Pilih Pegawai',
                select: '#select'
                })
                select.selected()
              </script>
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'SKS', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="number" min=0 name="Sks" value="{{ old('Acronym') }}" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Nilai Huruf', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select class="form-control form-control-sm" name="Grade_Letter_Id">
                @foreach($select_grade_letter as $grade_letter)
                <option value="{{ $grade_letter->Grade_Letter_Id }}">{{ $grade_letter->Grade_Letter }}</option>
                @endforeach
              </select>
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
