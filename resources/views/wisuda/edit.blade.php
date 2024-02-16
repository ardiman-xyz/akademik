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
      <h3 class="text-white">Wisuda</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('proses/wisuda/?periode='.$periode.'&department='.$department.'&tampilan='.$tampilan.'&rowpage='.$rowpage.'&search='.$search) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Edit</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">

				@if(Session::get('success') == true)
            @if (count($errors) > 0)
              @foreach ( $errors->all() as $error )
                <p class="alert alert-success">{{ $error }}</p>
              @endforeach
            @endif
        @else
          @if (count($errors) > 0)
            @foreach ( $errors->all() as $error )
              <p class="alert alert-danger">{{ $error }}</p>
            @endforeach
          @endif
        @endif
          {!! Form::open(['url' => route('wisuda.update', $query->Graduation_Reg_Id) , 'method' => 'put', 'class' => 'form']) !!}

          <input type="hidden" name="Nim" value="{{ $query->Nim }}" readonly class="form-control form-control-sm">

          <input type="hidden" name="Full_Name" value="{{ $query->Full_Name }}" disabled  class="form-control form-control-sm">

          <div class="form-group">
            {!! Form::label('', 'NIM', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" value="{{ $query->Nim }}" disabled class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Nama Lengkap', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text"  value="{{ $query->Full_Name }}" name="Full_Name"  class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Gender', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" value="{{ $query->Gender_Type }}" disabled class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Tempat Lahir', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" value="{{ $query->Birth_Place }}" name="Birth_Place" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            <?php
            $bdate = strtotime($query->Birth_Date);
            $da = Date('Y-m-d',$bdate);
            $Birth_Date = tanggal_indo($da,true);
            ?>
            {!! Form::label('', 'Tanggal Lahir', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" value="{{ $Birth_Date }}" disabled class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Tahun Ajaran Masuk', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" value="{{ $query->Entry_Year_Code }}" disabled class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            <?php
            $ydate = strtotime($query->Yudisium_Date);
            $ya = Date('Y-m-d',$ydate);
            $Yudisium_Date = tanggal_indo($ya,true);
            ?>
            {!! Form::label('', 'Tanggal Yudisium', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" value="{{ $Yudisium_Date }}" disabled class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            <?php
            $tdate = strtotime($query->Thesis_Exam_Date);
            $ba = Date('Y-m-d',$tdate);
            $Thesis_Exam_Date = tanggal_indo($ba,true);
            ?>
            {!! Form::label('', 'Tanggal Pendadaran', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" value="{{ $Thesis_Exam_Date }}" disabled class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'IPK', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" value="{{ $query->Gpa }}" disabled class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Jml Semester Cuti', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" value="{{ $query->Total_Smt_Vacation }}" disabled class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Judul TA', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" value="{{ $query->Thesis_Title }}" disabled class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Alamat Asal', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <textarea name="Address_0" rows="5" class="form-control form-control-sm" cols="80">{{ $query->Address_0 }}</textarea>
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Telp/HP', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" value="{{ $query->Phone }}" name="Phone" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Email', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" value="{{ $query->Email }}" name="Email" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Nama Orang Tua', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" value="{{ $query->Parent_Name }}" name="Parent_Name" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Status Mahasiswa', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" value="{{ $query->Register_Status_Name }}" disabled class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'No. SK Yudisium', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" value="{{ $query->Sk_Num }}" disabled class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'No. Transkrip Akhir', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" value="{{ $query->Transcript_Num }}" disabled class="form-control form-control-sm">
            </div>
          </div>
          <!-- <div class="form-group">
            {!! Form::label('', 'Umur Tahun', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text"  value="{{ $query->Age_Year }}" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Umur Bulan', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text"  value="{{ $query->Age_Year }}" class="form-control form-control-sm">
            </div>
          </div>
          <div class="form-group">
            {!! Form::label('', 'Umur Hari', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text"  value="{{ $query->Age_Year }}" class="form-control form-control-sm">
            </div>
          </div> -->

          <br><center><button type="submit" class="btn btn-primary btn-flat">Simpan</button></center>

          {!! Form::close() !!}
      </div>
    </div>
  </div>

<!-- /.row -->

</section>


@endsection
