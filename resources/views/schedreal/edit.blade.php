@extends('layouts._layout')
@section('pageTitle', 'Pertemuan Kuliah')
@section('content')
<section class="content">

  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Edit Pertemuan Kuliah</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('proses/schedreal/'.$idofc) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
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
          {!! Form::open(['url' => route('schedreal.update',$datas->Sched_Real_Id) , 'method' => 'PUT', 'class' => 'form-horizontal', 'role' => 'form']) !!}
          
          
          @foreach($offeredcourse as $ofc)
          <input type="hidden" name="Course_Id" value="{{ $ofc->Course_Id }}">
          <input type="hidden" name="Class_Id" value="{{ $ofc->Class_Id }}">
          <input type="hidden" name="Term_Year_Id" value="{{ $ofc->Term_Year_Id }}">
          <input type="hidden" name="Class_Prog_Id" value="{{ $ofc->Class_Prog_Id }}">
          @endforeach
          <div class="form-group">
            {!! Form::label('', 'Pertemuan Ke-', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="number" name="Meeting_Order" min="1" value="{{ $datas->Meeting_Order }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Dosen', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select id="select" class="form-control form-control-sm" name="Employee_Id[]" id="" multiple>
              <?php 
              $employ = array();
              foreach($datas->empEmployees as $empemploy){
                array_push($employ,$empemploy->Employee_Id);
              }
              ?>
                @foreach($employee as $val)
                    <option <?php if(in_array($val->Employee_Id,$employ)){ echo "selected"; } ?>  value="{{ $val->Employee_Id }}">{{ $val->First_Title }}{{ $val->Name }}{{ $val->Last_Title }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Ruang', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select id="ruang" class="form-control form-control-sm" name="Room_Id" id="" >
                <option readonly value="">--Pilih Ruang--</option>
                @foreach($room as $val)
                    <option <?php if($datas->Room_Id == $val->Room_Id){ echo "selected"; } ?> value="{{ $val->Room_Id }}">{{ $val->Room_Name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <?php 
          $tgl = explode(' ',$datas->Date);
          ?>
          <div class="form-group">
            {!! Form::label('', 'Tanggal', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-3 input-group date" id="datetimepicker1" data-target-input="nearest">
              <input type="text" name="Date" placeholder="2012-01-01 07:00" value="{{ $tgl[0] }}"  class="form-control datetimepicker-input" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" data-target="#datetimepicker1" />
              <span class="input-group-addon" data-target="#datetimepicker1" data-toggle="datetimepicker">
              <span class="fa fa-calendar"></span>
              </span>
            </div>
          </div>
          <!-- <div class="form-group">
            {!! Form::label('', 'Tanggal', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
             <input type="datetime-local" name="Date" min="1" value="{{ $tgl[0] }}T{{ $tgl[1] }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
            </div>
          </div> -->
          <!-- <div class="form-group">
            {!! Form::label('', 'Waktu Mulai', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="time" name="Time_Start" min="1" value="{{ $datas->Time_Start }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Waktu Selesai', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="time" name="Time_End" min="1" value="{{ $datas->Time_End }}"  class="form-control form-control-sm">
            </div>
          </div> -->
          <!-- <div class="form-group">
            {!! Form::label('', 'Token', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Token" min="1" value="{{ $datas->Token }}"  class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Waktu Maksimal', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="number" name="Max_Minutes" min="1" value="{{ $datas->Max_Minutes }}"  class="form-control form-control-sm">
            </div>
          </div> -->
          <div class="form-group">
            {!! Form::label('', 'Konten Matakuliah', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
            <textarea class="form-control form-control-sm" name="Course_Content" id="" style="height:100px;">{{ $datas->Course_Content }}</textarea>
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Deskripsi', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
            <textarea class="form-control form-control-sm" name="Description" id="" style="height:100px;">{{ $datas->Description }}</textarea>
            </div>
          </div>
          <br><center><button type="submit" class="btn btn-primary btn-flat">Simpan</button></center>

          {!! Form::close() !!}
      </div>
    </div>
  </div>
  </div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment-with-locales.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>
<script src="https://rawgit.com/tempusdominus/bootstrap-4/master/build/js/tempusdominus-bootstrap-4.js"></script>
<link href="https://rawgit.com/tempusdominus/bootstrap-4/master/build/css/tempusdominus-bootstrap-4.css" rel="stylesheet"/>

<script>
$(function() {
        $('#datetimepicker1').datetimepicker({
          format:'YYYY-MM-DD HH:mm',
        });
      });

var select = new SlimSelect({
select: '#select'
})

select.selected()

var ruang = new SlimSelect({
select: '#ruang'
})

ruang.selected()
</script>
</section>

@endsection
