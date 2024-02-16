@extends('layouts._layout')
@section('pageTitle', 'Offered Course Exam')
@section('content')

<section class="content">
    <div class="container-fluid-title">
      <div class="title-laporan">
        <h3 class="text-white">Jadwal dan Peserta Ujian</h3>
      </div>
    </div>
      <div class="container">
        <div class="panel panel-default bootstrap-admin-no-table-panel">
          <div class="panel-heading-green">
            <div class="pull-right tombol-gandeng dua">
              <?php $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; ?>
              @if(in_array('offered_course_exam-CanAddPeserta', $acc))<a href="{{ url('setting/offered_course_exam/peserta/create?id='.$query->Offered_Course_Exam_Id.'&class_program='.$query->Class_Prog_Id.'&offered_course_id='.$query->Offered_Course_Id.'&term_year='.$query->Term_Year_Id.'&department='.$query->Department_Id.'&page='.$page.'&rowpage='.$rowpage.'&currentsearch='.$currentsearch.'&currentpage='.$currentpage.'&currentrowpage='.$currentrowpage) }}" class="btn btn-success btn-sm">Tambah data &nbsp;<i class="fa fa-plus"></i></a>@endif
              <a href="{{ url('setting/offered_course_exam/'.$query->Offered_Course_id.'?class_program='.$query->Class_Prog_Id.'&term_year='.$query->Term_Year_Id.'&department='.$query->Department_Id.'&page='.$currentpage.'&rowpage='.$currentrowpage.'&search='.$currentsearch) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
              {{-- {{ url('setting/offered_course_exam/'.$data->Offered_Course_id.'?class_program='.$class_program.'&department='.$department.'&term_year='.$term_year.'&currentpage='.$page.'&currentrowpage='.$rowpage.'&currentsearch='.$search) }} --}}
            </div>
            <div class="bootstrap-admin-box-title right text-white">
              <b>Daftar Peserta</b>
            </div>
          </div>
      <br>
      <br><br>
      <!-- <div class="col-sm-6"> -->
      <div class="row">
        <div class="col-sm-6">
        <div >
            <label class="col-sm-5">Kode Matakuliah </label><label class="col-sm-1">:</label>
            <label  class="col-sm-5">{{ $query->Course_Code }}</label>
        </div>
        <div>
            <label  class="col-sm-5">Nama Matakuliah </label><label class="col-sm-1">:</label>
            <label  class="col-sm-5">{{ $query->Course_Name }}</label>
        </div>
        <div>
            <label  class="col-sm-5">Kelas </label><label class="col-sm-1">:</label>
            <label  class="col-sm-5">{{ $query->Class_Name }}</label>
        </div>
        <div>
            <label  class="col-sm-5">Pengawas 1 </label><label class="col-sm-1">:</label>
            <label  class="col-sm-5">{{ $query->Pengawas_1F }} {{ $query->Pengawas_1 }} {{ $query->Pengawas_1L }}</label>
        </div>
        <div>
            <label  class="col-sm-5">Pengawas 2 </label><label class="col-sm-1">:</label>
            <label  class="col-sm-5">{{ $query->Pengawas_2F }} {{ $query->Pengawas_2 }} {{ $query->Pengawas_2L }}</label>
        </div>
      </div>

      <div class="col-sm-6">
        <div>
          <label  class="col-sm-5">Kelompok </label><label class="col-sm-1">:</label>
          <label  class="col-sm-5">{{ $query->Room_Number }}</label>
        </div>
        <div>
          <label  class="col-sm-5">Ruang </label><label class="col-sm-1">:</label>
          <label  class="col-sm-5">{{ $query->Room_Name }}</label>
        </div>
        <div>
          <label  class="col-sm-5">Kapasitas Peserta Ujian </label><label class="col-sm-1">:</label>
          <label  class="col-sm-5">{{ $room->Capacity_Exam }}</label>
        </div>
        <div>
          <label  class="col-sm-5">Waktu Mulai </label><label class="col-sm-1">:</label>
          <label  class="col-sm-5">{{ $query->Exam_Start_Date }}</label>
        </div>
        <!-- <div>
          <label  class="col-sm-5">Waktu Selesai </label><label class="col-sm-1">:</label>
          <label  class="col-sm-5">{{ $query->Exam_End_Date }}</label>
        </div> -->
      <!-- </div> -->
      </div>
    </div>
    <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        @if (count($errors) > 0)
          @foreach ( $errors->all() as $error )
            <p class="alert alert-danger">{{ $error }}</p>
          @endforeach
        @endif
        <br>
        <div class="table-responsive" >
        <table class="table table-striped table-font-sm" >
          <thead class="thead-default thead-green">
              <tr>
                  <!-- <th class="col-sm-1">No</th> -->
                  <th width="10%">No</th>
                  <th width="10%"><input type="checkbox" onchange="Change(this);" name="" value="">Kehadiran</th>
                  <th width="35%">NIM</th>
                  <th width="40%">Nama Mahasiswa</th>
                  @if(in_array('offered_course_exam-CanHapusPeserta', $acc))
                  <th width="15%"><center><i class="fa fa-gear"></i></center></th>
                  @endif
              </tr>
          </thead>
          <tbody>
            <?php
            $a = "1";
            foreach ($member as $data) {
              // $prodi = DB::table('prodi')->join('prodi_user','prodi_user.id_prodi','=','prodi.id_prodi')->join('users','users.id','=','prodi_user.id_user')->where('users.id',$data->id)->get();
              // $prod = auth()->user()->Prodi();
              // if (count($prod)==0) {
              ?>
              <tr>
                  <!-- <th></th> -->
                  <td>{{ $a }}</td>
                  <td><center><input type="checkbox" name="Student_Id" value="{{ $data->Student_Id }}" class="checkbox" <?php if($data->Is_Presence == true) { echo "checked"; } ?>></td>
                  <td><center>{{ $data->Nim }}</center></td>
                  <td>{{ $data->Full_Name }}</td>
                  @if(in_array('offered_course_exam-CanHapusPeserta', $acc))
                  <td>
                    <center>
                      {!! Form::open(['url' => route('offered_course_exam.destroy_peserta', $data->Offered_Course_Exam_Member) , 'method' => 'delete', 'role' => 'form']) !!}
                      {!! Form::button('Hapus', ['type'=>'submit','class'=>'btn btn-danger btn-sm hapus','data-id'=>$data->Offered_Course_Exam_Member]) !!}
                      {!! Form::close() !!}
                    </center>
                  </td>
                  @endif
              </tr>
              <?php
              $a++;
            }
            ?>
            <tr>
                <td></td>
                <td> <a class="btn btn-success btn-sm" id="insertpeserta"  href="javascript:">Simpan</a></td>
                <td><center></center></td>
                <td></td>
                <td>
                </td>
            </tr>
          </tbody>
        </table>
        </div>
        <?php
        // echo $member->render('vendor.pagination.bootstrap-4');
        ?>
      </div>
    </div>
  </div>
   <input type="text" name="ocei" value="{{$query->Offered_Course_Exam_Id}}" hidden >
  
  <script>
