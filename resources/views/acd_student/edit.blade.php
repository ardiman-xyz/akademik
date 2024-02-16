@extends('layouts._layout')
@section('pageTitle', 'Student')
@section('content')

<?php

foreach ($query_edit as $data_edit) {

?>
<style>
* {
  box-sizing: border-box;
}

.row {
  display: flex;
}

/* Create two equal columns that sits next to each other */
.column {
  flex: 50%;
  padding: 10px;
}

.card {
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
  max-width: 300px;
  margin: auto;
  text-align: center;
  font-family: arial;
}

.title {
  color: grey;
  font-size: 18px;
}

button:hover, a:hover {
  opacity: 0.7;
}

img {
  display: block;
  margin-left: auto;
  margin-right: auto;
  padding-top: 10px;
}

.text {
  text-align:center;
}

.phg {
    width: 0%;
    background-color: #1a3b7f;
    padding: 5px 15px 15px 15px;
    color: white;
    margin-left: -15px;
    margin-right: -15px;
}

.profile-img {
    width: 100px;
    height: 100px;
}
</style>

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
      <div class="phg">
        <div class="btn-group">
          &nbsp;&nbsp;&nbsp;
          <button class="w3-bar-item w3-button" onclick="openCity('dm')">Data Mahasiswa</button>&nbsp;
          <button class="w3-bar-item w3-button" onclick="openCity('bm')">Berkas Mahasiswa</button>
        </div>
      </div>
      <br>
      @if (count($errors) > 0)
            @foreach ( $errors->all() as $error )
              @if ($error=="Berhasil Menyimpan Perubahan")
                <p class="alert alert-success">{{ $error }}</p>
              @else
                <p class="alert alert-danger">{{ $error }}</p>
              @endif
            @endforeach
          @endif
          <?php $tgl_dikti = Date("Y-m-d",strtotime($data_edit->Department_Dikti_Sk_Date)); ?>
          {!! Form::open(['url' => route('student.update', $data_edit->Student_Id) , 'method' => 'put', 'class' => 'form', 'enctype' => 'multipart/form-data']) !!}
          {{ csrf_field() }}
          {{ method_field('put') }}
        <div id="dm" class="city">
        <h3 class="text-grey">Data Mahasiswa</h3>
          <div class="row">
            <div class="column">
              <div class="row  text-green"  style="padding-top:5px;padding-left:15px;padding-right:15px;">
                <div class="row col-md-12">
                  <label class="col-md-4" >Tahun Angkatan :</label>
                  @foreach($entry_year as $term_y)
                  <input type="text" readonly value="{{ $term_y->Entry_Year_Code }}" class="form-control form-control-sm col-md-7">
                  @endforeach
                </div>
              </div>
              <div class="row  text-green"  style="padding-top:5px;padding-left:15px;padding-right:15px;">
                <div class="row col-md-12">
                  <label class="col-md-4" >Program Studi :</label>
                  @foreach($department as $depart)
                  <input type="text" readonly value="{{ $depart->Department_Name }}" class="form-control form-control-sm col-md-7">
                  @endforeach
                </div>
              </div>
              <div class="row  text-green"  style="padding-top:5px;padding-left:15px;padding-right:15px;">
                <div class="row col-md-12">
                  <label class="col-md-4" >NIM :</label>
                  <input type="text" readonly name="Nim" value="{{ $data_edit->Nim }}" class="form-control form-control-sm col-md-7">
                </div>
              </div>
              <div class="row  text-green"  style="padding-top:5px;padding-left:15px;padding-right:15px;">
                <div class="row col-md-12">
                  <label class="col-md-4" >No. Registrasi :</label>
                  <input type="text" readonly name="Register_Number" value="{{ $data_edit->Register_Number}}" class="form-control form-control-sm col-md-7">
                </div>
              </div>
              <div class="row  text-green"  style="padding-top:5px;padding-left:15px;padding-right:15px;">
                <div class="row col-md-12">
                  <label class="col-md-4" >Nama :</label>
                  <input type="text" name="Full_Name" value="{{ $data_edit->Full_Name}}" class="form-control form-control-sm col-md-7">
                </div>
              </div>
              <div class="row  text-green"  style="padding-top:5px;padding-left:15px;padding-right:15px;">
                <div class="row col-md-12">
                  <label class="col-md-4" >Program Kelas :</label>
                  <select class="form-control form-control-sm col-md-7" name="Class_Prog_Id" id="kota">
                    <option value="">Pilih Program Kelas</option>
                    @foreach ( $class_program as $entry )
                      <option <?php if($data_edit->Class_Prog_Id == $entry->Class_Prog_Id){ echo "selected"; } ?> value="{{ $entry->Class_Prog_Id }}">{{ $entry->Class_Program_Name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="row  text-green"  style="padding-top:5px;padding-left:15px;padding-right:15px;">
                <div class="row col-md-12">
                  <label class="col-md-4" >Kelas :</label>
                  <select class="form-control form-control-sm col-md-7" name="Class_Id" id="kota">
                    <option value="">Pilih Kelas</option>
                    @foreach ( $class as $entry )
                      <option <?php if($data_edit->Class_Id == $entry->Class_Id){ echo "selected"; } ?>  value="{{ $entry->Class_Id }}">{{ $entry->Class_Name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="row  text-green"  style="padding-top:5px;padding-left:15px;padding-right:15px;">
                <div class="row col-md-12">
                  <label class="col-md-4" >NIK :</label>
                  <input type="text" name="nik" value="{{ $data_edit->Nik}}" class="form-control form-control-sm col-md-7">
                </div>
              </div>
              <div class="row  text-green"  style="padding-top:5px;padding-left:15px;padding-right:15px;">
                <div class="row col-md-12">
                  <?php
                    $diterima = strtotime($data_edit->Created_Date);
                    $diterima = date('d-m-Y', $diterima);
                  ?>
                  <label class="col-md-4" >Tanggal Diterima :</label>
                  <input type="text" name="tgl_diterima" id="datepickertglditerima" readonly value="{{ $diterima}}" class="form-control form-control-sm col-md-7">
                </div>
              </div>
              <div class="row  text-green"  style="padding-top:5px;padding-left:15px;padding-right:15px;">
                <div class="row col-md-12">
                  <label class="col-md-4" >Semester Diterima :</label>
                  <input type="text" name="smt_diterima" readonly value="{{ $data_edit->Entry_Term_Id}}" class="form-control form-control-sm col-md-7">
                </div>
              </div>
              <div class="row  text-green"  style="padding-top:5px;padding-left:15px;padding-right:15px;">
                <div class="row col-md-12">
                  <label class="col-md-4" >NISN :</label>
                  <input type="text" name="nisn" value="{{ $data_edit->Nisn}}" class="form-control form-control-sm col-md-7">
                </div>
              </div>
              <div class="row  text-green"  style="padding-top:5px;padding-left:15px;padding-right:15px;">
                <div class="row col-md-12">
                  <label class="col-md-4" >NPWP :</label>
                  <input type="text" name="npwp" value="{{ $data_edit->Npwp}}" class="form-control form-control-sm col-md-7">
                </div>
              </div>
              <div class="row  text-green"  style="padding-top:5px;padding-left:15px;padding-right:15px;">
                <div class="row col-md-12">
                  <label class="col-md-4" >Penerima KPS :</label>
                  <select class="form-control form-control-sm col-md-7" name="kps">
                    <option <?php if($data_edit->Recieve_Kps == 1){ echo "selected"; } ?> value="1">Ya</option>
                    <option <?php if($data_edit->Recieve_Kps == 2){ echo "selected"; } ?> value="2">Tidak</option>
                    <option <?php if($data_edit->Recieve_Kps == null){ echo "selected"; } ?> value="">----</option>
                  </select>
                </div>
              </div><br><br>

              <div class="row  text-green"  style="padding-top:5px;padding-left:15px;padding-right:15px;">
                <div class="row col-md-12">
                  <label class="col-md-4" >Gelar Depan :</label>
                  <input type="text" name="First_Title" value="{{ $data_edit->First_Title}}" class="form-control form-control-sm col-md-7">
                </div>
              </div>
              <div class="row  text-green"  style="padding-top:5px;padding-left:15px;padding-right:15px;">
                <div class="row col-md-12">
                  <label class="col-md-4" >Gelar Belakang :</label>
                  <input type="text" name="Last_Title" value="{{ $data_edit->Last_Title}}" class="form-control form-control-sm col-md-7">
                </div>
              </div>
              <div class="row  text-green"  style="padding-top:5px;padding-left:15px;padding-right:15px;">
                <div class="row col-md-12">
                  <label class="col-md-4" >Jenis Kelamin :</label>
                  <select class="form-control form-control-sm col-md-7" name="Gender_Id">
                    <option value="">Pilih Jenis Kelamin</option>
                    @foreach ( $gender as $entry )
                      <option <?php if($data_edit->Gender_Id == $entry->Gender_Id){ echo "selected"; } ?> value="{{ $entry->Gender_Id }}">{{ $entry->Gender_Type }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="row  text-green"  style="padding-top:5px;padding-left:15px;padding-right:15px;">
                <div class="row col-md-12">
                  <label class="col-md-4" >Tempat Lahir :</label>
                  <input type="text" name="Birth_Place" value="{{ $data_edit->Birth_Place}}" class="form-control form-control-sm col-md-7">
                </div>
              </div>
              <div class="row  text-green"  style="padding-top:5px;padding-left:15px;padding-right:15px;">
                <div class="row col-md-12">
                  <label class="col-md-4" >Tempat Lahir :</label>
                  <select class="form-control form-control-sm col-md-7" name="" >
                    <option value="">Pilih Kota</option>
                    @foreach ( $city as $entry )
                      <option <?php if($entry->City_Id == $data_edit->Birth_Place_Id){ echo "selected"; } ?> value="{{ $entry->City_Id }}">{{ $entry->City_Name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="row  text-green"  style="padding-top:5px;padding-left:15px;padding-right:15px;">
                <div class="row col-md-12">
                  <label class="col-md-4" >Tanggal Lahir :</label>
                  <?php
                    $date = strtotime($data_edit->Birth_Date);
                    $birth = date('d-m-Y', $date);
                  ?>
                  <input type="text" id="datepicker" name="Birth_Date"  value="{{ $birth }}"  class="form-control form-control-sm col-md-7">
                </div>
              </div>
              <div class="row  text-green"  style="padding-top:5px;padding-left:15px;padding-right:15px;">
                <div class="row col-md-12">
                  <label class="col-md-4" >Kewarganegaraan :</label>
                  <select class="form-control form-control-sm col-md-7" name="Citizenship_Id" id="kota">
                    <option value="">Pilih Kewarganegaraan</option>
                    @foreach ( $citizenship as $entry )
                      <option <?php if($data_edit->Citizenship_Id == $entry->Citizenship_Id){ echo "selected"; } ?> value="{{ $entry->Citizenship_Id }}">{{ $entry->Citizenship_Name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="row  text-green"  style="padding-top:5px;padding-left:15px;padding-right:15px;">
                <div class="row col-md-12">
                  <label class="col-md-4" >Agama :</label>
                  <select class="form-control form-control-sm col-md-7" name="Religion_Id">
                    <option value="">Pilih Agama</option>
                    @foreach ( $religion as $entry )
                      <option <?php if($data_edit->Religion_Id == $entry->Religion_Id){ echo "selected"; } ?> value="{{ $entry->Religion_Id }}">{{ $entry->Religion_Name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="row  text-green"  style="padding-top:5px;padding-left:15px;padding-right:15px;">
                <div class="row col-md-12">
                  <label class="col-md-4" >Status Perkawinan :</label>
                  <select class="form-control form-control-sm col-md-7" name="Marital_Status_Id">
                    <option value="">Pilih Status Perkawinan</option>
                    @foreach ( $marital as $entry )
                      <option <?php if($data_edit->Marital_Status_Id == $entry->Marital_Status_Id){ echo "selected"; } ?> value="{{ $entry->Marital_Status_Id }}">{{ $entry->Marital_Status_Type }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="row  text-green"  style="padding-top:5px;padding-left:15px;padding-right:15px;">
                <div class="row col-md-12">
                  <label class="col-md-4" >Golongan Darah :</label>
                  <select class="form-control form-control-sm col-md-7" name="Blood_Id" id="kota">
                    <option value="">Pilih Golongan Darah</option>
                    @foreach ( $blood as $entry )
                      <option <?php if($data_edit->Blood_Id == $entry->Blood_Type_Id){ echo "selected"; } ?> value="{{ $entry->Blood_Type_Id }}">{{ $entry->Blood_Type }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <!-- <div class="row  text-green"  style="padding-top:5px;padding-left:15px;padding-right:15px;">
                <div class="row col-md-12">
                  <label class="col-md-4" >Jurusan di SMA :</label>
                  <select class="form-control form-control-sm col-md-7" name="High_School_Major_Id" id="kota">
                    <option value="">Pilih Jurusan di SMA</option>
                    @foreach ( $high_school_major as $entry )
                      <option <?php if($data_edit->High_School_Major_Id == $entry->High_School_Major_Id){ echo "selected"; } ?> value="{{ $entry->High_School_Major_Id }}">{{ $entry->High_School_Major_Name }}</option>
                    @endforeach
                  </select>
                </div>
              </div><br><br> -->
              <div class="row  text-green"  style="padding-top:5px;padding-left:15px;padding-right:15px;">
                <div class="row col-md-12">
                  <label class="col-md-4" >No. Hp :</label>
                  <input type="text" name="Phone_Mobile" value="{{ $data_edit->Phone_Mobile}}" class="form-control form-control-sm col-md-7">
                </div>
              </div>
              <div class="row  text-green"  style="padding-top:5px;padding-left:15px;padding-right:15px;">
                <div class="row col-md-12">
                  <label class="col-md-4" >Email :</label>
                  <input type="text" name="email" value="{{ $data_edit->Email_Corporate}}" class="form-control form-control-sm col-md-7">
                </div>
              </div>
              <div class="row  text-green"  style="padding-top:5px;padding-left:15px;padding-right:15px;">
                <div class="row col-md-12">
                  <label class="col-md-4" >Alamat :</label>
                  <input type="text" name="jalan" value="{{ $data_edit->Address}}" class="form-control form-control-sm col-md-7">
                </div>
              </div>
              <!-- <div class="row  text-green"  style="padding-top:5px;padding-left:15px;padding-right:15px;">
                <div class="row col-md-12">
                  <label class="col-md-4" >Dusun :</label>
                  <input type="text" name="dusun" value="{{ $data_edit->Dusun}}" class="form-control form-control-sm col-md-7">
                </div>
              </div> -->
              <!-- <div class="row  text-green"  style="padding-top:5px;padding-left:15px;padding-right:15px;">
                <div class="row col-md-12">
                  <label class="col-md-4" >Kelurahan :</label>
                  <input type="text" name="kelurahan" value="{{ $data_edit->Sub_District}}" class="form-control form-control-sm col-md-7">
                </div>
              </div> -->
              <!-- <div class="row  text-green"  style="padding-top:5px;padding-left:15px;padding-right:15px;">
                <div class="row col-md-12">
                  <label class="col-md-4" >Kecamatan :</label>
                  <input type="text" name="District_Id" value="{{ $data_edit->District_Id}}" class="form-control form-control-sm col-md-7">
                </div>
              </div> -->
              <div class="row  text-green"  style="padding-top:5px;padding-left:15px;padding-right:15px;">
                <div class="row col-md-12">
                  <label class="col-md-4" >Jenis Tinggal :</label>
                  <select class="form-control form-control-sm col-md-7" name="jenis_tinggal" id="kota">
                    <option value="">Pilih Jenis Tinggal</option>
                    @foreach ( $mstr_tinggal as $entry )
                      <option <?php if($data_edit->Residence_Type_Id == $entry->Residence_Type_Id){ echo "selected"; } ?> value="{{ $entry->Residence_Type_Id }}">{{ $entry->Residence_Type }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="row  text-green"  style="padding-top:5px;padding-left:15px;padding-right:15px;">
                <div class="row col-md-12">
                  <label class="col-md-4" >Alat Transportasi :</label>
                  <select class="form-control form-control-sm col-md-7" name="transport" id="kota">
                    <option value="">Pilih Transportasi</option>
                    @foreach ( $mstr_transport as $entry )
                      <option <?php if($data_edit->Transport_Type_Id == $entry->Transport_Type_Id){ echo "selected"; } ?> value="{{ $entry->Transport_Type_Id }}">{{ $entry->Transport_Type }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="column">
              <div class="card">
                @if(is_file(public_path($data_edit->Photo)))
                  <img width="151px" height="226px" src="<?php echo env('APP_URL')?>{{ $data_edit->Photo }}" alt="">
                @else
                    @if($data_edit->Photo != null || $data_edit->Photo != '')
                      <img width="151px" height="226px" src="<?php echo env('APP_URL_PENMARU')?>/uploads/{{$data_edit->Register_Number}}/photo3x4.jpg" alt="">
                    @else
                      <img width="151px" height="226px" src="<?php echo env('APP_URL')?>{{ 'img/noimage.png' }}" alt="">
                    @endif
                @endif
                <br>
                <h5>{{ $data_edit->Full_Name}}</h5>
                <p class="title">{{ $data_edit->Nim}}</p>
                <br>
                <!-- Ganti Foto
                <input type="file" accept="image/*" class="form-control form-control-sm col-md-12" name="file"/> -->
              </div><br>

              <?php 
                $ayah = DB::table('acd_student_parent')->where('Student_Id',$id)->where('Parent_Type_Id',1)->first();
              ?>
              <p class="title">Ayah</p>
              <div class="row  text-green" style="padding-top:5px;">
                <div class="row col-md-12">
                  <label class="col-md-3" >NIK :</label>
                  <input type="text" name="ayah_nik" value="@if($ayah != null){{$ayah->Nik}} @endif" class="form-control form-control-sm col-md-7">
                </div>
              </div>
              <div class="row  text-green" style="padding-top:5px;">
                <div class="row col-md-12">
                  <label class="col-md-3" >Nama :</label>
                  <input type="text" name="ayah_name" value="@if($ayah != null){{$ayah->Full_Name}} @endif" class="form-control form-control-sm col-md-7">
                </div>
              </div>
              <div class="row  text-green" style="padding-top:5px;">
                <div class="row col-md-12">
                  <label class="col-md-3" >Tanggal Lahir :</label>
                  <?php
                    if($ayah != null){
                      $date_ayah = strtotime($ayah->Birth_Date);
                      $birth_ayah = date('d-m-Y', $date_ayah);
                      
                    }
                  ?>
                  <input type="text" id="datepicker2" name="ayah_birth_date"  value="@if($ayah != null){{$birth_ayah}} @endif"  class="form-control form-control-sm col-md-7">
                </div>
              </div>
              <div class="row  text-green" style="padding-top:5px;">
                <div class="row col-md-12">
                  <label class="col-md-3" >Pendidikan :</label>
                  <select class="form-control form-control-sm col-md-7" name="ayah_pendidikan" id="kota">
                    <option value="">Pilih Pendidikan</option>
                    @if($ayah != null)
                      @foreach ( $mstr_education as $entry )
                        <option <?php if($ayah->Education_Type_Id == $entry->Education_Type_Id){ echo "selected"; } ?> value="{{ $entry->Education_Type_Id }}">{{ $entry->Education_Type_Name }}</option>
                      @endforeach
                    @endif
                  </select>
                </div>
              </div>
              <div class="row  text-green" style="padding-top:5px;">
                <div class="row col-md-12">
                  <label class="col-md-3" >Pekerjaan :</label>
                  <select class="form-control form-control-sm col-md-7" name="ayah_pekerjaan" id="kota">
                    <option value="">Pilih Pekerjaan</option>
                    @if($ayah != null)
                      @foreach ( $mstr_job as $entry )
                        <option <?php if($ayah->Job_Category_Id == $entry->Job_Category_Id){ echo "selected"; } ?> value="{{ $entry->Job_Category_Id }}">{{ $entry->Job_Category_Name }}</option>
                      @endforeach
                    @endif
                  </select>
                </div>
              </div>
              <div class="row  text-green" style="padding-top:5px;">
                <div class="row col-md-12">
                  <label class="col-md-3" >Penghasilan :</label>
                  <input type="text" name="ayah_penghasilan" value="@if($ayah != null){{$ayah->Income}} @endif" class="form-control form-control-sm col-md-7">
                </div>
              </div><br>

              <?php 
                $ibu = DB::table('acd_student_parent')->where('Student_Id',$id)->where('Parent_Type_Id',2)->first();
              ?>
              <p class="title">Ibu</p>
              <div class="row  text-green" style="padding-top:5px;">
                <div class="row col-md-12">
                  <label class="col-md-3" >NIK :</label>
                  <input type="text" name="ibu_nik" value="@if($ibu != null){{$ibu->Nik}} @endif" class="form-control form-control-sm col-md-7">
                </div>
              </div>
              <div class="row  text-green" style="padding-top:5px;">
                <div class="row col-md-12">
                  <label class="col-md-3" >Nama :</label>
                  <input type="text" name="ibu_name" value="@if($ibu != null){{$ibu->Full_Name}} @endif" class="form-control form-control-sm col-md-7">
                </div>
              </div>
              <div class="row  text-green" style="padding-top:5px;">
                <div class="row col-md-12">
                  <label class="col-md-3" >Tanggal Lahir :</label>
                  <?php
                    if($ibu != null){
                      $date_ibu = strtotime($ibu->Birth_Date);
                      $birth_ibu = date('d-m-Y', $date_ibu);
                    }
                  ?>
                  <input type="text" id="datepicker3" name="ibu_birth_date"  value="@if($ibu != null){{$birth_ibu}}@endif"  class="form-control form-control-sm col-md-7">
                </div>
              </div>
              <div class="row  text-green" style="padding-top:5px;">
                <div class="row col-md-12">
                  <label class="col-md-3" >Pendidikan :</label>
                  <select class="form-control form-control-sm col-md-7" name="ibu_pendidikan" id="kota">
                    <option value="">Pilih Pendidikan</option>
                    @if($ibu != null)
                      @foreach ( $mstr_education as $entry )
                        <option <?php if($ibu->Education_Type_Id == $entry->Education_Type_Id){ echo "selected"; } ?> value="{{ $entry->Education_Type_Id }}">{{ $entry->Education_Type_Name }}</option>
                      @endforeach
                    @endif
                  </select>
                </div>
              </div>
              <div class="row  text-green" style="padding-top:5px;">
                <div class="row col-md-12">
                  <label class="col-md-3" >Pekerjaan :</label>
                  <select class="form-control form-control-sm col-md-7" name="ibu_pekerjaan" id="kota">
                    <option value="">Pilih Pekerjaan</option>
                    @if($ibu != null)
                      @foreach ( $mstr_job as $entry )
                        <option <?php if($ibu->Job_Category_Id == $entry->Job_Category_Id){ echo "selected"; } ?> value="{{ $entry->Job_Category_Id }}">{{ $entry->Job_Category_Name }}</option>
                      @endforeach
                    @endif
                  </select>
                </div>
              </div>
              <div class="row  text-green" style="padding-top:5px;">
                <div class="row col-md-12">
                  <label class="col-md-3" >Penghasilan :</label>
                  <input type="text" name="ibu_penghasilan" value="@if($ibu != null){{$ibu->Income}}@endif" class="form-control form-control-sm col-md-7">
                </div>
              </div><br>
            </div>
          </div>
          <br><center><button type="submit" class="btn btn-primary btn-flat">Simpan</button></center>
          {!! Form::close() !!}
          </div>

          
        <div id="bm" class="city" style="display:block">
        <h3 class="text-grey">Berkas Mahasiswa</h3>
          <div class="row">
            <div class="column">
              @if ($upload!=null)
              Berkas Terupload
              <hr>
              <table class="table table-striped table-font-sm" id="example" >
                      
                      <thead class="thead-default thead-green">

                          <tr>

                              <td>Berkas</td>
                              <td>File</td>
                          </tr>

                      </thead>
                      @if($upload)
                        @foreach($requir as $berkas)
                        @php
                         $data_upload = DB::table('reg_camaru_attachment as a')
                          ->join('reg_camaru as b','a.Camaru_Id','=','b.Camaru_Id')
                          ->where('b.Reg_Num', $data_edit->Register_Number)
                          ->where('a.Camaru_Register_Requirement_Id', $berkas->Camaru_Register_Requirement_Id)
                          ->select('a.*')
                          ->first();
                          $url_berkas = '';
                        @endphp
                        @if ($data_upload) 
                          @if ($data_upload->File_Url != null)
                            @php
                              $url_berkas = env('APP_URL_PMB').'/'.$data_upload->File_Url;
                            @endphp
                          @endif                            
                        @endif
                        <tr>
                          <td>{{$berkas->Camaru_Requirement}}</td>
                          <td>
                            @if($url_berkas != '')
                                <img class="profile-img myImg img" onclick="imagePreview('{{$url_berkas}}')" src="{{$url_berkas}}" alt="" >
                            @else
                                    --Belum upload--
                            @endif
                          </td>
                        </tr>
                        @endforeach
                      @endif
                      </tbody>
              </table>
              @else
              <strong>Belum ada Berkas yang Diupload</strong>
          @endif
            </div>
          </div>
          </div>
        </div>
        <div id="bk" class="city" style="display:none">
          <h2>Paris</h2>
          <p>Paris is the capital of France.</p> 
        </div>
    </div>
  </div>

<div id="PictureModal" class="modal  priew">
  <span class="close">&times;</span>
  <img class="modal-content" id="img01">
  <div id="caption"></div>
</div>
<!-- /.row -->

</section>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</section>
<script type="text/javascript">

$(document).ready(function() {
  $( "#datepicker" ).datepicker({ dateFormat: 'dd-mm-yy' });
  $( "#datepicker2" ).datepicker({ dateFormat: 'dd-mm-yy' });
  $( "#datepicker3" ).datepicker({ dateFormat: 'dd-mm-yy' });
});

function openCity(cityName) {
  var i;
  var x = document.getElementsByClassName("city");
  for (i = 0; i < x.length; i++) {
    x[i].style.display = "none";  
  }
  document.getElementById(cityName).style.display = "block";  
}

// document.getElementById("ibu_nik").value = getSavedValue("ibu_nik"); 
// document.getElementById("ibu_name").value = getSavedValue("ibu_name");
function saveValue(e){
    // var id = e.id;  // get the sender's id to save it . 
    // var val = e.value; // get the value. 
    // localStorage.setItem(id, val);// Every time user writing something, the localStorage's value will override . 
}
function getSavedValue  (v){
    // if (!localStorage.getItem(v)) {
    //     return "";// You can change this to your defualt value. 
    // }
    // return localStorage.getItem(v);
}

var modal = document.getElementById('PictureModal');

// Get the image and insert it inside the modal - use its "alt" text as a caption
// var img = document.getElementsByClassName('myImg img' onclick="imagePreviwea'r modalImg = document.getElementById}}'()" );
var modalImg = document.getElementById("img01");
function imagePreview(src){
modal.style.display = "block";
modalImg.src = src;
}

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
modal.style.display = "none";
$('#img01').css({

  'transform': 'rotate(0deg)',
  '-ms-transform': 'rotate(0deg)',
  '-moz-transform': 'rotate(0deg)',
  '-webkit-transform': 'rotate(0deg)',
  '-o-transform': 'rotate(0deg)'
});
}
</script>
<?php
}
?>
@endsection
