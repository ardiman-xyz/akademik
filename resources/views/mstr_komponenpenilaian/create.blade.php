@extends('layouts._layout')
@section('pageTitle', 'Grade Letter')
@section('content')
<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Komponen Penilaian</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <!-- <a href="{{ url('parameter/komponen_penilaian') }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a> -->
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Index</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">

          @if (count($errors) > 0)
            @foreach ( $errors->all() as $error )
              @if ($error=="Berhasil Menambah Komponen Penilaian" || $error=="Berhasil Menyimpan Perubahan")
                <p class="alert alert-success">{{ $error }}</p>
              @else
                <p class="alert alert-danger">{{ $error }}</p>
              @endif
            @endforeach
          @endif
          {!! Form::open(['url' => route('komponen_penilaian.store') , 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form']) !!}
          <!-- <div class="row" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            <div  class="row col-md-7">
              <label class="col-md-3">Semester </label>
              <input type="text" name="Term_Year_Id" hidden value="{{$term_year}}" class="form-control form-control-sm col-md-7 col-sm-7">
              <select class="form-control form-control-sm col-md-7 col-sm-7" name="Term_Year_Id" id="term">
                <option value="0">Pilih Semester</option>
                @foreach ( $select_term_year as $data )
                  <option <?php if($term_year == $data->Term_Year_Id){ echo "selected"; } ?> value="{{ $data->Term_Year_Id }}">{{ $data->Term_Year_Name }}</option>
                @endforeach
              </select>
            </div>
          </div><br> -->
          <div class="row" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            <div  class="row col-md-7">
              <label class="col-md-3">Angkatan </label>
              <input type="text" name="Entry_Year_Id" hidden value="{{$entry_year}}" class="form-control form-control-sm col-md-7 col-sm-7">
              <select class="form-control form-control-sm col-md-7 col-sm-7" name="Entry_Year_Id" id="entry_year">
                <option value="0">Pilih Angkatan</option>
                @foreach ( $select_entry_year as $data )
                  <option <?php if($entry_year == $data->Entry_Year_Id){ echo "selected"; } ?> value="{{ $data->Entry_Year_Id }}">{{ $data->Entry_Year_Id }}</option>
                @endforeach
              </select>
            </div>
          </div><br>
          <!-- <div class="row" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            <div  class="row col-md-7">
              <label class="col-md-3">Prodi </label>
              <select class="form-control form-control-sm col-md-7 col-sm-7" name="Department_Id" id="dept">
                <option value="0">Pilih Prodi</option>
                @foreach ( $select_mstr_department as $data )
                  <option <?php if($department == $data->Department_Id){ echo "selected"; } ?> value="{{ $data->Department_Id }}">{{ $data->Department_Name }}</option>
                @endforeach
              </select>
              <input type="text" name="department" hidden value="{{$department}}" readonly class="form-control form-control-sm col-md-7 col-sm-7">
            </div>
          </div><br> -->
          <div class="row" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            <div  class="row col-md-7">
              <label class="col-md-3">Jenis Matakuliah </label>
              <select class="form-control form-control-sm col-md-7 col-sm-7" name="Course_type" id="c_type">
                <option value="0">Pilih Jenis Matakuliah</option>
                @foreach ( $select_course_type as $data )
                  <option <?php if($course_type == $data->Course_Type_Id){ echo "selected"; } ?> value="{{ $data->Course_Type_Id }}">{{ $data->Course_Type_Name }}</option>
                @endforeach
              </select>
              <input type="text" name="course_type" hidden value="{{$course_type}}" readonly class="form-control form-control-sm col-md-7 col-sm-7">
            </div>
          </div><br>
          <div class="row" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            <div  class="row col-md-7">
              <!-- <label class="col-md-3">Kopi data </label> -->
              @if($term_year==0 || $course_type ==null || $department==0)
              @else
                <a href="{{ url('/parameter/komponen_penilaian/create/copydata?department='.$department.'&term_year='.$term_year.'&course_type='.$course_type) }}" class="btn btn-success btn-sm">Copy data &nbsp;<i class="fa fa-plus"></i></a>
              @endif
              <input type="text" name="course_type" hidden value="{{$course_type}}" readonly class="form-control form-control-sm col-md-7 col-sm-7">
            </div>
          </div><br>
          <div class="form-group">
            <div class="col-md-12">
             <input type="checkbox" onchange="Change(this);" name="" value=""> Tambah Komponen Penilaian?
             </div>
          </div>          
          <div id="n_k" class="row" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            <div  class="row col-md-7">
              <label class="col-md-3">Nama Komponen Nilai </label>
              <input type="text" name="Item_Name" class="form-control form-control-sm col-md-7 col-sm-7">
            </div>
          </div><br>
          <div id="b_k" class="row" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            <div  class="row col-md-7">
              <label class="col-md-3">Bobot </label>
              <input type="text" name="Bobot" class="form-control form-control-sm col-md-7 col-sm-7">
            </div>
          </div><br>
          <div id="o_k" class="row" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            <div  class="row col-md-7">
              <label class="col-md-3">Order_Id </label>
              <input type="text" name="order_id" class="form-control form-control-sm col-md-7 col-sm-7">
            </div>
          </div><br>
          <center><button type="submit" id="tbh" class="btn btn-primary btn-flat">Tambah</button></center>
          {!! Form::close() !!}

          <br>
          <center>
          <div class="table-responsive col-md-10">
          <table class="table table-striped table-font-sm">
            <thead class="thead-default thead-green">
                <tr>
                    <!-- <th class="col-sm-1">No</th> -->
                    <th width="7%">No</th>
                    <th width="60%">Nama Kompenen</th>
                    <th width="10%">Bobot %</th>
                    <th width="10%">Order</th>
                    <th width="20%"><center><i class="fa fa-gear"></i></center></th>
                </tr>
            </thead>
            <tbody>
              <?php
              $a = "1";
              foreach ($acd_student_khs_item_bobot as $data) {

                ?>
                <tr>
                    <!-- <th></th> -->
                    <td><center>{{ $a }}</center></td>
                    <td><center>{{ $data->Item_Name }}</center></td>
                    <td><center>{{ $data->Bobot }} %</center></td>
                    <td><center>{{ $data->Order_Id }}</center></td>
                    <td>
                        <center>
                        {!! Form::open(['url' => route('komponen_penilaian.destroy', $data->Student_Khs_Item_Bobot_Id) , 'method' => 'delete', 'role' => 'form']) !!}
                        <a data-params="{{ $data->Student_Khs_Item_Bobot_Id }}"
                            data-department_id="{{ $data->Department_Id }}" 
                            data-term_year_id="{{ $data->Term_Year_Id }}"
                            data-entry_year_id="{{ $data->Entry_Year_Id }}"
                            data-item_name="{{ $data->Item_Name }}"
                            data-bobot="{{ $data->Bobot }}"
                            data-order="{{ $data->Order_Id }}"
                            class="btn btn-success btn-sm" 
                            id="edit"  
                            href="javascript:">Edit</a>
                        {!! Form::button('Hapus', ['class'=>'btn btn-danger btn-sm hapus', 'data-id'=>$data->Student_Khs_Item_Bobot_Id,'data-entry'=>$data->Entry_Year_Id]) !!}
                        {!! Form::close() !!}
                        </center>
                    </td> 
                </tr>
                <?php
                $a++;
              }
              ?>
              <tr>
                    <!-- <th></th> -->
                    <td bgcolor="#5F9EA0" colspan="3"><center><b>Total</center></td>
                    <td bgcolor="#5F9EA0"><center><b>{{ $total }} %</center></td>
                    <td bgcolor="#5F9EA0"><center><a href="{{ url('/parameter/komponen_penilaian/create/refresh_data?department='.$department.'&entry_year='.$entry_year.'&course_type='.$course_type) }}" class="btn btn-success btn-sm">Refresh Penilaian &nbsp;<i class="fa fa-refresh"></i></a></center></td>
                </tr>
            </tbody>
          </table>

            <div id="editform" class="w3-modal">
              <div class="w3-modal-content w3-animate-zoom w3-card-4">
              <header class="w3-container w3-teal"> 
                  <span onclick="location.reload()" 
                  class="w3-button w3-display-topright">&times;</span>
                  <h4>Edit</h4>
              </header>
              <div class="w3-container">
              </br>
              <form id="frm-upload" action="{{ route('komponen_penilaian.update') }}" method="POST" enctype="multipart/form-data">
                  <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
                    <div  class="row col-md-10">
                      <label class="col-md-4">Nama Komponen</label>
                      <input type="text" name="item_namee" id="item_namee"  value="" class="form-control form-control-sm col-md-7">
                      <input type="text" name="item_namee_old" id="item_namee_old" hidden value="" class="form-control form-control-sm col-md-7">

                      <input type="text" hidden name="jm" value="{{$request->course_type}}" class="form-control form-control-sm col-md-7 col-sm-7">
                      <input type="text" hidden name="entry" value="{{$request->entry_year}}"  class="form-control form-control-sm col-md-7 col-sm-7">
                    </div>
                    <div  class="row col-md-10">
                      <label class="col-md-4">Bobot</label>
                      <input type="text" name="bobote" id="bobote"  value="" class="form-control form-control-sm col-md-7">
                    </div>
                    <div  class="row col-md-10">
                      <label class="col-md-4">Order_Id</label>
                      <input type="text" name="order_id_edit" id="order_id_edit"  value="" class="form-control form-control-sm col-md-7">
                    </div>
                    <input type="text" name="id_nya" hidden >
                    <input type="text" name="term_yeare" hidden >
                    <input type="text" name="departmente" hidden >
                    <div  class="row col-md-10">
                      <label class="col-md-4"></label>
                      @csrf
                      <input type="submit"  value="Simpan" class="btn-success btn-sm form-control form-control-sm col-md-7">        
                    </div>
                    </form>
                    </div>
                  </br>

              </div>
              </div>
          </div>
          </div>
        </center>
      </div>
    </div>
  </div>
  <style>
  @media (min-width:993px){.w3-modal-content{width:50%}.w3-hide-large{display:none!important}.w3-sidebar.w3-collapse{display:block!important}}
</style>
<script type="text/javascript">
$(document).ready(function () {
  $("#n_k").hide();
  $("#b_k").hide(); 
  $("#o_k").hide(); 
  $("#tbh").hide(); 

  $("#entry_year").change(function () {
    var url = {!! json_encode(url('/')) !!};
    var id = $("#entry_year").val();
    var department = "<?php echo $department; ?>"; 
    window.location = url+"/parameter/komponen_penilaian/create?entry_year="+ id + "&department=" +department;
  });
  $("#dept").change(function () {
    var url = {!! json_encode(url('/')) !!};
    var id = $("#dept").val();
    var entry_year = "<?php echo $entry_year; ?>";   
    window.location = url+"/parameter/komponen_penilaian/create?entry_year="+ entry_year + "&department=" + id;
  });
  $("#c_type").change(function () {
    var url = {!! json_encode(url('/')) !!};
    var id = $("#c_type").val();
    var entry_year = "<?php echo $entry_year; ?>";   
    var department = "<?php echo $department; ?>"; 
    window.location = url+"/parameter/komponen_penilaian/create?entry_year="+ entry_year + "&department=" + department + "&course_type=" + id;
  });
});

$(document).on('click', '#edit', function (e) {
    document.getElementById("editform").style.display = "block";
    var id = $(this).data('params'),
        department_id = $(this).data('department_id'),
        term_year_id = $(this).data('term_year_id'),
        entry_year_id = $(this).data('entry_year_id'),
        item_name = $(this).data('item_name');
        bobot = $(this).data('bobot');
        order_id_edit = $(this).data('order_id_edit');

        $("[name='item_namee']").val(item_name);
        $("[name='item_namee_old']").val(item_name);
        $("[name='bobote']").val(bobot);
        $("[name='order_id_edit']").val(order_id_edit);
        $("[name='id_nya']").val(id);
        $("[name='entry_year']").val(term_year_id);
        $("[name='departmente']").val(department_id);
});


function Change(checkbox) {
        var id = $(checkbox).val();
        if(checkbox.checked == true){
          $("#n_k").show();
          $("#tbh").show();
          $("#b_k").show();
          $("#o_k").show();
        }else {
          $("#n_k").hide();
          $("#tbh").hide();
          $("#b_k").hide();
          $("#o_k").hide();
        }
    }

$(document).on('click', '.hapus', function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    var entry = $(this).data('entry');

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
                url: "{{ url('') }}/parameter/komponen_penilaian/destroy_bobot/" + id +"?entry_year_id="+entry,
                type: "DELETE",
                dataType: "json",
                data: {
                  "_token": "{{ csrf_token() }}",
                  
                },
                success: function (data) {
                  swal2();
                },
                error: function(){
                  swal2();
                }
            });
            // $("#hapus").submit();
          }
        });
});

function swal1() {
    swal({
      title: 'kesalahan / Data masih digunakan',
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


<!-- /.row -->

</section>


@endsection
