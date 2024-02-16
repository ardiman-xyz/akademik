@extends('layouts._layout')
@section('pageTitle', 'Course Identic')
@section('content')
<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Tambah Matakuliah Setara</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('parameter/course_identic?department='.$department.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Tambah</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">

          @if (count($errors) > 0)
            @foreach ( $errors->all() as $error )
              @if ($error=="Berhasil Menambah Matakuliah Setara")
                <p class="alert alert-success">{{ $error }}</p>
              @else
                <p class="alert alert-danger">{{ $error }}</p>
              @endif
            @endforeach
          @endif
          {!! Form::open(['url' => route('course_identic.store') , 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form']) !!}
          <div class="form-group">
            {!! Form::label('', 'Program Studi', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              @foreach ( $mstr_department as $data )
              <input type="hidden" name="Department_Id" value="{{ $department }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
              <input type="text" readonly value="{{ $data->Department_Name }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
              @endforeach
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Matakuliah Setara', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Identic_Name" value="{{ old('Identic_Name') }}" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Matakuliah', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select id="select"  class="form-control form-control-sm" multiple name="Course_Id[]">
                @foreach ( $select_course as $data )
                  <option value="{{ $data->Course_Id }}">{{ $data->Course_Name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <br><center><button type="submit" class="btn btn-primary btn-flat">Tambah</button></center>

          {!! Form::close() !!}
      </div>
    </div>
  </div>
<script type="text/javascript">
var select = new SlimSelect({
select: '#select'
})

select.selected()

</script>


<!-- /.row -->

</section>


@endsection
