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
            @if(in_array('offered_course-CanAdd', $acc)) <a href="{{ url('setting/offered_course/create?class_program='.$class_program.'&department='.$department.'&term_year='.$term_year.'&current_page='.$page.'&current_rowpage='.$rowpage.'&current_search='.$search.'&curriculum='.$curriculum ) }}" class="btn btn-success btn-sm">Tambah data &nbsp;<i class="fa fa-plus"></i></a>@endif
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
            <label class="col-md-2" >Kurikulum :</label>
            <select class="form-control form-control-sm col-md-3" name="curriculum" onchange="document.form.submit();">
              <option value="">Pilih Kurikulum</option>
              @foreach ( $select_curriculum as $data )
                <option <?php if($curriculum == $data->Curriculum_Id){ echo "selected"; } ?>  value="{{ $data->Curriculum_Id }}">{{ $data->Curriculum_Name }}</option>
              @endforeach
            </select>
            &nbsp<label class="col-md-2" >Program Kelas :</label>
            <select class="form-control form-control-sm col-md-4" name="class_program"  onchange="document.form.submit();">
              <option value="0">Pilih Program Kelas</option>
              @foreach ( $select_class_program as $data )
                <option <?php if($class_program == $data->Class_Prog_Id){ echo "selected"; } ?> value="{{ $data->Class_Prog_Id }}">{{ $data->Class_Program_Name }}</option>
              @endforeach
            </select>&nbsp
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
        <table class="table table-striped table-font-sm" id="datatable">
          <thead class="thead-default thead-green">
              <tr>
                  <!-- <th class="col-sm-1">No</th> -->
                  <th width="10%">Kode MK</th>
                  <th width="20%">Nama Matakuliah</th>
                  <th width="5%">Kelas</th>
                  <th width="5%">Kapasitas</th>
                  <th width="5%">Semester</th>
                  <!-- <th width="5%">Kurikulum</th> -->
                  <th width="30%">Nama Dosen</th>
                  @if(in_array('offered_course-CanEditCapacity', $acc) || in_array('offered_course-CanEditEmployee', $acc) || in_array('offered_course-CanDelete', $acc))
                  <th width="25%"><center><i class="fa fa-gear"></i></center></th>
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
              ?>
              <tr>
                  <!-- <th></th> -->
                  <td>{{ $data->Course_Code }}</td>
                  <td>{{ $data->Course_Name }}</td>
                  <td>{{ $data->Class_Name }}</td>
                  <td>{{ $data->Class_Capacity }}</td>
                  <td>{{ $data->Study_Level_Id }}</td>
                  <!-- <td>{{ $data->Curriculum_Id }}</td> -->
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
                  @if(in_array('offered_course-CanEditCapacity', $acc) || in_array('offered_course-CanEditEmployee', $acc) || in_array('offered_course-CanDelete', $acc))
                  <td align="center">
                      {!! Form::open(['url' => route('offered_course.destroy', $data->Offered_Course_id) , 'method' => 'delete', 'role' => 'form']) !!}
                        @if(in_array('offered_course-CanEditCapacity', $acc)) <a href="{{ url('setting/offered_course/'.$data->Offered_Course_id.'/edit_capacity'.'?class_program='.$class_program.'&department='.$department.'&term_year='.$term_year.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search.'&curriculum='.$curriculum) }}" class="btn btn-info btn-sm">Ubah Kapasitas</a>@endif
                        @if(in_array('offered_course-CanEditEmployee', $acc)) <a href="{{ url('setting/offered_course/'.$data->Offered_Course_id.'/edit_employee'.'?class_program='.$class_program.'&department='.$department.'&term_year='.$term_year.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search.'&curriculum='.$curriculum) }}" class="btn btn-warning btn-sm">Ubah Dosen</a>@endif
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
        <button class='btn' style='background:#1d6446; color:#fff; cursor:default; margin:1px;'></button> Dosen dari Prodi Lain 
        <?php
          }
        }
        ?>
      </div>
    </div>
  </div>

  <script type="text/javascript">

    $(document).ready(function() {
       $('#datatable').DataTable({
                dom: 'Bflrtip',
                select: true,
                searching: true,
                paging: true,
                lengthChange: true,
                ordering: true,
                autoWidth: true,
                    lengthMenu: [ [ 25, 50, -1], [ 25, 50, "All"]],
                    buttons: [{
                        extend: 'excel',
                        messageTop: 'The information in this table is copyright to Sirius Cybernetics Corp.'
                    }]
            });
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
