@extends('layouts._layout')
@section('pageTitle', 'Offered Course Exam')
@section('content')

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Tambah Jadwal dan Peserta Ujian</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('setting/offered_course_exam/'.$Offered_Course_id.'?class_program='.$class_program.'&term_year='.$term_year.'&department='.$department.'&page='.$page.'&rowpage='.$rowpage.'&currentsearch='.$currentsearch.'&currentpage='.$currentpage.'&currentrowpage='.$currentrowpage) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Tambah</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
          @if (count($errors) > 0)
            @foreach ( $errors->all() as $error )
              @if ($error=="Berhasil Menambah Jadwal Peserta Ujian")
                <p class="alert alert-success">{{ $error }}</p>
              @else
                <p class="alert alert-danger">{{ $error }}</p>
              @endif
            @endforeach
          @endif
          <div class="form-group">
            {!! Form::open(['url' => route('offered_course_exam.store') , 'method' => 'POST', 'class' => 'form']) !!}
            <div class="col-md-12">
              <input type="hidden" name="Offered_Course_id" value="{{ $Offered_Course_id }}">
              <input type="hidden" name="class_program" value="{{ $class_program }}">
              <input type="hidden" name="department" value="{{ $department }}">
            </div>
          </div>
          <div >
          <label class="col-sm-3">Kode Matakuliah</label>
          <label  class="col-sm-3">: {{ $ma->Course_Code }}</label>
      </div>
      <div>
          <label  class="col-sm-3">Nama Matakuliah</label>
          <label  class="col-sm-3">: {{ $ma->Course_Name }}</label>
      </div>
      <div>
          <label  class="col-sm-3">Kelas</label>
          <label  class="col-sm-3">: {{ $ma->Class_Name }}</label>
      </div>
      <div>
          <label  class="col-sm-3">Kapasitas Kelas Matakuliah</label>
          <label  class="col-sm-3">: {{ $ma->Class_Capacity }}</label>
      </div>
      <div>
          <label  class="col-sm-3">Jumlah Mahasiswa</label>
          <label  class="col-sm-3">: {{ $count_mahasiswa->jml_peserta }}</label>
      </div>
      <hr>


      <div class="row  col-md-12 text-green">
        <label class="col-md-2">Kelompok :</label>
        <input type="text" name="Room_Number" value="{{ old('Room_Number') }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm col-md-3">
      </div>
      <div class="row  col-md-12 text-green">
        <label class="col-md-2">Jenis Ujian :</label>
         <select class="form-control form-control-sm col-md-3" name="Exam_Type_Id" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')">
            <option value="">Pilih Jenis Ujian</option>
            @foreach ( $select_exam_type as $data )
              <option <?php if( old('Exam_Type_Id') == $data->Exam_Type_Id){ echo "selected"; } ?> value="{{ $data->Exam_Type_Id }}">{{ $data->Exam_Type_Code }}</option>
            @endforeach
          </select>
      </div>
      <div class="row  col-md-12 text-green">
        <label class="col-md-2">Ruang :</label>
         <select id="select3" class="form-control form-control-sm col-md-3" name="Room_Id[]" multiple required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')">
            <!-- <option value="">Pilih Ruang</option> -->
            @foreach ( $select_room as $data )
              <option  <?php if( old('Room_Id') == $data->Room_Id){ echo "selected"; } ?>  value="{{ $data->Room_Id }}">{{ $data->Room_Name }} / Kpst {{ $data->Capacity_Exam }}</option>
            @endforeach
          </select>
      </div>
      <div class="row  col-md-12 text-green">
        <label class="col-md-2">Waktu Mulai :</label>
         <input type="datetime-local" name="Exam_Start_Date" value="{{ old('Exam_Start_Date') }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm col-md-3">
      </div>
      <!-- <div class="row  col-md-12 text-green">
        <label class="col-md-2">Waktu Selesai :</label>
         <input type="datetime-local" name="Exam_End_Date" value="{{ old('Exam_End_Date') }}"  oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm col-md-3">
      </div> -->
      <div class="row  col-md-12 text-green">
        <label class="col-md-2">Pengawas 1 :</label>
         <select id="select1" class="form-control form-control-sm col-md-3" name="Inspector_Id_1">
            <option value="">Pilih Pengawas</option>
            @foreach ( $select_employee as $data )
              <option <?php if( old('Inspector_Id_1')  == $data->Employee_Id){ echo "selected"; } ?> value="{{ $data->Employee_Id }}">{{ $data->First_Title }} {{ $data->Name }} {{ $data->Last_Title }}</option>
            @endforeach
          </select>
      </div>
      <div class="row  col-md-12 text-green">
        <label class="col-md-2">Pengawas 2 :</label>
         <select id="select2" class="form-control form-control-sm col-md-3" name="Inspector_Id_2">
            <option value="">Pilih Pengawas</option>
            @foreach ( $select_employee as $data )
              <option  <?php if( old('Inspector_Id_2')  == $data->Employee_Id){ echo "selected"; } ?>  value="{{ $data->Employee_Id }}">{{ $data->First_Title }} {{ $data->Name }} {{ $data->Last_Title }}</option>
            @endforeach
          </select>
      </div>
          <br><center><button type="submit" class="btn btn-primary btn-flat">Tambah</button></center>
          {!! Form::close() !!}

          <script type="text/javascript">
          var select1 = new SlimSelect({
          select: '#select1'
          })
          var select2 = new SlimSelect({
          select: '#select2'
          })
          var select3 = new SlimSelect({
          select: '#select3'
          })

          select1.selected()
          select2.selected()
          select3.selected()


          </script>
      </div>
    </div>
  </div>

<!-- /.row -->

</section>

@endsection
