@extends('layouts._layout')
@section('pageTitle', 'Offered Course Exam')
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
      <h3 class="text-white">Jadwal Dan Peserta Ujian</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          <b>Jadwal Dan Peserta Ujian</b>
        </div>
      </div>
      <br>
          <!-- <b>Daftar Fakultas</b> -->
          {!! Form::open(['url' => route('offered_course_exam.index') , 'method' => 'GET', 'name' => 'form', 'role' => 'form', 'style' => 'padding-top:5px;padding-left:15px;padding-right:15px;']) !!}
          <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            <!-- <label class="col-md-2" >Department :</label> -->
            <div class="row col-md-3">
              <select class="form-control form-control-sm col-md-9" name="term_year" onchange="document.form.submit();">
                <option value="0">Pilih Tahun / Semester</option>
                @foreach ( $select_term_year as $data )
                  <option <?php if($term_year == $data->Term_Year_Id){ echo "selected"; } ?>  value="{{ $data->Term_Year_Id }}">{{ $data->Term_Year_Name }}</option>
                @endforeach
              </select>
            </div>
            <div class="row col-md-4">
              <select class="form-control form-control-sm col-md-10" name="department"  onchange="document.form.submit();">
                <option value="0">Pilih Program Studi</option>
                @foreach ( $select_department as $data )
                  <option <?php if($department == $data->Department_Id){ echo "selected"; } ?> value="{{ $data->Department_Id }}">{{ $data->Department_Name }}</option>
                @endforeach
              </select>
            </div>
            <div class=" row col-md-4">
              <select class="form-control form-control-sm col-md-8" name="class_program"  onchange="document.form.submit();">
                <option value="0">Pilih Program Kelas</option>
                @foreach ( $select_class_program as $data )
                  <option <?php if($class_program == $data->Class_Prog_Id){ echo "selected"; } ?> value="{{ $data->Class_Prog_Id }}">{{ $data->Class_Program_Name }}</option>
                @endforeach
              </select>
            </div>
              <!-- <label class="col-md-2">Tahun Semester :</label> -->

              <?php $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; ?>

          </div>
          <br>
					<div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            <div class="row col-md-4">
              <input type="date" name="date"  class="form-control form-control-sm col-md-10" value="{{$date}}">
            </div>
            <div  class="row col-md-5">
              <input type="text" name="search"  class="form-control form-control-sm col-md-8" value="{{ $search }}" placeholder="Kode/Nama Matakuliah">
              <input type="submit" name="" class="btn btn-primary btn-sm col-md-2 col-sm-2" value="Cari">
            </div>
          </div><br>
        </div>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">

        <div style="float: right; text-align: right; padding:0px;" class="col-md-4">
          <label class="col-md-3">Baris </label>
          <select class="form-control form-control-sm col-md-7 float-right" name="rowpage" onchange="form.submit()">
            <option <?php if($rowpage == 10){ echo "selected"; } ?> value="10">10</option>
            <option <?php if($rowpage == 20){ echo "selected"; } ?> value="20">20</option>
            <option <?php if($rowpage == 50){ echo "selected"; } ?> value="50">50</option>
            <option <?php if($rowpage == 100){ echo "selected"; } ?> value="100">100</option>
            <option <?php if($rowpage == 200){ echo "selected"; } ?> value="200">200</option>
            <option <?php if($rowpage == "1000000"){ echo "selected"; } ?> value="1000000">semua</option>
          </select>
        </div>

        {!! Form::close() !!}

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
                  <th width="10%">Kode Matakuliah</th>
                  <th width="25%">Nama Matakuliah</th>
                  <th width="5%">kelas</th>
                  <!-- <th width="7%">Kapasitas Kelas</th> -->
                  <th width="5%">Jumlah Mahasiswa</th>
                  <!-- <th width="5%">Mahasiswa Remidi</th> -->
                  <th width="10%">Dosen</th>
                  <th width="10%">Tanggal</th>
                  <th width="5%">Jam</th>
                  <th width="15%">Ruang</th>
                  <th width="5%"><center><i class="fa fa-gear"></i></center></th>
              </tr>
          </thead>
          <tbody>
            <?php
            $a = "1";

            foreach ($query as $data) {
              $peserta_kelas = DB::table('acd_student_krs as a')
                ->join('acd_student as b','a.Student_Id','=','b.Student_Id')
                ->where([['a.Term_Year_Id',$data->Term_Year_Id],['a.Class_Prog_Id',$data->Class_Prog_Id],['a.Course_Id',$data->Course_Id],['a.Class_Id',$data->Class_Id],
                        ['b.Department_Id',$data->Department_Id],
                        ['a.Is_Approved',1]
                ])
                ->count();
              ?>
              <tr>
                  <!-- <th></th> -->
                  <td>{{ $data->Course_Code }}</td>
                  <td>{{ $data->Course_Name }}</td>
                  <td><center>{{ $data->Class_Name }}</td>
                  <!-- <td>{{ $data->Class_Capacity }}</td> -->
                  <td><center>{{ $peserta_kelas }}</td>
                  <!-- <td> -->
                  <?php
                    $cek_ujians = DB::table('acd_offered_course_exam')                              
                              ->where('Offered_Course_Id',$data->Offered_Course_id)
                              ->where('Exam_Type_Id',3)                              
                              ->get();
                    $jumlah_peserta = 0;
                    $cek = 0;
                    $jml_peserta = 0;
                    foreach ($cek_ujians as $cek_ujian) {
                      $peserta = DB::table('acd_offered_course_exam_member')
                                    ->join('acd_offered_course_exam','acd_offered_course_exam.Offered_Course_Exam_Id','=','acd_offered_course_exam_member.Offered_Course_Exam_Id')
                                    ->join('acd_offered_course','acd_offered_course.Offered_Course_Id','=','acd_offered_course_exam.Offered_Course_Id')
                                    ->join('acd_student_krs' ,function ($join)
                                    {
                                      $join->on('acd_student_krs.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
                                      ->on('acd_student_krs.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
                                      ->on('acd_student_krs.Course_Id','=','acd_offered_course.Course_Id')
                                      ->on('acd_student_krs.Class_Id','=','acd_offered_course.Class_Id')
                                      ->on('acd_student_krs.Student_Id','=','acd_offered_course_exam_member.Student_Id');
                                    })
                                    ->where('acd_offered_course_exam.Offered_Course_Exam_Id',$cek_ujian->Offered_Course_Exam_Id)      
                                    ->where('acd_student_krs.Is_Remediasi',1)               
                                    ->get();
                      $jml = count($peserta);
                      $jumlah_peserta = $jumlah_peserta + $jml;
                      $cek++;
                    }
                  ?>
                  <!-- <center>{{ $jumlah_peserta }} -->
                  <!-- </td> -->
                  <td>
                    <?php
                    $dosen = explode('|',$data->dosen);
                    $id_dosen = explode('|',$data->id_dosen);
                      // dd($data);
                      foreach ($id_dosen as $key) {
                          if ($key != null) {
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
                            if(isset($anu->Department_Id)){
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

                            // $end = explode(" ",$enddate[$n]);
                            // $e_date = $end[0];
                            // $e_time = explode(":",$end[1]);
                            // unset($e_time[1]);
                            // $e_time = implode(".",$e_time);

                            echo "<div class='btn btn-sm' style='background:#4cb24e; color:#000; cursor:default; margin:1px;'>".tanggal_indo($s_date,false)." <font style='color:#7cffff;'>".$s_time."</font></div>";
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
                      foreach ($eti as $ddate){
                        if($ddate == 1){
                          $uas = 'UAS / ';
                          $startd = $startdate[$cti];
                          $start = explode(" ",$startd);
                          $s_date = $start[0];  
                          if($simpan_date == $s_date){
                            echo "<div class='btn btn-sm' margin:1px;'>&nbsp;<br></div><br>";
                          }else{
                            echo "<div class='btn btn-sm' style='background:#5bb9e9; color:#000; cursor:default; margin:1px;'>".$uas.tanggal_indo($s_date,false)."</div>";                              
                          }
                          $simpan_date = $s_date;
                        }else if($ddate == 2){
                          $uas = 'UTS / ';
                          $startd = $startdate[$cti];
                          $start = explode(" ",$startd);
                          $s_date = $start[0];  
                          if($simpan_date == $s_date){
                            echo "<div class='btn btn-sm' margin:1px;'>&nbsp;<br></div><br>";
                          }else{
                            echo "<div class='btn btn-sm' style='background:#4cb24e; color:#000; cursor:default; margin:1px;'>".$uas.tanggal_indo($s_date,false)."</div>";
                          }
                          $simpan_date = $s_date;
                        }else if($ddate == 3){
                          $uas = 'Remidi / ';
                          $startd = $startdate[$cti];
                          $start = explode(" ",$startd);
                          $s_date = $start[0];  
                          if($simpan_date == $s_date){
                            echo "<div class='btn btn-sm' margin:1px;'>&nbsp;<br></div><br>";
                          }else{
                            echo "<div class='btn btn-sm' style='background:#3ba17d; color:#000; cursor:default; margin:1px;'>".$uas.tanggal_indo($s_date,false)."</div>";
                          }
                          $simpan_date = $s_date;
                        }
                        $cti++;
                      }
                      // foreach ($startdate as $key) {
                      //       $start = explode(" ",$key);
                      //       $s_date = $start[0];
                      //       if($jenis == $eti){
                      //       }else{
                      //         $jenis = $eti;                              
                      //         if($jenis == 1){
                      //           echo "<div class='btn btn-sm' style='background:#4cb24e; color:#000; cursor:default; margin:1px;'>".tanggal_indo($s_date,false)."</div>";
                      //         }else{
                      //           echo "<div class='btn btn-sm' style='background:#4cb24f; color:#000; cursor:default; margin:1px;'>".tanggal_indo($s_date,false)."</div>";
                      //         }
                      //       }
                      //       $n++;
                      //     }
                      }
                    ?>
                  </td>
                  <td>
                    <?php
                      $startdate = explode('|', $data->start_date);
                      $enddate = explode('|', $data->end_date);
                      $eti = explode('|', $data->eti);
                      $n = 0;
                      if ($data->start_date != "") {

                        $cti = 0;
                        $simpan_date = "";
                        $simpan_time = "";
                      foreach ($eti as $ddate) {
                        if($ddate == 1){
                          $uas = 'UAS / ';
                          $startd = $startdate[$cti];
                          $start = explode(" ",$startd);
                          $s_date = $start[0];  
                          if($simpan_date == $s_date){
                            $s_time = explode(":",$start[1]); 
                            $s_hour = $s_time[0];
                            $s_minute = $s_time[1];
                            // unset($s_time[1]);
                            // $s_time = implode(".",$s_time);
                            $s_time = $s_hour.'.'.$s_minute;
                            if($simpan_time == $s_time){
                              echo "<div class='btn btn-sm' margin:1px;'>&nbsp;<br></div><br>";
                            }else{
                              echo "<div class='btn btn-sm' style='background:#5bb9e9; color:#000; cursor:default; margin:1px;'>".$uas.$s_time."</div>";                            
                            }
                            $simpan_time = $s_time;
                          }else{
                            $simpan_time = "";
                            $s_time = explode(":",$start[1]); 
                            $s_hour = $s_time[0];
                            $s_minute = $s_time[1];
                            // unset($s_time[1]);
                            // $s_time = implode(".",$s_time);
                            $s_time = $s_hour.'.'.$s_minute;
                            if($simpan_time == $s_time){
                              echo "<div class='btn btn-sm' margin:1px;'>&nbsp;<br></div><br>";
                            }else{
                              echo "<div class='btn btn-sm' style='background:#5bb9e9; color:#000; cursor:default; margin:1px;'>".$uas.$s_time."</div>";                            
                            }
                            $simpan_time = $s_time;
                          }
                          $simpan_date = $s_date;
                        }else if($ddate == 2){
                          $uas = 'UTS / ';
                          $startd = $startdate[$cti];
                          $start = explode(" ",$startd);
                          $s_date = $start[0];
                          if($simpan_date == $s_date){
                            $s_time = explode(":",$start[1]); 
                            $s_hour = $s_time[0];
                            $s_minute = $s_time[1];
                            // unset($s_time[1]);
                            // $s_time = implode(".",$s_time);
                            $s_time = $s_hour.'.'.$s_minute;
                            if($simpan_time == $s_time){
                              echo "<div class='btn btn-sm' margin:1px;'><br></div><br>";
                            }else{
                              echo "<div class='btn btn-sm' style='background:#4cb24e; color:#000; cursor:default; margin:1px;'>".$uas.$s_time."</div>";                            
                            }
                            $simpan_time = $s_time;
                          }else{
                            $simpan_time = "";
                            $s_time = explode(":",$start[1]); 
                            $s_hour = $s_time[0];
                            $s_minute = $s_time[1];
                            // unset($s_time[1]);
                            // $s_time = implode(".",$s_time);
                            $s_time = $s_hour.'.'.$s_minute;
                            if($simpan_time == $s_time){
                              echo "<div class='btn btn-sm' margin:1px;'><br></div><br>";
                            }else{
                              echo "<div class='btn btn-sm' style='background:#4cb24e; color:#000; cursor:default; margin:1px;'>".$uas.$s_time."</div>";                            
                            }
                            $simpan_time = $s_time;
                          }
                          $simpan_date = $s_date;
                        }else if($ddate == 3){
                          $uas = 'Remidi / ';
                          $startd = $startdate[$cti];
                          $start = explode(" ",$startd);
                          $s_date = $start[0];
                          if($simpan_date == $s_date){
                            $s_time = explode(":",$start[1]); 
                            $s_hour = $s_time[0];
                            $s_minute = $s_time[1];
                            // unset($s_time[1]);
                            // $s_time = implode(".",$s_time);
                            $s_time = $s_hour.'.'.$s_minute;
                            if($simpan_time == $s_time){
                              echo "<div class='btn btn-sm' margin:1px;'><br></div><br>";
                            }else{
                              echo "<div class='btn btn-sm' style='background:#3ba17d; color:#000; cursor:default; margin:1px;'>".$uas.$s_time."</div>";                            
                            }
                            $simpan_time = $s_time;
                          }else{
                            $simpan_time = "";
                            $s_time = explode(":",$start[1]); 
                            $s_hour = $s_time[0];
                            $s_minute = $s_time[1];
                            // unset($s_time[1]);
                            // $s_time = implode(".",$s_time);
                            $s_time = $s_hour.'.'.$s_minute;
                            if($simpan_time == $s_time){
                              echo "<div class='btn btn-sm' margin:1px;'><br></div><br>";
                            }else{
                              echo "<div class='btn btn-sm' style='background:#3ba17d; color:#000; cursor:default; margin:1px;'>".$uas.$s_time."</div>";                            
                            }
                            $simpan_time = $s_time;
                          }
                          $simpan_date = $s_date;
                        }
                        $cti++;
                      }

                      // foreach ($startdate as $key) {
                      //       $start = explode(" ",$key);
                      //       $s_date = $start[0];
                      //       $s_time = explode(":",$start[1]);
                      //       unset($s_time[1]);
                      //       $s_time = implode(".",$s_time);
                      //       $n++;
                      //     }
                      //     echo "<div class='btn btn-sm' style='background:#4cb24e; color:#000; cursor:default; margin:1px;'>".$s_time."</div>";
                      }
                    ?>
                  </td>
                  <td>
                    <?php
                      $roomid = explode('|', $data->room_id);
                      $eti = explode('|', $data->eti);
                      $n = 0;
                      if ($data->room_id != "") {
                        $n = 0;
                        if ($data->start_date != "") {
                        $cti = 0;
                        foreach ($eti as $ddate) {
                          if($ddate == 1){
                            $uas = 'UAS / ';
                            $startd = $roomid[$cti];
                            $start = explode(" ",$startd);
                            $s_room = $start[0]; 
                            $n_room = DB::table('mstr_room')->where('Room_Id',$s_room)->select('Room_Code')->first();
                            echo "<div class='btn btn-sm' style='background:#5bb9e9; color:#000; cursor:default; margin:1px;'>".$uas.$n_room->Room_Code."</div><br>";
                          }else if($ddate == 2){
                            $uas = 'UTS / ';
                            $startd = $roomid[$cti];
                            $start = explode(" ",$startd);
                            $s_room = $start[0]; 
                            $n_room = DB::table('mstr_room')->where('Room_Id',$s_room)->select('Room_Code')->first();
                            echo "<div class='btn btn-sm' style='background:#4cb24e; color:#000; cursor:default; margin:1px;'>".$uas.$n_room->Room_Code."</div><br>";
                          }else if($ddate == 3){
                            $uas = 'Remidi / ';
                            $startd = $roomid[$cti];
                            $start = explode(" ",$startd);
                            $s_room = $start[0]; 
                            $n_room = DB::table('mstr_room')->where('Room_Id',$s_room)->select('Room_Code')->first();
                            echo "<div class='btn btn-sm' style='background:#3ba17d; color:#000; cursor:default; margin:1px;'>".$uas.$n_room->Room_Code."</div><br>";
                          }
                          $cti++;
                        }
                      }
                      // foreach ($roomid as $key) {
                      //       $start = explode(" ",$key);
                      //       $s_room = $start[0];
                      //       $n_room = DB::table('mstr_room')->where('Room_Id',$s_room)->select('Room_Code')->first();
                      //       $n++;
                      //       echo "<div class='btn btn-sm' style='background:#4cb24e; color:#000; cursor:default; margin:1px;'>".$n_room->Room_Code."</div>";
                      //     }
                      }
                    ?>
                  </td>
									
                  <td align="center">
                      <!-- {!! Form::open(['url' => route('offered_course_sched.destroy', $data->Offered_Course_id) , 'method' => 'delete', 'role' => 'form']) !!} -->
                      <a href="{{ url('setting/offered_course_exam/'.$data->Offered_Course_id.'?class_program='.$class_program.'&department='.$department.'&term_year='.$term_year.'&currentpage='.$page.'&currentrowpage='.$rowpage.'&currentsearch='.$search) }}" class="btn btn-info btn-sm">Detail Jadwal</a>
                      <!-- {!! Form::button('Hapus', ['type'=>'submit','class'=>'btn btn-danger btn-sm']) !!}
                      {!! Form::close() !!} -->
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
        window.open("{{ url('') }}/setting/offered_course_exam/exportdata/exportdata/" + department + "/" + term_year+ "/" + class_program); 
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
