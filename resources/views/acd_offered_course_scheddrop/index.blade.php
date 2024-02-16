@extends('layouts._layout')
@section('pageTitle', 'Offered Course Sched')
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
      <h3 class="text-white">Entry Jadwal Kuliah</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          <b>Entry Jadwal Kuliah</b>
        </div>
      </div>
      <br>
          <!-- <b>Daftar Fakultas</b> -->
          {!! Form::open(['url' => route('offered_course_schedV2.index') , 'method' => 'GET', 'name' => 'form', 'role' => 'form', 'style' => 'padding-top:5px;padding-left:15px;padding-right:15px;']) !!}
          <div class="row text-green">
                <label class="col-md-2" >Semester :</label>             
		<select class="form-control form-control-sm col-md-3" name="term_year" onchange="document.form.submit();">
              <option value="">Pilih Tahun / Semester</option>
              @foreach ( $select_term_year as $data )
                <option <?php if($term_year == $data->Term_Year_Id){ echo "selected"; } ?>  value="{{ $data->Term_Year_Id }}">{{ $data->Term_Year_Name }}</option>
              @endforeach
            </select>&nbsp
	<label class="col-md-2">Program Studi :</label>
            <select class="form-control form-control-sm col-md-3" name="department"  onchange="document.form.submit();">
              <option value="0">Pilih Program Studi</option>
              @foreach ( $select_department as $data )
                <option <?php if($department == $data->Department_Id){ echo "selected"; } ?> value="{{ $data->Department_Id }}">{{ $data->Department_Name }}</option>
              @endforeach
            </select>&nbsp
             <label class="col-md-2">Program Kelas :</label>
            <select class="form-control form-control-sm col-md-3" name="class_program"  onchange="document.form.submit();">
              <option value="0">Pilih Program Kelas</option>
              @foreach ( $select_class_program as $data )
                <option <?php if($class_program == $data->Class_Prog_Id){ echo "selected"; } ?> value="{{ $data->Class_Prog_Id }}">{{ $data->Class_Program_Name }}</option>
              @endforeach
            </select>&nbsp

            <label class="col-md-2" >Grup Sesi :</label>
            <select class="form-control form-control-sm col-md-3" name="sched_session_group_id" onchange="document.form.submit();">
              <!-- <option value="99999">Semua Grup Sesi</option> -->
               @foreach ( $select_sched_session_group as $data )
                <option <?php if($Sched_Session_Group_Id == $data->Sched_Session_Group_Id){ echo "selected"; } ?> value="{{ $data->Sched_Session_Group_Id }}">{{ $data->Sched_Session_Group_Name }}</option>
              @endforeach
            </select>


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
        @if($class_program==0 || $term_year ==null || $department==0 ||$Sched_Session_Group_Id == 0 || $Sched_Session_Group_Id == null)
        @else
          <a href="#" class="btn btn-primary btn-sm export" id='export' style="font-size:medium;margin-right:5px;">Export Excel &nbsp;<i class="fa fa-print"></i></a> &nbsp
          <a href="#" class="btn btn-primary btn-sm exportcsv" id='exportcsv' style="font-size:medium;margin-right:5px;">Export CSV &nbsp;<i class="fa fa-print"></i></a> &nbsp
          <input type="text" hidden id="dept" value="{{$department}}">
          <input type="text" hidden id="smt" value="{{$term_year}}">
          <input type="text" hidden id="kpg" value="{{$class_program}}">
          <input type="text" hidden id="cur" value="{{$curriculum}}">
        @endif
        <div class="table-responsive">
        <table class="table table-striped table-font-sm">
          <thead class="thead-default thead-green">
              <tr>
                  <!-- <th class="col-sm-1">No</th> -->
                  <th width="10%">Kode Matakuliah</th>
                  <th width="25%">Nama Matakuliah</th>
                  <th width="5%">kelas</th>
                  <th width="5%">Kapasitas</th>
                  <th width="5%">Semester</th>
                  <th width="5%">Peserta</th>
                  <th width="30%">Jadwal</th>
                  @if(in_array('offered_course_sched-CanEdit', $acc))
                  <th width="10%"><center><i class="fa fa-gear"></i></center></th>
                  @endif
              </tr>
          </thead>
          <tbody>
            <?php
            $a = "1";
            // $property_types = array();
            foreach ($query as $data) {
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
              // ?>
              <tr>
                  <!-- <th></th> -->
                  <td>{{ $data->Course_Code }}</td>
                  <td>{{ $data->Course_Name }}</td>
                  <td>{{ $data->Class_Name }}</td>
                  <td>{{ $data->Class_Capacity }}</td>
                  <td>{{ $data->Study_Level_Id }}</td>
                  <td>{{ $count }}</td>
                  <td>
                    <?php
                      $jadwal = explode('|',$data->jadwal);
                      $room = explode('|',$data->room);
                      $n = 0;
                      if ($data->jadwal != "") {
                      foreach ($jadwal as $key) {
                          echo "<div class='btn btn-sm' style='background:#4cb24e; color:#fff; cursor:default; margin:1px;'>".$key."/".$room[$n]."</div>";
                          $n++;
                        }
                      }
                    ?>
                  </td>
                  @if(in_array('offered_course_sched-CanEdit', $acc))
                  <td align="center">
                      <!-- {!! Form::open(['url' => route('offered_course_sched.destroy', $data->Offered_Course_id) , 'method' => 'delete', 'role' => 'form']) !!} -->
                      <a href="{{ url('setting/offered_course_schedV2/create?offered_course_id='.$data->Offered_Course_id.'&class_program='.$class_program.'&department='.$department.'&term_year='.$term_year.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search.'&curriculum='.$curriculum.'&sched_session_group_id='.$Sched_Session_Group_Id) }}" class="btn btn-info btn-sm">Edit Jadwal</a>
                      <!-- {!! Form::button('Hapus', ['type'=>'submit','class'=>'btn btn-danger btn-sm']) !!}
                      {!! Form::close() !!} -->
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
        <?php 
        if($Sched_Session_Group_Id != '99999'){
          echo $query->render('vendor.pagination.bootstrap-4'); 
        }
        ?>
      </div>
    </div>
  </div>
<script>
    $("#export").click(function(e) {
      var department = $('#dept').val();
      // var curriculum = $('#cur').val();
      var term_year = $('#smt').val();
      var class_program = $('#kpg').val();
        window.open("{{ url('') }}/setting/offered_course_schedV2/exportdata/exportdata/" + department + "/" + term_year+ "/" + class_program); 
    });

    $("#exportcsv").click(function(e) {
      var department = $('#dept').val();
      // var curriculum = $('#cur').val();
      var term_year = $('#smt').val();
      var class_program = $('#kpg').val();
        window.open("{{ url('') }}/setting/offered_course_schedV2/exportdata/exportdatacsv?Department_Id=" + department + "&Term_Year_Id=" + term_year+ "&Class_Prog_Id=" + class_program); 
    });

</script>
</section>
@endsection
