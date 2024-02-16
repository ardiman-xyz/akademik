@extends('layouts._layout')
@section('pageTitle', 'Student')
@section('content')

<?php

foreach ($query_edit as $data_edit) {

?>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Edit Data Mahasiswa</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('setting/student?department='.$department_id.'&entry_year='.$entry_year_id.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Edit</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
          <?php $tgl_dikti = Date("Y-m-d",strtotime($data_edit->Department_Dikti_Sk_Date)); ?>
          @if (count($errors) > 0)
            @foreach ( $errors->all() as $error )
              @if ($error=="Berhasil Menyimpan Perubahan")
                <p class="alert alert-success">{{ $error }}</p>
              @else
                <p class="alert alert-danger">{{ $error }}</p>
              @endif
            @endforeach
          @endif
          {!! Form::open(['url' => route('student.update', $data_edit->Student_Id) , 'method' => 'put', 'class' => 'form', 'enctype' => 'multipart/form-data']) !!}
          {{ csrf_field() }}
          {{ method_field('put') }}
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
              <input type="text" name="Nim" value="{{ $data_edit->Nim }}" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'No. Registrasi', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Register_Number" value="{{ $data_edit->Register_Number }}" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Full_Name', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Full_Name" value="{{ $data_edit->Full_Name }}" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Gelar Depan', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="First_Title" value="{{ $data_edit->First_Title }}" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Gelar Belakang', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Last_Title"value="{{ $data_edit->Last_Title }}"  class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Jenis Kelamin', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select class="form-control form-control-sm col-md-12" name="Gender_Id">
                <option value="">Pilih Jenis Kelamin</option>
                @foreach ( $gender as $entry )
                  <option <?php if($data_edit->Gender_Id == $entry->Gender_Id){ echo "selected"; } ?> value="{{ $entry->Gender_Id }}">{{ $entry->Gender_Type }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Tempat Lahir', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Birth_Place" value="{{ $data_edit->Birth_Place }}" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Tempat Lahir', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select class="form-control form-control-sm col-md-12" name="Birth_Place_Id" id="kota">
                <option value="">Pilih Kota</option>
                @foreach ( $city as $entry )
                  <option <?php if($entry->City_Id == $data_edit->Birth_Place_Id){ echo "selected"; } ?> value="{{ $entry->City_Id }}">{{ $entry->City_Name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Tanggal Lahir', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <?php
                $date = strtotime($data_edit->Birth_Date);
                $birth = date('Y-m-d', $date);
              ?>
              <input type="date" name="Birth_Date"  value="{{ $birth }}"  class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Kewarganegaraan', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select class="form-control form-control-sm col-md-12" name="Citizenship_Id" id="kota">
                <option value="">Pilih Kewarganegaraan</option>
                @foreach ( $citizenship as $entry )
                  <option <?php if($data_edit->Citizenship_Id == $entry->Citizenship_Id){ echo "selected"; } ?> value="{{ $entry->Citizenship_Id }}">{{ $entry->Citizenship_Name }}</option>
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
                  <option <?php if($data_edit->Religion_Id == $entry->Religion_Id){ echo "selected"; } ?> value="{{ $entry->Religion_Id }}">{{ $entry->Religion_Name }}</option>
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
                  <option <?php if($data_edit->Marital_Status_Id == $entry->Marital_Status_Id){ echo "selected"; } ?> value="{{ $entry->Marital_Status_Id }}">{{ $entry->Marital_Status_Type }}</option>
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
                  <option <?php if($data_edit->Blood_Id == $entry->Blood_Type_Id){ echo "selected"; } ?> value="{{ $entry->Blood_Type_Id }}">{{ $entry->Blood_Type }}</option>
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
                  <option <?php if($data_edit->High_School_Major_Id == $entry->High_School_Major_Id){ echo "selected"; } ?> value="{{ $entry->High_School_Major_Id }}">{{ $entry->High_School_Major_Name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'No. Hp', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Phone_Mobile"  value="{{ $data_edit->Phone_Mobile }}" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Program Kelas', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select class="form-control form-control-sm col-md-12" name="Class_Prog_Id" id="kota">
                <option value="">Pilih Program Kelas</option>
                @foreach ( $class_program as $entry )
                  <option <?php if($data_edit->Class_Prog_Id == $entry->Class_Prog_Id){ echo "selected"; } ?> value="{{ $entry->Class_Prog_Id }}">{{ $entry->Class_Program_Name }}</option>
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
                  <option <?php if($data_edit->Class_Id == $entry->Class_Id){ echo "selected"; } ?>  value="{{ $entry->Class_Id }}">{{ $entry->Class_Name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Foto Mahasiswa', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
            @if(is_file(public_path('foto_mhs/'.$data_edit->Entry_Year_Id.'/'.$data_edit->Nim.'.jpg')))
              <img src="<?php echo env('APP_URL')?>{{ 'foto_mhs/'.$data_edit->Entry_Year_Id.'/'.$data_edit->Nim.'.jpg' }}" alt="">
            @else
              <img width="151px" height="226px" src="<?php echo env('APP_URL')?>{{ 'img/noimage.png' }}" alt="">
            @endif
            </div>
            <div class="col-md-12">
              <input type="file" accept="image/*" class="form-control form-control-sm" name="file"  />
            </div>
          </div>
          <br><center><button type="submit" class="btn btn-primary btn-flat">Simpan</button></center>
          {!! Form::close() !!}
      </div>
    </div>
  </div>

<!-- /.row -->

</section>
<!-- <script type="text/javascript">

$('[data-onload]').each(function(){
    eval($(this).data('onload'));
});

function handleChange(checkbox) {
    if(checkbox.checked == true){
      list = document.getElementsByClassName("fakultas");
      for (index = 0; index < list.length; ++index) {
        list[index].setAttribute("disabled","disabled");
        list[index].checked = false;
      }
    }else {
      list = document.getElementsByClassName("fakultas");
      for (index = 0; index < list.length; ++index) {
        list[index].removeAttribute("disabled");
      }
    }
}
  function Change(checkbox) {
      var id = $(checkbox).val();
      if(checkbox.checked == true){
        list = document.getElementsByClassName("prodi"+id);
        for (index = 0; index < list.length; ++index) {
          // list[index].setAttribute("disabled","disabled");
          list[index].checked = true;
          // list[index].setAttribute("checked","checked");
        }
      }else {
        list = document.getElementsByClassName("prodi"+id);
        for (index = 0; index < list.length; ++index) {
          // list[index].removeAttribute("disabled");
          list[index].checked = false;
          // list[index].removeAttribute("checked");
        }
      }
  }
  function ubah(id) {
      if($('.prodi'+id+':checked').length == $('.prodi'+id+'').length){
        list = document.getElementsByClassName("fac"+id);
        for (index = 0; index < list.length; ++index) {
          list[index].checked = true;
        }
      }else {
        list = document.getElementsByClassName("fac"+id);
        for (index = 0; index < list.length; ++index) {
          list[index].checked = false;
        }
      }
  }
</script> -->
<?php
}
?>
@endsection
