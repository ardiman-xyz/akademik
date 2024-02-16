@extends('layouts._layout')
@section('pageTitle', 'Cetak Transkrip Akhir')
@section('content')
  <?php
  $access = auth()->user()->akses();
            $acc = $access;
  ?>

<style>
@font-face {
    font-family: tahoma;
    /* src: url(sansation_light.woff); */
    src: local('tahoma'), url('/fonts/tahoma.ttf') format('ttf');
  }

  .tahoma{
    font-family: tahoma;
  }
</style>
<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Transkrip Nilai Akhir</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          <b>Transkrip Nilai Akhir</b>
        </div>
      </div>
      <br>
        {!! Form::open(['url' => route('transcript_akhir.index') , 'method' => 'GET', 'name' => 'form', 'role' => 'form', 'style' => 'padding-top:5px;padding-left:15px;padding-right:15px;']) !!}
          <div class="row text-green">
          <label class="col-md-2">NIM Mahasiswa :</label>
            <input type="text" class="form-control form-control-sm col-md-3" name="nim" placeholder=" NIM" value="{{$nim}}">
        </div>
        <hr>

        {!! Form::close() !!}
        </div>
        <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
          <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            <div class="col-sm-6">
              @if($student != "")
                <div >
                  <label for="" class="col-sm-5">Nama Mahasiswa</label>
                  <label for="" class="col-sm-5">:
                    @if($student != "")
                      {{ $student->Full_Name }}
                    @endif
                  </label>
                </div>

                <div>
                  <label for="" class="col-sm-5">Cetak Transkrip akhir</label><?php
                  foreach ($jmldata as $jmldat){
                    ?>

                    @if(in_array('transcript_akhir-CanExport', $acc))
                      @if($jmldat != 0)
                        <label class="col-sm-5"><a href="{{ url('client/export_transcript_akhir/'.$student->Nim.'?to=download') }}" class="btn btn-info btn-sm" style="margin:5px;">Cetak <i class="fa fa-print"></i> </a></label>

                      @else
                        <button disabled class="btn btn-info btn-sm" target="_blank"><i class="fa fa-print"></i> Cetak </button>
                      @endif
                    @endif
                  <?php
                  }
                    ?></div>
                @endif
            </div>
          </div>
          @if (count($errors) > 0)
            @foreach ( $errors->all() as $error )
              <p class="alert alert-danger">{{ $error }}</p>
            @endforeach
          @endif

          @if ($student != "")
            <iframe width="100%" height="1056px" src="<?php echo env('APP_URL')?>{{ 'client/export_transcript_akhir/'.$student->Nim }}"></iframe>
          @endif

      <div class="table-responsive">
        <table class="table table-striped table-font-sm">
          <thead class="thead-default thead-green">
            <tr>
              <th width="10%">No.</th>
                <th width="10%">Kode Matakuliah</th>
                <th width="50%">Nama Matakuliah</th>
                <th width="10%">Nilai Huruf</th>
                <th width="10%">SKS</th>
                <th width="10%">Bobot x Nilai</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $a = "1";
            foreach ($query as $data) {

              ?>
              <tr>
                  <!-- <th></th> -->
                  <td>{{ $a }}</td>
                  <td>{{ $data->Course_Code }}</td>
                  <td>{{ $data->Course_Name }}</td>
                  <td>{{ $data->Grade_Letter }}</td>
                  <td>{{$data->Sks}}</td>
                  <td>{{ $data->weightvalue }}</td>

              </tr>
              <?php
              $a++;
            }
            ?>
            <tr style="background-color: #d9d9d9; font-size:12px; font-weight:bold;">
                <td colspan="3" ><center>JUMLAH</td>
                  <td></td>
                <td>{{$query_->jml_sks}}</td>
                <td>{{ $query_->jml_mutu }}</td>
            </tr>
          </tbody>
        </table>
      </div>

        <div class="col-sm-6">
          <div >
            <label for="" class="col-sm-6">Bobot Nilai x Jumlah SKS Semester</label>
            <label for="" class="col-sm-4">: {{ $query_->jml_mutu }}</label>
          </div>
            <div >
              <label for="" class="col-sm-6">Jumlah SKS Semester</label>
              <label for="" class="col-sm-4">: {{$query_->jml_sks}}</label>
            </div>
            <div >
              <label for="" class="col-sm-6">Index Prestasi Semester</label>
              <label for="" class="col-sm-4">: {{ $query_->ipk }}</label>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
</section>
@endsection()
