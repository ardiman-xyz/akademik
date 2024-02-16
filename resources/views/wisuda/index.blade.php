@extends('layouts._layout')
@section('pageTitle', 'Wisuda')
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
?>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">WISUDA</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          <div class="pull-right tombol-gandeng dua">
            <?php $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; ?>
            <?php
            if ($department != null && $periode != null && $tampilan != "resume") {
              ?>
              @if(in_array('wisuda-CanAdd', $acc))<a href="{{ url('proses/wisuda/create/?department='.$department.'&periode='.$periode.'&tampilan='.$tampilan.'&currentsearch='.$search.'&currentrowpage'.$rowpage ) }}" class="btn btn-success btn-sm">Tambah data &nbsp;<i class="fa fa-plus"></i></a>@endif
              <?php }
              ?>
          </div>
          <b>Index</b>
        </div>
      </div>
      <br>
          <!-- <b>Daftar Fakultas</b> -->
          {!! Form::open(['url' => route('wisuda.index') , 'method' => 'GET', 'name' => 'form', 'role' => 'form', 'style' => 'padding-top:5px;padding-left:15px;padding-right:15px;']) !!}
          <div class="row text-green">
            <!-- <label class="col-md-2" >Department :</label> -->
            <label class="col-md-2">Prodi :</label>
            <select class="form-control form-control-sm col-md-3" name="department"  onchange="document.form.submit();">
              <option value="0">Pilih Program Studi</option>
              @foreach ( $select_department as $data )
                <option <?php if($department == $data->Department_Id){ echo "selected"; } ?> value="{{ $data->Department_Id }}">{{ $data->Department_Name }}</option>
              @endforeach
            </select>
            <label value="0" class="col-md-2">Periode :</label>
            <select class="form-control form-control-sm col-md-3" name="periode"  onchange="document.form.submit();">
                <!-- <option value="0" selected>Semua</option> -->
                <option value="" selected>Pilih Periode</option>
                @foreach ( $select_periode as $data )
                <option <?php if($periode == $data->Graduation_Period_Id){ echo "selected"; } ?> value="{{ $data->Graduation_Period_Id }}">{{ $data->Period_Name }}</option>
              @endforeach
            </select>

          </div>
          <br>
          <div class="row text-green">
            <label class="col-md-2">Tampilan :</label>
            <select class="form-control form-control-sm col-md-3" name="tampilan"  onchange="document.form.submit();">
              <option <?php if($tampilan == ""){ echo "selected"; } ?> value="">Standar</option>
              <option <?php if($tampilan == "lengkap"){ echo "selected"; } ?> value="lengkap">Tampilan Lengkap</option>
              <option <?php if($tampilan == "resume"){ echo "selected"; } ?> value="resume">Resume Wisuda Fakultas</option>
            </select>
          </div>
          <br>
          <div class="row text-green">
            <label class="col-md-2">Pencarian:</label>
            <input type="text" name="search"  class="form-control form-control-sm col-md-3" value="{{ $search }}" placeholder="Search NIM/Nama Mahasiswa">
            <label class="col-md-2" >Baris Per halaman :</label>
            <input type="number" name="rowpage"  class="form-control form-control-sm col-md-3" value="{{ $rowpage }}" placeholder="Baris Per halaman">
            &nbsp
            <input type="submit" name="" style="float:right;" class="btn btn-primary btn-sm" value="Submit">

          </div><br>
        {!! Form::close() !!}
        </div>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        @if (count($errors) > 0)
          @foreach ( $errors->all() as $error )
            <p class="alert alert-danger">{{ $error }}</p>
          @endforeach
        @endif

            @if($tampilan == "resume")
            <b>Data Calon Peserta Wisuda</b>
            <div class="row">
                <div class="col-md-1">
                    <b>Periode</b>
                </div>
                <div>
                    <b>: {{ $Periodes->Period_Name }}</b>
                </div>
            </div>
            <div class="row">
                <div class="col-md-1">
                    <b>Fakultas</b>
                </div>
                <div>
                    <b>: {{ $Facultys->Faculty_Name }}</b>
                </div>
            </div>
            <br />
            @endif

            @if($tampilan == "")
          <div class="table-responsive">
            <table class="table table-striped table-font-sm" style="width:3000px;">
            <thead class="thead-default thead-green">
                <tr>
                    <th>NIM</th>
                    <th>Nama Mahasiswah</th>
                    <th>Ttl</th>
                    <th>L/P</th>
                    <th>IPK</th>
                    <th>Tgl Lulus Yudisium</th>
                    <th>Cuti</th>
                    <th>Smst Masuk</th>
                    <th>Lama Studi</th>
                    <th>Usia</th>
                    <th>Predikat</th>
                    <th>Status</th>
                    <th>Email</th>
                    <th>No.Telp</th>
                    <th>Nama Ortu</th>
                    <th>Alamat Asal</th>
                    <th>Judul Skripsi</th>
                    <th>Title Of Thesis</th>
                    <th>No.SK Yudisium</th>
                    <th>No.Transkrip</th>
                    <th>No.Ijazah</th>
                    <th width="7%"><center><i class="fa fa-gear"></i></center></th>
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
                    <td>{{ $data->Birth_Place }} {{ $data->Birth_Date }}</td>
                    <td>{{ $data->Gender_Type }}</td>
                    <td>{{ $data->Gpa }}</td>
                    <?php
                    $ydate = strtotime($data->Yudisium_Date);
                    $ya = Date('Y-m-d',$ydate);
                    $Yudisium_Date = tanggal_indo($ya,true);
                    ?>
                    <td>{{ $Yudisium_Date }}</td>
                    <td>{{ $data->Total_Smt_Vacation }}</td>
                    <td>{{ $data->Entry_Year_Id }}/{{ $data->Term_Name }}</td>
                    <td>{{ $data->Total_Smt_Study }}</td>
                    <td>{{ $data->Age_Year }}</td>
                    <td>{{ $data->Predicate_Name }}</td>
                    <td>{{ $data->Register_Status_Name }}</td>
                    <td>{{ $data->Email }}</td>
                    <td>{{ $data->Phone }}</td>
                    <td>{{ $data->Parent_Name }}</td>
                    <td>{{ $data->Address_0 }}</td>
                    <td>{{ $data->Thesis_Title }}</td>
                    <td>{{ $data->Thesis_Title_Eng }}</td>
                    <td>{{ $data->Sk_Num }}</td>
                    <td>{{ $data->Transcript_Num }}</td>
                    <td>{{ $data->Certificate_Serial_Full }}</td>


                    <td align="center">

                        {!! Form::open(['url' => route('wisuda.destroy', $data->Graduation_Reg_Id) , 'method' => 'delete', 'role' => 'form']) !!}
                        @if(in_array('wisuda-CanEdit', $acc))<a href="{{ url('proses/wisuda/'.$data->Graduation_Reg_Id.'/edit?periode='.$periode.'&department='.$department.'&current_page='.$page.'&current_rowpage='.$rowpage.'&current_search='.$search) }}" class="btn btn-warning btn-sm" style="margin:5px;">Edit <i class="fa fa-edit"></i> </a>@endif
                        @if(in_array('wisuda-CanDelete', $acc)){!! Form::button('Hapus', ['type'=>'submit','class'=>'btn btn-danger btn-sm hapus','data-id'=>$data->Graduation_Reg_Id]) !!}@endif
                        {!! Form::close() !!}
                    </td>
                </tr>
                <?php
                $a++;
              }
              ?>
            </tbody>
          </table>
        </div>
            @elseif($tampilan == "resume")
        <div class="table-responsive">
          <table class="table table-striped table-font-sm" style="width:2000px;">
            <thead class="thead-default thead-green">
                <tr>
                    <th>Kriteria</th>
                    <th>NIM</th>
                    <th>Nama Mahasiswah</th>
                    <th>IPK</th>
                    <th>Tgl Lulus Yudisium</th>
                    <th>Lama Studi</th>
                    <th>Usia</th>
                    <th>Predikat</th>
                    <th>Status</th>
                    <th width="15%"><center><i class="fa fa-gear"></i></center></th>
                </tr>
            </thead>
            <tbody>
              <?php
              $a = "1";
              foreach ($query as $data) {
                ?>
                <tr>
                    <!-- <th></th> -->
                    <td></td>
                    <td>{{ $data->Nim }}</td>
                    <td>{{ $data->Full_Name }}</td>
                    <td>{{ $data->Gpa }}</td>
                    <?php
                    $ydate = strtotime($data->Yudisium_Date);
                    $ya = Date('Y-m-d',$ydate);
                    $Yudisium_Date = tanggal_indo($ya,true);
                    ?>
                    <td>{{ $Yudisium_Date }}</td>
                    <td>{{ $data->Total_Smt_Study }}</td>
                    <td>{{ $data->Age_Year }}</td>
                    <td>{{ $data->Predicate_Name }}</td>
                    <td>{{ $data->Register_Status_Name }}</td>
                    <td align="center">

                        {!! Form::open(['url' => route('wisuda.destroy', $data->Graduation_Reg_Id) , 'method' => 'delete', 'role' => 'form']) !!}
                        @if(in_array('wisuda-CanEdit', $acc))<a href="{{ url('proses/wisuda/'.$data->Graduation_Reg_Id.'/edit?periode='.$periode.'&department='.$department.'&current_page='.$page.'&current_rowpage='.$rowpage.'&current_search='.$search) }}" class="btn btn-warning btn-sm" style="margin:5px;">Edit <i class="fa fa-edit"></i> </a>@endif
                        @if(in_array('wisuda-CanDelete', $acc)){!! Form::button('Hapus', ['type'=>'submit','class'=>'btn btn-danger btn-sm hapus','data-id'=>$data->Graduation_Reg_Id]) !!}@endif
                        {!! Form::close() !!}
                    </td>
                </tr>
                <?php
                $a++;
              }
              ?>
            </tbody>
          </table>
        </div>
            @elseif($tampilan == "lengkap")
        <div class="table-responsive">
          <table class="table table-striped table-font-sm" style="width:4800px;">
            <thead class="thead-default thead-green">
                <tr>
                  <th>NIM</th>
                  <th>Nama Mahasiswah</th>
                  <th>Ttl</th>
                  <th>L/P</th>
                  <th>IPK</th>
                  <th>Tgl Lulus Yudisium</th>
                  <th>Cuti</th>
                  <th>Smst Masuk</th>
                  <th>Lama Studi</th>
                  <th>Usia</th>
                  <th>Predikat</th>
                  <th>Status</th>
                  <th>Email</th>
                  <th>No.Telp</th>
                  <th>Nama Ortu</th>
                  <th>Alamat Asal</th>
                  <th>Judul Skripsi</th>
                  <th>Title Of Thesis</th>
                  <th>No.SK Yudisium</th>
                  <th>No. Transkrip</th>
                  <th>No.Ijazah</th>
                  <th>Dosen Pembimbing TA 1</th>
                  <th>Dosen Pembimbing TA 2</th>
                  <th>Dosen Penguji TA 1</th>
                  <th>Dosen Penguji TA 2</th>
                  <th>Tgl Pendadaran</th>
                  <th>Nilai TA</th>
                  <th>Email</th>
                    <th width="5%"><center><i class="fa fa-gear"></i></center></th>
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
                    <td>{{ $data->Birth_Place }} {{ $data->Birth_Date }}</td>
                    <td>{{ $data->Gender_Type }}</td>
                    <td></td>
                    <?php
                    $ydate = strtotime($data->Yudisium_Date);
                    $ya = Date('Y-m-d',$ydate);
                    $Yudisium_Date = tanggal_indo($ya,true);
                    ?>
                    <td>{{ $Yudisium_Date }}</td>
                    <td>{{ $data->Total_Smt_Vacation }}</td>
                    <td>{{ $data->Term_Name }}</td>
                    <td>{{ $data->Total_Smt_Study }}</td>
                    <td>{{ $data->Age_Year }}</td>
                    <td>{{ $data->Predicate_Name }}</td>
                    <td>{{ $data->Register_Status_Name }}</td>
                    <td>{{ $data->Email }}</td>
                    <td>{{ $data->Phone }}</td>
                    <td>{{ $data->Parent_Name }}</td>
                    <td>{{ $data->Address_0 }}</td>
                    <td>{{ $data->Thesis_Title }}</td>
                    <td>{{ $data->Thesis_Title_Eng }}</td>
                    <td>{{ $data->Sk_Num }}</td>
                    <td>{{ $data->Transcript_Num }}</td>
                    <td>{{ $data->Certificate_Serial_Full }}</td>

                    <td>{{ $data->DosenPemb1 }}</td>
                    <td>{{ $data->DosenPemb2 }}</td>
                    <td>{{ $data->DosenPenguji1 }}</td>
                    <td>{{ $data->DosenPenguji2 }}</td>
                    <?php
                    $adate = strtotime($data->Application_Date);
                    $aa = Date('Y-m-d',$adate);
                    $Application_Date = tanggal_indo($aa,true);
                    ?>
                    <td>{{ $Application_Date }}</td>
                    <?php
                    $tdate = strtotime($data->Thesis_Exam_Date);
                    $ta = Date('Y-m-d',$tdate);
                    $Thesis_Exam_Date = tanggal_indo($ta,true);
                    ?>
                    <td>{{ $Thesis_Exam_Date }}</td>
                    <td>{{ $data->Grade }}</td>

                    <td align="center">

                        {!! Form::open(['url' => route('wisuda.destroy', $data->Graduation_Reg_Id) , 'method' => 'delete', 'role' => 'form']) !!}
                        @if(in_array('wisuda-CanEdit', $acc))<a href="{{ url('proses/wisuda/'.$data->Graduation_Reg_Id.'/edit?periode='.$periode.'&department='.$department.'&current_page='.$page.'&current_rowpage='.$rowpage.'&current_search='.$search) }}" class="btn btn-warning btn-sm" style="margin:5px;">Edit <i class="fa fa-edit"></i> </a>@endif
                        @if(in_array('wisuda-CanDelete', $acc)){!! Form::button('Hapus', ['type'=>'submit','class'=>'btn btn-danger btn-sm hapus','data-id'=>$data->Graduation_Reg_Id]) !!}@endif
                        {!! Form::close() !!}
                    </td>
                </tr>
                <?php
                $a++;
              }
              ?>
            </tbody>
          </table>
        </div>
            @endif


          @if($tampilan == "resume")
          <?php
            $gpa = 0;
            $age = 0;
            $study = 0;

            foreach ($query as $data) {
              $gpa += $data->Gpa;
              $age += $data->Age_Year;
              $study += $data->Total_Smt_Study;
            }
            $jml = $query->Count();
          ?>
          <div class="row">
              <div class="col-md-2">
                  <b>Rata-rata IPK</b>
              </div>
              <div>
                @if($gpa != 0 && $jml != 0)
                  <b>: {{ $gpa/$jml }}</b>
                @else
                  <b>: 0 </b>
                @endif
              </div>
          </div>
          <div class="row">
              <div class="col-md-2">
                <b>Rata-rata Masa Studi</b>
              </div>
              <div>
                @if($age != 0 && $jml != 0)
                  <b>: {{ $age/$jml }}</b>
                @else
                  <b>: 0 </b>
                @endif
              </div>
          </div>
          <div class="row">
              <div class="col-md-2">
                <b>Rata-rata Usia</b>
              </div>
              <div>
                @if($study != 0 && $jml != 0)
                  <b>: {{ $study/$jml }}</b>
                @else
                  <b>: 0 </b>
                @endif
              </div>
          </div>
          <br />
          @endif

      </div>
    </div>
  </div>
  <script>
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
                  url: "{{ url('') }}/master/faculty/" + id,
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