function Change(checkbox) {
    var id = $(checkbox).val();
    if(checkbox.checked == true){
      list = document.getElementsByClassName("checkbox"+id);
      for (index = 0; index < list.length; ++index) {
        // list[index].setAttribute("disabled","disabled");
        list[index].checked = true;
        // list[index].setAttribute("checked","checked");
      }
    }else {
      list = document.getElementsByClassName("checkbox"+id);
      for (index = 0; index < list.length; ++index) {
        // list[index].removeAttribute("disabled");
        list[index].checked = false;
        // list[index].removeAttribute("checked");
      }
    }
}

$("#insertpeserta").click(function(e){
      var ocei = $("[name='ocei']").val();
      var Student_Id = [];
          $("input[name='Student_Id']:checked").each(function() {
            Student_Id.push($(this).val());
          });
      if (ocei == "") {
          swal('Perhatian', "field harus diisi atau Belum cek nilai", 'warning');
      } else {
          $.ajax({
              headers: {
                  "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
              },

              url:'{{route('offered_course_exam.store_presence')}}',
              type: "POST",
              data: {
                    ocei :ocei,
                    Student_Id : Student_Id,
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
                          text: 'Error !! ' + xhr.status,
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
        console.log(id);
							$.ajax({
									url: "{{ url('') }}/setting/offered_course_exam/destroy_peserta/" + id,
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
