@extends('layouts._layout')
@section('pageTitle', 'Offered Course Exam')
@section('content')

<?php
$access = auth()->user()->akses();
          $acc = $access;
?>
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
      <h3 class="text-white">Laporan Pengisian Nilai Dosen</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          <b>Laporan Pengisian Nilai Dosen</b>
        </div>
      </div>
      <br>
          <!-- <b>Daftar Fakultas</b> -->
          {!! Form::open(['url' => route('detail_pengisian_nilai.index') , 'method' => 'GET', 'name' => 'form', 'role' => 'form', 'style' => 'padding-top:5px;padding-left:15px;padding-right:15px;']) !!}
          <div class="row text-green">
            <!-- <label class="col-md-2" >Department :</label> -->&nbsp &nbsp &nbsp &nbsp
            <select class="form-control form-control-sm col-md-3" name="term_year" onchange="document.form.submit();">
              <option value="0">Pilih Tahun / Semester</option>
              @foreach ( $select_term_year as $data )
                <option <?php if($term_year == $data->Term_Year_Id){ echo "selected"; } ?>  value="{{ $data->Term_Year_Id }}">{{ $data->Term_Year_Name }}</option>
              @endforeach
            </select>&nbsp
            <select class="form-control form-control-sm col-md-4" name="department"  onchange="document.form.submit();">
              <option value="0">Pilih Program Studi</option>
              @foreach ( $select_department as $data )
                <option <?php if($department == $data->Department_Id){ echo "selected"; } ?> value="{{ $data->Department_Id }}">{{ $data->Department_Name }}</option>
              @endforeach
            </select>&nbsp
            <!-- <label class="col-md-2">Tahun Semester :</label> -->
            <select class="form-control form-control-sm col-md-4" name="class_program"  onchange="document.form.submit();">
              <option value="0">Pilih Program Kelas</option>
              @foreach ( $select_class_program as $data )
                <option <?php if($class_program == $data->Class_Prog_Id){ echo "selected"; } ?> value="{{ $data->Class_Prog_Id }}">{{ $data->Class_Program_Name }}</option>
              @endforeach
            </select>&nbsp

              <?php $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; ?>

          </div>
          <br>
					<div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            <div  class="row col-md-7">
            <label class="col-md-3">Pencarian :</label>
            <input type="text" name="search"  class="form-control form-control-sm col-md-7 col-sm-7" value="{{ $search }}" placeholder="Search">
            <input type="submit" name="" class="btn btn-primary btn-sm col-md-2 col-sm-2" value="Cari">
            </div>
            <div  class="row col-md-5">
            <label class="col-md-5">Baris per halamam :</label>
            <select class="form-control form-control-sm col-md-7" name="rowpage" onchange="form.submit()">
              <option <?php if($rowpage == 10){ echo "selected"; } ?> value="10">10</option>
              <option <?php if($rowpage == 20){ echo "selected"; } ?> value="20">20</option>
              <option <?php if($rowpage == 50){ echo "selected"; } ?> value="50">50</option>
              <option <?php if($rowpage == 100){ echo "selected"; } ?> value="100">100</option>
              <option <?php if($rowpage == 200){ echo "selected"; } ?> value="200">200</option>
              <option <?php if($rowpage == "1000000"){ echo "selected"; } ?> value="1000000">semua</option>
            </select>
            </div>
          </div><br>
          {!! Form::close() !!}
        </div>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        @if (count($errors) > 0)
          @foreach ( $errors->all() as $error )
            <p class="alert alert-danger">{{ $error }}</p>
          @endforeach
        @endif
        @if($class_program==0 || $term_year ==null || $department==0)
        @else
          <a href="#" class="btn btn-primary btn-sm export" id='export' style="font-size:medium;margin-right:5px;">Export Excel &nbsp;<i class="fa fa-print"></i></a> &nbsp
          <input type="text" hidden id="dept" value="{{$department}}">
          <input type="text" hidden id="smt" value="{{$term_year}}">
          <input type="text" hidden id="kpg" value="{{$class_program}}">
        @endif
        <div class="table-responsive">
        <table class="table table-striped table-font-sm">
          <thead class="thead-default thead-green">
              <tr>
                  <!-- <th class="col-sm-1">No</th> -->
                  <th width="15%">Kode Matakuliah</th>
                  <th width="25%">Nama Matakuliah</th>
                  <th width="7%">kelas</th>
                  <!-- <th width="7%">Kapasitas Kelas</th> -->
                  <th width="7%">Jumlah Mahasiswa</th>
                  <th width="10%">Dosen</th>
                  <th width="10%">Tanggal Ujian</th>
                  <th width="10%">Tanggal Akhir Pengisian</th>
                  <th width="10%">Ikut Ujian</th>
                  <th width="10%">Sudah diisi nilai</th>
                  <th width="10%"><center><i class="fa fa-gear"></i></center></th>
              </tr>
          </thead>
          <tbody>
            <?php
            $a = "1";

            foreach ($query as $data) {

              ?>
              <tr>
                  <!-- <th></th> -->
                  <td>{{ $data->Course_Code }}</td>
                  <td>{{ $data->Course_Name }}</td>
                  <td>{{ $data->Class_Name }}</td>
                  <!-- <td>{{ $data->Class_Capacity }}</td> -->
                  <td>{{ $data->jml_peserta }}</td>
                  <td>
                    <?php
                    $dosen = explode('|',$data->dosen);
                    $id_dosen = explode('|',$data->id_dosen);
                      // dd($data);
                      foreach ($id_dosen as $key) {
                          if ($key != null) {
                            $anu = DB::table('emp_employee')->where('acd_department_lecturer.Employee_Id',$key)
                            ->join('acd_department_lecturer','acd_department_lecturer.Employee_Id','=','emp_employee.Employee_Id')
                            ->select('acd_department_lecturer.Department_Id','acd_department_lecturer.Employee_Id')
                            ->first();

                            $anu = DB::table('emp_employee')
                            ->join(DB::Raw("(SELECT Employee_Id,placement_Id,MAX(Tmt_Date) as Tmt_Date FROM emp_placement GROUP BY Employee_Id) as max_placement"), 'emp_employee.Employee_Id', 'max_placement.Employee_Id'
                            )
                            ->join('emp_placement',function($golru){
                                $golru->on('emp_placement.Employee_Id','emp_employee.Employee_Id')
                                ->on('emp_placement.Tmt_Date','max_placement.Tmt_Date');
                            })
                            // ->where('emp_placement.Department_Id', $department)
                            ->where('emp_placement.Employee_Id', $key)
                            ->first();
                            // dd($anu->Department_Id);
                            if($anu->Department_Id != $department){
                              $dosennya = DB::table('emp_employee')->where('Employee_Id',$anu->Employee_Id)->first();
                              $firstitle = $dosennya->First_Title;
                              $name = $dosennya->Name;
                              $lasttitle = $dosennya->Last_Title;
                              // dd($firstitle);
                              echo "<div class='btn btn-sm' style='background:#1d6446; color:#fff; cursor:default; margin:1px;'> ".$firstitle." ".$name." ".$lasttitle."</div>";
                            }else{
                               $dosennya = DB::table('emp_employee')->where('Employee_Id',$anu->Employee_Id)->first();
                              $firstitle = $dosennya->First_Title;
                              $name = $dosennya->Name;
                              $lasttitle = $dosennya->Last_Title;
                              echo "<div class='btn btn-sm' style='background:#3ac98d; color:#fff; cursor:default; margin:1px;'> ".$firstitle." ".$name." ".$lasttitle."</div>";
                            }
                          }
                      }
                    ?>
                  </td>
                  <!-- <td>
                    <?php
                      $startdate = explode('|', $data->start_date);
                      $enddate = explode('|', $data->end_date);
                      $n = 0;
                      if ($data->start_date != "") {
                      foreach ($startdate as $key) {
                            $start = explode(" ",$key);
                            $s_date = $start[0];
                            $s_time = explode(":",$start[1]);
                            unset($s_time[1]);
                            $s_time = implode(".",$s_time);
                            echo "<div class='btn btn-sm' style='background:#4cb24e; color:#fff; cursor:default; margin:1px;'>".tanggal_indo($s_date,false)." <font style='color:#7cffff;'>".$s_time."</font></div>";
                            $n++;
                        }
                      }
                    ?>
                  </td> -->
                  <td>
                    <?php
                      $startdate = explode('|', $data->start_date);
                      $eti = explode('|', $data->eti);
                      $n = 0;
                      if ($data->start_date != "") {
                        $cti = 0;
                        $simpan_date = "";
                        foreach ($eti as $ddate) {
                          if($ddate == 1){
                            $uas = 'UAS / ';
                            $startd = $startdate[$cti];
                              $start = explode(" ",$startd);
                              $s_date = $start[0];  
                              if($simpan_date == $s_date){
                                echo "<div class='btn btn-sm' margin:1px;'><br></div>";
                              }else{
                                echo "<div class='btn btn-sm' style='background:#4cb24e; color:#fff; cursor:default; margin:1px;'>".$uas.tanggal_indo($s_date,false)."</div>";                              
                              }
                              $simpan_date = $s_date;
                          }else{
                            $uas = 'UTS / ';
                            $startd = $startdate[$cti];
                              $start = explode(" ",$startd);
                              $s_date = $start[0];  
                              if($simpan_date == $s_date){
                                echo "<div class='btn btn-sm' margin:1px;'><br></div>";
                              }else{
                                echo "<div class='btn btn-sm' style='background:#4cb24e; color:#fff; cursor:default; margin:1px;'>".$uas.tanggal_indo($s_date,false)."</div>";
                              }
                              $simpan_date = $s_date;
                          }
                          $cti++;
                        }
                      }
                    ?>
                  </td>
                  <td>
                  <?php
                    $startdate = explode('|', $data->start_date);
                    $eti = explode('|', $data->eti);
                    $now = date('Y-m-d');
                    $n = 0;
                    if ($data->start_date != "") {
                      $cti = 0;
                      $simpan_date = "";
                      foreach ($eti as $ddate) {
                        if($ddate == 1){
                          $uas = 'UAS / ';
                          $startd = $startdate[$cti];
                          $start = explode(" ",$startd);
                          $s_date = $start[0];
                          $l_date = date('Y-m-d', strtotime($s_date. ' + 6 days'));
                          if($simpan_date == $s_date){
                            echo "<div class='btn btn-sm' margin:1px;'><br></div>";
                          }else{
                            while ($s_date <= $l_date){
                              $hari = date('l', strtotime($s_date));
                              if($hari == 'Saturday' || $hari == 'Sunday'){
                                // dd($s_date);
                                $l_date = date('Y-m-d', strtotime($l_date. ' + 1 days'));
                              }else{
                                $holiday = DB::table('mstr_holiday')->get();
                                if($holiday){
                                  foreach ($holiday as $key_holiday) {
                                    if($s_date >= $key_holiday->Start_Date && $s_date <= $key_holiday->End_Date){
                                      $haris = date('l', strtotime($s_date));
                                      if($haris == 'Saturday' || $haris == 'Sunday'){
                                        $l_date = date('Y-m-d', strtotime($l_date. ' + 1 days'));
                                      }
                                      $l_date = date('Y-m-d', strtotime($l_date. ' + 1 days'));
                                    }
                                  }
                                }
                              }
                              $s_date = date('Y-m-d', strtotime($s_date. ' + 1 days'));
                            }
                            $hari2 = date('l', strtotime($s_date));
                            if($hari2 == 'Saturday'){
                              $s_date = date('Y-m-d', strtotime($s_date. ' + 1 days'));
                            }
                            $hari3 = date('l', strtotime($s_date));
                            if($hari3 == 'Sunday'){
                              $s_date = date('Y-m-d', strtotime($s_date. ' + 1 days'));
                            }
                            // dd($s_date);
                            $last_date = new DateTime($s_date);
                            $now_date = new DateTime($now);
                            $hariakhir = date('d', strtotime($s_date));
                            $hariini = date('d', strtotime($now));
                            $last_date_f = $s_date;
                            $diff=date_diff($now_date,$last_date);                            
                            if(($hariakhir - $hariini) <= 2 && ($hariakhir - $hariini) >= 0){
                              if($simpan_date == $s_date){                              
                              }else{
                                echo "<div class='btn btn-sm' style='background:#ffff00; color:#000; cursor:default; margin:1px;'>".$uas.tanggal_indo($s_date,false)."</div>";
                              }
                            }elseif($now > $last_date_f){
                              if($simpan_date == $s_date){                              
                              }else{
                                echo "<div class='btn btn-sm' style='background:#f55142; color:#fff; cursor:default; margin:1px;'>".$uas.tanggal_indo($s_date,false)."</div>";
                              }
                            }else{
                              if($simpan_date == $s_date){                              
                              }else{
                                echo "<div class='btn btn-sm' style='background:#4cb24f; color:#fff; cursor:default; margin:1px;'>".$uas.tanggal_indo($s_date,false)."</div>";
                              }
                            }
                          }
                          $simpan_date = $s_date;
                        }else{
                          $uas = 'UTS / ';
                          $startd = $startdate[$cti];
                          $start = explode(" ",$startd);
                          $s_date = $start[0];  
                          $l_date = date('Y-m-d', strtotime($s_date. ' + 6 days'));
                          if($simpan_date == $s_date){
                            echo "<div class='btn btn-sm' margin:1px;'><br></div>";
                          }else{
                            while ($s_date <= $l_date){
                              $hari = date('l', strtotime($s_date));
                              if($hari == 'Saturday' || $hari == 'Sunday'){
                                $l_date = date('Y-m-d', strtotime($l_date. ' + 1 days'));
                              }else{
                                $holiday = DB::table('mstr_holiday')->get();
                                if($holiday){
                                  foreach ($holiday as $key_holiday) {
                                    if($s_date >= $key_holiday->Start_Date && $s_date <= $key_holiday->End_Date){
                                      $l_date = date('Y-m-d', strtotime($l_date. ' + 1 days'));
                                    }
                                  }
                                }
                              }
                              $s_date = date('Y-m-d', strtotime($s_date. ' + 1 days'));
                            }
                            $last_date = new DateTime($s_date);
                            $now_date = new DateTime($now);
                            $hariakhir = date('d', strtotime($s_date));
                            $hariini = date('d', strtotime($now));
                            $last_date_f = $s_date;
                            $diff=date_diff($now_date,$last_date);                            
                            if(($hariakhir - $hariini) <= 2 && ($hariakhir - $hariini) >= 0){
                              if($simpan_date == $s_date){                              
                              }else{
                                echo "<div class='btn btn-sm' style='background:#ffff00; color:#000; cursor:default; margin:1px;'>".$uas.tanggal_indo($s_date,false)."</div>";
                              }
                            }elseif($now > $last_date_f){
                              if($simpan_date == $s_date){                              
                              }else{
                                echo "<div class='btn btn-sm' style='background:#f55142; color:#fff; cursor:default; margin:1px;'>".$uas.tanggal_indo($s_date,false)."</div>";
                              }
                            }else{
                              if($simpan_date == $s_date){                              
                              }else{
                                echo "<div class='btn btn-sm' style='background:#4cb24f; color:#fff; cursor:default; margin:1px;'>".$uas.tanggal_indo($s_date,false)."</div>";
                              }
                            }
                          }
                          $simpan_date = $s_date;
                        }
                        $cti++;
                      }
                    }
                  ?>
                  </td>
                  <td>
                  <?php 
                  $startdate = explode('|', $data->start_date);
                  $eti = explode('|', $data->eti);
                  if($eti[0] != ""){
                  $type_uts = 0;
                  $type_uas = 0;
                  $cti = 0;
                  $simpan_date = "";
                  foreach ($eti as $ddate) {
                    if($ddate == 1){
                      $uas = 'UAS / ';
                      $startd = $startdate[$cti];
                      $start = explode(" ",$startd);
                      $s_date = $start[0];  
                      $datax = DB::table('acd_offered_course_exam as a')
                      ->where('a.Offered_Course_Id',$data->Offered_Course_id)
                      ->where('a.Exam_Type_Id',1)
                      ->get();
                      $ikut_ujian = 0;
                      // dd($datax);
                      foreach ($datax as $keys) {
                        // dd($keys->Offered_Course_Exam_Id);
                        $cekdata = DB::table('acd_offered_course_exam_member')->where('Offered_Course_Exam_Id',$keys->Offered_Course_Exam_Id)->select('Student_Id')->get();
                        // dd($cekdata);
                        foreach ($cekdata as $cekdatas) {
                          $krs_id = DB::table('acd_student_krs')
                            ->where('Student_Id',$cekdatas->Student_Id)
                            ->where('Course_Id',$data->Course_Id)
                            ->where('Term_Year_Id',$data->Term_Year_Id)
                            ->where('Class_Prog_Id',$data->Class_Prog_Id)
                            ->where('Class_Id',$data->Class_Id)
                            ->where('Is_Approved',1)
                            ->first();
                          $krs_ids = DB::table('acd_student_krs')
                            ->where('Student_Id',$cekdatas->Student_Id)
                            ->where('Course_Id',$data->Course_Id)
                            ->where('Term_Year_Id',$data->Term_Year_Id)
                            ->where('Class_Prog_Id',$data->Class_Prog_Id)
                            ->where('Class_Id',$data->Class_Id)
                            ->where('Is_Approved',1)
                            ->count();
                          if($krs_ids <= 0){
                          }else{
                            $cekisi = DB::table('acd_offered_course_exam_member')->where('Offered_Course_Exam_Id',$keys->Offered_Course_Exam_Id)->where('Student_Id',$cekdatas->Student_Id)->first();
                            // dd($cekisi);
                            if($cekisi == null){
                                $ikut_ujian = $ikut_ujian;
                              }else{
                                if($cekisi->Is_Presence == 1){
                                  $ikut_ujian = $ikut_ujian+1;
                                }
                            }
                            // dd($ikut_ujian);
                          }
                        }
                      }

                      if($simpan_date == $s_date){
                            echo "<div class='btn btn-sm' margin:1px;'><br></div>";
                          }else{
                            echo "<div class='btn btn-sm' style='background:#4cb24e; color:#fff; cursor:default; margin:1px;'>".$uas.$ikut_ujian."</div>";
                          }
                      $simpan_date = $s_date;
                    }elseif($ddate == 2){
                      $uas = 'UTS / ';
                      $startd = $startdate[$cti];
                      $start = explode(" ",$startd);
                      $s_date = $start[0];  
                      $datax = DB::table('acd_offered_course_exam as a')
                      ->where('a.Offered_Course_Id',$data->Offered_Course_id)
                      ->where('a.Exam_Type_Id',2)
                      ->get();
                      $ikut_ujian = 0;
                      // dd($datax);
                      foreach ($datax as $keys) {
                        // dd($keys->Offered_Course_Exam_Id);
                        $cekdata = DB::table('acd_offered_course_exam_member')->where('Offered_Course_Exam_Id',$keys->Offered_Course_Exam_Id)->select('Student_Id')->get();
                        // dd($cekdata);
                        foreach ($cekdata as $cekdatas) {
                          $krs_id = DB::table('acd_student_krs')
                            ->where('Student_Id',$cekdatas->Student_Id)
                            ->where('Course_Id',$data->Course_Id)
                            ->where('Term_Year_Id',$data->Term_Year_Id)
                            ->where('Class_Prog_Id',$data->Class_Prog_Id)
                            ->where('Class_Id',$data->Class_Id)
                            ->where('Is_Approved',1)
                            ->first();
                          $krs_ids = DB::table('acd_student_krs')
                            ->where('Student_Id',$cekdatas->Student_Id)
                            ->where('Course_Id',$data->Course_Id)
                            ->where('Term_Year_Id',$data->Term_Year_Id)
                            ->where('Class_Prog_Id',$data->Class_Prog_Id)
                            ->where('Class_Id',$data->Class_Id)
                            ->where('Is_Approved',1)
                            ->count();
                          if($krs_ids <= 0){
                          }else{
                            $cekisi = DB::table('acd_offered_course_exam_member')->where('Offered_Course_Exam_Id',$keys->Offered_Course_Exam_Id)->where('Student_Id',$cekdatas->Student_Id)->first();
                            // dd($cekisi);
                            if($cekisi == null){
                                $ikut_ujian = $ikut_ujian;
                              }else{
                                if($cekisi->Is_Presence == 1){
                                  $ikut_ujian = $ikut_ujian+1;
                                }
                            }
                            // dd($ikut_ujian);
                          }
                        }
                      }

                      if($simpan_date == $s_date){
                            echo "<div class='btn btn-sm' margin:1px;'><br></div>";
                          }else{
                            echo "<div class='btn btn-sm' style='background:#4cb24e; color:#fff; cursor:default; margin:1px;'>".$uas.$ikut_ujian."</div>";
                          }
                      $simpan_date = $s_date;
                    }
                    $cti++;
                  }
              
                  ?>
                  <?php } ?>
                  </td>
                  <td>
                  <?php 
                  $startdate = explode('|', $data->start_date);
                  $eti = explode('|', $data->eti);
                  if($eti[0] != ""){
                  $type_uts = 0;
                  $type_uas = 0;
                  $cti = 0;
                  $simpan_date = "";
                  foreach ($eti as $ddate) {
                    if($ddate == 1){
                      $uas = 'UAS / ';
                      $startd = $startdate[$cti];
                      $start = explode(" ",$startd);
                      $s_date = $start[0];  
                      $datax = DB::table('acd_offered_course_exam as a')
                      ->where('a.Offered_Course_Id',$data->Offered_Course_id)
                      ->where('a.Exam_Type_Id',1)
                      ->get();
                      $ikut_ujian = 0;
                      // dd($datax);
                      foreach ($datax as $keys) {
                        // dd($keys->Offered_Course_Exam_Id);
                        $cekdata = DB::table('acd_offered_course_exam_member')->where('Offered_Course_Exam_Id',$keys->Offered_Course_Exam_Id)->select('Student_Id')->get();
                        // dd($cekdata);
                        foreach ($cekdata as $cekdatas) {
                          $krs_id = DB::table('acd_student_krs')
                            ->where('Student_Id',$cekdatas->Student_Id)
                            ->where('Course_Id',$data->Course_Id)
                            ->where('Term_Year_Id',$data->Term_Year_Id)
                            ->where('Class_Prog_Id',$data->Class_Prog_Id)
                            ->where('Class_Id',$data->Class_Id)
                            ->where('Is_Approved',1)
                            ->first();
                          $krs_ids = DB::table('acd_student_krs')
                            ->where('Student_Id',$cekdatas->Student_Id)
                            ->where('Course_Id',$data->Course_Id)
                            ->where('Term_Year_Id',$data->Term_Year_Id)
                            ->where('Class_Prog_Id',$data->Class_Prog_Id)
                            ->where('Class_Id',$data->Class_Id)
                            ->where('Is_Approved',1)
                            ->count();
                          if($krs_ids <= 0){
                          }else{
                            $cekisi = DB::table('acd_student_khs_nilai_component')->where('Krs_Id',$krs_id->Krs_Id)->first();
                            // dd($cekisi);
                            if($cekisi == null){
                                $ikut_ujian = $ikut_ujian;
                              }else{
                                if($cekisi->Uas >0 || $cekisi->Uas == '0'){
                                  $ikut_ujian = $ikut_ujian+1;
                                }
                            }
                            // dd($ikut_ujian);
                          }
                        }
                      }

                      if($simpan_date == $s_date){
                            echo "<div class='btn btn-sm' margin:1px;'><br></div>";
                          }else{
                            echo "<div class='btn btn-sm' style='background:#4cb24e; color:#fff; cursor:default; margin:1px;'>".$uas.$ikut_ujian."</div>";
                          }
                      $simpan_date = $s_date;
                    }elseif($ddate == 2){
                      $uas = 'UTS / ';
                      $startd = $startdate[$cti];
                      $start = explode(" ",$startd);
                      $s_date = $start[0];  
                      $datax = DB::table('acd_offered_course_exam as a')
                      ->where('a.Offered_Course_Id',$data->Offered_Course_id)
                      ->where('a.Exam_Type_Id',2)
                      ->get();
                      $ikut_ujian = 0;
                      // dd($datax);
                      foreach ($datax as $keys) {
                        // dd($keys->Offered_Course_Exam_Id);
                        $cekdata = DB::table('acd_offered_course_exam_member')->where('Offered_Course_Exam_Id',$keys->Offered_Course_Exam_Id)->select('Student_Id')->get();
                        // dd($cekdata);
                        foreach ($cekdata as $cekdatas) {
                          $krs_id = DB::table('acd_student_krs')
                            ->where('Student_Id',$cekdatas->Student_Id)
                            ->where('Course_Id',$data->Course_Id)
                            ->where('Term_Year_Id',$data->Term_Year_Id)
                            ->where('Class_Prog_Id',$data->Class_Prog_Id)
                            ->where('Class_Id',$data->Class_Id)
                            ->where('Is_Approved',1)
                            ->first();
                          $krs_ids = DB::table('acd_student_krs')
                            ->where('Student_Id',$cekdatas->Student_Id)
                            ->where('Course_Id',$data->Course_Id)
                            ->where('Term_Year_Id',$data->Term_Year_Id)
                            ->where('Class_Prog_Id',$data->Class_Prog_Id)
                            ->where('Class_Id',$data->Class_Id)
                            ->where('Is_Approved',1)
                            ->count();
                          if($krs_ids <= 0){
                          }else{
                            $cekisi = DB::table('acd_student_khs_nilai_component')->where('Krs_Id',$krs_id->Krs_Id)->first();
                            if($cekisi == null){
                                $ikut_ujian = $ikut_ujian;
                              }else{
                                if($cekisi->Uts >0 || $cekisi->Uts == '0'){
                                  $ikut_ujian = $ikut_ujian+1;
                                }
                            }
                            // dd($ikut_ujian);
                          }
                        }
                      }

                      if($simpan_date == $s_date){
                            echo "<div class='btn btn-sm' margin:1px;'><br></div>";
                          }else{
                            echo "<div class='btn btn-sm' style='background:#4cb24e; color:#fff; cursor:default; margin:1px;'>".$uas.$ikut_ujian."</div>";
                          }
                      $simpan_date = $s_date;
                    }
                    $cti++;
                  }
              
                  ?>
                  <?php } ?>
                  </td>
                  <td align="center">
                  @if(in_array('khs_matakuliah-CanEditDetail', $acc))
                      <!-- {!! Form::open(['url' => route('offered_course_sched.destroy', $data->Offered_Course_id) , 'method' => 'delete', 'role' => 'form']) !!} -->
                      <a href="{{ url('proses/khs_matakuliah/'.$data->Offered_Course_id.'?class_program='.$class_program.'&department='.$department.'&term_year='.$term_year.'&currentpage='.$page.'&currentrowpage='.$rowpage.'&current_search='.$search.'&course_type='.$data->Course_Type_Id.'&count='.$data->jml_peserta.'&from=lp') }}" class="btn btn-info btn-sm">Detail Jadwal</a>
                      <!-- {!! Form::button('Hapus', ['type'=>'submit','class'=>'btn btn-danger btn-sm']) !!}
                      {!! Form::close() !!} -->
                  @endif
                  </td>
              </tr>
              <?php
              $a++;
            }
            ?>
          </tbody>
        </table>
        </div>
        <?php echo $query->render('vendor.pagination.bootstrap-4'); ?>
      </div>
    </div>
  </div>
<script>
    $("#export").click(function(e) {
      var department = $('#dept').val();
      // var curriculum = $('#cur').val();
      var term_year = $('#smt').val();
      var class_program = $('#kpg').val();
        window.open("{{ url('') }}/laporan/detail_pengisian_nilai/exportdata/exportdata/" + department + "/" + term_year+ "/" + class_program); 
    });
</script>
  <!-- <script type="text/javascript">
     $(document).ready(function () {
     $(".hapus").click(function(e) {
       var url = "{{ url('modal/faculty') }}"
           $("#detail").load(url);
           $("#detail").modal('show',{backdrop: 'true'});
        });
      });
  </script> -->

<!-- /.row -->
<!-- <script>
    $(document).ready(function () {
        $('#dataTables-example').dataTable();
    });
</script> -->
</section>
@endsection
