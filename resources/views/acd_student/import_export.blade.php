@extends('layouts._layout')
@section('pageTitle', 'mahasiswa')
@section('content')

<?php
$access = auth()->user()->akses();
          $acc = $access;
?>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Mahasiswa</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          <?php $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; ?>
          <div class="pull-right tombol-gandeng dua">
            <a href="{{ url('setting/student?department='.$department.'&entry_year='.$entry_year) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
         </div>
          <b>Mahasiswa</b>
        </div>
      </div>
      <br>
    </div>

      @if($message = Session::get('success'))
          <div class="alert alert-info alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
            <strong>Success!</strong> {{ $message }}
          </div>
      @endif
      {!! Session::forget('success') !!}
      <br />
      <!-- <a href="{{ URL::to('download-excel/xls') }}"><button class="btn btn-success">Download Excel xls</button></a>
      <a href="{{ URL::to('download-excel/xlsx') }}"><button class="btn btn-success">Download Excel xlsx</button></a>
      <a href="{{ URL::to('download-excel/csv') }}"><button class="btn btn-success">Download CSV</button></a> -->
      <div class="form-group">
        <div class="col-md-12">
          <input type='button' id='download_contoh' onclick='download_contoh()' value='Download Contoh' class='btn btn-success btn-sm' style="float:left; font-size:medium;margin-right:5px;">
        </div>
      </div>
      <br>
      <div class="form-group">
        {!! Form::label('', 'Program Studi', ['class' => 'col-md-4 form-label']) !!}
        <div class="col-md-12">
          @foreach ( $mstr_department as $data )
          <input type="hidden" name="department" value="{{ $department }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
          <input type="text" readonly value="{{ $data->Department_Name }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
          @endforeach
        </div>
      </div>
      <div class="form-group">
        {!! Form::label('', 'Tahun Angkatan', ['class' => 'col-md-4 form-label']) !!}
        <div class="col-md-12">
          <input type="text" readonly value="{{ $entry_year }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
        </div>
      </div>
      <form style="border: 4px solid #a1a1a1;margin-top: 15px;padding: 10px;" action="{{ route('student.import_excel') }}" class="form-horizontal" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input type="hidden" name="entry_year" value="{{ $entry_year }}">
        <input type="hidden" name="department" value="{{ $department }}">
        <input type="file" name="import_file" />
        <button class="btn btn-primary">Import Filew</button>
      </form>

      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        @if (count($errors) > 0)
          @foreach ( $errors->all() as $error )
            <p class="alert alert-danger">{{ $error }}</p>
          @endforeach
        @endif
        <br>
      </div>
    </div>
  </div>

  <script>
  function download_contoh() {
    console.log('downlaod');
    // window.open("https://www.w3schools.com","_blank");
    window.open("<?php echo env('APP_URL')?>{{ 'panduan/contoh_mahasiswa.xlsx' }}");
  }
  </script>
</section>
@endsection
