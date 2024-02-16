@extends('layouts._layout')
@section('pageTitle', 'Pertemuan Kuliah')
@section('content')

  <?php
  $access = auth()->user()->akses();
  $acc = $access;
  ?>

<section class="content">

<div class="container-fluid-title">
  <div class="title-laporan">
    <h3 class="text-white">Pertemuan Kuliah</h3>
  </div>
</div>
<div class="container">
  <div class="panel panel-default bootstrap-admin-no-table-panel">
    <div class="panel-heading-green">
      <div class="bootstrap-admin-box-title right text-white">
        <b>Pertemuan Kuliah</b>
      </div>
    </div>
    <br>
        <!-- <b>Daftar Fakultas</b> -->
        {!! Form::open(['url' => route('schedreal.index') , 'method' => 'GET', 'name' => 'form', 'class' => 'row text-green', 'style' => 'padding-top:5px;padding-left:15px;padding-right:15px;', 'role' => 'form']) !!}
        <div class="row col-md-12">
          <label class="col-md-3">Semester :</label>
          <label class="col-md-3">Program Studi :</label>
          <label class="col-md-3">Kelas Program :</label>
        </div>

        <div class="row col-md-12" style="margin-bottom:30px;">
          <div class="col-md-3">
          <select class="form-control form-control-sm col-md-12" name="Term_Year_Id" onchange="form.submit()">
              <option value="">Pilih Semester</option>
              @foreach ($Term_Year as $val) {
                  <option <?php if($val->Term_Year_Id == $Term_Year_Id){ echo 'selected'; } ?> value="{{ $val->Term_Year_Id }}">{{ $val->Term_Year_Name }}</option>
              @endforeach
          </select>
          </div>

          <div class="col-md-3">
          <select class="form-control form-control-sm col-md-12" name="Department_Id" onchange="form.submit()">
              <option value="">Pilih Program Studi</option>
              @foreach ($Department as $val) {
                  <option <?php if($val->Department_Id == $Department_Id){ echo 'selected'; } ?> value="{{ $val->Department_Id }}">{{ $val->Department_Name }}</option>
              @endforeach
          </select>
          </div>

          <!-- <div class="col-md-3">
          <select class="form-control form-control-sm col-md-12" name="Curriculum_Id" onchange="form.submit()">
              <option value="">Pilih Kurikulum</option>
              @foreach ($Curriculum as $val) {
                  <option <?php if($val->Curriculum_Id == $Curriculum_Id){ echo 'selected'; } ?> value="{{ $val->Curriculum_Id }}">{{ $val->Curriculum_Name }}</option>
              @endforeach
          </select>
          </div> -->

          <div class="col-md-3">
          <select class="form-control form-control-sm col-md-12" name="Class_Prog_Id" onchange="form.submit()">
              <option value="">Pilih Kelas Program</option>
              @foreach ($ClassProg as $val) {
                  <option <?php if($val->Class_Prog_Id == $Class_Prog_Id){ echo 'selected'; } ?> value="{{ $val->Class_Prog_Id }}">{{ $val->Class_Program_Name }}</option>
              @endforeach
          </select>
          </div>
        </div>

        <div  class="row col-md-12">
          <label class="col-md-2">Pencarian :</label>
          <div class="col-md-4 col-sm-4">
            <div class="row">
            <input type="text" name="search"  class="form-control form-control-sm col-md-10 col-sm-10" value="{{ $search }}" placeholder="Search">
            <input type="submit" name="" class="btn btn-primary btn-sm col-md-2 col-sm-2" value="Cari">        
            </div>
          </div>

          <label class="col-md-2">Baris per halamam :</label>
          <select class="form-control form-control-sm col-md-4" name="rowpage" onchange="form.submit()">
            <option <?php if($rowpage == "10"){ echo "selected"; } ?> value="">10 Baris</option>
            <option <?php if($rowpage == "20"){ echo "selected"; } ?> value="20">20 Baris</option>
            <option <?php if($rowpage == "50"){ echo "selected"; } ?> value="50">50 Baris</option>
            <option <?php if($rowpage == "100"){ echo "selected"; } ?> value="100">100 Baris</option>
            <option <?php if($rowpage == "1000000"){ echo "selected"; } ?> value="1000000">semua</option>
          </select>
        </div>
        {{-- <label>Baris</label> --}}
        {!! Form::close() !!}

    <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
      @if (count($errors) > 0)
        @foreach ( $errors->all() as $error )
          <p class="alert alert-danger">{{ $error }}</p>
        @endforeach
      @endif
      <br>
      <div class="table-responsive">
      <table class="table table-striped table-font-sm">
        <thead class="thead-default thead-green">
            <tr>
                  <th width="10%">Kode MK</th>
                  <th width="20%">Nama Matakuliah</th>
                  <th width="5%">Kelas</th>
                  <th width="10%">Kapasitas</th>
                  <th width="10%">Jumlah Mahasiswa</th>
                  <th width="10%">Semester</th>                  
                  <th width="10%">Total Pertemuan</th>                  
                  <th width="30%">Nama Dosen</th>
                  <th width="15%"><center><i class="fa fa-gear"></i></center></th>
            </tr>
        </thead>
        <tbody>
        <?php
            $a = "1";
            // $property_types = array();
            foreach ($datas as $data) {
              // if ( in_array($data->Offered_Course_id, $property_types) ) {
              //     continue;
              // }
              // $property_types[] = $data->Offered_Course_id;
              ?>
              <tr>
                  <!-- <th></th> -->
                  <td>{{ $data->Course_Code }}</td>
                  <td>{{ $data->Course_Name }}</td>
                  <td><center>{{ $data->Class_Name }}</td>
                  <?php 
                    $count_p = DB::table('acd_offered_course')
                      ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
                      ->join('mstr_department','mstr_department.Department_Id','=','acd_offered_course.Department_Id')

                      ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
                      ->join('acd_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
                      ->join('mstr_class','mstr_class.Class_Id','=','acd_offered_course.Class_Id')
                      ->leftjoin('acd_student_krs' ,function ($join)
                      {
                        $join->on('acd_student_krs.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
                        ->on('acd_student_krs.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
                        ->on('acd_student_krs.Course_Id','=','acd_offered_course.Course_Id')
                        ->on('acd_student_krs.Class_Id','=','acd_offered_course.Class_Id');
                      })
                      ->leftjoin('acd_student' , function ($join)
                      {
                        $join->on('acd_student.Student_Id', '=', 'acd_student_krs.Student_Id')
                        ->on('acd_student.Department_Id', '=', 'acd_offered_course.Department_Id');
                      })
                      ->where('acd_offered_course.Offered_Course_id', $data->Offered_Course_id)
                      ->where('acd_offered_course.Department_Id', $data->Department_Id)
                      ->where('acd_offered_course.Class_Prog_Id', $data->Class_Prog_Id)
                      ->where('acd_offered_course.Term_Year_Id', $data->Term_Year_Id)
                      ->select( 'acd_offered_course.*','acd_course.*','mstr_class.Class_Name',
                            DB::raw('COUNT(acd_student.Student_Id) as jml_peserta'))
                      ->groupBy('acd_course.Course_Id', 'mstr_class.Class_Id', 'acd_course.Course_Code', 'acd_course.Course_Name', 'mstr_class.Class_Name', 'acd_offered_course.Class_Capacity', 'acd_offered_course.Offered_Course_id')
                      ->orderBy('start_date', 'asc')
                      ->orderBy('acd_course.Course_Name', 'asc')
                      ->orderBy('mstr_class.class_Name', 'asc')
                      ->first();
                  ?>
                  <td><center>{{ $data->Class_Capacity }}</td>                 
                  <td><center>{{ $count_p->jml_peserta }}</td>
                  <td><center>{{ $data->Study_Level_Id }}</center></td>
                  <?php 
                    $totalpertemuan = DB::table('acd_sched_real')
                      ->where('Course_Id',$data->Course_Id)
                      ->where('Term_Year_Id',$data->Term_Year_Id)
                      ->where('Class_Prog_Id',$data->Class_Prog_Id)
                      ->where('Class_Id',$data->Class_Id)
                      ->count();
                  ?>
                  <td><center>{{ $totalpertemuan }}</center></td>
                  <td>
                    <?php
                    $dosen = explode('|',$data->dosen);
                    $id_dosen = explode('|',$data->id_dosen);
                    // dd($merge);
                      foreach ($id_dosen as $key) {
                          if ($key != null) {
                            // $anu = DB::table('emp_employee')->where('acd_department_lecturer.Employee_Id',$key)
                            // ->join('acd_department_lecturer','acd_department_lecturer.Employee_Id','=','emp_employee.Employee_Id')
                            // ->select('acd_department_lecturer.Department_Id','acd_department_lecturer.Employee_Id')
                            // ->first();
                            $anu = DB::table('emp_employee')
                            ->join(DB::Raw("(SELECT Employee_Id,placement_Id,MAX(Tmt_Date) as Tmt_Date FROM emp_placement GROUP BY Employee_Id) as max_placement"), 'emp_employee.Employee_Id', 'max_placement.Employee_Id'
                            )
                            ->join('emp_placement',function($golru){
                                $golru->on('emp_placement.Employee_Id','emp_employee.Employee_Id')
                                ->on('emp_placement.Tmt_Date','max_placement.Tmt_Date');
                            })
                            // ->where('emp_placement.Department_Id', $key->Department_Id)
                            ->where('emp_employee.Employee_Id', $key)
                            ->first();
                            // dd($anu->Department_Id);
                            if($anu){
                              if($anu->Department_Id != $Department_Id){
                                $dosennya = DB::table('emp_employee')->where('Employee_Id',$key)->first();
                                $firstitle = $dosennya->First_Title;
                                $name = $dosennya->Name;
                                $lasttitle = $dosennya->Last_Title;
                                // dd($firstitle);
                                echo "<div class='btn btn-sm' style='background:#1d6446; color:#fff; cursor:default; margin:1px;'> ".$firstitle." ".$name." ".$lasttitle."</div>";
                              }else{
                                 $dosennya = DB::table('emp_employee')->where('Employee_Id',$key)->first();
                                $firstitle = $dosennya->First_Title;
                                $name = $dosennya->Name;
                                $lasttitle = $dosennya->Last_Title;
                                echo "<div class='btn btn-sm' style='background:#3ac98d; color:#fff; cursor:default; margin:1px;'> ".$firstitle." ".$name." ".$lasttitle."</div>";
                              }
                            }else{
                                 $dosennya = DB::table('emp_employee')->where('Employee_Id',$key)->first();
                                $firstitle = $dosennya->First_Title;
                                $name = $dosennya->Name;
                                $lasttitle = $dosennya->Last_Title;
                                echo "<div class='btn btn-sm' style='background:#3ac98d; color:#fff; cursor:default; margin:1px;'> ".$firstitle." ".$name." ".$lasttitle."</div>";
                              }
                          }
                      }
                    ?>
                  </td>
                  <td align="center">
                    <a href="{{ url('proses/schedreal/export/'.$data->Offered_Course_id) }}" target="_blank" class="btn btn-warning btn-sm">Export</a>
                    @if($totalpertemuan > 0)
                    <a href="{{ url('proses/schedreal/exportdosen/'.$data->Offered_Course_id) }}" target="_blank" class="btn btn-success btn-sm">Export Materi</a>
                    @endif
                    <a href="{{ url('proses/schedreal/'.$data->Offered_Course_id) }}" class="btn btn-info btn-sm">Lihat Jadwal</a>
                  </td>
              </tr>
              <?php
              $a++;
            }
            ?>
        </tbody>
      </table>
      </div>
      <?php echo $datas->render('vendor.pagination.bootstrap-4'); ?>
      Ket: </br>
      <?php
        if($Class_Prog_Id != null){  
          if($Class_Prog_Id != 0){
      ?>
        <button class='btn' style='background:#3ac98d; color:#fff; cursor:default; margin:1px;'></button> Dosen Prodi </br>
        <button class='btn' style='background:#1d6446; color:#fff; cursor:default; margin:1px;'></button> Dosen dari Prodi Lain 
      <?php
          }
        }
      ?>
    </div>
  </div>
</div>

<script>
$(document).on('click', '.hapus', function (e) {
    e.preventDefault();
    var id = $(this).data('id');

  //  console.log(id);
    swal({
      title: 'Data Akan Dihapus',
        text: "Klik hapus untuk menghapus data",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Hapus',
        cancelButtonText: 'cancel!',
        confirmButtonClass: 'btn btn-success',
        cancelButtonClass: 'btn btn-danger',
        buttonsStyling: true
      }, function(isConfirm) {
    if (isConfirm) {
            $.ajax({
                url: "{{ url('') }}/master/faculty/" + id,
                type: "DELETE",
                dataType: "json",
                data: {
                  "_token": "{{ csrf_token() }}"
                },
                success: function (data) {
                  swal2();
                },
                error: function(){
                  swal1();
                }
            });
            // $("#hapus").submit();
          }
        });
});
  function swal1() {
    swal({
      title: 'Data masih digunakan',
        type: 'error',
        showCancelButton: false,
        cancelButtonColor: '#d33',
        cancelButtonText: 'cancel!',
        cancelButtonClass: 'btn btn-danger',
      });
  }
  function swal2() {
    swal({
      title: 'Data telah dihapus',
      type: 'success', showConfirmButton:false,
      });
      window.location.reload();
  }
</script>

</section>


@endsection