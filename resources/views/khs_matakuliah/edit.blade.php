@extends('layouts._layout')
@section('pageTitle', 'KHS')
@section('content')

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">KHS Per Matakuliah</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('proses/khs_matakuliah/'.$Offered_Course_id.'?term_year='.$term_year.'&class_program='.$class_program.'&department='.$department.'&term_year='.$term_year.'&current_page='.$currentpage.'&current_rowpage='.$currentrowpage.'&current_search='.$currentsearch) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
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
          {!! Form::open(['url' => route('khs_matakuliah.update', $query->Krs) , 'method' => 'put', 'class' => 'form']) !!}

          @if($query->Khs_Id != "")
          <input type="hidden" name="Khs_Id" value="{{ $query->Khs_Id }}"  class="form-control form-control-sm">

          @endif
          <input type="hidden" name="Student_Id" value="{{ $query->student }}"  class="form-control form-control-sm">

          <input type="hidden" name="Sks" value="{{ $query->Transcript_Sks }}"  class="form-control form-control-sm">
          <input type="hidden" name="Is_For_Transkrip" value="{{ $query->is_transcript }}"  class="form-control form-control-sm">

          <input type="hidden" name="Department_Id" value="{{ $department }}"  class="form-control form-control-sm">
          <input type="hidden" name="Term_Year_Id" value="{{ $term_year }}"  class="form-control form-control-sm">
          <input type="hidden" name="Course_Id" value="{{ $query->Course_Id }}"  class="form-control form-control-sm">
          <input type="hidden" name="idnya" value="{{ $Offered_Course_id }}"  class="form-control form-control-sm">
          <input type="hidden" name="Class_Prog_Id" value="{{ $query->Class_Prog_Id }}"  class="form-control form-control-sm">

          <div class="form-group">
            {!! Form::label('', 'NIM', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" value="{{ $query->Nim }}" disabled class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Nama', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text"  value="{{ $query->Full_Name }}" disabled class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Matakuliah', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text"  value="{{ $query->Course_Name }}" disabled min="1" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Kelas', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text"  value="{{ $query->Class_Name }}" disabled min="1" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Nilai', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select class="form-control form-control-sm col-md-12 col-xs-12" name="Grade_Letter_Id">
                <option value=""></option>
                @foreach ( $grade_letter as $data )
                  <option <?php if($data->Grade_Letter_Id == $query->Grade_Letter_Id){ echo "selected"; } ?> value="{{ $data->Grade_Letter_Id }}">{{ $data->Grade_Letter }}</option>
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
