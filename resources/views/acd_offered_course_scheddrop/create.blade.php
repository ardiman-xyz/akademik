@extends('layouts._layout')
@section('pageTitle', 'Offered Course Sched')
@section('content')

<?php

foreach ($query_edit as $data_edit) {

?>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Edit Entry Jadwal Kuliah</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
        @if($from == 'oc')
            <a href="{{ url('setting/offered_course?term_year='.$term_year.'&class_program='.$class_program.'&department='.$department.'&curriculum='.$curriculum.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search.'&curriculum='.$curriculum.'&sched_session_group_id='.$sched_session_group_id) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
          @else
            <a href="{{ url('setting/offered_course_schedV2?class_program='.$class_program.'&term_year='.$term_year.'&department='.$department.'&curriculum='.$curriculum.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search.'&curriculum='.$curriculum.'&sched_session_group_id='.$sched_session_group_id) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
          @endif
          <!-- <a href="{{ url('setting/offered_course_schedV2?class_program='.$class_program.'&term_year='.$term_year.'&department='.$department.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search.'&sched_session_group_id='.$sched_session_group_id) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a> -->
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Edit Jadwal</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
          @if (count($errors) > 0)
            @foreach ( $errors->all() as $error )
              @if ($error=="Berhasil Menambah Jadwal Kuliah")
                <p class="alert alert-success">{{ $error }}</p>
              @else
                <p class="alert alert-danger">{{ $error }}</p>
              @endif
            @endforeach
          @endif
	<input type="hidden" name="term_year" id="$term_year" value="{{ $term_year }}">
          <div class="form-group">
            {!! Form::open(['url' => route('offered_course_schedV2.store') , 'method' => 'POST', 'class' => 'form']) !!}
            <div class="col-md-12">
              <input type="hidden" name="Offered_Course_id" value="{{ $data_edit->Offered_Course_id }}">
              <input type="hidden" name="curriculum" value="{{ $curriculum }}">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Kode Matakuliah', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Course_Code"  value="{{ $data_edit->Course_Code }}" readonly class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Nama Matakuliah', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Course_Name" value="{{ $data_edit->Course_Name }}" readonly class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Kelas', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Class_Id" min="1" value="{{ $data_edit->Class_Name }}" readonly class="form-control form-control-sm">
            </div>
          </div>

          @if($dosen2 == 0)
          <div class="form-group">
            {!! Form::label('', 'Dosen Pengampu', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="dosen" min="1" value="Dosen pengampu belum diatur" readonly class="form-control form-control-sm">
            </div>
          </div>
          @else
          <?php
          foreach($dosen as $dsn){
            ?>
          <div class="form-group">
            {!! Form::label('', 'Dosen Pengampu', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="dosen" min="1" value="{{ $dsn->First_Title }} {{ $dsn->Name }} {{ $dsn->Last_Title }}" readonly class="form-control form-control-sm">
            </div>
          </div>
          <?php
          }
           ?>
          @endif

        <div class="form-group">
            <div class="col-md-12">
             <!-- <input type="checkbox" onchange="Change(this);" name="" value=""> Jadwal Gabungan? -->
             </div>
          </div>

        <div id="full">
          <div class="form-group">
            {!! Form::label('', 'Grup Sesi', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="hidden" name="grubsesi_id" value="" class="grubsesi_id">
              <select id="grubsesi"  class="form-control form-control-sm" name="Sched_Session_Group_Id">
                <option value="">Pilih Grup Sesi</option>
                @foreach ( $select_session_group as $data )
                  <option value="{{ $data->Sched_Session_Group_Id }}">{{ $data->Sched_Session_Group_Name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-md-1">Type</label>
            <div class="col-md-12">
              <select id="types" class="form-control form-control-sm" name="sched_type_id" >
                <option value="">Pilih Type</option>
                @foreach ( $select_sched_type as $data )
                  <option <?php if($Sched_Type_Id == $data->Sched_Type_Id){ echo "selected"; } ?>  value="{{ $data->Sched_Type_Id }}">{{ $data->Sched_Type_Name }}</option>
                @endforeach
              </select>
              <input type="hidden" name="type_id" value="" class="type_id">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Ruang', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select  id="Room_Id" class="form-control form-control-sm" name="Room_Id">
                <option value="">Pilih Ruang</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Sesi', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select  id="select2" class="form-control form-control-sm" name="Sched_Session_Id[]" multiple>
              </select>
            </div>
          </div>
        </div>

        <div id="class_gabungan">
          <div class="form-group">
              {!! Form::label('', 'jadwal', ['class' => 'col-md-4 form-label']) !!}
              <div class="col-md-12">
                <input type="hidden" name="jadwalgabungan_id" value="" class="jadwalgabungan_id">
                <select id="jadwalgabungan_id"  class="form-control form-control-sm" name="jadwalgabungan_id">
                  <option value="">Pilih Grup Sesi</option>
                  @foreach ( $cek_jadwal as $data )
                    <option value="{{ $data->ocsi }}">{{ $data->Course_Name }} || {{ $data->Class_Name }} || {{ $data->Department_Name }} ||  {{ $data->room }}|| {{ $data->jadwal }} </option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
          <br><center><button type="submit" class="btn btn-primary btn-flat">Tambah</button></center>
          {!! Form::close() !!}
          <br>
          <center>
          <div class="table-responsive col-md-10">
          <table class="table table-striped table-font-sm">
            <thead class="thead-default thead-green">
                <tr>
                    <!-- <th class="col-sm-1">No</th> -->
                    <th width="45%">Sesi</th>
                    <th width="45%">Ruang</th>
                    <th width="10%"><center><i class="fa fa-gear"></i></center></th>
                </tr>
            </thead>
            <tbody>
              <?php
              $a = "1";
              foreach ($query as $data) {

                ?>
                <tr>
                    <td><center>{{ $data->Description }}</center></td>
                    <td><center>{{ $data->Room_Name }}</center></td>
                    <td>
                        <center>
                        {!! Form::open(['url' => route('offered_course_schedV2.destroy', $data->Offered_Course_Sched_id) , 'method' => 'delete', 'role' => 'form']) !!}
                        {!! Form::button('Hapus', ['type'=>'submit','class'=>'btn btn-danger btn-sm hapus','data-id'=>$data->Offered_Course_Sched_id]) !!}
                        {!! Form::close() !!}
                        </center>
                    </td>
                </tr>
                <?php
                $a++;
              }
              ?>
            </tbody>
          </table>
          </div>
        </center>
      </div>
    </div>
  </div>

  <script>

  $("#full").show();
  $("#class_gabungan").hide();
  function Change(checkbox) {
      var id = $(checkbox).val();
      if(checkbox.checked == true){
        $("#full").hide();
        $("#class_gabungan").show();
      }else {
        $("#full").show();
        $("#class_gabungan").hide();
      }
  }

  var select = new SlimSelect({
  select: '#Room_Id'
  })
  select.selected()

  var select2 = new SlimSelect({
  select: '#select2'
  })
  select2.selected()

  $(function () {
    $("select[name='sched_type_id']").change(function (e) {
      var grubsesi_id = $("[name='grubsesi_id']").val()
      var term_year = "<?php echo $request->term_year ?>"
      $.ajax({
        url: "{{ url('') }}/setting/offered_course_schedV2/findroom/findroomwithsched/" + grubsesi_id +"?term_year=" + term_year,
        type: "GET",
        dataType:"json",
        success: function (res) {
          console.log(res);
          var el = "<option value=''>Pilih Ruang</option>";
            $.each(res, function (index, val) {
              el += "<option value='" + val.Room_Id + "'>" + val.Room_Name + "</option>";
            });

            if (res.length == 0) {
                $("[name='Room_Id']").html(" ");
            } else {
              $("[name='Room_Id']").html(el);
            }
        }
      })
    });

    $("select[name='Room_Id']").change(function (e) {
      var grubsesi_id = $("[name='grubsesi_id']").val(),
          room_id = $(this).val(),
          sched_type_id = $("[name='type_id']").val();
      	  term_year = $("[name='term_year']").val();
          offered_course_id = $("[name='Offered_Course_id']").val();
          curriculum = $("[name='curriculum']").val();

      $.ajax({
        url: "{{ url('') }}/setting/offered_course_schedV2/findjadwal/findjadwal/" + grubsesi_id + "/" + sched_type_id + "/" + room_id + "/" + offered_course_id,
        type: "GET",
	      data: {
                  term_year:term_year,
              },
        dataType:"json",
        success: function (res) {
          var el;
            $.each(res, function (index, val) {
              el += "<option value='" + val.Sched_Session_Id + "'>" + val.Description + "</option>";
            });

            if (res.length == 0) {
                $("[name='Sched_Session_Id[]']").html(" ");
            } else {
              $("[name='Sched_Session_Id[]']").html(el);
            }
        }
      })
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
                  url: "{{ url('') }}/setting/offered_course_schedV2/" + id,
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



    $("#grubsesi").on('change',function () {
      var Sched_Session_Group_Id=$(this).val();
      var a=$(this).parent();
      var op="";
      var url = {!! json_encode(url('/')) !!};
      $.ajax({
        type:'get',
        url: url + '/setting/offered_course_schedV2/findgrubsesi/findgrubsesi',
        data:{'Sched_Session_Group_Id':Sched_Session_Group_Id},
        dataType:'json',//return data will be json
        success:function(data){
            console.log(data);
           $(".grubsesi_id").val(data.Sched_Session_Group_Id);
           $("#types").val("");
           $("[name='Room_Id']").val("");
        },
        error:function(){

        }
      });
    });

    $("#types").on('change',function () {
      var Sched_Type_Id=$(this).val();
      var a=$(this).parent();
      var op="";
      var url = {!! json_encode(url('/')) !!};
      $.ajax({
        type:'get',
        url: url + '/setting/offered_course_schedV2/findtype/findtype',
        data:{'Sched_Type_Id':Sched_Type_Id},
        dataType:'json',//return data will be json
        success:function(data){
            console.log(data);
           $(".type_id").val(data.Sched_Type_Id);
           $("#Room_Id").val("");
        },
        error:function(){

        }
      });
    });

    $("#Room_Id").on('change',function () {
      var Room_Id=$(this).val();
      var a=$(this).parent();
      var op="";
      var url = {!! json_encode(url('/')) !!};
      $.ajax({
        type:'get',
        url: url + '/setting/offered_course_schedV2/findroom/findroom',
        data:{'Room_Id':Room_Id},
        dataType:'json',//return data will be json
        success:function(data){
            console.log(data);
           $(".room_id").val(data.Room_Id);
        },
        error:function(){

        }
      });
    });
          </script>

<!-- /.row -->

</section>

<?php
}
?>


@endsection
