@extends('layouts._layout')
@section('pageTitle', 'Student')
@section('content')
<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Tambah Data Mahasiswa</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('setting/student?entry_year='.$entry_year_id.'&department='.$department_id.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Tambah</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">

          @if (count($errors) > 0)
            @foreach ( $errors->all() as $error )
              @if ($error=="Berhasil Menambah Data Mahasiswa")
                <p class="alert alert-success">{{ $error }}</p>
              @else
                <p class="alert alert-danger">{{ $error }}</p>
              @endif
            @endforeach
          @endif
          {!! Form::open(['url' => route('student.store') , 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form' , 'enctype' => 'multipart/form-data' ]) !!}
          {{ csrf_field() }}
          {{ method_field('post') }}
          <input type="hidden" name="Entry_Year_Id"  value="{{ $entry_year_id }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control">
          <input type="hidden" name="Department_Id"  value="{{ $department_id }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control">
          <div class="form-group">
            {!! Form::label('', 'Tahun Angkatan', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              @foreach($entry_year as $term_y)
              <input type="text" readonly value="{{ $term_y->Entry_Year_Code }}" class="form-control form-control-sm">
              @endforeach
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Program Studi', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              @foreach($department as $depart)
              <input type="text" readonly value="{{ $depart->Department_Name }}"  class="form-control form-control-sm">
              @endforeach
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'NIM', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Nim" value="{{ old('Nim') }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'No. Registrasi', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Register_Number" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" value="{{ old('Register_Number') }}" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Nama', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Full_Name" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" value="{{ old('Full_Name') }}" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Gelar Depan', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="First_Title" value="{{ old('First_Title') }}" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Gelar Belakang', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Last_Title" value="{{ old('Last_Title') }}" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Jenis Kelamin', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select class="form-control form-control-sm col-md-12" name="Gender_Id">
                <option value="">Pilih Jenis Kelamin</option>
                @foreach ( $gender as $entry )
                  <option  <?php if(old('Gender_Id') == $entry->Gender_Id ){ echo "selected"; } ?> value="{{ $entry->Gender_Id }}">{{ $entry->Gender_Type }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Tempat Lahir', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Birth_Place" value="{{ old('Birth_Place') }}" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Tempat Lahir', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select class="form-control form-control-sm col-md-12" name="Birth_Place_Id" id="kota">
                <option value="">Pilih Kota</option>
                @foreach ( $city as $entry )
                  <option  <?php if(old('Birth_Place_Id') == $entry->City_Id ){ echo "selected"; } ?> value="{{ $entry->City_Id }}">{{ $entry->City_Name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Tanggal Lahir', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <?php
                $date = strtotime(old('Birth_Date'));
                $birth = date('Y-m-d', $date);
              ?>
              <input type="date" name="Birth_Date" value="{{ old('$birth') }}" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Kewarganegaraan', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select class="form-control form-control-sm col-md-12" name="Citizenship_Id" id="kota">
                <option value="">Pilih Kewarganegaraan</option>
                @foreach ( $citizenship as $entry )
                  <option <?php if(old('Citizenship_Id') == $entry->Citizenship_Id ){ echo "selected"; } ?> value="{{ $entry->Citizenship_Id }}">{{ $entry->Citizenship_Name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Agama', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select class="form-control form-control-sm col-md-12" name="Religion_Id" id="kota">
                <option value="">Pilih Agama</option>
                @foreach ( $religion as $entry )
                  <option <?php if(old('Religion_Id') == $entry->Religion_Id ){ echo "selected"; } ?> value="{{ $entry->Religion_Id }}">{{ $entry->Religion_Name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Status Perkawinan', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select class="form-control form-control-sm col-md-12" name="Marital_Status_Id" id="kota">
                <option value="">Pilih Status Perkawinan</option>
                @foreach ( $marital as $entry )
                  <option <?php if(old('Marital_Status_Id') == $entry->Marital_Status_Id ){ echo "selected"; } ?> value="{{ $entry->Marital_Status_Id }}">{{ $entry->Marital_Status_Type }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Golongan Datah', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select class="form-control form-control-sm col-md-12" name="Blood_Id" id="kota">
                <option value="">Pilih Golongan Darah</option>
                @foreach ( $blood as $entry )
                  <option <?php if(old('Blood_Id') == $entry->Blood_Type_Id ){ echo "selected"; } ?> value="{{ $entry->Blood_Type_Id }}">{{ $entry->Blood_Type }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Jurusan di SMA', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select class="form-control form-control-sm col-md-12" name="High_School_Major_Id" id="kota">
                <option value="">Pilih Jurusan di SMA</option>
                @foreach ( $high_school_major as $entry )
                  <option <?php if(old('High_School_Major_Id') == $entry->High_School_Major_Id ){ echo "selected"; } ?> value="{{ $entry->High_School_Major_Id }}">{{ $entry->High_School_Major_Name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'No. Hp', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Phone_Mobile" value="{{ old('Phone_Mobile') }}" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Program Kelas', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select class="form-control form-control-sm col-md-12" name="Class_Prog_Id" id="kota">
                <option value="">Pilih Program Kelas</option>
                @foreach ( $class_program as $entry )
                  <option <?php if(old('Class_Prog_Id') == $entry->Class_Prog_Id ){ echo "selected"; } ?> value="{{ $entry->Class_Prog_Id }}">{{ $entry->Class_Program_Name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Kelas', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select class="form-control form-control-sm col-md-12" name="Class_Id" id="kota">
                <option value="">Pilih Kelas</option>
                @foreach ( $class as $entry )
                  <option <?php if(old('Class_Id') == $entry->Class_Id ){ echo "selected"; } ?> value="{{ $entry->Class_Id }}">{{ $entry->Class_Name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Foto Mahasiswa', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="file" accept="image/*" class="form-control form-control-sm" name="file"  />
            </div>
          </div>
          <br><center><button type="submit" class="btn btn-primary btn-flat">Tambah</button></center>

          {!! Form::close() !!}
      </div>
    </div>
  </div>

<!-- /.row -->

</section>
<script type="text/javascript">
var select = new SlimSelect({
select: '#kota'
})

select.selected()


  $(document).on("change", "#pilih", function (event) {
    var entry_year_id = <?php echo $entry_year_id; ?>;
    var department_id = <?php echo $department; ?>;
    var entry_year_id = $('#pilih').val();
    var url = {!! json_encode(url('/')) !!};

    $.ajax({
        url: url + "/setting/student/create/class_program",
        data: {
            entry_year_id: entry_year_id,
            department_id: department_id,
            entry_year_id: entry_year_id
        },
        cache: false,
        type: "GET",
        dataType: "html",

        success: function (data, textStatus, XMLHttpRequest) {
            $("#select").html(data);// HTML DOM replace
        }
    });
  });
</script>

@endsection
