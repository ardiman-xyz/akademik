@extends('layouts._layout')
@section('pageTitle', 'Student')
@section('content')

<?php
function tanggal_indo($tanggal, $cetak_hari = false)
{
	$hari = array ( 1 =>    'Senin',
				'Selasa',
				'Rabu',
				'Kamis',
				'Jumat',
				'Sabtu',
				'Minggu'
			);

	$bulan = array (1 =>   'Januari',
				'Februari',
				'Maret',
				'April',
				'Mei',
				'Juni',
				'Juli',
				'Agustus',
				'September',
				'Oktober',
				'November',
				'Desember'
			);
	$split 	  = explode('-', $tanggal);
	$tgl_indo = $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];

	if ($cetak_hari) {
		$num = date('N', strtotime($tanggal));
		return $hari[$num] . ', ' . $tgl_indo;
	}
	return $tgl_indo;
}

$access = auth()->user()->akses();
		  $acc = $access;
?>
<style>
* {
  box-sizing: border-box;
}

.row {
  display: flex;
}

/* Create two equal columns that sits next to each other */
.column {
  flex: 50%;
  padding: 10px;
}

.card {
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
  max-width: 300px;
  margin: auto;
  text-align: center;
  font-family: arial;
}

.title {
  color: grey;
  font-size: 18px;
}

button:hover, a:hover {
  opacity: 0.7;
}

img {
  display: block;
  margin-left: auto;
  margin-right: auto;
  padding-top: 10px;
}

.text {
  text-align:center;
}

