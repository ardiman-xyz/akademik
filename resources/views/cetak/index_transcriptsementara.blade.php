@extends('layouts._layout')
@section('pageTitle', 'Cetak Transkrip Sementara')
@section('content')
  <?php
  $access = auth()->user()->akses();
            $acc = $access;
  ?>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Transkrip Sementara</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          <b>Transkrip Sementara</b>
        </div>
      </div>
      <br>
        {!! Form::open(['url' => route('transcript_sementara.index') , 'method' => 'GET', 'name' => 'form', 'role' => 'form', 'style' => 'padding-top:5px;padding-left:15px;padding-right:15px;']) !!}
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
                  <label for="" class="col-sm-5">Cetak Transkrip Sementara</label>
                  <?php
                  foreach ($jmldata as $jmldat){
                    ?>
                    @if(in_array('transcript_sementara-CanExport', $acc))
                      @if($jmldat != 0)
                        <label class="col-sm-5"><a href="{{ url('cetak/transcript_sementara/'.$student->Nim.'/export'.'?type=transkripsementara&nim='.$nim) }}" class="btn btn-info btn-sm" style="margin:5px;" target="_blank">Cetak <i class="fa fa-print"></i> </a></label>
                      @else
                        <button disabled class="btn btn-info btn-sm" target="_blank"><i class="fa fa-print"></i> Cetak </button>
                      @endif
                    @endif
                  <?php
                  }
                    ?>
                </div>
                @endif
            </div>
          </div>
          @if (count($errors) > 0)
            @foreach ( $errors->all() as $error )
              <p class="alert alert-danger">{{ $error }}</p>
            @endforeach
          @endif

      <div class="table-responsive">
        <table class="table table-striped table-font-sm">
          <thead class="thead-default thead-green">
            <tr>
              <th>No.</th>
                <th>Kode Matakuliah</th>
                <th>Nama Matakuliah</th>
                <th>Nilai Huruf</th>
                <th>SKS</th>
                <th>Bobot x Nilai</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $a = "1";
            foreach ($query as $data) {

              ?>
              <tr>
                  <!-- <th></th> -->
                  <td width="10%">{{ $a }}</td>
                  <td width="10%">{{ $data->Course_Code }}</td>
                  <td width="50%">{{ $data->Course_Name }}</td>
                  <td width="10%">{{ $data->Grade_Letter }}</td>
                  <td width="10%">{{$data->Sks}}</td>
                  <td width="10%">{{ $data->weightvalue }}</td>

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
            <label for="" class="col-sm-6">Bobot Nilai x Jumlah SKS</label>
            <label for="" class="col-sm-4">: {{ $query_->jml_mutu }}</label>
          </div>
            <div >
              <label for="" class="col-sm-6">Jumlah SKS</label>
              <label for="" class="col-sm-4">: {{$query_->jml_sks}}</label>
            </div>
            <div >
              <label for="" class="col-sm-6">Index Prestasi</label>
              <label for="" class="col-sm-4">: {{ $query_->ipk }}</label>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
</section>
@endsection()
