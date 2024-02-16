@extends('layouts._layout')
@section('pageTitle', 'Student Password')
@section('content')

<?php
function tanggal_indo($tanggal, $cetak_hari = false)
{
	$hari = array ( 1 =>    'Senin',
				'Selasa',
				'Rabu',
				'Kamis',
				'Jumat',
				'Sabtu',
				'Minggu'
			);

	$bulan = array (1 =>   'Januari',
				'Februari',
				'Maret',
				'April',
				'Mei',
				'Juni',
				'Juli',
				'Agustus',
				'September',
				'Oktober',
				'November',
				'Desember'
			);
	$split 	  = explode('-', $tanggal);
	$tgl_indo = $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];

	if ($cetak_hari) {
		$num = date('N', strtotime($tanggal));
		return $hari[$num] . ', ' . $tgl_indo;
	}
	return $tgl_indo;
}
?>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Ubah Password Mahasiswa</h3>
    </div>
	</div>
	<div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
					<b>Password Mahasiswa</b>
				</div>
			</div>
			<br>
          <!-- <b>Daftar Fakultas</b> -->
          {!! Form::open(['url' => route('student_password.index') , 'method' => 'GET', 'name' => 'form', 'role' => 'form', 'style' => 'padding-top:5px;padding-left:15px;padding-right:15px;']) !!}
          <div class="row text-green">
            <label class="col-md-2">Cari NIM :</label>
            <input type="text" name="nim"  class="form-control form-control-sm col-md-3" value="{{ $nim }}" placeholder="NIM">
            <label class="col-md-2" >Cari Berdasarkan Nama :</label>
            <input type="text" name="search"  class="form-control form-control-sm col-md-3" value="{{ $search }}" placeholder="Nama Mahasiswa">
            &nbsp
            <input type="submit" name="" style="float:right;" class="btn btn-primary sm" value="Submit">

          </div><br>
          {!! Form::close() !!}
        </div>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        @if (count($errors) > 0)
          @foreach ( $errors->all() as $error )
            <p class="alert alert-danger">{{ $error }}</p>
          @endforeach
        @endif
        <div class="table-responsive">
        <table class="table table-striped table-font-sm">
          <thead class="thead-default thead-green">
              <tr>
                  <th>NIM</th>
                  <th>Nama Mahasiswa</th>
                  <th>Program Studi</th>
                  <th width="15%"><center><i class="fa fa-gear"></i></center></th>
              </tr>
          </thead>
          <tbody>
            <?php
            foreach ($query as $data) {
              if ($data->Birth_Date == null) {
                $birth = "";
              }else {
                $date = strtotime($data->Birth_Date);
                $da = Date('Y-m-d',$date);
                $birth = tanggal_indo($da,true);
              }

            ?>
              <tr>
                  <td>{{ $data->Nim }}</td>
                  <td>{{ $data->Full_Name }}</td>
                  <td>{{ $data->Department_Name }}</td>
                  <td align="center">
                      <a href="{{ url('setting/student_password/'.$data->Student_Id.'/edit?search='.$search.'&nim='.$nim) }}" class="btn btn-info btn-sm">Edit Password</a>
                  </td>
              </tr>
              <?php
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
