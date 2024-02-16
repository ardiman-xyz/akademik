@extends('layouts._layout')
@section('pageTitle', 'Cuti')
@section('content')


<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Edit Cuti</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('proses/cuti?page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
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
          {!! Form::open(['url' => route('cuti.update', $data_student->Student_Vacation_Id) , 'method' => 'put', 'class' => 'form','enctype'=>'multipart/form-data']) !!}
            <div class="form-group">
              {!! Form::label('', 'Nama Mahasiswa', ['class' => 'col-md-4 form-label']) !!}
              <div class="col-md-12">
                <input type="text" readonly name="Full_Name" class="form-control form-control-sm col-md-12" value="{{$data_student->Full_Name}}">
              </div>
            </div>
            <div class="form-group">
              {!! Form::label('', 'Semester', ['class' => 'col-md-4 form-label']) !!}
              <div class="col-md-12">
                <input type="text" readonly name="Semester" class="form-control form-control-sm col-md-12" value="{{$data_student->Term_Year_Id}}">
            </div>
            </div>
            <div class="form-group">
              {!! Form::label('', 'Alasan', ['class' => 'col-md-4 form-label']) !!}
              <div class="col-md-12">
                <input type="text" name="Deskripsi" class="form-control form-control-sm col-md-12"  value="{{ $data_student->Description }}">
              </div>
            </div>
            <div class="form-group">
            {!! Form::label('', 'Terima / Tidak', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select id="acc" class="form-control form-control-sm col-md-12" name="acc">
                <option value="0">Pilih</option>
                <option value="1" <?php if($data_student->Is_Approved == 1){ echo "selected"; } ?>>Ya</option>
                <option value="2" <?php if($data_student->Is_Approved == '0'){ echo "selected"; } ?>>Tidak</option>
              </select>
            </div>
          </div>
            <div class="form-group">
              {!! Form::label('', 'SK Date', ['class' => 'col-md-4 form-label']) !!}
              <div class="col-md-12">
                <?php
                  $date = strtotime($data_student->Sk_Date);
                  $SK_Date = date('Y-m-d', $date);
                ?>
                <input type="date" name="Sk_Date" value="{{ $SK_Date }}" class="form-control form-control-sm">
              </div>
            </div>
            <div class="form-group">
              {!! Form::label('', 'SK Number', ['class' => 'col-md-4 form-label']) !!}
              <div class="col-md-12">
                <input type="text" name="Sk_Number" min="1" value="{{ $data_student->Sk_Number}}" class="form-control form-control-sm">
              </div>
            </div>
            <div class="form-group">
              {!! Form::label('', 'File', ['class' => 'col-md-4 form-label']) !!}
              @if($data_student->File != null)
              <div class="col-md-12">
                <a href="{{ url('') }}/storage/{{ $data_student->File }}" target="_blank">{{ $data_student->File }}</a>
              </div>
              @endif
              <div class="col-md-12">
                <input type="file" name="file" id="" class="form-control" accept=".jpg,.jpeg,.pdf,.png"><br>
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
