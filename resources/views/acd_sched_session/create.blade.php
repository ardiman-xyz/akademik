@extends('layouts._layout')
@section('pageTitle', 'Sched Session')
@section('content')
<section class="content">
<div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">SESI JADWAL KULIAH </h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('setting/sched_session?sched_session_group_id='.$Sched_Session_Group_Id.'&sched_type_id='.$Sched_Type_Id.'&term_year='.$request->term_year) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Tambah</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">

          @if (count($errors) > 0)
            @foreach ( $errors->all() as $error )
              @if ($error=="Berhasil Menambah Sesi Jadwal")
                <p class="alert alert-success">{{ $error }}</p>
              @else
                <p class="alert alert-danger">{{ $error }}</p>
              @endif
            @endforeach
          @endif
          <div class="form-group">
            {!! Form::open(['url' => route('sched_session.store') , 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form']) !!}
            <div class="col-md-12">
              <input type="hidden" name="Sched_Session_Group_Id" min="1" value="{{ $Sched_Session_Group_Id }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control">
              <input type="hidden" name="Sched_Type_Id" min="1" value="{{ $Sched_Type_Id }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control">
              <input type="hidden" name="term_year" min="1" value="{{ $request->term_year }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Type', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" readonly value="{{ $type->Sched_Type_Name }}" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Grup Sesi', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" readonly value="{{ $session_group->Sched_Session_Group_Name }}"  class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Hari', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select class="form-control form-control-sm col-md-12" name="Day_Id" id="pilih" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')">
                <option value="">Pilih Hari</option>
                @foreach ( $select_day as $class )
                  <option value="{{ $class->Day_Id }}">{{ $class->Day_Name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Sesi', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select class="form-control form-control-sm col-md-12" name="Order_Id" id="select" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')">
                <option value="">Pilih Sesi</option>
                <?php
                for ($i=1; $i <= 20; $i++) {
                ?>
                <option value="{{ $i }}">{{ $i }}</option>
                <?php
                }
                ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Jam Mulai', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="time" id="time" name="Time_Start"  value="{{ old('Time_Start') }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Jam Selesai', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="time" name="Time_End"  value="{{ old('Time_End') }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
            </div>
          </div>
          <br><center><button type="submit" class="btn btn-primary btn-flat">Tambah</button></center>

          {!! Form::close() !!}
      </div>
    </div>
  </div>

<!-- /.row -->

  <link href="{{ asset('css/timepicker.min.css') }}" rel="stylesheet">
  <script src="{{ asset('js/timepicker.min.js') }}"></script>

</section>
<script type="text/javascript">

$(document).on("change", "#pilih", function (event) {
    var day_id = $("#pilih").val();
    var sched_session_group_id = <?php echo $Sched_Session_Group_Id; ?>;
    var sched_type_id = <?php echo $Sched_Type_Id; ?>;
    var term_year = <?php echo $request->term_year; ?>;
    var url = {!! json_encode(url('/')) !!};

        $.ajax({
            url: url + "/setting/sched_session/create/session?sched_session_group_id="
            + sched_session_group_id
            + "&sched_type_id=" + sched_type_id
            + "&term_year=" + term_year,
            data: {
                day_id: day_id
            },
            cache: false,
            type: "GET",
            dataType: "html",

            success: function (data, textStatus, XMLHttpRequest) {
                $("#select").html(data);// HTML DOM replace
            }
        });

});


// var timepicker = new TimePicker('time', {
//   lang: 'en',
//   theme: 'dark'
// });
// timepicker.on('change', function(evt) {
//
//   var value = (evt.hour || '00') + ':' + (evt.minute || '00');
//   evt.element.value = value;
//
// });
</script>

@endsection
