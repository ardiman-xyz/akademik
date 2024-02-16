@extends('layouts._layout')
@section('pageTitle', 'Course Curriculum')
@section('content')

  <section class="content">
    <div class="container-fluid-title">
      <div class="title-laporan">
        <h3 class="text-white">Tambah Prasyarat</h3>
      </div>
    </div>
    <div class="container">
      <div class="panel panel-default bootstrap-admin-no-table-panel">
        <div class="panel-heading-green">
          <div class="pull-right tombol-gandeng dua">
            <a  href="{{ url('parameter/prasyarat?class_program='.$class_program.'&department='.$department.'&course_id='.$course_id.'&curriculum='.$curriculum.'&semester='.$semester.'&page='.$current_page.'&rowpage='.$current_rowpage.'&search='.$current_search) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
          </div>
          <div class="bootstrap-admin-box-title right text-white">
            <b>Tambah</b>
          </div>
        </div>
        <!-- <b>Daftar Fakultas</b> -->
        {!! Form::open(['url' => route('course_curriculum.create') , 'method' => 'GET', 'name' => 'form', 'role' => 'form', 'style' => 'margin:2%;']) !!}
        <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">

        </div>
        {!! Form::close() !!}
      </div>
      {!! Form::open(['url' => route('prasyarat.store') , 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form']) !!}
      <input type="hidden" name="department" value="{{ $department }}">
      <input type="hidden" name="curriculum" value="{{ $curriculum }}">
      <input type="hidden" name="class_program" value="{{ $class_program }}">
      <input type="hidden" name="semester" value="{{ $semester }}">

      <input type="hidden" name="current_page" value="{{ $current_page }}">
      <input type="hidden" name="current_rowpage" value="{{ $current_rowpage }}">
      <input type="hidden" name="current_search" value="{{ $current_search }}">
      <input type="hidden" name="Course_Id" value="{{ $course_id }}">
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        @if (count($errors) > 0)
          @foreach ( $errors->all() as $error )
            @if ($error=="Berhasil Menambah Matakuliah Kurikulum")
              <p class="alert alert-success">{{ $error }}</p>
            @else
              <p class="alert alert-danger">{{ $error }}</p>
            @endif
          @endforeach
        @endif

        <div class="form-group">
          {!! Form::label('', 'Jenis Prasyarat', ['class' => 'col-md-4 form-label']) !!}
          <div class="form-control form-control-sm col-md-12">
            <select class="form-control form-control-sm col-md-12" name="jenis_prasyarat" id="jenis_prasyarat">
              <option value="0">Pilih Jenis Prasyarat</option>
              @foreach ( $jenis_prasyarat as $entry )
                <option  <?php if(old('jenis_prasyarat') == $entry->Prerequisite_Type_Id ){ echo "selected"; } ?> value="{{ $entry->Prerequisite_Type_Id }}">{{ $entry->Prerequisite_Type_Name }}</option>
              @endforeach
            </select>
          </div><br>

          <div id="matkul">
            {!! Form::label('', 'Mata Kuliah', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select id="matakuliah" class="form-control form-control-sm col-md-12" name="matakuliah[]" multiple>
                {{-- <option value="">Pilih Mata Kuliah</option> --}}
                @foreach ( $Course_Id as $entry )
                  <option  <?php if(old('jenis_prasyarat') == $entry->Course_Id ){ echo "selected"; } ?> value="{{ $entry->Course_Id }}">({{ $entry->Course_Code }}) {{ $entry->Course_Name }}</option>
                @endforeach
              </select>
            </div><br>
          </div>
          <div id="nilai_min">
            {!! Form::label('', 'Nilai Minimal', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <div class="form-control form-control-sm col-md-12">
                <select class="form-control form-control-sm col-md-12" name="nilai_min">
                  {{-- <option value="">Pilih Nilai Minimal</option> --}}
                  <option value="">-</option>
                  @foreach ( $grade_department as $entry )
                    <option
                    <?php if(old('jenis_prasyarat') == $entry->Grade_Letter_Id ){ echo "selected"; } ?>
                    value="{{ $entry->Grade_Letter_Id }}">{{ $entry->Grade_Letter }}
                  </option>
                  @endforeach
                </select>
              </div>
            </div><br>
          </div>

          <div id="nilai_min_null">
            {!! Form::label('', 'Nilai Minimal', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <div class="form-control form-control-sm col-md-12">
                <select class="form-control form-control-sm col-md-12" name="nilai_min_null">
                  <option value="">-</option>
                </select>
              </div>
            </div><br>
          </div>

          <div id="semester_min">
            {!! Form::label('', 'Semester Minimal', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <div class="form-control form-control-sm col-md-12">
                <input type="text" placeholder="Cth: 1 / 2 / 3 / .." name="value_semester_min" value="{{ old('value_semester_min') }}" class="form-control form-control-sm">
              </div>
            </div><br>
          </div>

          <div id="sks_min">
            {!! Form::label('', 'Total Sks Min', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <div class="form-control form-control-sm col-md-12">
                <input type="text" placeholder="Isi total SKS" name="sks_min" value="{{ old('sks_min') }}" class="form-control form-control-sm">
              </div>
            </div><br>
          </div>

          <div id="sks_d">
            {!! Form::label('', 'Total Sks Nilai D (maksimal)', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <div class="form-control form-control-sm col-md-12">
                <input type="text" placeholder="Isi total SKS Nilai D" name="sks_d" value="{{ old('sks_d') }}" class="form-control form-control-sm">
              </div>
            </div><br>
          </div>

          <div id="ipk_min">
            {!! Form::label('', 'IPK Minimal', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <div class="form-control form-control-sm col-md-12">
                <input type="text" placeholder="Cth: 3.21" name="ipk_min" value="{{ old('ipk_min') }}" class="form-control form-control-sm">
              </div>
            </div><br>
          </div>

        </div>
        <div align="center">
          <button type="submit" class="btn btn-primary btn-flat">Tambah</button><br>
        </div>
      </div>
      {!! Form::close() !!}
    </div>
  </div>

</section>

<script>
  var select = new SlimSelect({
    select: '#matakuliah'
  })
  select.selected()

  $(document).ready(function() {
    $("#sks_d").hide();
    $("#nilai_min_null").hide();
    $("#semester_min").hide();
    $("#sks_min").hide();
    $("#ipk_min").hide();
    $("#nilai_min").hide();
    $("#matkul").hide();
  });

  $(document).on("change", "#jenis_prasyarat", function (event) {
    var FP = $("#jenis_prasyarat").val();
    if (FP == 0 ) {
      $("#sks_d").hide();
      $("#nilai_min_null").hide();
      $("#semester_min").hide();
      $("#sks_min").hide();
      $("#ipk_min").hide();
      $("#nilai_min").hide();
      $("#matkul").hide();
    }
    if (FP == 1  || FP == 3 ) {
      $("#sks_d").hide();
      $("#nilai_min_null").hide();
      $("#semester_min").hide();
      $("#sks_min").hide();
      $("#ipk_min").hide();
      $("#nilai_min").show();
      $("#matkul").show();
    }
    if (FP == 2) {
      $("#sks_d").hide();
      $("#nilai_min_null").show();
      $("#nilai_min").hide();
      $("#semester_min").hide();
      $("#sks_min").hide();
      $("#ipk_min").hide();
      $("#matkul").show();
    }
    if (FP == 4) {
      $("#sks_d").hide();
      $("#nilai_min").hide();
      $("#semester_min").show();
      $("#nilai_min_null").hide();
      $("#sks_min").hide();
      $("#ipk_min").hide();
      $("#matkul").hide();
    }
    if (FP == 5) {
      $("#sks_d").hide();
      $("#nilai_min").hide();
      $("#nilai_min_null").hide();
      $("#sks_min").show();
      $("#semester_min").hide();
      $("#ipk_min").hide();
      $("#matkul").hide();
    }
    if (FP == 6) {
      $("#nilai_min").hide();
      $("#nilai_min_null").hide();
      $("#semester_min").hide();
      $("#sks_min").hide();
      $("#ipk_min").hide();
      $("#sks_d").show();
      $("#matkul").hide();
    }
    if (FP == 7) {
      $("#sks_d").hide();
      $("#nilai_min").hide();
      $("#nilai_min_null").hide();
      $("#semester_min").hide();
      $("#sks_min").hide();
      $("#ipk_min").show();
      $("#matkul").hide();
    }
  });
</script>
@endsection