.phg {
    width: 0%;
    background-color: #1a3b7f;
    padding: 5px 15px 15px 15px;
    color: white;
    margin-left: -15px;
    margin-right: -15px;
}
</style>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Kartu Ujian Mahasiswa</h3>
    </div>
	</div>
	<div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
					<b>Kartu Ujian Mahasiswa</b>
				</div>
			</div>

		{!! Form::open(['url' => route('kartuujian.index') , 'method' => 'GET', 'name' => 'form', 'role' => 'form', 'style' => 'padding-top:5px;padding-left:15px;padding-right:15px;']) !!}

			<br>
			<div class="row text-green all" style="padding-top:5px;padding-left:15px;padding-right:15px;">
				<div class="row col-md-8">
					<label class="col-md-3" >Program Studi</label>
					<select class="form-control form-control-sm col-md-4" name="department" id="department" onchange="document.form.submit();">
					<option value="0">Pilih Program Studi</option>
					@foreach ( $select_department as $data )
						<option <?php if($department == $data->Department_Id){ echo "selected"; } ?> value="{{ $data->Department_Id }}">{{ $data->Department_Name }}</option>
					@endforeach
					</select>&nbsp
				</div><br>
			</div>

			<div class="row  text-green all"  style="padding-top:5px;padding-left:15px;padding-right:15px;">
			<div class="row col-md-8">
				<label class="col-md-3" >Angkatan</label>
				<select class="form-control form-control-sm col-md-4" name="entry_year" id="entry_year" onchange="document.form.submit();">
				<option value="0">Pilih Th Angkatan</option>
				@foreach ( $select_entry_year as $data )
					<option <?php if($entry_year == $data->Entry_Year_Id){ echo "selected"; } ?>  value="{{ $data->Entry_Year_Id }}">{{ $data->Entry_Year_Code   }}</option>
				@endforeach
				</select>&nbsp
			</div><br>
			</div>

			<div class="row  text-green all" style="padding-top:5px;padding-left:15px;padding-right:15px;">
			<div class="row col-md-8">
				<label class="col-md-3" >Cetak Dari</label>
				<select id="select"  class="form-control form-control-sm col-md-4" name="nimawal" onchange="document.form.submit();" >
				<option value="0">Pilih Nim/Nama</option>
				@foreach($select_nim as $ni)
					<option <?php if($nimawal == $ni->Nim){ echo "selected"; } ?> value="{{ $ni->Nim }}">{{$ni->Nim}}  {{ $ni->Full_Name }}</option>
				@endforeach
				</select>

				<label class="col-md-1" >s/d</label>
				<select id="select2"  class="form-control form-control-sm col-md-4" name="nimakhir" onchange="document.form.submit();" >
				<option value="0">Pilih Nim/Nama</option>
				@foreach($select_nim as $ni)
					<option <?php if($nimakhir == $ni->Nim){ echo "selected"; } ?> value="{{ $ni->Nim }}">{{$ni->Nim}}  {{ $ni->Full_Name }}</option>
				@endforeach
				</select>
			</div><br>
			</div>

			<div class="row text-green all" style="padding-top:5px;padding-left:15px;padding-right:15px;">
		        <div  class="row col-md-8">
		        <label class="col-md-3">Tahun Akademik</label>
		        <select class="form-control form-control-sm col-md-4" id="term_year" name="term_year">
					<option value="0">Pilih Th Akademik</option>
					@foreach ( $select_term_year as $data )
						<option value="{{ $data->Term_Year_Id }}">{{ $data->Term_Year_Name }}</option>
					@endforeach
				</select>
		        </div>
		    </div>
			<div class="row text-green all" style="padding-top:5px;padding-left:15px;padding-right:15px;">
		        <div  class="row col-md-8">
		        <label class="col-md-3">Jenis Ujian</label>
		        <select class="form-control form-control-sm col-md-4" id="exam_type" name="exam_type">
					<option value="0">Pilih Jenis Ujian</option>
					@foreach ( $select_exam_type as $data )
						<option value="{{ $data->Exam_Type_Id }}">{{ $data->Exam_Type_Code }}</option>
					@endforeach
				</select>
		        </div><br><br>
		    </div>
		<script type="text/javascript">
          var select = new SlimSelect({
            select: '#select'
          })
          select.selected()

          var select2 = new SlimSelect({
            select: '#select2'
          })
          select2.selected()
        </script>
		{!! Form::close() !!}

		<div class="form-group">
            <div class="col-md-12">
             <input type="checkbox" id="cekbok" onchange="Change(this);" name="" value=""> Cetak Per Mahasiswa?
             </div>
          </div>
			<br>

			<div class="form-group" id="per_mahasiswa">
			<div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
		        <div  class="row col-md-7">
		        <label class="col-md-3">NIM :</label>
		        <input type="text" name="search" id="search" class="form-control form-control-sm col-md-7 col-sm-7" value="{{ $search }}" placeholder="Search">
		        <!-- <input type="submit" name="" class="btn btn-primary btn-sm col-md-2 col-sm-2" value="Cari"> -->
		        </div>
		    </div><br>
			<div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
		        <div  class="row col-md-7">
		        <label class="col-md-3">Tahun Akademik</label>
		        <select class="form-control form-control-sm col-md-3" id="term_year2" name="term_year2">
					<option value="0">Pilih Th Akademik</option>
					@foreach ( $select_term_year as $data )
						<option value="{{ $data->Term_Year_Id }}">{{ $data->Term_Year_Name }}</option>
					@endforeach
				</select>
		        </div>
		    </div><br>
			<div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
		        <div  class="row col-md-7">
		        <label class="col-md-3">Jenis Ujian</label>
		        <select class="form-control form-control-sm col-md-3" id="exam_type2" name="exam_type2">
					<option value="0">Pilih Jenis Ujian</option>
					@foreach ( $select_exam_type as $data )
						<option value="{{ $data->Exam_Type_Id }}">{{ $data->Exam_Type_Code }}</option>
					@endforeach
				</select>
		        </div><br>
		    </div>

			<div class="row">
				<div class="column">
					<div class="card">
					<div id="image"></div>
					<!-- <img width="151px" height="226px" src="<?php echo env('APP_URL')?>{{ 'img/noimage.png' }}" alt=""> -->
					</div>
				</div>
				<div class="column">
					<div class="row  text-green"  style="padding-top:5px;padding-left:15px;padding-right:15px;">
						<div class="row col-md-12">
							<label class="col-md-4" >NIM :</label>
							<input type="text" id="nim" name="nim" readonly value="" class="form-control form-control-sm col-md-7">
						</div>
					</div>
					<div class="row  text-green"  style="padding-top:5px;padding-left:15px;padding-right:15px;">
						<div class="row col-md-12">
							<label class="col-md-4" >Name :</label>
							<input type="text" id="name" name="name" readonly value="" class="form-control form-control-sm col-md-7">
						</div>
					</div>
					<div class="row  text-green"  style="padding-top:5px;padding-left:15px;padding-right:15px;">
						<div class="row col-md-12">
							<label class="col-md-4" >Prodi :</label>
							<input type="text" id="prodi" name="prodi" readonly value="" class="form-control form-control-sm col-md-7">
						</div>
					</div>
					<div class="row  text-green"  style="padding-top:5px;padding-left:15px;padding-right:15px;">
						<div class="row col-md-12">
							<input type="text" id="entry_year" hidden name="entry_year" value="" class="form-control form-control-sm col-md-7">
						</div>
					</div>
				</div>
			</div>
		  </div>

		  	<input type="text" id="cek_cekbok" hidden name="cek_cekbok" value="" class="form-control form-control-sm col-md-7">
			<div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
		        <div  class="row col-md-7">
		        <label class="col-md-3"></label>
				<a href="#" class="btn btn-primary btn-sm export" id='export' style="font-size:medium;margin-right:5px;">Export &nbsp;<i class="fa fa-print"></i></a> &nbsp
		        </div>
		    </div><br>
        </div>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        @if (count($errors) > 0)
          @foreach ( $errors->all() as $error )
            <p class="alert alert-danger">{{ $error }}</p>
          @endforeach
        @endif
		 <!-- @if($entry_year ==0 || $entry_year ==null || $department==0 || $department==null)
        @else -->
          <!-- <a href="#" class="btn btn-primary btn-sm export" id='export' style="font-size:medium;margin-right:5px;">Export Excel &nbsp;<i class="fa fa-print"></i></a> &nbsp
          <input type="text" hidden id="dept" value="{{$department}}">
          <input type="text" hidden id="entry" value="{{$entry_year}}"> -->
        <!-- @endif -->

	
    </div>
  </div>

	<script>
	$( document ).ready(function() {
		$('#pesan').hide();
		$("#per_mahasiswa").hide();
		$(".all").show();
		$("#cek_cekbok").val(0);
	});

	function Change(checkbox) {
              var id = $(checkbox).val();
              if(checkbox.checked == true){
                $("#per_mahasiswa").show();
                $(".all").hide();
				$("#cek_cekbok").val(1);
              }else {
                $("#per_mahasiswa").hide();
                $(".all").show();
				$("#cek_cekbok").val(0);
              }
          }

	$('#search').keyup(function(e) {
		var search = $('#search').val();
		var department = $('#department').val();
		$.ajax({
			url: "{{ url('') }}/setting/rfid/get_data/get_data",
			type: "GET",
			dataType: "json",
			data: {
				search: search,
				department: department,
				"_token": "{{ csrf_token() }}"
			},
			success: function (result) {
				if(result.total != 0){
					$.each(result.data, function (index, value) {
						$("#nim").val(value.Nim);					
						$("#name").val(value.Full_Name);					
						$("#prodi").val(value.Department_Name);					
						$("#department").val(value.Department_Id);					
						$("#entry_year").val(value.Entry_Year_Id);		
						var home = "<?php echo env('APP_URL')?>";
						var foto = home+'foto_mhs/'+value.Entry_Year_Id+'/'+value.Nim+'.jpg';
						console.log(foto);
						if(foto == "http://localhost/simak_sttnas_gmail/public/foto_mhs//.jpg"){
							$("#image").html('<img width="151px" height="226px" src="' + home + '/img/noimage.jpg" />');
						}else{
							$("#image").html('<img width="151px" height="226px" src="' + home + 'foto_mhs/'+value.Entry_Year_Id+'/'+value.Nim+'.jpg" />');
						}			
					});
				}else{
					$("#nim").val('');
					$("#name").val('');
					$("#prodi").val('');
					$("#department").val('');
				}				
			},
			error: function(e){
				console.log(e);
			}
		});
	});

	$('#new_rfid').keyup(function(e) {
		var nim = $('#nim').val();
		var new_rfid = $('#new_rfid').val();
		$.ajax({
			url: "{{ url('') }}/setting/rfid/post_data/post_data",
			type: "GET",
			dataType: "json",
			data: {
				nim: nim,
				new_rfid: new_rfid,
				"_token": "{{ csrf_token() }}"
			},
			success: function (result) {
				if(result.success == 'true'){			
					$("#rfid").val(result.data);		
					$('#pesan').show(0).hide(120);
				}else{
					$('#pesan').hide();
				}				
			},
			error: function(e){
				console.log(e);
			}
		});
	});

	$("#export").click(function(e) {
      var department = $('#department').val();
      var term_year = $('#term_year').val();
      var term_year2 = $('#term_year2').val();
      var nim = $('#nim').val();
      var exam_type = $('#exam_type').val();
      var exam_type2 = $('#exam_type2').val();
      var nimawal = $('#select').val();
      var nimakhir = $('#select2').val();
      var cekbok = $("#cek_cekbok").val();
	  if(cekbok == 0){
    	window.open("{{ url('') }}/cetak/kartuujian/exportdata/exportdata/" + department + "/" + term_year + "/" + nimawal+ "/" + exam_type + "/" + nimakhir); 
	  }else{
    	window.open("{{ url('') }}/cetak/kartuujian/exportdata/exportdata/" + department + "/" + term_year2 + "/" + nim+ "/" + exam_type2); 
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
							$.ajax({
									url: "{{ url('') }}/setting/student/" + id,
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
