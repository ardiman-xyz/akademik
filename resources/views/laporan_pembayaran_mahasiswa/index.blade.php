@extends('layouts._layout')
@section('pageTitle', 'Biaya Mahasiswa')
@section('content')

<?php


// $url = 'http://10.64.21.72:8080/webservice/rest/simpleserver.php?wsusername=a&wspassword=a';
// $xmlll = simplexml_load_file($url) or die("feed not loading");
// // $xml=simplexml_load_string($xmlll) or die("Error: Cannot create object");
// print_r($xmlll);
?>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Laporan Pembayaran Mahasiswa Mahasiswa</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          <b>Laporan Pembayaran Mahasiswa Mahasiswa</b>
        </div>
      </div>
      <br>
          <!-- <b>Daftar Fakultas</b> -->
          {!! Form::open(['url' => route('laporan_pembayaran_mahasiswa.index') , 'method' => 'GET', 'name' => 'form', 'role' => 'form', 'style' => 'padding-top:5px;padding-left:15px;padding-right:15px;']) !!}
          <div class="row text-green">
            <!-- <label class="col-md-2" >Department :</label> -->
            <select class="form-control form-control-sm col-md-2" name="term_year" id="term_year" onchange="document.form.submit();">
              <option value="0">Pilih Tahun Semester</option>
              @foreach ( $select_term_year as $data )
                <option <?php if($term_year == $data->Term_Year_Id){ echo "selected"; } ?>  value="{{ $data->Term_Year_Id }}">{{ $data->Term_Year_Name }}</option>
              @endforeach
            </select>&nbsp
            <select class="form-control form-control-sm col-md-4" name="department" id="department" onchange="document.form.submit();">
              <option value="0">Pilih Program Studi</option>
              @foreach ( $select_department as $data )
                <option <?php if($department == $data->Department_Id){ echo "selected"; } ?> value="{{ $data->Department_Id }}">{{ $data->Department_Name }}</option>
              @endforeach
            </select>&nbsp
            <!-- <label class="col-md-2">Tahun Semester :</label> -->
            <select class="form-control form-control-sm col-md-2" name="class_program" id="class_program" onchange="document.form.submit();">
              <option value="0">Pilih Program Kelas</option>
              @foreach ( $select_class_program as $data )
                <option <?php if($class_program == $data->Class_Prog_Id){ echo "selected"; } ?> value="{{ $data->Class_Prog_Id }}">{{ $data->Class_Program_Name }}</option>
              @endforeach
            </select>&nbsp
            <select class="form-control form-control-sm col-md-2" name="entry_year_id" id="entry_year_id" onchange="document.form.submit();">
              <option value="">Pilih Angkatan</option>
              @foreach ( $mstr_entry_year as $data )
                <option <?php if($request->entry_year_id == $data->Entry_Year_Id){ echo "selected"; } ?> value="{{ $data->Entry_Year_Id }}">{{ $data->Entry_Year_Id }}</option>
              @endforeach
            </select>&nbsp

          </div>
          <br>
          <div class="row text-green">
            <?php
            foreach ( $select_class_program as $data) {
            if ($class_program == $data->Class_Prog_Id) {
              ?>
                <label class="col-md-2">Pencarian:</label>
                <input type="text" name="search"  class="form-control form-control-sm col-md-3" value="{{ $search }}" placeholder="NIM/Nama Mahasiswa">&nbsp
                <input type="submit" name="" class="btn btn-primary btn-sm" value="Cari">&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp
                <label class="col-md-2">Baris Per halaman :</label>
                <select class="form-control form-control-sm col-md-1" name="rowpage" onchange="document.form.submit();" >
                  <option <?php if($rowpage == "10"){ echo "selected"; } ?> value="">10 Baris</option>
                  <option <?php if($rowpage == "20"){ echo "selected"; } ?> value="20">20 Baris</option>
                  <option <?php if($rowpage == "50"){ echo "selected"; } ?> value="50">50 Baris</option>
                  <option <?php if($rowpage == "100"){ echo "selected"; } ?> value="100">100 Baris</option>
                  <option <?php if($rowpage == "1000000"){ echo "selected"; } ?> value="1000000">semua</option>
                </select>
              <?php } }
              ?>
          </div><br>
        {!! Form::close() !!}
        </div>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        @if (count($errors) > 0)
          @foreach ( $errors->all() as $error )
            <p class="alert alert-danger">{{ $error }}</p>
          @endforeach
        @endif
		   @if($term_year ==0 || $term_year ==null || $department==0 || $department==null || $class_program == 0 || $class_program == null)
          @else
            <a href="#" class="btn btn-primary btn-sm export" id='export' style="font-size:medium;margin-right:5px;">Export Excel &nbsp;<i class="fa fa-print"></i></a> &nbsp
            <input type="text" hidden id="dept" value="{{$department}}">
            <input type="text" hidden id="entry" value="{{$term_year}}">
            <input type="text" hidden id="classp" value="{{$class_program}}">
        @endif
        <div class="table-responsive">
        <table id="datatable" class="table table-striped table-font-sm">
          <thead class="thead-default thead-green">
              <tr>
                  <!-- <th class="col-sm-1">No</th> -->
                  <th width="10%">NIM</th>
                  <th width="35%">Nama</th>
                  <th width="20%">Program Kelas</th>
                  <th width="20%">Tagihan Mahasiswa</th>
                  <th width="25%"><center><i class="fa fa-gear"></i></center></th>
              </tr>
          </thead>
          <tbody>
            <?php
            $a = "1";
            foreach ($query as $data) {

              ?>
              <tr>
                  <!-- <th></th> -->
                  <td>{{ $data->Nim }}</td>
                  <td>{{ $data->Full_Name }}</td>
                  <td>{{ $data->Class_Program_Name }}</td>
                  <?php 
                    $studentbill = DB::select('CALL usp_GetStudentBill(?,?,?)',array($data->Register_Number,'',''));
                    $tagihan = DB::select('CALL usp_GetStudentBill_For_KRS(?,?,?)',[$data->Register_Number,"",""]);
                    $tagihan2 = DB::select('CALL usp_GetStudentBill(?,?,?)',[$data->Register_Number,"",""]);
                    $i = 0;
                    $ListTagihan = [];
                    $total=0;
                    if($tagihan!=null || $tagihan2 !=null){
                      foreach ($tagihan as $key) if($key->Term_Year_Bill_id == $term_year){
                        $ListTagihan[$i]['Amount'] = $key->Amount;
                        $ListTagihan[$i]['Cost_Item_Name'] = $key->Cost_Item_Name;
                        $ListTagihan[$i]['Cost_Item_Id'] = $key->Cost_Item_Id;
                        $i++;
                      }

                      foreach ($tagihan2 as $key) if($key->Term_Year_Bill_id == $term_year){
                        $ListTagihan[$i]['Cost_Item_Id'] = $key->Cost_Item_Id;
                        $ListTagihan[$i]['Cost_Item_Name'] = $key->Cost_Item_Name;
                        $ListTagihan[$i]['Payment_Order'] = $key->Payment_Order;
                        $ListTagihan[$i]['Amount'] = $key->Amount;
                        $ListTagihan[$i]['Term_Year_Bill_Id'] = $key->Term_Year_Bill_id;
                        $i++;
                      }

                      $sumAmount =0;
                            foreach ($ListTagihan as $tagihan) {
                              $sumAmount += $tagihan['Amount'];
                            }
                    $total = number_format($sumAmount,'0',',','.');
                  }
                    // $totalbiaya = number_format($data->biaya,'0',',','.'); 
                  ?>
                  <td>{{$total}}</td>
                  <td>
                  @if($tutupan == 1)
                      {!! Form::open(['url' => route('laporan_pembayaran_mahasiswa.destroy', $data->Student_Id) , 'method' => 'delete', 'role' => 'form']) !!}
                      <a href="{{ url('laporan/laporan_pembayaran_mahasiswa/'.$data->Nim.'?term_year='.$term_year.'&class_program='.$class_program.'&department='.$department.'&search='.$search.'&rowpage='.$rowpage.'&entry_year_id='.$request->entry_year_id) }}" class="btn btn-warning btn-sm" style="margin:5px;">Detail <i class="fa fa-list"></i> </a>
                      <!-- {!! Form::button('Hapus', ['type'=>'submit','class'=>'btn btn-danger btn-sm hapus','data-id'=>$data->Student_Id]) !!} -->
                      {!! Form::close() !!}
                  @else
                      <a href="{{ url('laporan/laporan_pembayaran_mahasiswa/'.$data->Nim.'?term_year='.$term_year.'&class_program='.$class_program.'&department='.$department.'&search='.$search.'&rowpage='.$rowpage.'&entry_year_id='.$request->entry_year_id) }}" class="btn btn-warning btn-sm" style="margin:5px;">Detail <i class="fa fa-list"></i> </a>
                  @endif
                  </td>


              </tr>
              <?php
              $a++;
            }
            ?>
          </tbody>
        </table>
        </div>
        <?php echo $query->render('vendor.pagination.bootstrap-4'); ?>
      </div>
    </div>
  </div>
	<script>
  $(document).ready(function() {  
    var table = $('#datatable').DataTable({
      searching: false, paging: false, info: false
    });
  });

	$("#export").click(function(e) {
      var department = $('#dept').val();
      var entry_year = $('#entry').val();
      var classp = $('#classp').val();
    	window.open("{{ url('') }}/laporan/laporan_pembayaran_mahasiswa/exportdata/exportdata/" + department + "/" + entry_year + "/" + classp); 
    });
	$(document).on('click', '.hapus', function (e) {
			e.preventDefault();
			var id = $(this).data('id');
			var term_year = $('#term_year').val();
			var department = $('#department').val();
			var class_program = $('#class_program').val();

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
									url: "{{ url('') }}/laporan/laporan_pembayaran_mahasiswa/" + id,
									type: "DELETE",
									dataType: "json",
									data: {
										"_token": "{{ csrf_token() }}",
                    term_year : term_year,
                    department : department,
                    class_program : class_program
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
