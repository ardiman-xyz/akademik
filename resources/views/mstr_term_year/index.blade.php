@extends('layouts._layout')
@section('pageTitle', 'Term Year')
@section('content')

<?php


// $url = 'http://10.64.21.72:8080/webservice/rest/simpleserver.php?wsusername=a&wspassword=a';
// $xmlll = simplexml_load_file($url) or die("feed not loading");
// // $xml=simplexml_load_string($xmlll) or die("Error: Cannot create object");
// print_r($xmlll);
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
?>

<section class="content">

  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Semester Berlaku</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        @if($entry_year != null && $entry_year != 0)
        <div class="pull-right tombol-gandeng dua">
          <?php $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; ?>
          <a href="{{ url('master/term_year/create/?entry_year='.$entry_year.'&page='.$page.'&rowpage='.$rowpage.'&search='.$search ) }}" class="btn btn-success btn-sm">Tambah data &nbsp;<i class="fa fa-plus"></i></a>
        </div>
        @endif
        <div class="bootstrap-admin-box-title right text-white">
          <b>Index</b>
        </div>
      </div>
      <br>
          
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        @if (count($errors) > 0)
          @foreach ( $errors->all() as $error )
            <p class="alert alert-danger">{{ $error }}</p>
          @endforeach
        @endif
        <button class='btn' style='background:#ccffcc; color:#fff; cursor:default; margin:5px;'></button> Semester Berlaku Saat Ini</br>
        <div class="table-responsive">
        <table class="table table-striped table-font-sm">
          <thead class="thead-default thead-green">
              <tr>
                  <!-- <th class="col-sm-1">No</th> -->
                  <th width="10%">Tahun</th>
                  <th width="20%">Semester</th>
                  <th width="20%">Tanggal Mulai</th>
                  <th width="20%">Tanggal Selesai</th>
                  <th width="10%">Semester untuk Seting</th>
                  <th width="15%"><center><i class="fa fa-gear"></i></center></th>
              </tr>
          </thead>
          <tbody>
          <?php 
            foreach ($th['tahun'] as $data1) {
              $data = DB::table('mstr_term_year')->where('Year_Id',$data1->Entry_Year_Id)->orderby('Year_Id','desc')->get();
              $datac = DB::table('mstr_term_year')->where('Year_Id',$data1->Entry_Year_Id)->orderby('Year_Id','desc')->count();
              if($datac > 0){
              foreach ($data as $data2) {    
                $start = explode(" ",$data2->Start_Date);
                $s_date = $start[0];      
                
                $end = explode(" ",$data2->End_Date);
                $e_date = $end[0];
            if($active != null){            
            if($active->Term_Year_Id == $data2->Term_Year_Id){ ?>
              <tr style="background-color: #ccffcc;">
           <?php } else {
          ?>
           <tr> <?php } }else{ ?> <tr> <?php } ?>
              <td rowspan="">{{ $data2->Year_Id}}</td>
              <td>{{ $data2->Term_Year_Name}}</td>
              <td>@if($s_date != null)
                    {{ tanggal_indo($s_date,true) }}
                    @endif</td>
              <td>@if($e_date != null)
                    {{ tanggal_indo($e_date,true) }}
                    @endif</td>
              <td><center>
                  <input type="checkbox" onchange="semester_berlaku({{$data2->Term_Year_Id}},'{{$data2->Term_Year_Name}}')" class="" name="Pilih_Jdwl[]" value="" <?php if ($term_year_now == $data2->Term_Year_Id) { echo "checked"; } ?>/></center></td>                  
              <td><div class="btn-group">
                {!! Form::open(['url' => route('term_year.destroy', $data2->Term_Year_Id) , 'method' => 'delete', 'role' => 'form']) !!}
                <a href="{{ url('master/term_year/'.$data2->Term_Year_Id.'/edit?entry_year='.$entry_year) }}" class="btn btn-info btn-sm">Edit</a>
                {!! Form::button('Hapus', ['type'=>'submit','class'=>'btn btn-danger btn-sm hapus','data-id'=>$data2->Term_Year_Id]) !!}
                {!! Form::close() !!}
                &nbsp;<a href="{{ url('master/term_year/create/?entry_year='.$data1->Entry_Year_Id) }}" class="btn btn-success btn-sm">Tambah<i class="fa fa-plus"></i></a>
              </div></td>
          
            <?php
                } ?> 
          </tr>
          <?php
              } else{ ?>
              

              <tr>
               <td>{{$data1->Entry_Year_Id}}</td>
               <td colspan="4"></td>
               <td colspan="">          
                <a href="{{ url('master/term_year/create/?entry_year='.$data1->Entry_Year_Id) }}" class="btn btn-success btn-sm">Tambah&nbsp;<i class="fa fa-plus"></i></a>
              </td>
                <?php }
            }
          ?>
          </tr>
          </tbody>
        </table>
        </div>
        <?php echo $query->render('vendor.pagination.bootstrap-4'); ?>
      </div>
    </div>
  </div>

  <script>

  function semester_berlaku(Term_Year_Id,Term_Year_Name){
    swal({
        title: 'Ubah Semester Untuk Setting',
          text: "Anda akan Mengubah Semester Untuk setting ke "+Term_Year_Name,
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Ganti',
          cancelButtonText: 'Batalkan!',
          confirmButtonClass: 'btn btn-success',
          cancelButtonClass: 'btn btn-danger',
          buttonsStyling: true
        }, function(isConfirm) {
      if (isConfirm) {
              	$.ajax({
                  url: '{{route('term_year.term_year_active')}}',
                  type: "post",
                  dataType: "json",
                  data: {
                    Term_Year_Id: Term_Year_Id,                 
                    _token: "{{ csrf_token() }}"                    
                  },
                  success: function (res) {
                   window.location.reload();
                  },
                  error: function(xhr, ajaxOptions, thrownError){
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
}


  $(document).on('click', '.hapus', function (e) {
      e.preventDefault();
      var id = $(this).data('id');

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
                  url: "{{ url('') }}/master/term_year/" + id,
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
