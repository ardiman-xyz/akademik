@extends('layouts._layout')
@section('pageTitle', 'Daftar Mahasiswa KRS')
@section('content')

  <?php
  $access = auth()->user()->akses();
            $acc = $access;
  ?>
<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">DETAIL MAHASISWA TIDAK AKTIF</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('laporan/laporan_mhskrs?term_year='.$term_year.'&prog_kelas='.$prog_kelas.'&angkatan='.$angkatan) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Detail Mahasiswa Non-Aktif Prodi {{$department->Department_Name}} th/smt {{ $thsmt->Term_Year_Name }}</b>
        </div>
      </div>
        </div>

      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        <br>
        @if (count($errors) > 0)
          @foreach ( $errors->all() as $error )
            <p class="alert alert-danger">{{ $error }}</p>
          @endforeach
        @endif
<input type="text" name="id_prod" hidden value="{{ $id }}">
          @if(in_array('laporan_mhskrs-CanExport', $acc))
          <a href="{{ url('laporan/laporan_mhskrs/exportexcelnonaktif/exportexcelnonaktif?term_year='.$term_year.'&prog_kelas='.$prog_kelas.'&angkatan='.$entry_year.'&department='.$id) }}" target="_blank" class="btn btn-primary btn-sm" style="float:left; font-size:medium;margin-right:5px;">Export Excel &nbsp;<i class="fa fa-print"></i></a>
          @endif
        <div class="table-responsive">
          <br>
        <table id="tbl" class="table table-striped table-font-sm">
          <thead class="thead-default thead-green">
              <tr>
                  <th width="10%">No</th>
                  <th width="15%">Nim</th>
                  <th width="30%">Nama Mahasiswa</th>
                  <th width="15%">Program Kelas</th>
                  <th width="15%">Tagihan</th>
              </tr>
          </thead>
          <tbody>
            <?php
            $a = "1";
            foreach ($query as $data) {
              ?>
              <tr>
                  <!-- <th></th> -->
                  <td>{{$a}}</td>
                  <td>{{ $data->Nim }}</td>
                  <td>{{ $data->Full_Name }}</td>
                  <td>{{ $data->Class_Program_Name }}</td>
                  <?php
                  $studentbill = DB::select('CALL usp_GetStudentBill(?,?,?)',array($data->Register_Number,'',''));
                  $i = 0;
                  $ListTagihan = [];
                   $total=0;
                  if($studentbill!=null){
                    foreach ($studentbill as $key) {
                      $ListTagihan[$i]['Amount'] = $key->Amount;
                      $ListTagihan[$i]['Cost_Item_Name'] = $key->Cost_Item_Name;
                      $i++;
                    }

                    $sumAmount =0;
                          foreach ($ListTagihan as $tagihan) {
                            $sumAmount += $tagihan['Amount'];
                          }
                   $total = number_format($sumAmount,'0',',','.');
                 }
                   ?>
                  <td>{{ $total }}</td>
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
