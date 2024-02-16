@extends('layouts._layout')
@section('pageTitle', 'Pertemuan Kuliah')
@section('content')
<section class="content">

  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Tambah Pertemuan Kuliah</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('proses/schedreal/'.$id) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Tambah</b>
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
          {!! Form::open(['url' => route('schedreal.store') , 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form']) !!}
          
          
          @foreach($offeredcourse as $ofc)
          <input type="hidden" name="Course_Id" value="{{ $ofc->Course_Id }}">
          <input type="hidden" name="Class_Id" value="{{ $ofc->Class_Id }}">
          <input type="hidden" name="Term_Year_Id" value="{{ $ofc->Term_Year_Id }}">
          <input type="hidden" name="Class_Prog_Id" value="{{ $ofc->Class_Prog_Id }}">
          @endforeach
          <div class="form-group">
            {!! Form::label('', 'Pertemuan Ke-', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="number" name="Meeting_Order" min="1" value="{{ $totalpertemuan }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Dosen', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select id="select" class="form-control form-control-sm" name="Employee_Id[]" id="" multiple>
                @foreach($data_dosen as $dosen)
                    <option selected value="{{ $dosen->Employee_Id }}">{{ $dosen->First_Title }}{{ $dosen->Name }}{{ $dosen->Last_Title }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Ruang', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select id="ruang" class="form-control form-control-sm" name="Room_Id">
                <option value="">--Pilih Ruang--</option>
                @foreach($room as $val)
                    <option <?php if($val->Room_Id == ($room_kuliah == null ? 0 : $room_kuliah->Room_Id) ){ echo "selected"; } ?> value="{{ $val->Room_Id }}">{{ $val->Room_Name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <!-- <div class="form-group">
            {!! Form::label('', 'Tanggal', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12 date" id='datetimepicker1'>
             <input type="datetime-local" name="Date" min="1" value="{{ old('Date') }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Tanggal', ['class' => 'col-md-4 form-label']) !!}
              <div class='col-md-12 input-group date' id='datetimepicker1'>
                  <input type='text' class="form-control" />
                  <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                  </span>
              </div>
          </div> -->
          <div class="form-group">
            {!! Form::label('', 'Tanggal', ['class' => 'col-md-4 form-label']) !!}
            <!-- <div class="col-md-3 input-group date" id="datetimepicker1" data-target-input="nearest">
              <input type="text" name="Date" placeholder="2012-01-01 07:00" class="form-control datetimepicker-input"  oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" data-target="#datetimepicker1" />
              <span class="input-group-addon" data-target="#datetimepicker1" data-toggle="datetimepicker">
              <span class="fa fa-calendar"></span>
              </span>
            </div> -->
            <div class="col-md-12">
              <input id="datetimepickerkendo" name="datetimepickerkendo" title="datetimepickerkendo" />
            </div>
          </div>
          <!-- <div class="form-group">
            {!! Form::label('', 'Waktu Mulai', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="time" name="Time_Start" min="1" value="{{ old('Time_Start') }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Waktu Selesai', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="time" name="Time_End" min="1" value="{{ old('Time_End') }}"  class="form-control form-control-sm">              
            </div>
          </div> -->
          <!-- <div class="form-group">
            {!! Form::label('', 'Token', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Token" min="1" value="{{ old('Token') }}"  class="form-control form-control-sm">
            </div>
          </div> -->
          <!-- <div class="form-group">
            {!! Form::label('', 'Waktu Maksimal', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="number" name="Max_Minutes" min="1" value="{{ old('Max_Minutes') }}"  class="form-control form-control-sm">
            </div>
          </div> -->
          <div class="form-group">
            {!! Form::label('', 'Konten Matakuliah', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
            <textarea class="form-control form-control-sm" name="Course_Content" id="" style="height:100px;">{{ old('Course_Content') }}</textarea>
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Deskripsi', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
            <textarea class="form-control form-control-sm" name="Description" id="" style="height:100px;">{{ old('Description') }}</textarea>
            </div>
          </div>

                <table class="table table-striped table-font-sm">
        <thead class="thead-default thead-green">
            <tr>
                <th width="15%">NIM</th>
                <th width="75%">Nama</th>
                <th width="5%">Kehadiran</th>
                <th width="5%">
                 @if(in_array('schedreal-CanEditPeserta', $acc))
                  <input disable type="checkbox" id="all" />
                @else
                  <input disable type="checkbox" id="all" />
                @endif
              </th>                
            </tr>
        </thead>
        <tbody>
          @foreach($datass as $data)
            <tr>
              <td>{{ $data->Nim }}</td>
              <td>{{ $data->Full_Name }}</td>
              <td colspan="2"><center>
              <input  type="checkbox" class="student" name="Student_Id[]" value="{{ $data->StudentId }}">
              </center></td>
            </tr>
          @endforeach
        </tbody>
      </table>

          <br><center><button type="submit" class="btn btn-primary btn-flat">Tambah</button></center>

          {!! Form::close() !!}
      </div>
    </div>
  </div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment-with-locales.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>
<script src="https://rawgit.com/tempusdominus/bootstrap-4/master/build/js/tempusdominus-bootstrap-4.js"></script>
<link href="https://rawgit.com/tempusdominus/bootstrap-4/master/build/css/tempusdominus-bootstrap-4.css" rel="stylesheet"/>

<script>
$(document).ready(function () {
      $('#all').click(function () {
          $('.student').prop("checked", this.checked);
      });

      $(function() {
        $('#datetimepicker1').datetimepicker({
          format:'YYYY-MM-DD HH:mm',
        });
      });

      $("#datetimepickerkendo").kendoDateTimePicker({
          value: new Date(),
          timeFormat: "HH:mm",
          format: "yyyy-MM-dd HH:mm",
          dateInput: true
      });

      //  $(function () {
      //   var bindDatePicker = function() {
      //     $(".date").datetimepicker({
      //         format:'YYYY-MM-DD HH:mm:ss',
      //       icons: {
      //         time: "fa fa-clock-o",
      //         date: "fa fa-calendar",
      //         up: "fa fa-arrow-up",
      //         down: "fa fa-arrow-down"
      //       }
      //     }).find('input:first').on("blur",function () {
      //       // check if the date is correct. We can accept dd-mm-yyyy and yyyy-mm-dd.
      //       // update the format if it's yyyy-mm-dd
      //       var date = parseDate($(this).val());

      //       if (! isValidDate(date)) {
      //         //create date based on momentjs (we have that)
      //         date = moment().format('YYYY-MM-DD HH:mm:ss');
      //       }

      //       $(this).val(date);
      //     });
      //   }
        
      //   var isValidDate = function(value, format) {
      //     format = format || false;
      //     // lets parse the date to the best of our knowledge
      //     if (format) {
      //       value = parseDate(value);
      //     }

      //     var timestamp = Date.parse(value);

      //     return isNaN(timestamp) == false;
      //   }
        
      //   var parseDate = function(value) {
      //     var m = value.match(/^(\d{1,2})(\/|-)?(\d{1,2})(\/|-)?(\d{4})$/);
      //     if (m)
      //       value = m[5] + '-' + ("00" + m[3]).slice(-2) + '-' + ("00" + m[1]).slice(-2);

      //     return value;
      //   }
        
      //   bindDatePicker();
      // });

      // $(function(){           
      //   if (!Modernizr.inputtypes.date) {
      //       $('input[type=date]').datepicker({
      //             dateFormat : 'yy-mm-dd HH:mm:ss'
      //           }
      //       );
      //   }
      // });
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
