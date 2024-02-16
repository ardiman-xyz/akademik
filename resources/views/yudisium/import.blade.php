@extends('layouts._layout')
@section('pageTitle', 'Yudisium')
@section('content')

<?php
$access = auth()->user()->akses();
          $acc = $access;
?>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Yudisium</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          <?php $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; ?>
          <div class="pull-right tombol-gandeng dua">
            <a href="{{ url('proses/yudisium?department='.$request->department) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
         </div>
          <b>Yudisium</b>
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

      <div class="form-group">
            <div class="col-md-12">
                <a href="{{ url('getfile?name=panduan/yudisium.xlsx') }}" class="btn btn-info btn-sm">Download Contoh &nbsp;<i class="fa fa-download"></i></a>
            </div>
            <br>
            {!! Form::label('', 'Program Studi', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              @foreach ( $mstr_department as $data )
              <input type="hidden" name="department" value="{{ $request->department }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
              <input type="text" readonly value="{{ $data->Department_Name }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm">
              @endforeach
            </div>
          </div>
          <br/>
      <form style="border: 4px solid #a1a1a1;margin-top: 15px;padding: 10px;" action="{{ route('storeImportYudisium') }}" class="form-horizontal" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}
          <input type="hidden" name="department" value="{{ $request->department }}">
          <input type="hidden" name="term_year" value="{{ $request->term_year }}">
          <input type="file" name="import_file" />
          <button class="btn btn-primary">Import File</button>
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
</section>
@endsection
