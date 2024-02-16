@extends('layouts._layout')
@section('pageTitle', 'Sched Session')
@section('content')
<section class="content">
<div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">SESI JADWAL KULIAH</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('setting/sched_session?sched_session_group_id='.$data->Sched_Session_Group_Id.'&sched_type_id='.$data->Sched_Type_Id) }}" class="btn btn-danger btn-sm">Batal &nbsp;<i class="fa fa-close"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Tambah</b>
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
          {!! Form::open(['url' => route('sched_session.update',$data->Sched_Session_Id) , 'method' => 'PUT', 'class' => 'form-horizontal', 'role' => 'form']) !!}
          <input type="hidden" name="Day_Id" value="{{ $data->Day_Id }}">
          <div class="form-group">
            {!! Form::label('', 'Type', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" readonly value="{{ $data->Sched_Type_Name }}" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Grup Sesi', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" readonly value="{{ $data->Sched_Session_Group_Name }}"  class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Hari', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" readonly value="{{ $data->Day_Name }}"  class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Sesi', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" readonly value="{{ $data->Order_Id }}"  class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Jam Mulai', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="time" name="Time_Start"  value="{{ $data->Time_Start }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Jam Selesai', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="time" name="Time_End"  value="{{$data->Time_End }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
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
