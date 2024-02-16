@extends('layouts._layout')
@section('pageTitle', 'Course Curriculum')
@section('content')

<?php
$access = auth()->user()->akses();
$acc = $access;
?>

<style>
  td.details-control {
    background: url("{{ asset('img/plus.png') }}") no-repeat center center;
    background-size: 20px 20px;
    cursor: pointer;
  }

  tr.shown td.details-control {
    background: url("{{ asset('img/minus.png') }}") no-repeat center center;
    background-size: 20px 20px;
  }
</style>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Matakuliah Kurikulum</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          @if($class_program != null && $class_program != 0 && $department != null && $department != 0 && $curriculum != null && $curriculum != 0)
          <div class="pull-right tombol-gandeng dua">
            <?php $page = "";
            if (isset($_GET['page'])) {
              $page = $_GET['page'];
            }; ?>
            <a href="{{ url('parameter/course_curriculum/export/exportexcel?class_program='.$class_program.'&department='.$department.'&curriculum='.$curriculum.'&semester='.$semester.'&current_page='.$page.'&current_rowpage='.$rowpage.'&current_search='.$search ) }}" class="btn btn-warning btn-sm">Export &nbsp;<i class="fa fa-print"></i></a>
            @if(in_array('course_curriculum-CanAdd', $acc)) <a href="{{ url('parameter/course_curriculum/create?class_program='.$class_program.'&department='.$department.'&curriculum='.$curriculum.'&semester='.$semester.'&current_page='.$page.'&current_rowpage='.$rowpage.'&current_search='.$search ) }}" class="btn btn-success btn-sm">Tambah data &nbsp;<i class="fa fa-plus"></i></a>@endif
          </div>
          @endif
          <b>Matakuliah Kurikulum</b>
        </div>
      </div>
      <!-- <b>Daftar Fakultas</b> -->
      {!! Form::open(['url' => route('course_curriculum.index') , 'method' => 'GET', 'name' => 'form', 'role' => 'form', 'style' => 'margin:2%;']) !!}
      <div class="row  col-md-12 text-green">
        <label class="col-md-2">Program Studi :</label>
        <select class="form-control form-control-sm col-md-4" name="department" onchange="document.form.submit();">
          <option value="">Pilih Program Studi</option>
          @foreach ( $select_department as $data )
          <option <?php if ($department == $data->Department_Id) {
                    echo "selected";
                  } ?> value="{{ $data->Department_Id }}">{{ $data->Department_Name }}</option>
          @endforeach
        </select>
        <label class="col-md-2">Program Kelas :</label>
        <select class="form-control form-control-sm col-md-4" name="class_program" onchange="document.form.submit();">
          <option value="">Pilih Program Kelas</option>
          @foreach ( $select_class_program as $data )
          <option <?php if ($class_program == $data->Class_Prog_Id) {
                    echo "selected";
                  } ?> value="{{ $data->Class_Prog_Id }}">{{ $data->Class_Program_Name }}</option>
          @endforeach
        </select>
      </div>
      <div class="row  col-md-12 text-green">
        <label class="col-md-2">Kurikulum :</label>
        <select class="form-control form-control-sm col-md-4" name="curriculum" onchange="document.form.submit();">
          <option value="">Pilih Kurikulum</option>
          @foreach ( $select_curriculum as $data )
          <option <?php if ($curriculum == $data->Curriculum_Id) {
                    echo "selected";
                  } ?> value="{{ $data->Curriculum_Id }}">{{ $data->Curriculum_Name }}</option>
          @endforeach
        </select>
        <label class="col-md-2">Semester :</label>
        <select class="form-control form-control-sm col-md-4" name="semester" onchange="document.form.submit();">
          <option value="">Pilih Semester</option>
          @foreach ( $select_semester as $data )
          <option <?php if ($semester == $data->Study_Level_Id) {
                    echo "selected";
                  } ?> value="{{ $data->Study_Level_Id }}">{{ $data->Level_Name }}</option>
          @endforeach
          <option <?php if ($semester == 999) {
                    echo "selected";
                  } ?> value="999">Semua Semester</option>
        </select>
      </div>
      <div class="row  col-md-12 text-green">
        <label class="col-md-2"></label>
        @if($class_program==0 || $curriculum==0 || $semester ==null || $department==0)
        @else
        <a href="{{ url('/parameter/course_curriculum/create/copydata?department='.$department.'&curriculum='.$curriculum.'&class_program='.$class_program.'&semester='.$semester) }}" class="btn btn-success btn-sm">Copy data &nbsp;<i class="fa fa-plus"></i></a>
        @endif
      </div>
      <br>
      <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
        <div class="row col-md-7">
          <label class="col-md-3">Pencarian :</label>
          <input type="text" name="search" class="form-control form-control-sm col-md-7 col-sm-7" value="{{ $search }}" placeholder="Search">
          <input type="submit" name="" class="btn btn-primary btn-sm col-md-2 col-sm-2" value="Cari">
        </div>
        <div class="row col-md-5">
          <label class="col-md-5">Baris per halamam :</label>
          <select class="form-control form-control-sm col-md-7" name="rowpage" onchange="form.submit()">
            <option <?php if ($rowpage == 10) {
                      echo "selected";
                    } ?> value="10">10</option>
            <option <?php if ($rowpage == 20) {
                      echo "selected";
                    } ?> value="20">20</option>
            <option <?php if ($rowpage == 50) {
                      echo "selected";
                    } ?> value="50">50</option>
            <option <?php if ($rowpage == 100) {
                      echo "selected";
                    } ?> value="100">100</option>
            <option <?php if ($rowpage == 200) {
                      echo "selected";
                    } ?> value="200">200</option>
            <option <?php if ($rowpage == "1000000") {
                      echo "selected";
                    } ?> value="1000000">semua</option>
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
      <input type="" hidden id="department" class="" value="{{$department}}">
      <input type="" hidden id="curriculum" class="" value="{{$curriculum}}">
      <input type="" hidden id="semester" class="" value="{{$semester}}">
      <input type="" hidden id="class_program" class="" value="{{$class_program}}">
      <div class="table-responsive">
        <table id="example" class="table table-striped table-font-sm">
          <thead class="thead-default thead-green">
            <tr>
              <!-- <th class="col-sm-1">No</th> -->
              <th width="2%"></th>
              <th width="7%">Kode Matakuliah</th>
              <th width="7%">Nama Matakuliah</th>
              <th width="5%">SKS</th>
              <th width="5%">Transkrip</th>
              <th width="5%">SKS Transkrip</th>
              <!-- <th width="5%">Sifat</th> -->
              <!-- <th width="7%">Nama Kelompok</th> -->
              <th width="5%">SMT</th>
              <!-- <th width="5%">Sub SMT</th> -->
              <th width="5%">Jenis Kurikulum</th>
              @if(in_array('course_curriculum-CanEdit', $acc) || in_array('course_curriculum-CanDelete', $acc))
              <th width="13%">
                <center><i class="fa fa-gear"></i></center>
              </th>
              @endif
            </tr>
          </thead>
          <tbody>
            <?php
            $a = "1";
            foreach ($query as $data) {
              // $prodi = DB::table('prodi')->join('prodi_user','prodi_user.id_prodi','=','prodi.id_prodi')->join('users','users.id','=','prodi_user.id_user')->where('users.id',$data->id)->get();
              // $prod = auth()->user()->Prodi();
              // if (count($prod)==0) {
            ?>
              <tr <?php
                  $prerequisite_detail = DB::table('acd_prerequisite_detail')
                    ->leftjoin('acd_course', 'acd_course.Course_Id', '=', 'acd_prerequisite_detail.Course_Id')
                    ->leftjoin('acd_prerequisite', 'acd_prerequisite.Prerequisite_Id', '=', 'acd_prerequisite_detail.Prerequisite_Id')
                    ->leftjoin('acd_grade_letter', 'acd_grade_letter.Grade_Letter_Id', '=', 'acd_prerequisite_detail.Grade_Letter_Id')
                    ->join('mstr_prerequisite_type', 'mstr_prerequisite_type.Prerequisite_Type_Id', '=', 'acd_prerequisite_detail.Prerequisite_Type_Id')
                    ->where('acd_prerequisite.Course_Id', $data->Course_Id)
                    ->where('acd_prerequisite.Class_Prog_Id', $data->Class_Prog_Id)
                    ->where('acd_prerequisite.Curriculum_Id', $data->Curriculum_Id)
                    ->select('acd_prerequisite_detail.*', 'acd_prerequisite.*', 'mstr_prerequisite_type.Prerequisite_Type_Name', 'acd_course.Course_Name', 'acd_course.Course_Code', 'acd_grade_letter.Grade_Letter')
                    ->groupby('acd_prerequisite_detail.Prerequisite_Detail_Id')
                    ->get();
                  ?> data-params="{{ $prerequisite_detail }}" <?php if ($data->Applied_Sks == null || $data->Study_Level_Id == null || $data->Transcript_Sks == null) {
                                                                echo "style='color:red;'";
                                                              } ?>>
                <!-- <th></th> -->
                <td class="details-control">
                  <center>
                </td>
                <td>
                  <center>{{ $data->Course_Code }}
                </td>
                <td>
                  <center>{{ $data->Course_Name }}
                </td>
                <td>
                  <center>{{ $data->Applied_Sks }}
                </td>
                <td>
                  <center>
                    @if($data->Is_For_Transcript == true)
                    <label>Ya</label>
                    @else
                    <label>Tidak</label>
                    @endif
                </td>
                <td>
                  <center>{{ $data->Transcript_Sks }}
                </td>
                <!-- <td><center>
                    @if($data->	Is_Required == true)
                    <label>Wajib</label>
                    @else
                    <label>Pilihan</label>
                    @endif
                  </td>
                  <td><center>{{ $data->Name_Of_Group }}</td> -->
                <td>
                  <center>{{ $data->Study_Level_Code }}
                </td>
                <!-- <td><center>{{ $data->Study_Level_Sub }}</td> -->
                <td>
                  <center>{{ $data->Curriculum_Type_Name }}
                </td>
                @if(in_array('course_curriculum-CanEdit', $acc) || in_array('course_curriculum-CanDelete', $acc))
                <td align="center">
                  {!! Form::open(['url' => route('course_curriculum.destroy', $data->Course_Cur_Id) , 'method' => 'delete', 'role' => 'form']) !!}
                  <div class="btn-group">
                    @if(in_array('course_curriculum-CanEdit', $acc)) <a href="{{ url('parameter/course_curriculum/'.$data->Course_Cur_Id.'/edit'.'?class_program='.$class_program.'&department='.$department.'&curriculum='.$curriculum.'&semester='.$semester.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-info btn-sm">Edit</a>@endif
                    <a href="{{ url('parameter/prasyarat?class_program='.$class_program.'&department='.$department.'&curriculum='.$curriculum.'&semester='.$semester.'&cekpage='.$page.'&course_id='.$data->Course_Id.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-warning btn-sm">Prasyarat</a>
                    <!-- <a data-params="{{ $data->Course_Name }}"
                            data-courseid="{{ $data->Course_Id }}" 
                            data-curname="{{ $data->Curriculum_Name }}"
                            data-curid="{{ $data->Curriculum_Id }}"
                            data-sks="{{ $data->Applied_Sks }}"
                            data-smt="{{ $data->Study_Level_Code }}"
                            data-smtid="{{ $data->Study_Level_Id }}"
                            data-deptid="{{ $data->Department_Id }}"
                            data-classid="{{ $data->Class_Prog_Id }}"
                            data-coursecur="{{ $data->Course_Cur_Id }}"
                            data-url="{{ $data->Silabus_Upload }}"
                            class="btn btn-success btn-sm" 
                            id="silabus"  
                            href="javascript:">Silabus</a> -->
                    @if(in_array('course_curriculum-CanDelete', $acc)) {!! Form::button('Hapus', ['type'=>'submit','class'=>'btn btn-danger btn-sm hapus','data-id'=>$data->Course_Cur_Id]) !!}@endif <br>
                  </div>
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
        </br>
      </div>
      <?php echo $query->render('vendor.pagination.bootstrap-4'); ?>
    </div>
  </div>
  </div>



  <div id="ubahpembayaran" class="w3-modal">
    <div class="w3-modal-content w3-animate-zoom w3-card-4">
      <header class="w3-container w3-teal">
        <span onclick="location.reload()" class="w3-button w3-display-topright">&times;</span>
        <h4>Upload Silabus</h4>
      </header>
      <div class="w3-container">
        </br>
        <form id="frm-upload" action="{{ route('course_curriculum.store_silabus') }}" method="POST" enctype="multipart/form-data">
          <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            <div class="row col-md-10">
              <label class="col-md-4">Matakuliah</label>
              <input type="text" name="Course_Id" id="Course_Id" value="" readonly class="form-control form-control-sm col-md-7">
            </div>
            <div class="row col-md-10">
              <label class="col-md-4">SKS</label>
              <input type="text" name="Sks" id="Course_Id" value="" readonly class="form-control form-control-sm col-md-7">
            </div>
            <div class="row col-md-10">
              <label class="col-md-4">Kurikulum</label>
              <input type="text" name="Curriculum_Name" id="Class_Id" value="" readonly class="form-control form-control-sm col-md-7">
            </div>
            <div class="row col-md-10">
              <label class="col-md-4">Semester</label>
              <input type="text" name="Smt" id="Class_Capacity" value="" readonly class="form-control form-control-sm col-md-7">
            </div>
            <div class="row col-md-10">
              <label class="col-md-4">Silabus</label>
              <input type="text" name="coursecur2" hidden>
              <input type="text" name="smtid" value="" hidden>
              <input type="text" name="deptid" value="" hidden>
              <input type="text" name="classid" value="" hidden>
              <input type="text" name="curid" value="" hidden>
              <input type="text" name="url" value="" hidden>
              @csrf
              <input type="file" require accept=".xlsx,.xls,.doc, .docx,.pdf" class="form-control form-control-sm col-md-7" name="imageProfile" id="profilDialog" />
            </div>

            <div class="row col-md-10">
              <label class="col-md-4"></label>
              <a id="urlslb" class=""><label id="text"></label></a>
              <button id="hapussilb" class="hapus2" style="background:none!important; color:inherit; border:none; padding:0!important; font: inherit; cursor: pointer;"><i class="fa fa-close" style="cursor:pointer; color:red;"></i></button>
            </div>

            <div class="row col-md-10">
              <label class="col-md-4"></label>
              <input type="submit" value="Simpan" class="btn-success btn-sm form-control form-control-sm col-md-7">
            </div>
            <div class="row col-md-10">
              <label class="col-md-4"></label>
              <!-- <button value="up" id="bnt-cancel" onclick="" type="submit" class="btn-danger btn-sm btn form-control form-control-sm col-md-7" style="width:80%; margin-top: 2%;">
                  Batal
                </button>       -->
            </div>
        </form>
      </div>
      </br>
    </div>
  </div>
  <table id="eaea">
  </table>
  </div>

  <style>
    @media (min-width:993px) {
      .w3-modal-content {
        width: 50%
      }

      .w3-hide-large {
        display: none !important
      }

      .w3-sidebar.w3-collapse {
        display: block !important
      }
    }
  </style>

  <script>
    $(document).ready(function() {
      var table = $('#example').DataTable({
        searching: false,
        paging: false,
        info: false
      });

      function format(d) {
        var class_program = document.getElementById("class_program").value;
        var curriculum = document.getElementById("curriculum").value;
        var semester = document.getElementById("semester").value;
        var department = document.getElementById("department").value;
        console.log(d.params);
        var tablechild = '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;margin-left: 38px;">' +
          '<thead>' +
          '<tr>' +
          '<th>Jenis Prasyarat</th>' +
          '<th>Kode Matakuliah</th>' +
          '<th>Nama Matakuliah</th>' +
          '<th>Nilai Minimum</th>' +
          '<th>Value</th>' +
          '</tr>' +
          '</thead>';
        d.params.forEach(function(element) {
          tablechild = tablechild + '<tr>' +
            '<td>' + (element.Prerequisite_Type_Name == null ? '' : element.Prerequisite_Type_Name) + '</td>' +
            '<td>' + (element.Course_Code == null ? '' : element.Course_Code) + '</td>' +
            '<td>' + (element.Course_Name == null ? '' : element.Course_Name) + '</td>' +
            '<td>' + (element.Grade_Letter == null ? '' : element.Grade_Letter) + '</td>' +
            '<td>' + (element.Value == null ? '' : element.Value) + '</td>' +
            '</tr>';
        });
        tablechild = tablechild + '</table>';
        return tablechild;
      }

      $('#example tbody').on('click', 'td.details-control', function() {
        var tr = $(this).closest('tr');
        var row = table.row(tr);

        if (row.child.isShown()) {
          // This row is already open - close it
          row.child.hide();
          tr.removeClass('shown');
        } else {
          // Open this row
          row.child(format(tr.data())).show();
          tr.addClass('shown');
        }
      });
    });



    $(document).on('click', '#silabus', function(e) {
      document.getElementById("ubahpembayaran").style.display = "block";
      var Course_Name = $(this).data('params'),
        Curriculum_Name = $(this).data('curname'),
        Smt = $(this).data('smt'),
        Sks = $(this).data('sks');
      courseid = $(this).data('courseid');
      curid = $(this).data('curid');
      smtid = $(this).data('smtid');
      deptid = $(this).data('deptid');
      classid = $(this).data('classid');
      coursecur = $(this).data('coursecur');
      url = $(this).data('url');
      console.log(url);

      $("[name='Course_Id']").val(Course_Name);
      $("[name='Curriculum_Name']").val(Curriculum_Name);
      $("[name='Sks']").val(Sks);
      $("[name='Smt']").val(Smt);
      $("[name='courseid']").val(courseid);
      $("[name='curid']").val(curid);
      $("[name='smtid']").val(smtid);
      $("[name='deptid']").val(deptid);
      $("[name='classid']").val(classid);
      $("[name='coursecur']").val(coursecur);
      $("[name='coursecur2']").val(coursecur);
      $("[name='url_silabus']").val(url);

      if (url == null || url == '') {
        $('#hapussilb').hide();
      } else {
        var a = document.getElementById('urlslb'); //or grab it by tagname etc
        a.href = url

        document.getElementById('text').textContent = 'Silabus Matakuliah ' + Course_Name;
      }
    });

    //  function readURL(input) {
    //       if (input.files && input.files[0]) {
    //           var reader = new FileReader();

    //           reader.onload = function (e) {
    //               $('#image_upload_preview').attr('src', e.target.result);
    //           }

    //           reader.readAsDataURL(input.files[0]);
    //       }
    //   }

    //   $("#profilDialog").change(function () {
    //       readURL(this);
    //   });

    $(document).on('click', '.hapus', function(e) {
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
            url: "{{ url('') }}/parameter/course_curriculum/" + id,
            type: "DELETE",
            dataType: "json",
            data: {
              "_token": "{{ csrf_token() }}"
            },
            success: function(data) {
              swal2();
            },
            error: function() {
              swal1();
            }
          });
          // $("#hapus").submit();
        }
      });
    });

    $(document).on('click', '.hapus2', function(e) {
      e.preventDefault();

      var id = $("[name='coursecur2']").val();
      console.log(id);
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
            url: "{{ url('') }}/parameter/course_curriculum/hapussilabus",
            type: "POST",
            dataType: "json",
            data: {
              "id": id,
              "_token": "{{ csrf_token() }}"
            },
            success: function(data) {
              swal2();
            },
            error: function() {
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
        type: 'success',
        showConfirmButton: false,
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