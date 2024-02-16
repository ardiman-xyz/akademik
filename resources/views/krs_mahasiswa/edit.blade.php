@extends('layouts._layout')
@section('pageTitle', 'KRS')
@section('content')
<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">KRS Per Mahasiswa</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('proses/krs_mahasiswa?nim='.$nim.'&term_year='.$term_year) }}" class="btn btn-danger btn-sm">Batal &nbsp;<i class="fa fa-close"></i></a>
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
            @if ($notif != null)
                <p class="alert alert-danger">{{ $notif }}</p>
            @endif
          @endif
          <div class="form-group">
            {!! Form::label('', 'Matakuliah', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Course" value="{{ $course->Course_Name }}" readonly class="form-control form-control-sm">
            </div>
          </div>

          <div class="form-group">
            {!! Form::label('', 'SKS', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Sks" value="{{ $acd_student_krs->Sks }}" readonly class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Amount', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Amount" value="{{ $acd_student_krs->Amount }}" readonly class="form-control form-control-sm">
            </div>
          </div>

          {!! Form::open(['url' => route('krs_mahasiswa.edit',$acd_student_krs->Krs_Id) , 'method' => 'GET', 'class' => 'form-horizontal', 'name' => 'form', 'role' => 'form' , 'enctype' => 'multipart/form-data' ]) !!}
          <input type="hidden" name="nim" value="{{ $nim }}">
          <input type="hidden" name="term_year" value="{{ $term_year }}">
          <div class="form-group">
            {!! Form::label('', 'kelas', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select class="form-control form-control-sm col-md-12" name="class" onchange="document.form.submit();">
                <option value="">Kelas</option>
                @if($select_class != "")
                @foreach ( $select_class as $S_class )
                  <option <?php if($class == $S_class->Class_Id ){ echo "selected"; } ?> value="{{ $S_class->Class_Id }}">{{ $S_class->Class_Name }}</option>
                @endforeach
                @endif
              </select>
            </div>
          </div>
          <br>
          <br>
          {!! Form::close() !!}

          {!! Form::open(['url' => route('krs_mahasiswa.update',$acd_student_krs->Krs_Id) , 'method' => 'PUT', 'class' => 'form-horizontal', 'role' => 'form' , 'enctype' => 'multipart/form-data' ]) !!}
          <div class="form-group">
            <div class="col-md-12">
              <input type="hidden" name="Student_Id" value="{{ $student->Student_Id }}">
              <input type="hidden" name="class_id" value="{{ $class }}">

              <input type="hidden" name="Sks" value="{{ $acd_student_krs->Sks }}">
              <input type="hidden" name="Amount" value="{{ $acd_student_krs->Amount }}">

            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Kapasaitas', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Capacity" value="{{ $kapasitas }}" readonly class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Terdaftar', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Terdaftar" value="{{ $terdaftar }}" readonly class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Sisa Kuota', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Sisa_Kuota" value="{{ $sisakuota }}" readonly class="form-control form-control-sm">
            </div>
          </div>

          <br><center><button type="submit" class="btn btn-primary btn-flat">Simpan</button></center>
          {!! Form::close() !!}

      </div>
    </div>
  </div>

<!-- /.row -->

</section>

@endsection
