@extends('layouts._layout')
@section('pageTitle', 'Offered Course')
@section('content')
<style>
table#example.dataTable tbody tr:hover {
  background-color: #ccc;
}
</style>
<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Tambah Matakuliah Ditawarkan</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          <div class="pull-right tombol-gandeng dua">
            <a  href="{{ url('setting/offered_course?term_year='.$term_year.'&department='.$department.'&class_program='.$class_program.'&page='.$current_page.'&rowpage='.$current_rowpage.'&search='.$current_search) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
          </div>
          <b>Tambah</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">

          @if (count($errors) > 0)
            @foreach ( $errors->all() as $error )
              @if ($error=="Berhasil Menambah Matakuliah Ditawarkan")
                <p class="alert alert-success">{{ $error }}</p>
              @else
                <p class="alert alert-danger">{{ $error }}</p>
              @endif
            @endforeach
          @endif

          <div class="form-group">
            {!! Form::open(['url' => route('offered_course.store') , 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form']) !!}
            @foreach ($mstr_term_year as $data)
            <input type="hidden" name="Department_Id" value="{{ $department }}">
            <input type="hidden" name="Term_Year_Id" value="{{ $term_year }}">
            <input type="hidden" name="Class_Prog_Id" value="{{ $class_program }}">
            <input type="hidden" name="curriculum" value="{{ $curriculum }}">
            {!! Form::label('', 'Tahun/Semester', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" min="1" value="{{ $data->Term_Year_Name }}" readonly class="form-control form-control-sm">
              @endforeach
            </div>
          </div>
          <div class="form-group">
            @foreach ($mstr_department as $data)
            {!! Form::label('', 'Program Studi', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" min="1" value="{{ $data->Department_Name }}" readonly class="form-control form-control-sm">
              @endforeach
            </div>
          </div>
          <div class="form-group">
            @foreach ($mstr_class_program as $data)
            {!! Form::label('', 'Nama Fakultas Eng', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" min="1" value="{{ $data->Class_Program_Name  }}" readonly class="form-control form-control-sm">
              @endforeach
            </div>
          </div>
          <!-- <div class="form-group">
            {!! Form::label('', 'Matakuliah', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select class="form-control form-control-sm" name="Course_Id" id="course">
                <option value=""></option>
                @foreach ( $mstr_course as $data )
                  <option  <?php if(old('Course_Id') == $data->Course_Id ){ echo "selected"; }elseif($course_id == $data->Course_Id ){ echo "selected"; } ?> value="{{ $data->Course_Id }}">({{ $data->Course_Code }})  {{ $data->Course_Name }}</option>
                @endforeach
              </select>
            </div>
          </div> -->
          <div class="form-group">
            {!! Form::label('', 'Kelas', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select id="classid" class="form-control form-control-sm" name="Class_Id" >
                <option value="">pilih kelas</option>
                @foreach ( $mstr_class as $data )
                  <option  <?php 
                  if($Class_Id != null){
                    if($Class_Id == $data->Class_Id ){
                      echo "selected"; 
                    } 
                  }
                     ?> value="{{ $data->Class_Id }}">{{ $data->Class_Name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Kapasitas', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Capacity" min="1" value="{{ old('capacity') }}"  class="form-control form-control-sm">
            </div>
          </div>
          <table id="example" class="table table-striped table-font-sm">
          <thead class="thead-default thead-green">
              <tr>
                  <!-- <th class="col-sm-1">No</th> -->
                  <th><input type="checkbox" id="all" /></th>
                  <th width="40%">Kode Matakuliah</th>
                  <th width="50%">Nama Matakuliah</th>
                  <th width="50%">Semester</th>
              </tr>
          </thead>
          <tbody>
            <?php
            $a = "1";
            foreach ($mstr_course as $data) {
            ?>
            <tr>
              <td><center><input type="checkbox" class="courseid" name="Course_Id" id="Course_Id" value="{{ $data->Course_Id }}" /></center></td>
              <td><center>{{ $data->Course_Code }}</center></td>
              <td>{{ $data->Course_Name }}</td>
              <td>{{ $data->Study_Level_Id }}</td>
            </tr>
            <?php
            $a++;
            }
            ?>
          </tbody>
        </table>
          <br><center><a class="btn btn-success btn-sm" id="insertdata"  href="javascript:">Tambah Data</a></center>

          {!! Form::close() !!}
          <br>
          <center>
          <div class="table-responsive col-md-10">
          </div>
        </center>
      </div>
    </div>
  </div>
<script type="text/javascript">
$(document).ready(function () {
  $(document).ready(function () {
      $('#all').click(function () {
          $('.courseid').prop("checked", this.checked);
      });
    });

  $("#classid").change(function () {
    var url = {!! json_encode(url('/')) !!};
    var id = $("#classid").val();
    var class_program = "<?php echo $class_program; ?>";
    var current_page = "<?php echo $current_page; ?>";
    var current_rowpage = "<?php echo $current_rowpage; ?>";
    var current_search = "<?php echo $current_search; ?>";
    var department = "<?php echo $department; ?>";
    var term_year = "<?php echo $term_year; ?>";  
    window.location = url+"/setting/offered_course/create?class_program="+ class_program + "&current_page=" + current_page + "&current_rowpage=" + current_rowpage + "&current_search=" + current_search + "&department=" + department + "&term_year=" + term_year + "&Class_Id="+id ;
  });


  $('#example thead tr').clone(true).appendTo( '#example thead' );
    $('#example thead tr:eq(1) th').each( function (i) {
        var title = $(this).text();
        $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
 
        $( 'input', this ).on( 'keyup change', function () {
            if ( table.column(i).search() !== this.value ) {
                table
                    .column(i)
                    .search( this.value )
                    .draw();
            }
        } );
    } );
  var table = $('#example').DataTable({
    paging : false,
    orderCellsTop: true
  });
});

    $("#insertdata").click(function(e){
      var department = $("[name='Department_Id']").val(),
          class_program = $("[name='Class_Prog_Id']").val(),
          term_year = $("[name='Term_Year_Id']").val(),
          class_id = $("[name='Class_Id']").val(),
          kapasitas = $("[name='Capacity']").val();
      var course_id = [];
          $("input[name='Course_Id']:checked").each(function() {
            course_id.push($(this).val());
          });
      if (course_id == "" || kapasitas=="") {
          swal('Perhatian', "field harus diisi", 'warning');
      } else {
          $.ajax({
              headers: {
                  "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
              },

              url:'{{route('offered_course.update_datacourse')}}',
              type: "POST",
              data: {
                    department : department,
                    class_program : class_program,
                    term_year : term_year,
                    class_id : class_id,
                    kapasitas : kapasitas,
                    course_id : course_id,
              },

              success: function (res) {
                console.log(res.message);
                swal({
                  title: res.message,
                    showCancelButton: false,
                    cancelButtonColor: '#d33',
                    confirmButtonText: "Oke",
                    closeOnConfirm: false,
                  },function (isConfirm) {
                      if (isConfirm) {
                              window.location.reload(true) // submitting the form when user press yes
                      }
                  });
              },
              error: function (xhr, ajaxOptions, thrownError) {
                  swal({
                          title: thrownError,
                          text: 'Error !! ' + xhr.status+7,
                          type: "error",
                          confirmButtonColor: "#02991a",
                          confirmButtonText: "Refresh Serkarang",
                          cancelButtonText: "Tidak, Batalkan!",
                          closeOnConfirm: false,
                      },
                      function (isConfirm) {
                          if (isConfirm) {
                                  window.location.reload(true) // submitting the form when user press yes
                          }
                      });
              }
          });
      }
  });

var classid = new SlimSelect({
select: '#classid'
})

classid.selected()

</script>
<link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/fixedheader/3.1.5/js/dataTables.fixedHeader.min.js"></script>
<!-- /.row -->

</section>

@endsection
