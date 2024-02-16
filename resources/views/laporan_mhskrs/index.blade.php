@extends('layouts._layout')
@section('pageTitle', 'Daftar Mahasiswa Krs')
@section('content')
  <?php
  $access = auth()->user()->akses();
            $acc = $access;
  ?>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">RESUME MAHASISWA AKTIF KRS</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          <div class="pull-right tombol-gandeng dua">
          </div>
          <b>Resume Mahasiswa Aktif KRS</b>
        </div>
      </div>
      <br>
          <!-- <b>Daftar Fakultas</b> -->
          {!! Form::open(['url' => route('laporan_mhskrs.index') , 'method' => 'GET', 'name' => 'form', 'role' => 'form', 'style' => 'padding-top:5px;padding-left:15px;padding-right:15px;']) !!}
          <div class="row text-green">
            <!-- <label class="col-md-2" >Department :</label> -->
            <label value="0" class="col-md-2">Tahun/Semester :</label>
            <select class="form-control form-control-sm col-md-2" name="term_year"  onchange="document.form.submit();">
              <option value="" selected>Pilih...</option>
                @foreach ( $select_term_year as $data )
                <option <?php if($term_year == $data->Term_Year_Id){ echo "selected"; } ?> value="{{ $data->Term_Year_Id }}">{{ $data->Term_Year_Name }}</option>
              @endforeach
            </select>&nbsp
            <!-- <label class="col-md-2">Tahun Semester :</label> -->
            <label value="0" class="col-md-2">Program Kelas :</label>
            <select class="form-control form-control-sm col-md-2" name="prog_kelas"  onchange="document.form.submit();">
              <option value="" selected>Pilih...</option>
                @foreach ( $select_class_program as $data )
                <option <?php if($prog_kelas == $data->Class_Prog_Id){ echo "selected"; } ?> value="{{ $data->Class_Prog_Id }}">{{ $data->Class_Program_Name }}</option>
              @endforeach
            </select>&nbsp

            <label value="0" class="col-md-1">Angkatan:</label>
            <select class="form-control form-control-sm col-md-2" name="angkatan"  onchange="document.form.submit();">
              <option value="">Semua</option>
              @foreach ( $select_entry_year as $data )
              <option <?php if($entry_year == $data->Entry_Year_Id){ echo "selected"; } ?> value="{{ $data->Entry_Year_Id }}">{{ $data->Entry_Year_Code }}</option>
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
                    <th>Prodi</th>
                    <th>Jumlah Mhs Aktif</th>
                    <th>Cuti</th>
                    <th>Jumlah Mhs Non-Aktif</th>
                    <!-- <th>Lulus</th> -->
                </tr>
            </thead>
            <tbody>
              <?php
              $a = "1";
              $jmlnonaktif = 0;
              $jmlaktif = 0;
              $MhsCuti = 0;
              foreach ($new_data as $data) {
                $jmlnonaktif += $data['JumlahMhsNonAktif'];
                $jmlaktif += $data['JumlahMhsAktif'];
                $MhsCuti += $data['MhsCuti'];
                ?>
                <tr>
                    <!-- <th></th> -->
                    <td>{{ $a }}</td>
                    <td>{{ $data['Department_Name'] }}</td>
                    <?php
                    if ($data['JumlahMhsAktif'] != 0) {
                      ?>
                      <td><center>
                        @if(in_array('laporan_mhskrs-CanView', $acc))
                        <a  style="text-decoration:none;" href="{{ url('laporan/laporan_mhskrs/'.$data['Department_Id'].'?term_year='.$term_year.'&prog_kelas='.$prog_kelas.'&angkatan='.$entry_year) }}">{{ $data['JumlahMhsAktif'] }} &nbsp <span class="glyphicon glyphicon-zoom-in"></span></a>
                        @else
                        {{ $data['JumlahMhsAktif'] }}
                        @endif
                      </center></td>
                      <?php
                    }else {
                      ?>
                      <td><center>0</td>
                      <?php
                    }?>

                    <?php
                    if ($data['MhsCuti'] != 0) {
                      ?>
                      <td><center>
                      <a  style="text-decoration:none;" href="{{ url('laporan/laporan_mhskrs/showmhscuti/'.$data['Department_Id'].'?term_year='.$term_year.'&prog_kelas='.$prog_kelas.'&angkatan='.$entry_year) }}">{{ $data['MhsCuti'] }} &nbsp <span class="glyphicon glyphicon-zoom-in"></span></a>
                      </center></td>
                      <?php
                    }else {
                      ?>
                      <td><center>0</td>
                      <?php
                    }?>

                    <?php
                    if ($data['JumlahMhsNonAktif'] != 0) {
                      ?>
                      <td><center>
                        @if(in_array('showmhsnonaktif-CanViewnonaktif', $acc))
                        <a  style="text-decoration:none;" href="{{ url('laporan/laporan_mhskrs/showmhsnonaktif/'.$data['Department_Id'].'?term_year='.$term_year.'&prog_kelas='.$prog_kelas.'&angkatan='.$entry_year) }}">{{ $data['JumlahMhsNonAktif'] }} &nbsp <span class="glyphicon glyphicon-zoom-in"></span></a>
                        @endif
                      </center></td>
                      <?php
                    }else {
                      ?>
                      <td><center>0</td>
                      <?php
                    }?>
                </tr>
                <?php
                $a++;
              }
              ?>
              <tr bgcolor='#dbc3c0'>
                  <td colspan="2"><center>TOTAL</td>
                  <td><center>{{$jmlaktif}}</td>
                  <td><center>{{$MhsCuti}}</td>
                  <td><center>{{ $jmlnonaktif }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
