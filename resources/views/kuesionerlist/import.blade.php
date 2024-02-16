@extends('layouts._layout')
@section('pageTitle', 'Import')
@section('content')

<?php
$access = auth()->user()->akses();
          $acc = $access;
?>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Import</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          <?php $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; ?>
          <div class="pull-right tombol-gandeng dua">
            <a href="{{ url('proses/krslist/index?department='.$request->department.'term_year='.$request->term_year) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
         </div>
          <b>Import</b>
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
                <a href="{{ url('getfile?name=panduan/mhs_kuesioner.xlsx') }}" class="btn btn-info btn-sm">Download Contoh &nbsp;<i class="fa fa-download"></i></a>
            </div>
            <br>
            <div class="row">
                <div  class="row col-md-7">
                    <label class="col-md-3">Program Studi :</label>
                    @foreach ( $mstr_department as $data )
                    <input type="hidden" name="department" value="{{ $request->department }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="">
                    <input type="text" readonly value="{{ $data->Department_Name }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm col-md-7 col-sm-7">
                    @endforeach
                </div>
            </div>
            <div class="row">
                <div  class="row col-md-7">
                    <label class="col-md-3">Semester :</label>
                    <input type="text" readonly value="{{ $request->term_year }}" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" class="form-control form-control-sm col-md-7 col-sm-7">
                </div>
            </div>
          </div>
          <br/>
      <form style="border: 4px solid #a1a1a1;margin-top: 15px;padding: 10px;" action="{{ route('krslist.storemhskuesioner') }}" class="form-horizontal" method="post" enctype="multipart/form-data">
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
