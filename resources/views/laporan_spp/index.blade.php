@extends('layouts._layout')
@section('pageTitle', 'Laporan Pembayaran SPP')
@section('content')
  <?php
  $access = auth()->user()->akses();
            $acc = $access;
  ?>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Data Registrasi Mahasiswa Tahun Akademik {{$term_year}}</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          <div class="pull-right tombol-gandeng dua">
          </div>
          <b>Data Registrasi Mahasiswa Tahun Akademik {{$term_year}}</b>
        </div>
      </div>
      <br>
          <!-- <b>Daftar Fakultas</b> -->
          {!! Form::open(['url' => route('laporan_spp.index') , 'method' => 'GET', 'name' => 'form', 'role' => 'form', 'style' => 'padding-top:5px;padding-left:15px;padding-right:15px;']) !!}
          <div class="row text-green">
            <!-- <label class="col-md-2" >Department :</label> -->
            <label value="0" class="col-md-2">Tahun/Semester :</label>
            <select class="form-control form-control-sm col-md-2" name="term_year"  onchange="document.form.submit();">
              <option value="" selected>Pilih...</option>
                @foreach ( $select_term_year as $data )
                <option <?php if($term_year == $data->Term_Year_Id){ echo "selected"; } ?> value="{{ $data->Term_Year_Id }}">{{ $data->Term_Year_Name }}</option>
              @endforeach
            </select>&nbsp
          </div>
        {!! Form::close() !!}
      </div><br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        @if (count($errors) > 0)
          @foreach ( $errors->all() as $error )
            <p class="alert alert-danger">{{ $error }}</p>
          @endforeach
        @endif
        <div class="table-responsive">
          <table width="100%" class="table table-striped table-font-sm">
            <thead class="thead-default thead-green">
                <tr>
                    <th>No.</th>
                    <th>Fakultas</th>
                    <th>Prodi</th>
                    <th>L</th>
                    <th>P</th>
                    <th>Total</th>
                    <th width="15%"><center><i class="fa fa-gear"></i></center></th>
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
                    <td>{{$data->Fak}}</td>
                    <td>{{$data->Prodi}}</td>
                    <td>{{$data->Total_L}}</td>
                    <td>{{$data->Total_P}}</td>
                    <td>{{$data->Total}}</td>
                    <td align="center">
                      <a href="{{ url('laporan/laporan_spp/exportexcel/exportexcel/'.$data->dep_Id.'/'.$term_year) }}" class="btn btn-info btn-sm">Detail</a>
                    </td>
                </tr>
                <?php
                $a++;
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
