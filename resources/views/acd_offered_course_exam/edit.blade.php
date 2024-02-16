@extends('layouts._layout')
@section('pageTitle', 'Offered Course Exam')
@section('content')

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Edit Jadwal dan Peserta Ujian</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('setting/offered_course_exam/'.$query->Offered_Course_id.'?class_program='.$query->Class_Prog_Id.'&term_year='.$query->Term_Year_Id.'&department='.$query->Department_Id.'&page='.$page.'&rowpage='.$rowpage.'&currentsearch='.$currentsearch.'&currentpage='.$currentpage.'&currentrowpage='.$currentrowpage) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Edit</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
          @if (count($errors) > 0)
            @foreach ( $errors->all() as $error )
              @if ($error == 'Berhasil Menyimpan Perubahan')
                <p class="alert alert-success">{{ $error }}</p>
              @else
                <p class="alert alert-danger">{{ $error }}</p>
              @endif
            @endforeach
          @endif
          {!! Form::open(['url' => route('offered_course_exam.update', $query->Offered_Course_Exam_Id) , 'method' => 'PUT', 'class' => 'form']) !!}
          <div class="form-group">
            {!! Form::label('', 'Kelompok', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Room_Number" value="{{ $query->Room_Number }}" class="form-control form-control-sm">
              <input type="text" hidden name="Offered_Course_id" value="{{ $query->Offered_Course_id }}" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Jenis Ujian', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select  class="form-control form-control-sm" name="Exam_Type_Id">
                <option value="">Pilih Jenis Ujian</option>
                @foreach ( $select_exam_type as $data )
                  <option <?php if( $query->Exam_Type_Id == $data->Exam_Type_Id){ echo "selected"; } ?> value="{{ $data->Exam_Type_Id }}">{{ $data->Exam_Type_Code }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Ruang', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select  class="form-control form-control-sm" name="Room_Id">
                <option value="">Pilih Ruang</option>
                @foreach ( $select_room as $data )
                  <option  <?php if( $query->Room_Id == $data->Room_Id){ echo "selected"; } ?>  value="{{ $data->Room_Id }}">{{ $data->Room_Name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Waktu Mulai', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="datetime-local" name="Exam_Start_Date" value="{{ str_replace(' ', 'T', $query->Exam_Start_Date) }}" class="form-control form-control-sm">
            </div>
          </div>
          <!-- <div class="form-group">
            {!! Form::label('', 'Waktu Selesai', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="datetime-local" name="Exam_End_Date" value="{{ str_replace(' ', 'T', $query->Exam_End_Date) }}" class="form-control form-control-sm">
            </div>
          </div> -->
          <div class="form-group">
            {!! Form::label('', 'Pengawas 1', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select id="select1" class="form-control form-control-sm" name="Inspector_Id_1">
                <option value="">Pilih Pengawas</option>
                @foreach ( $select_employee as $data )
                  <option <?php if( $query->Inspector_Id_1  == $data->Employee_Id){ echo "selected"; } ?> value="{{ $data->Employee_Id }}">{{ $data->Full_Name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Pengawas 2', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select id="select2" class="form-control form-control-sm" name="Inspector_Id_2">
                <option value="">Pilih Pengawas</option>
                @foreach ( $select_employee as $data )
                  <option  <?php if( $query->Inspector_Id_2  == $data->Employee_Id){ echo "selected"; } ?>  value="{{ $data->Employee_Id }}">{{ $data->Full_Name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <br><center><button type="submit" class="btn btn-primary btn-flat">Simpan</button></center>

          {!! Form::close() !!}


          <script type="text/javascript">
          var select1 = new SlimSelect({
          select: '#select1'
          })
          var select2 = new SlimSelect({
          select: '#select2'
          })

          select1.selected()
          select2.selected()


          </script>
      </div>
    </div>
  </div>

<!-- /.row -->

</section>
@endsection
