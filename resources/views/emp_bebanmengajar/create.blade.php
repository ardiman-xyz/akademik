@extends('layouts._layout')
@section('pageTitle', 'Grade Letter')
@section('content')
<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Tambah Beban Mengajar</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('parameter/beban_mengajar') }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Tambah</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">

          @if (count($errors) > 0)
            @foreach ( $errors->all() as $error )
              @if ($error=="Berhasil Menambah Beban Mengajar")
                <p class="alert alert-success">{{ $error }}</p>
              @else
                <p class="alert alert-danger">{{ $error }}</p>
              @endif
            @endforeach
          @endif
          {!! Form::open(['url' => route('beban_mengajar.store') , 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form']) !!}
          <div class="form-group">
            {!! Form::label('', 'Semester', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
            <select class="form-control form-control-sm" name="Term_Year_Id">
              <option value="0">Pilih Tahun Semester</option>
              @foreach ( $select_term_year as $data )
                <option <?php if($term_year == $data->Term_Year_Id){ echo "selected"; } ?> value="{{ $data->Term_Year_Id }}">{{ $data->Term_Year_Name }}</option>
              @endforeach
              </select>
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Jabatan', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
            <select class="form-control form-control-sm" name="Jabatan">
              <option value="0">Dosen Tetap</option>
              <option value="1">Dosen Luar</option>
              @foreach ( $emp_structural as $data )
                <option value="{{ $data->Structural_Id }}">( {{$data->Structural_Code}} ){{ $data->Structural_Name }}</option>
              @endforeach
              </select>
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Beban Sks', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Weight_Value" value="{{ old('Weight_Value') }}" class="form-control form-control-sm">
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
