@extends('layouts._layout')
@section('pageTitle', 'Offered Course')
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
      <h3 class="text-white">Matakuliah Ditawarkan</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          @if($class_program != null && $class_program != 0 && $department != null && $department != 0 && $term_year != null && $term_year != 0)
          <div class="pull-right tombol-gandeng dua">
            <?php $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; ?>
            @if(in_array('offered_course-CanAdd', $acc)) <a href="{{ url('setting/offered_course/create?class_program='.$class_program.'&department='.$department.'&term_year='.$term_year.'&current_page='.$page.'&current_rowpage='.$rowpage.'&current_search='.$search) }}" class="btn btn-success btn-sm">Tambah data &nbsp;<i class="fa fa-plus"></i></a>@endif
          </div>
          @endif
          <b>Matakuliah Ditawarkan</b>
        </div>
      </div>
      <br>
          {!! Form::open(['url' => route('offered_course.index') , 'method' => 'GET', 'name' => 'form', 'role' => 'form', 'style' => 'padding-top:5px;padding-left:15px;padding-right:15px;']) !!}
          <div class="row  col-md-12 text-green">
            <label class="col-md-2">TahunSemester :</label>
            <select class="form-control form-control-sm col-md-3" name="term_year" onchange="document.form.submit();">
              <option value="0">Pilih Tahun Semester</option>
              @foreach ( $select_term_year as $data )
                <option <?php if($term_year == $data->Term_Year_Id){ echo "selected"; } ?>  value="{{ $data->Term_Year_Id }}">{{ $data->Term_Year_Name }}</option>
              @endforeach
            </select>&nbsp
            <label class="col-md-2">Program Studi :</label>
            <select class="form-control form-control-sm col-md-4" name="department"  onchange="document.form.submit();">
              <option value="0">Pilih Program Studi</option>
              @foreach ( $select_department as $data )
                <option <?php if($department == $data->Department_Id){ echo "selected"; } ?> value="{{ $data->Department_Id }}">{{ $data->Department_Name }}</option>
              @endforeach
            </select>&nbsp
          </div>
          <div class="row  col-md-12 text-green">
            <!-- <label class="col-md-2" >Kurikulum :</label>
            <select class="form-control form-control-sm col-md-3" name="curriculum" onchange="document.form.submit();">
              <option value="">Pilih Kurikulum</option>
              @foreach ( $select_curriculum as $data )
                <option <?php if($curriculum == $data->Curriculum_Id){ echo "selected"; } ?>  value="{{ $data->Curriculum_Id }}">{{ $data->Curriculum_Name }}</option>
              @endforeach
            </select> -->
            <label class="col-md-2" >Program Kelas :</label>
            <select class="form-control form-control-sm col-md-3" name="class_program"  onchange="document.form.submit();">
              <option value="0">Pilih Program Kelas</option>
              @foreach ( $select_class_program as $data )
                <option <?php if($class_program == $data->Class_Prog_Id){ echo "selected"; } ?> value="{{ $data->Class_Prog_Id }}">{{ $data->Class_Program_Name }}</option>
              @endforeach
            </select>&nbsp
          </div>
          <div class="row  col-md-12 text-green">
            <label class="col-md-2" ></label>
            @if($term_year==0 || $department==0 || $class_program ==0)
            @else
            <a href="{{ url('/setting/offered_course/create/copydata?class_program='.$class_program.'&department='.$department.'&term_year='.$term_year.'&current_page='.$page.'&current_rowpage='.$rowpage.'&current_search='.$search) }}" class="btn btn-success btn-sm">Copy data &nbsp;<i class="fa fa-plus"></i></a>
          @endif
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
             @if ($error=="Berhasil Menyimpan Perubahan")
                <p class="alert alert-success">{{ $error }}</p>
              @else
                <p class="alert alert-danger">{{ $error }}</p>
              @endif
          @endforeach
        @endif
        <div class="table-responsive">
        <table class="table table-striped table-font-sm">
          <thead class="thead-default thead-green">
              <tr>
                  <!-- <th class="10%">No</th> -->
                  <th width="5%">Kode MK</th>
                  <th width="20%">Nama Matakuliah</th>
                  <th width="5%">Kelas</th>
                  <th width="5%">Kapasitas</th>
                  <th width="5%">Semester</th>
                  <th width="5%">Peserta</th>
                  <!-- <th width="5%">Kurikulum</th> -->
                  <th width="25%">Nama Dosen</th>
                  <th width="5%">jadwal</th>
                  @if(in_array('offered_course-CanEditCapacity', $acc) || in_array('offered_course-CanEditEmployee', $acc) || in_array('offered_course-CanDelete', $acc))
                  <th width="25%"><center><i class="fa fa-gear"></i></center></th>
                  @endif
              </tr>
          </thead>
          <tbody>
            <?php
            $a = 1;
            // $property_types = array();
            // dd($query);
            foreach ($query as $data){
              // if ( in_array($data->Offered_Course_id, $property_types) ) {
              //     continue;
              // }
              // $property_types[] = $data->Offered_Course_id;
              // dd($data);
              $count = DB::table('acd_student_krs as a')->join('acd_student as b','a.Student_Id','=','b.Student_Id')->where([
                    ['a.Term_Year_Id',$data->Term_Year_Id],
                    ['b.Department_Id',$data->Department_Id],
                    ['b.Class_Prog_Id',$data->Class_Prog_Id],
                    ['a.Course_Id',$data->Course_Id],
                    ['a.Class_Id',$data->Class_Id],
                    ['a.Is_Approved',1],
                    ])->count();
              $jadwal = DB::table('acd_offered_course_sched')->where('Offered_Course_id',$data->Offered_Course_id)->get();
              $set_jadwal = (count($jadwal) > 0 ? 'set':'notset');
              // dd($set_jadwal);
              ?>
              <tr>
                  <!-- <th>{{$a}}</th> -->
                  <td>{{ $data->Course_Code }}</td>
                  <td>{{ $data->Course_Name }}</td>
                  <td>{{ $data->Class_Name }}</td>
                  <td>{{ $data->Class_Capacity }}</td>
                  <td>{{ $data->Study_Level_Id }}</td>
                  <td>{{ $count }}</td>
                  <!-- <td>{{ $data->Curriculum_Id }}</td> -->
                  <td>
                    <?php
                    // dd($data);
                    $dosen = explode('|',$data->dosen);
                    $id_dosen = explode('|',$data->id_dosen);
                      // dd($id_dosen);
                      // if($id_dosen != null){

                      // }else{
                        
                      // }
                      foreach ($id_dosen as $key) {
                        // dd($key);
                        // if(isset($key->Department_Id)){
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
                            // dd($anu);

                            if(isset($anu->Department_Id)){
                              if($anu->Department_Id != $department){
                                $dosennya = DB::table('emp_employee')->where('Employee_Id',$anu->Employee_Id)->first();
                                $firstitle = $dosennya->First_Title;
                                $name = $dosennya->Name;
                                $lasttitle = $dosennya->Last_Title;
                                echo "<div class='btn btn-sm' style='background:#1d6446; color:#fff; cursor:default; margin:1px;'> ".$firstitle." ".$name." ".$lasttitle."</div>";
                              }else{
                                 $dosennya = DB::table('emp_employee')->where('Employee_Id',$anu->Employee_Id)->first();
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
                              echo "<div class='btn btn-sm' style='background:#BEBEBE; color:#000; cursor:default; margin:1px;'> ".$firstitle." ".$name." ".$lasttitle."</div>";
                            }
                          }
                        // }
                      }
                    ?>
                  </td>
                  <td>{{ $set_jadwal }}</td>
                  @if(in_array('offered_course-CanEditCapacity', $acc) || in_array('offered_course-CanEditEmployee', $acc) || in_array('offered_course-CanDelete', $acc))
                  <td align="center">
                  <input type="text" name="findOffered_Course_id" value="{{$data->Offered_Course_id}}" hidden >
                  <input type="text" name="findclass_program" value="{{$class_program}}" hidden >
                  <input type="text" name="finddepartment" value="{{$department}}" hidden >
                  <input type="text" name="findterm_year" value="{{$term_year}}" hidden >
                  <input type="text" name="findcurriculum" value="{{$curriculum}}" hidden >
                      {!! Form::open(['url' => route('offered_course.destroy', $data->Offered_Course_id) , 'method' => 'delete', 'role' => 'form']) !!}
                        <!-- @if(in_array('offered_course-CanEditCapacity', $acc)) <a href="{{ url('setting/offered_course/'.$data->Offered_Course_id.'/edit_capacity'.'?class_program='.$class_program.'&department='.$department.'&term_year='.$term_year.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search.'&curriculum='.$curriculum) }}" class="btn btn-info btn-sm">Ubah Jadwal</a>@endif -->
                        @if(in_array('offered_course_sched-CanView',$acc)) <a href="{{ url('setting/offered_course_schedV2/create?offered_course_id='.$data->Offered_Course_id.'&class_program='.$class_program.'&department='.$department.'&term_year='.$term_year.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search.'&from=oc') }}" class="btn btn-info btn-sm">Ubah Jadwal</a>@endif
                        @if(in_array('offered_course-CanEditEmployee', $acc)) <a data-oci="{{$data->Offered_Course_id}}" data-classprogram="{{$class_program}}" data-department="{{$department}}" data-termyear="{{$term_year}}" data-curriculum="{{$curriculum}}"  href="#" class="btn btn-warning btn-sm cekbeban">Ubah Kapasitas / Dosen</a>@endif
                        @if(in_array('offered_course-CanDelete', $acc)) {!! Form::button('Hapus', ['type'=>'submit','class'=>'btn btn-danger btn-sm hapus','data-id'=>$data->Offered_Course_id]) !!}@endif
                      {!! Form::close() !!}
                  </td>
                  @endif
              </tr>
              <?php
              $a++;
            }
            ?>
          </tbody>
        </table>
        </div>
        <?php echo $query->render('vendor.pagination.bootstrap-4'); ?>
        Ket: </br>
        <?php
        if($class_program != null){  
          if($class_program != 0){
        ?>
        <button class='btn' style='background:#3ac98d; color:#fff; cursor:default; margin:1px;'></button> Dosen Prodi </br>
        <button class='btn' style='background:#1d6446; color:#fff; cursor:default; margin:1px;'></button> Dosen dari Prodi Lain  </br>
        <button class='btn' style='background:#BEBEBE; color:#fff; cursor:default; margin:1px;'></button> Dosen sudah diset tapi belum punya base prodi 
        <?php
          }
        }
        ?>
      </div>
    </div>
  </div>

  <script>
  $(document).on('click', '.cekbeban', function (e) {
    var oci = $(this).data('oci'),
        classprogram = $(this).data('classprogram'),
        department = $(this).data('department'),
        termyear = $(this).data('termyear'),
        jatah = $(this).data('jatahbeban'),
        curriculum = $(this).data('curriculum');
    // if(jatah > 0){
      window.location.href = "{{ url('') }}/setting/offered_course/"+oci+'/edit_employee?class_program='+classprogram+'&department='+department+'&term_year='+termyear+'&curriculum='+curriculum;
    // }else{
    //   swal({
    //     title: 'Beban Mengajar Dosen',
    //       text: "Beban Mengajar Dosen Belum Diset",
    //       type: 'warning',
    //       showCancelButton: false,
    //       confirmButtonColor: '#3085d6',
    //       cancelButtonColor: '#d33',
    //       confirmButtonText: 'Oke',
    //       cancelButtonText: 'cancel!',
    //       confirmButtonClass: 'btn btn-success',
    //       cancelButtonClass: 'btn btn-danger',
    //       buttonsStyling: true
    //     }, function(isConfirm) {
    //   if (isConfirm) {
    //           window.location.reload();
    //         }
    //       });
    // }
  });

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
                  url: "{{ url('') }}/setting/offered_course/" + id,
                  type: "DELETE",
                  dataType: "json",
                  data: {
                    "_token": "{{ csrf_token() }}"
                  },
                  success: function (data) {
                    // swal2();
                    swal({
                      title: data.message,
                      type: data.type, showConfirmButton:true,
                      },function(isConfirm){
                        if(isConfirm){
                          window.location.reload();
                        }
                      });
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
        type: 'success', showConfirmButton:true,
        });
        window.location.reload();
    }
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
