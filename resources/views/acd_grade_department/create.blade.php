@extends('layouts._layout')
@section('pageTitle', 'Grade Letter')
@section('content')
<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Tambah Grade Nilai</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('parameter/grade_department?department='.$department.'&term_year='.$term_year.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Tambah</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">

          @if (count($errors) > 0)
            @foreach ( $errors->all() as $error )
              @if ($error=="Berhasil Menambah Grade Nilai")
                <p class="alert alert-success">{{ $error }}</p>
              @else
                <p class="alert alert-danger">{{ $error }}</p>
              @endif
            @endforeach
          @endif
          {!! Form::open(['url' => route('grade_department.store') , 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form']) !!}
          <!-- <div class="form-group">
            {!! Form::label('', 'Program Studi', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              @foreach ( $mstr_department as $data )
              <input type="text"  name="Department_Id" hidden value="{{ $data->Department_Id }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
              <input type="text" readonly value="{{ $data->Department_Name }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
              @endforeach
            </div>
          </div> -->
          <!-- <div class="form-group">
            {!! Form::label('', 'Semester', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              @foreach ( $mstr_department as $data )
              <input type="text"  name="term_year" hidden value="{{ $term_year }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
              <input type="text" readonly value="{{ $mstr_term_year->Term_Year_Name }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
              @endforeach
            </div>
          </div> -->
          <input type="text" hidden name="entry_year" value="{{ $request->entry_year }}"class="form-control form-control-sm">
          <div class="form-group">
            {!! Form::label('', 'Nilai Huruf', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select class="form-control form-control-sm" name="Grade_Letter_Id">
                <option value="">Pilih Nilai Huruf</option>
                @foreach ( $select_grade_letter as $data )
                  <option  <?php if(old('Grade_Letter_Id') == $data->Grade_Letter_Id ){ echo "selected"; } ?> value="{{ $data->Grade_Letter_Id }}">{{ $data->Grade_Letter }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Bobot', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" max="4" name="Weight_Value" value="{{ old('Weight_Value') }}" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Predikat', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Predicate" value="{{ old('Predicate') }}" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Predikat (English)', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Predicate_Eng" value="{{ old('Predicate_Eng') }}" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Batas Atas', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Scale_Numeric_Max" value="{{ old('Scale_Numeric_Max') }}" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Batas Bawah', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Scale_Numeric_Min" value="{{ old('Scale_Numeric_Min') }}" class="form-control form-control-sm">
            </div>
          </div>
          <br><center><button type="submit" class="btn btn-primary btn-flat">Tambah</button></center>

          {!! Form::close() !!}
      </div>
    </div>
  </div>



<!-- /.row -->

</section>


@endsection
