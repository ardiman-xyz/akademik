@extends('layouts._layout')
@section('pageTitle', 'Curriculum Entry Year')
@section('content')
<section class="content">

  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Edit Kurikulum Angkatan</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('parameter/curriculum_entry_year?term_year='.$query_edit->Term_Year_Id.'&department='.$query_edit->Department_Id.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
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
          {!! Form::open(['url' => route('curriculum_entry_year.update',$query_edit->Curriculum_Entry_Year_Id) , 'method' => 'PUT', 'class' => 'form-horizontal', 'role' => 'form']) !!}
          <div class="form-group">
            {!! Form::label('', 'Tahun / Semester', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="" value="{{ $query_edit->Term_Year_Name }}" disabled class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Program Studi', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="" value="{{ $query_edit->Department_Name }}" disabled class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Angkatan', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="" value="{{ $query_edit->Entry_Year_Name }}" disabled class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Program Kelas', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="" value="{{ $query_edit->Class_Program_Name }}" disabled class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Kurikulum', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select class="form-control form-control-sm col-md-12" name="Curriculum_Id">
                <option value="">Pilih Kurikulum</option>
                @foreach ( $select_curriculum as $curri )
                  <option <?php if($curri->Curriculum_Id == $query_edit->Curriculum_Id){ echo "selected"; } ?> value="{{ $curri->Curriculum_Id }}">{{ $curri->Curriculum_Name }}</option>
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


@endsection
