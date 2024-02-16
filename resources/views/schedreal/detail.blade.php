@extends('layouts._layout')
@section('pageTitle', 'Pertemuan Kuliah')
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

$date = explode(' ',$datas->Date);
$tanggal = tanggal_indo($date[0],false);
$ja = explode(':',$date[1]);
unset($ja[2]);
$jam = implode(':',$ja);

?>
<section class="content">

  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">DETAIL Pertemuan Kuliah</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
         <a href="{{ url('proses/schedreal/'.$id) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Detail</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
          <div class="row col-md-12 col-xs-12">
            {!! Form::label('', 'Pertemuan Ke', ['class' => 'col-md-4 col-xs-12']) !!}:
            <label for="" class="col-md-4 col-xs-12">{{$datas->Meeting_Order}}</label>
          </div>
          <div class="row col-md-12 col-xs-12">
            {!! Form::label('', 'Dosen', ['class' => 'col-md-4 col-xs-12']) !!}:
            <label for="" class="col-md-4 col-xs-12">
            <?php
                  foreach ($datas->empEmployees as $key) {
                      if ($key != null) {
                        $anu = DB::table('emp_employee')->where('acd_department_lecturer.Employee_Id',$key->Employee_Id)
                        ->join('acd_department_lecturer','acd_department_lecturer.Employee_Id','=','emp_employee.Employee_Id')
                        ->select('acd_department_lecturer.Department_Id','acd_department_lecturer.Employee_Id')
                        ->first();
                        // dd($anu->Department_Id);
                        if($anu->Department_Id != $offeredcourse->Department_Id){
                          $dosennya = DB::table('emp_employee')->where('Employee_Id',$anu->Employee_Id)->first();
                          $firstitle = $dosennya->First_Title;
                          $name = $dosennya->Name;
                          $lasttitle = $dosennya->Last_Title;
                          // dd($firstitle);
                          echo $firstitle." ".$name." ".$lasttitle."<br>";
                        }else{
                            $dosennya = DB::table('emp_employee')->where('Employee_Id',$anu->Employee_Id)->first();
                          $firstitle = $dosennya->First_Title;
                          $name = $dosennya->Name;
                          $lasttitle = $dosennya->Last_Title;
                          echo $firstitle." ".$name." ".$lasttitle." (dosen prodi lain) <br>";
                        }
                      }
                  }
              ?>
            </label>
          </div>
          <div class="row col-md-12 col-xs-12">
            {!! Form::label('', 'Tanggal', ['class' => 'col-md-4 col-xs-12']) !!}:
            <label for="" class="col-md-4 col-xs-12">{{ $tanggal }} {{ $jam }}</label>
          </div>
          <div class="row col-md-12 col-xs-12">
            {!! Form::label('', 'Jam Mulai', ['class' => 'col-md-4 col-xs-12']) !!}:
            <label for="" class="col-md-4 col-xs-12">{{$datas->Time_Start}}</label>
          </div>
          <div class="row col-md-12 col-xs-12">
            {!! Form::label('', 'Jam Selesai', ['class' => 'col-md-4 col-xs-12']) !!}:
            <label for="" class="col-md-4 col-xs-12">{{$datas->Time_End}}</label>
          </div>
          <!-- <div class="row col-md-12 col-xs-12">
            {!! Form::label('', 'Max Minutes', ['class' => 'col-md-4 col-xs-12']) !!}:
            <label for="" class="col-md-4 col-xs-12">{{$datas->Max_Minutes}}</label>
          </div>
          <div class="row col-md-12 col-xs-12">
            {!! Form::label('', 'Token', ['class' => 'col-md-4 col-xs-12']) !!}:
            <label for="" class="col-md-4 col-xs-12">{{$datas->Token}}</label>
          </div> -->
          <div class="row col-md-12 col-xs-12">
            {!! Form::label('', 'Konten Matakuliah', ['class' => 'col-md-4 col-xs-12']) !!}:
            <label for="" class="col-md-4 col-xs-12">{{$datas->Course_Content}}</label>
          </div>
          <div class="row col-md-12 col-xs-12">
            {!! Form::label('', 'Deskripsi', ['class' => 'col-md-4 col-xs-12']) !!}:
            <label for="" class="col-md-4 col-xs-12">{{$datas->Description}}</label>
          </div>
      </div>
    </div>
  </div>



<!-- /.row -->

</section>
@endsection
