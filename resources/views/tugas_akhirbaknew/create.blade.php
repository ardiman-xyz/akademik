@extends('layouts._layout')
@section('pageTitle', 'Tugas Akhir')
@section('content')
<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Tambah Tugas Akhir</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('proses/tugas_akhir?department='.$department.'&term_year='.$term_year.'&angkatan='.$entry_year) }}" class="btn btn-danger btn-sm" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Tambah</b>
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
          {!! Form::open(['url' => route('tugas_akhir.store') , 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form' , 'enctype' => 'multipart/form-data' ]) !!}
          {{ csrf_field() }}
          {{ method_field('post') }}
          <input type="hidden" name="Entry_Year_Id"  value=""  class="form-control">
          <input type="hidden" hidden name="krs_id"  value=""  class="form-control krs_id">
            <input type="hidden" readonly name="std_id"  value=""  class="form-control std_id">
            <input type="hidden" readonly name="department"  value="{{$department}}"  class="form-control">

            <div class="form-group">
              {!! Form::label('', 'Mahasiswa', ['class' => 'col-md-4 form-label']) !!}
              <div class="col-md-12">
                <select id="select" class="form-control form-control-sm col-md-12" name="mahasiwa">
                  <option value="0">Pilih Mahasiswa...</option>
                  @foreach($select_mahasiswa as $data)
                  <option <?php if($mahasiswa == $data->Student_Id){ echo "selected"; } ?> value="{{ $data->Student_Id }}">{{$data->Nim}} | {{ $data->Full_Name }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="form-group">
              {!! Form::label('', 'Th/Smt Mulai', ['class' => 'col-md-4 form-label']) !!}
              <div class="col-md-12">
                <select class="form-control form-control-sm col-md-12" name="smt_mulai">
                  <option value="0">Pilih Th/Smt Mulai...</option>
                  @foreach($select_term_year as $data)
                  <option <?php if($term_year == $data->Term_Year_Id){ echo "selected"; } ?> value="{{ $data->Term_Year_Id }}">{{ $data->Term_Year_Name }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="form-group">
              {!! Form::label('', 'Th/Smt Selesai *', ['class' => 'col-md-4 form-label']) !!}
              <div class="col-md-12">
                <select class="form-control form-control-sm col-md-12" name="smt_selesai">
                  <option value="0">Pilih Th/Smt Selesai...</option>
                  @foreach($select_term_year as $data)
                  <option  value="{{ $data->Term_Year_Id }}">{{ $data->Term_Year_Name }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="form-group">
              {!! Form::label('', 'Matakuliah', ['class' => 'col-md-4 form-label']) !!}
              <div class="col-md-12">
                  <input type="text" readonly name="nm_matakuliah" value="{{$matakuliah->Course_Name}}"  class="form-control form-control-sm nm_matakuliah">
                </div>
              </div>
              <input type="text"  hidden name="matakuliah" value="{{$matakuliah->Course_Id}}"  class="form-control form-control-sm matakuliah">

          <!-- <div class="form-group">
            {!! Form::label('', 'Tanggal Permohonan ', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <?php
                $date = strtotime(old('tgl_permohonan'));
                $tgl_ = date('Y-m-d', $date);
              ?>
              <input type="date" name="tgl_permohonan" value="{{ old('$tgl_') }}" class="form-control form-control-sm">
            </div>
          </div> -->

          <div class="form-group">
            {!! Form::label('', 'SKS', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" readonly name="sks_krs" value=""  class="form-control form-control-sm sks_krs">
            </div>
          </div>

          <!-- <div class="form-group">
            {!! Form::label('', 'Sks_Trough', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="sks_trough" value=""  class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Bnk', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="bnk" value=""  class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Proposal', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select class="form-control form-control-sm col-md-12" name="is_proposal">
                <option value="1">Ya</option>
                <option  value="0">Tidak</option>
              </select>
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Judul Proposal', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="judul_proposal" value=""  class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Tanggal Proposal Masuk', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <?php
                $date = strtotime(old('tgl_proposalmks'));
                $tgl_propmsk = date('Y-m-d', $date);
              ?>
              <input type="date" name="tgl_proposalmsk" value="{{ old('$tgl_propmsk') }}" class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Tanggal Proposal Diterima', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <?php
                $date = strtotime(old('tgl_proposaltrm'));
                $tgl_proptrm = date('Y-m-d', $date);
              ?>
              <input type="date" name="tgl_proposaltrm" value="{{ old('$tgl_proptrm') }}" class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Sk Proposal Acc', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="skproposalacc" value=""  class="form-control form-control-sm">
            </div>
          </div> -->

          <div class="form-group">
            {!! Form::label('', 'Judul', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="judul" value=""  class="form-control form-control-sm">
            </div>
          </div>

          <div class="form-group">
            {!! Form::label('', 'Judul English', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="judul_eng" value=""  class="form-control form-control-sm">
            </div>
          </div>

          <div class="form-group">
            {!! Form::label('', 'Pembimbing 1 *', ['class' => 'col-md-4 form-label']) !!}
            <!-- <p style="color:red; visibility: hidden;" id="bim1" class="col-md-12">Mohon Diisi .</p> -->
            <div class="col-md-12">
              <!-- <select id="pem1" class="form-control form-control-sm col-md-12" required oninvalid="this.setCustomValidity('Data Tidak Bolek Kosong')" oninput="setCustomValidity('')" name="pembimbing1"> -->
              <select id="pem1" class="form-control form-control-sm col-md-12" required oninvalid="this.setCustomValidity('Data Tidak Bolek Kosong')" oninput="setCustomValidity('')" name="pembimbing1">
                <option value="0">Pilih Pembimbing 1...</option>
                @foreach($dosen as $data)
                <option <?php if($pembimbing1 == $data->Employee_Id){ echo "selected"; } ?> value="{{ $data->Employee_Id }}">{{ $data->Full_Name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="form-group">
            {!! Form::label('', 'Pembimbing 2 *', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select id="pem2" class="form-control form-control-sm col-md-12" required oninvalid="this.setCustomValidity('Data Tidak Bolek Kosong')" oninput="setCustomValidity('')" name="pembimbing2">
                <option value=0"">Pilih Pembimbing 2...</option>
                @foreach($dosen as $data)
                <option <?php if($pembimbing2 == $data->Employee_Id){ echo "selected"; } ?> value="{{ $data->Employee_Id }}">{{ $data->Full_Name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="form-group">
            {!! Form::label('', 'Pembimbing 3', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select id="pem3" class="form-control form-control-sm col-md-12" name="pembimbing3">
                <option value="0">Pilih Pembimbing 3...</option>
                @foreach($dosen as $data)
                <option <?php if($pembimbing3 == $data->Employee_Id){ echo "selected"; } ?> value="{{ $data->Employee_Id }}">{{ $data->Full_Name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="form-group">
            {!! Form::label('', 'Penguji 1 *', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select id="exam1" class="form-control form-control-sm col-md-12" required oninvalid="this.setCustomValidity('Data Tidak Bolek Kosong')" oninput="setCustomValidity('')" name="penguji1">
                <option value="0">Pilih Penguji 1...</option>
                @foreach($dosen as $data)
                <option <?php if($penguji1 == $data->Employee_Id){ echo "selected"; } ?> value="{{ $data->Employee_Id }}">{{ $data->Full_Name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="form-group">
            {!! Form::label('', 'Penguji 2 *', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select id="exam2" class="form-control form-control-sm col-md-12" required oninvalid="this.setCustomValidity('Data Tidak Bolek Kosong')" oninput="setCustomValidity('')" name="penguji2">
                <option value="0">Pilih Penguji 2...</option>
                @foreach($dosen as $data)
                <option <?php if($penguji2 == $data->Employee_Id){ echo "selected"; } ?> value="{{ $data->Employee_Id }}">{{ $data->Full_Name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="form-group">
            {!! Form::label('', 'Penguji 3 *', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select id="exam3" class="form-control form-control-sm col-md-12" required oninvalid="this.setCustomValidity('Data Tidak Bolek Kosong')" oninput="setCustomValidity('')" name="penguji3">
                <option value="0">Pilih Penguji 3...</option>
                @foreach($dosen as $data)
                <option <?php if($penguji3 == $data->Employee_Id){ echo "selected"; } ?> value="{{ $data->Employee_Id }}">{{ $data->Full_Name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <!-- <div class="form-group">
            {!! Form::label('', 'Tanggal Mulai', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <?php
                $date = strtotime(old('tgl_mulai'));
                $tgl_mulai = date('Y-m-d', $date);
              ?>
              <input type="date" name="tgl_mulai" value="{{ old('$tgl_mulai') }}" class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Invitation_Thesis_Exam', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Invitation_Thesis_Exam" value=""  class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Ruang Ujian', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="ruang_ujian" value=""  class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Tanggal Undangan Pendadaran', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <?php
                $date = strtotime(old('tgl_udgpendadaran'));
                $tgl_udgpendadaran = date('Y-m-d', $date);
              ?>
              <input type="date" name="tgl_udnpendadaran" value="{{ old('$tgl_udgpendadaran') }}" class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Tanggal Selesai', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <?php
                $date = strtotime(old('tgl_selesai'));
                $tgl_selesai = date('Y-m-d', $date);
              ?>
              <input type="date" name="tgl_selesai" value="{{ old('$tgl_selesai') }}" class="form-control form-control-sm">
            </div>
          </div> -->

          <div class="form-group">
            {!! Form::label('', 'Nilai Ujian', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="total_ujian" value=""  class="form-control form-control-sm">
            </div>
          </div>

          <!-- <input type="text" hidden readonly name="nl_ujian" value=""  class="form-control form-control-sm nl_ujian">
          <div class="form-group">
            {!! Form::label('', 'Nilai Ujian Pendadaran', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select class="form-control form-control-sm col-md-12 nilai_ujian" name="nilai_ujian">
                <option value="">Pilih Nilai...</option>
                @foreach($select_grade as $data)
                <option <?php if($grade_letter == $data->Grade_Letter_Id){ echo "selected"; } ?> value="{{ $data->Grade_Letter_Id}}">{{ $data->Grade_Letter }}</option>
                @endforeach
              </select>
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Sks Trough Exam', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="sks_troughexam" value=""  class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Bnk Exam', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="bnk_exam" value=""  class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Functionary_Department_Exam', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Functionary_Department_Exam" value=""  class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Functionary_Name_Department_Exam', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Functionary_Name_Department_Exam" value=""  class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Nilai', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select class="form-control form-control-sm col-md-12" name="nilai">
                <option value="">Pilih Nilai...</option>
                @foreach($select_grade as $data)
                <option <?php if($grade_letter == $data->Grade_Letter_Id){ echo "selected"; } ?> value="{{ $data->Grade_Letter_Id}}">{{ $data->Grade_Letter }}</option>
                @endforeach
              </select>
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Department_Functionary', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Department_Functionary" value=""  class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Department_Functionary_Name', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Department_Functionary_Name" value=""  class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Nomor Izin Thesis', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="nomor_izinthesis" value=""  class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Halaman Izin Thesis', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="halaman_izinthesis" value=""  class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Nama Perusahaan', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="nama_perusahaan" value=""  class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Company_Address', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Company_Address" value=""  class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Functionary_Company', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Functionary_Company" value=""  class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Cq_Functionary_Company', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Cq_Functionary_Company" value=""  class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Tanggal Daftar Seminar', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <?php
                $date = strtotime(old('tgl_dftseminar'));
                $tgl_dftseminar = date('Y-m-d', $date);
              ?>
              <input type="date" name="tgl_dftseminar" value="{{ old('$tgl_dftseminar') }}" class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Tanggal Pelaksanaan Seminar', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <?php
                $date = strtotime(old('tgl_plkseminar'));
                $tgl_plkseminar = date('Y-m-d', $date);
              ?>
              <input type="date" name="tgl_plkseminar" value="{{ old('$tgl_plkseminar') }}" class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Ruang Seminar', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="ruang_seminar" value=""  class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Department_Seminar_Functionary', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Department_Seminar_Functionary" value=""  class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Department_Seminar_Functionary_Name', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Department_Seminar_Functionary_Name" value=""  class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Permission_Thesis_Long_Text', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Permission_Thesis_Long_Text" value=""  class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Permission_Thesis_Start_Date', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <?php
                $date = strtotime(old('Permission_Thesis_Start_Date'));
                $Permission_Thesis_Start_Date = date('Y-m-d', $date);
              ?>
              <input type="date" name="Permission_Thesis_Start_Date" value="{{ old('$Permission_Thesis_Start_Date') }}" class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Permission_Thesis_Complete_Date', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <?php
                $date = strtotime(old('Permission_Thesis_Complete_Date'));
                $Permission_Thesis_Complete_Date = date('Y-m-d', $date);
              ?>
              <input type="date" name="Permission_Thesis_Complete_Date" value="{{ old('$Permission_Thesis_Complete_Date') }}" class="form-control form-control-sm">
            </div>
          </div> -->



          <br><center><button type="submit" class="btn btn-primary btn-flat">Tambah</button></center>

          {!! Form::close() !!}
      </div>
    </div>
  </div>

<!-- /.row -->

</section>

<script type="text/javascript">
  var select = new SlimSelect({
  select: '#select'
  })
  select.selected()
  
  var pem1 = new SlimSelect({
  select: '#pem1'
  })
  pem1.selected()

  var pem2 = new SlimSelect({
  select: '#pem2'
  })
  pem2.selected()

  var pem3 = new SlimSelect({
  select: '#pem3'
  })
  pem3.selected()

  var exam1 = new SlimSelect({
  select: '#exam1'
  })
  exam1.selected()

  var exam2 = new SlimSelect({
  select: '#exam2'
  })
  exam2.selected()

  var exam3 = new SlimSelect({
  select: '#exam3'
  })
  exam3.selected()
</script>

<script type="text/javascript">
	$(document).ready(function(){

		$("#select").on('change',function () {
			var student_id=$(this).val();
      var department = <?php echo $department; ?>;
      var term_year = <?php echo $term_year; ?>;
      var angkatan = <?php echo $angkatan; ?>;
			var a=$(this).parent();
			var op="";
      var url = {!! json_encode(url('/')) !!};
			$.ajax({
				type:'get',
				url: url + '/proses/tugas_akhir/finddata/finddata',
				data:{'Student_Id':student_id,'department':department,'term_year':term_year,'angkatan':angkatan},
				dataType:'json',//return data will be json
				success:function(data){
            // console.log(data);
            if(data.total == 0){
              swal({
                  title: data.std.Nim,
                  text: 'Belum Menggambil Matakuliah Skripsi/Thesis/Tugas akhir',
                  type: "warning",
                  confirmButtonColor: "#02991a",
                  // confirmButtonText: "Refresh Serkarang",
                  cancelButtonText: "Batalkan",
                  closeOnConfirm: false,
              },
              function(isConfirm) {
                  if (isConfirm) {
                  window.location.reload(true) // submitting the form when user press yes
                  }
              });
            }
           $(".nm_matakuliah").val(data.data.Course_Name);
           $(".matakuliah").val(data.data.Course_Id);
          //  $(".krs_id").val(data.krs.Krs_Id);
           $(".sks_krs").val(data.data.Applied_Sks);
           $(".std_id").val(data.std.Student_Id);
				},
				error:function(){

				}
			});
		});

    $(document).on('change','.nilai_ujian',function () {
			var grade_letter=$(this).val();
      var department = <?php echo $department; ?>;
			var a=$(this).parent();
			var op="";
      var url = {!! json_encode(url('/')) !!};
			$.ajax({
				type:'get',
				url: url + '/proses/tugas_akhir/findgrade/findgrade',
				data:{'Grade_Letter_Id':grade_letter,'Department_Id':department},
				dataType:'json',//return data will be json
				success:function(data){
            console.log(data);
           $(".nl_ujian").val(data.Weight_Value);
				},
				error:function(){

				}
			});
		});
	});
</script>


@endsection
