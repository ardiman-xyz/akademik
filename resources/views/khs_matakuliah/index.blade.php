@extends('layouts._layout')
@section('pageTitle', 'KHS')
@section('content')

<?php


// $url = 'http://10.64.21.72:8080/webservice/rest/simpleserver.php?wsusername=a&wspassword=a';
// $xmlll = simplexml_load_file($url) or die("feed not loading");
// // $xml=simplexml_load_string($xmlll) or die("Error: Cannot create object");
// print_r($xmlll);
?>
<?php
$access = auth()->user()->akses();
          $acc = $access;
?>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">KHS Per Matakuliah</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">

            <?php $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; ?>

          <b>KHS Per Matakuliah</b>
        </div>
      </div>
      <br>
          <!-- <b>Daftar Fakultas</b> -->
          {!! Form::open(['url' => route('khs_matakuliah.index') , 'method' => 'GET', 'name' => 'form', 'role' => 'form', 'style' => 'padding-top:5px;padding-left:15px;padding-right:15px;']) !!}
          <div class="row text-green">
            <!-- <label class="col-md-2" >Department :</label> -->&nbsp &nbsp &nbsp &nbsp
            <select class="form-control form-control-sm col-md-3" name="term_year" onchange="document.form.submit();">
              <option value="0">Pilih Tahun Semester</option>
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
            </select>
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
        <div class="table-responsive">
        <table class="table table-striped table-font-sm">
          <thead class="thead-default thead-green">
              <tr>
                  <!-- <th class="col-sm-1">No</th> -->
                  <th width="25%">Kode MK</th>
                  <th width="30%">Nama Matakuliah</th>
                  <th width="25%">Nama Dosen</th>
                  <th width="15%">Kelas</th>
                  <th width="15%">Peserta</th>
                  <th width="15%">Nilai Terisi</th>
                  @if(in_array('khs_matakuliah-CanViewDetail', $acc))
                  <th width="10%"><center><i class="fa fa-gear"></i></center></th>
                  @endif
              </tr>
          </thead>
          <tbody>
            <?php
            $a = "1";
            foreach ($query as $data) {
              $cekfortranscript = DB::table('acd_course_curriculum')
              ->leftjoin('acd_offered_course' ,function ($join)
                {
                  $join->on('acd_course_curriculum.Department_Id','=','acd_offered_course.Department_Id')
                  ->on('acd_course_curriculum.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
                  ->on('acd_course_curriculum.Course_Id','=','acd_offered_course.Course_Id');
                })
              ->where('acd_offered_course.Offered_Course_id', $data->Offered_Course_id)
              ->where('acd_offered_course.Department_Id', $data->Department_Id)
              ->where('acd_offered_course.Class_Prog_Id', $data->Class_Prog_Id)
              ->where('acd_offered_course.Term_Year_Id', $data->Term_Year_Id)
              ->first();
              // if(!$cekfortranscript){
              //   dd($data);
              // }
              // dd($cekfortranscript);
              $count = DB::table('acd_student_krs as a')->join('acd_student as b','a.Student_Id','=','b.Student_Id')->where([
                    ['a.Term_Year_Id',$data->Term_Year_Id],
                    ['b.Department_Id',$data->Department_Id],
                    ['b.Class_Prog_Id',$data->Class_Prog_Id],
                    ['a.Course_Id',$data->Course_Id],
                    ['a.Class_Id',$data->Class_Id],
                    ['a.Is_Approved',1],
                    ])->count();
              $krs = DB::table('acd_student_krs as a')->join('acd_student as b','a.Student_Id','=','b.Student_Id')->where([
                    ['a.Term_Year_Id',$data->Term_Year_Id],
                    ['b.Department_Id',$data->Department_Id],
                    ['b.Class_Prog_Id',$data->Class_Prog_Id],
                    ['a.Course_Id',$data->Course_Id],
                    ['a.Class_Id',$data->Class_Id],
                    ['a.Is_Approved',1],
                    ])->get();
              $sudah_diisi = 0;
              // dd($krs);
              foreach ($krs as $key) {
                $khs = DB::table('acd_student_khs')->where('Krs_Id',$key->Krs_Id)->first();
                if($khs){
                  $sudah_diisi++;
                }
              }
              ?>
              <tr>
                  <!-- <th></th> -->
                  <td>{{ $data->Course_Code }}</td>
                  <td>{{ $data->Course_Name }}</td>
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
                      } ?>
                  </td>
                  <td>{{ $data->Class_Name }}</td>
                  <td>{{ $count }}</td>
                  <td>{{ $sudah_diisi }}</td>
                  @if(in_array('khs_matakuliah-CanViewDetail', $acc))
                  <td align="center">
                      <a href="{{ url('proses/khs_matakuliah/'.$data->Offered_Course_id.'?term_year='.$term_year.'&class_program='.$class_program.'&department='.$department.'&term_year='.$term_year.'&currentpage='.$page.'&current_rowpage='.$rowpage.'&current_search='.$search.'&course_type='.$data->Course_Type_Id.'&count='.$count.'&from=khs') }}" class="btn btn-warning btn-sm" style="margin:5px;">Detail <i class="fa fa-list"></i> </a>
                      
                      <a href="#" 
                      data-class="{{ $data->Class_Id }}" 
                      data-course="{{ $data->Course_Id }}" 
                      data-istrns="{{($cekfortranscript ? $cekfortranscript->Is_For_Transcript:'')}}" 
                      data-skstrns="{{($cekfortranscript ? $cekfortranscript->Transcript_Sks:'')}}" 
                      data-oci="{{$data->Offered_Course_id}}"                       
                      class="btn btn-sm btn-primary btn-sm publishnilai">Publish</i></a>
                      @if(in_array('khs_matakuliah-CanEditDetail', $acc))
                      @endif
                  </td>
                  @endif
              </tr>
              <?php
              $a++;
            }
            ?>
          </tbody>
        </table>
        <input type="text" id="term_year" readonly hidden value="{{$term_year}}" >
        <input type="text" id="class_program" readonly hidden value="{{$class_program}}" >
        <input type="text" id="department" readonly hidden value="{{$department}}" >
        </div>
        <?php echo $query->render('vendor.pagination.bootstrap-4'); ?>
      </div>
    </div>
  </div>
  <script type="text/javascript">
     $(document).on('click', '.publishnilai', function (e) {
      var term_year = $('#term_year').val();
      var class_program = $('#class_program').val();
      var department = $('#department').val();
      var Offered_Course_id = $(this).data('oci');
      var Class_Id = $(this).data('class');
      var Course_Id = $(this).data('course');
      var Is_For_Transcript = $(this).data('istrns');
      var Transcript_Sks = $(this).data('skstrns');
      console.log([[term_year],[class_program],[department],[Offered_Course_id],[Class_Id],[Course_Id],[Is_For_Transcript],[Transcript_Sks]])
			swal({
				title: 'Publish Nilai',
					text: "Nilai akan dimasukkan ke KHS Mahasiswa",
					type: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Oke',
					cancelButtonText: 'cancel!',
					confirmButtonClass: 'btn btn-success',
					cancelButtonClass: 'btn btn-danger',
					buttonsStyling: true
				}, function(isConfirm) {
			if (isConfirm) {
							$.ajax({
									url: '{{route('khs_matakuliah.publishNilai')}}',
									type: "get",
									dataType: "json",
									data: {
										"_token": "{{ csrf_token() }}",
                    term_year : term_year,
                    Course_Id : Course_Id,
                    Class_Id : Class_Id,
                    Offered_Course_id : Offered_Course_id,
                    department : department,
                    class_program : class_program,
                    Is_For_Transcript : Is_For_Transcript,
                    Transcript_Sks : Transcript_Sks
									},
									success:function (data) {
                    console.log(data);
                    swal({
                      title: data.message,
                      type: 'success', 
                      showCancelButton: true,
                      cancelButtonText: 'oke',
                    });
                    window.location.reload(true);
									},
									error: function(xhr, ajaxOptions, thrownError) {
                                    swal({
                                            title: thrownError,
                                            text: 'Error!! ' + xhr.status,
                                            type: "error",
                                            confirmButtonColor: "#02991a",
                                            confirmButtonText: "Refresh Serkarang",
                                            cancelButtonText: "Tidak, Batalkan!",
                                            closeOnConfirm: false,
                                        },
                                        function(isConfirm) {
                                            if (isConfirm) {
                                            window.location.reload(true) // submitting the form when user press yes
                                            }
                                        });
                                }
							});
							// $("#hapus").submit();
						}else{
              // console.log('cenceled');
            }
					});
	});
  </script>

<!-- /.row -->
<!-- <script>
    $(document).ready(function () {
        $('#dataTables-example').dataTable();
    });
</script> -->
</section>
@endsection
