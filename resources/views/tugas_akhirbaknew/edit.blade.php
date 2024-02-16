@extends('layouts._layout')
@section('pageTitle', 'Tugas Akhir')
@section('content')
<?php
  foreach ($data as $data_edit) {
?>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Edit Tugas Akhir</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('proses/tugas_akhir?department='.$department) }}" class="btn btn-danger btn-sm" >Kembali &nbsp;<i class="fa fa-close"></i></a>
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
          {!! Form::open(['url' => route('tugas_akhir.update',$data_edit->Thesis_Id) , 'method' => 'put', 'class' => 'form-horizontal', 'class' => 'form' , 'enctype' => 'multipart/form-data' ]) !!}
          {{ csrf_field() }}
          {{ method_field('put') }}
          <input type="hidden" name="Entry_Year_Id"  value=""  class="form-control">

            <div class="form-group">
              {!! Form::label('', 'Mahasiswa', ['class' => 'col-md-4 form-label']) !!}
              <div class="col-md-12">
                <select id="select" readonly class="form-control form-control-sm col-md-12" name="mahasiwa">
                  @foreach($select_mahasiswa as $data)
                  <option <?php if($data_edit->Student_Id == $data->Student_Id){ echo "selected"; } ?> value="{{ $data->Student_Id }}">{{$data->Nim}} | {{ $data->Full_Name }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="form-group">
              <div class="col-md-12">
                <select class="form-control form-control-sm col-md-12" id="smt_now" hidden>
                  <option value="0">Pilih Th/Smt Mulai...</option>
                  @foreach($select_term_year as $data)
                  <option <?php if($data_edit->Term_Year_Id == $data->Term_Year_Id){ echo "selected"; } ?> value="{{ $data->Term_Year_Id }}">{{ $data->Term_Year_Name }}</option>
                  @endforeach
                </select>
              </div>

            <div class="form-group">
              {!! Form::label('', 'Th/Smt Mulai', ['class' => 'col-md-4 form-label']) !!}
              <div class="col-md-12">
                <select class="form-control form-control-sm col-md-12" name="smt_mulai">
                  <option value="0">Pilih Th/Smt Mulai...</option>
                  @foreach($select_term_year as $data)
                  <option <?php if($data_edit->Term_Year_Id_Start == $data->Term_Year_Id){ echo "selected"; } ?> value="{{ $data->Term_Year_Id }}">{{ $data->Term_Year_Name }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="form-group">
              {!! Form::label('', 'Th/Smt Selesai', ['class' => 'col-md-4 form-label']) !!}
              <div class="col-md-12">
                <select class="form-control form-control-sm col-md-12" name="smt_selesai">
                  <option value="0">Pilih Th/Smt Selesai...</option>
                  @foreach($select_term_year as $data)
                  <option <?php if($data_edit->Term_Year_Id_Complete == $data->Term_Year_Id){ echo "selected"; } ?> value="{{ $data->Term_Year_Id }}">{{ $data->Term_Year_Name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            
            {{-- <input type="text" readonly hidden name="Course_Id" value="{{ $Krs_Id->Course_Id }}"  class="form-control form-control-sm"> --}}

            <div class="form-group">
              <div class="col-md-12">
                <select id="select2" hidden class="form-control form-control-sm col-md-12" name="matakuliah" >
                  <option value="">Pilih Mata Kuliah...</option>
                  @foreach ($matakuliah as $data)
                    <option <?php if($data_edit->Course_Id == $data->Course_Id){ echo "selected"; } ?> value="{{ $data->Course_Id }}">{{ $data->Course_Name }}</option>
                  @endforeach
                </select>
              </div>
            </div>

          <div class="form-group">
            {!! Form::label('', 'Tanggal Permohonan', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <?php
                $date = strtotime($data_edit->Application_Date);
                $tgl_ = date('Y-m-d', $date);
              ?>
              <input type="date" name="tgl_permohonan" value="{{ $tgl_ }}" class="form-control form-control-sm">
            </div>
          </div>

          <!-- <div class="form-group">
            {!! Form::label('', 'Sks_Trough', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="sks_trough" value="{{ $data_edit->Sks_Trough }}"  class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Bnk', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="bnk" value="{{ $data_edit->Bnk }}"  class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Proposal', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select class="form-control form-control-sm col-md-12" name="is_proposal">
                <option <?php if($data_edit->Is_Proposal==1){ echo "selected"; } ?> value="1">Ya</option>
                <option <?php if($data_edit->Is_Proposal==0){ echo "selected"; } ?> value="0">Tidak</option>
              </select>
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Judul Proposal', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="judul_proposal" value="{{ $data_edit->Proposal_Title }}"  class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Tanggal Proposal Masuk', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <?php
                $date = strtotime($data_edit->Proposal_Date_Msk);
                $tgl_propmsk = date('Y-m-d', $date);
              ?>
              <input type="date" name="tgl_proposalmsk" value="{{ $tgl_propmsk }}" class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Tanggal Proposal Diterima', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <?php
                $date = strtotime($data_edit->Proposal_Date_Acc);
                $tgl_proptrm = date('Y-m-d', $date);
              ?>
              <input type="date" name="tgl_proposaltrm" value="{{ $tgl_proptrm }}" class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Sk Proposal Acc', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="skproposalacc" value="{{ $data_edit->Sk_Proposal_Acc }}"  class="form-control form-control-sm">
            </div> -->
          </div>

          <div class="form-group">
            {!! Form::label('', 'Judul', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="judul" value="{{ $data_edit->Thesis_Title }}"  class="form-control form-control-sm">
            </div>
          </div>

          <div class="form-group">
            {!! Form::label('', 'Judul Eng', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="judul_eng" value="{{ $data_edit->Thesis_Title_Eng }}"  class="form-control form-control-sm">
            </div>
          </div>

          <div class="form-group">
            {!! Form::label('', 'Pembimbing 1', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select id="select" class="form-control form-control-sm col-md-12" required oninvalid="this.setCustomValidity('Data Tidak Bolek Kosong')" oninput="setCustomValidity('')" name="pembimbing1">
                <option value="0">Pilih Pembimbing 1...</option>
                @foreach($dosen as $data)
                <option <?php if($data_edit->Supervisor_1 == $data->Employee_Id){ echo "selected"; } ?> value="{{ $data->Employee_Id }}">{{ $data->Full_Name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="form-group">
            {!! Form::label('', 'Pembimbing 2', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select id="select" class="form-control form-control-sm col-md-12" required oninvalid="this.setCustomValidity('Data Tidak Bolek Kosong')" oninput="setCustomValidity('')" name="pembimbing2">
                <option value="0">Pilih Pembimbing 2...</option>
                @foreach($dosen as $data)
                <option <?php if($data_edit->Supervisor_2 == $data->Employee_Id){ echo "selected"; } ?> value="{{ $data->Employee_Id }}">{{ $data->Full_Name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="form-group">
            {!! Form::label('', 'Pembimbing 3', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select id="select" class="form-control form-control-sm col-md-12" name="pembimbing3">
                <option value="0">Pilih Pembimbing 3...</option>
                @foreach($dosen as $data)
                <option <?php if($data_edit->Supervisor_3 == $data->Employee_Id){ echo "selected"; } ?> value="{{ $data->Employee_Id }}">{{ $data->Full_Name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="form-group">
            {!! Form::label('', 'Penguji 1', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select class="form-control form-control-sm col-md-12" required oninvalid="this.setCustomValidity('Data Tidak Bolek Kosong')" oninput="setCustomValidity('')" name="penguji1">
                <option value="0">Pilih Penguji 1...</option>
                @foreach($dosen as $data)
                <option <?php if($data_edit->Examiner_1 == $data->Employee_Id){ echo "selected"; } ?> value="{{ $data->Employee_Id }}">{{ $data->Full_Name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="form-group">
            {!! Form::label('', 'Penguji 2', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select class="form-control form-control-sm col-md-12" required oninvalid="this.setCustomValidity('Data Tidak Bolek Kosong')" oninput="setCustomValidity('')" name="penguji2">
                <option value="0">Pilih Penguji 2...</option>
                @foreach($dosen as $data)
                <option <?php if($data_edit->Examiner_2 == $data->Employee_Id){ echo "selected"; } ?> value="{{ $data->Employee_Id }}">{{ $data->Full_Name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="form-group">
            {!! Form::label('', 'Penguji 3', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <select class="form-control form-control-sm col-md-12" required oninvalid="this.setCustomValidity('Data Tidak Bolek Kosong')" oninput="setCustomValidity('')" name="penguji3">
                <option value="0">Pilih Penguji 3...</option>
                @foreach($dosen as $data)
                <option <?php if($data_edit->Examiner_3 == $data->Employee_Id){ echo "selected"; } ?> value="{{ $data->Employee_Id }}">{{ $data->Full_Name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <!-- <div class="form-group">
            {!! Form::label('', 'Tanggal Mulai', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <?php
                $date = strtotime($data_edit->Thesis_Start_Date);
                $tgl_mulai = date('Y-m-d', $date);
              ?>
              <input type="date" name="tgl_mulai" value="{{ $tgl_mulai }}" class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Tanggal Pendadaran', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <?php
                $date = strtotime($data_edit->Thesis_Exam_Date);
                $tgl_pendadaran = date('Y-m-d', $date);
              ?>
              <input type="date" name="tgl_pendadaran" value="{{ $tgl_pendadaran }}" class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Ruang Ujian', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="ruang_ujian" value="{{ $data_edit->Thesis_Exam_Room }}"  class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Invitation_Thesis_Exam', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Invitation_Thesis_Exam" value="{{ $data_edit->Invitation_Thesis_Exam }}"  class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Tanggal Selesai', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <?php
                $date = strtotime($data_edit->Thesis_Complete_Date);
                $tgl_selesai = date('Y-m-d', $date);
              ?>
              <input type="date" name="tgl_selesai" value="{{ $tgl_selesai }}" class="form-control form-control-sm">
            </div>
          </div> -->

          <div class="form-group">
            {!! Form::label('', 'Total Ujian', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="total_ujian" value="{{ $data_edit->Total_Thesis_Exam }}"  class="form-control form-control-sm">
            </div>
          </div>

          <input type="text" name="sks_trough" value="{{ $data_edit->Sks_Trough }}" hidden class="form-control form-control-sm">
          
          <input type="text" hidden name="sks_krs" value=""  class="form-control form-control-sm sks_krs">


          <!-- <div class="form-group">
            {!! Form::label('', 'Sks Trough Exam', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="sks_troughexam" value="{{ $data_edit->Sks_Trough_Exam }}"  class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Bnk Exam', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="bnk_exam" value="{{ $data_edit->Bnk_Exam }}"  class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Functionary_Department_Exam', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Functionary_Department_Exam" value="{{ $data_edit->Functionary_Department_Exam }}"  class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Functionary_Name_Department_Exam', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Functionary_Name_Department_Exam" value="{{ $data_edit->Functionary_Name_Department_Exam }}"  class="form-control form-control-sm">
            </div>
          </div> -->


          <!-- <div class="form-group">
            {!! Form::label('', 'Department_Functionary', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Department_Functionary" value="{{ $data_edit->Department_Functionary }}"  class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Department_Functionary_Name', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Department_Functionary_Name" value="{{ $data_edit->Department_Functionary_Name }}"  class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Nomor Izin Thesis', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="nomor_izinthesis" value="{{ $data_edit->Permission_Thesis_Num }}"  class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Halaman Izin Thesis', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="halaman_izinthesis" value="{{ $data_edit->Permission_Thesis_Page }}"  class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Nama Perusahaan', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="nama_perusahaan" value="{{ $data_edit->Company_Name }}"  class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Company_Address', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Company_Address" value="{{ $data_edit->Company_Address }}"  class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Functionary_Company', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Functionary_Company" value="{{ $data_edit->Functionary_Company }}"  class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Cq_Functionary_Company', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Cq_Functionary_Company" value="{{ $data_edit->Cq_Functionary_Company }}"  class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Tanggal Daftar Seminar', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <?php
                $date = strtotime($data_edit->Seminar_App_Date);
                $tgl_dftseminar = date('Y-m-d', $date);
              ?>
              <input type="date" name="tgl_dftseminar" value="{{ $tgl_dftseminar }}" class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Tanggal Pelaksanaan Seminar', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <?php
                $date = strtotime($data_edit->Seminar_Date);
                $tgl_plkseminar = date('Y-m-d', $date);
              ?>
              <input type="date" name="tgl_plkseminar" value="{{ $tgl_plkseminar }}" class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Ruang Seminar', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="ruang_seminar" value="{{ $data_edit->Seminar_Room }}"  class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Department_Seminar_Functionary', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Department_Seminar_Functionary" value="{{ $data_edit->Department_Seminar_Functionary }}"  class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Department_Seminar_Functionary_Name', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Department_Seminar_Functionary_Name" value="{{ $data_edit->Department_Seminar_Functionary_Name }}"  class="form-control form-control-sm">
            </div> -->
          </div>

          <!-- <div class="form-group">
            {!! Form::label('', 'Permission_Thesis_Long_Text', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <input type="text" name="Permission_Thesis_Long_Text" value="{{ $data_edit->Permission_Thesis_Long_Text }}"  class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Permission_Thesis_Start_Date', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <?php
                $date = strtotime($data_edit->Permission_Thesis_Start_Date);
                $Permission_Thesis_Start_Date = date('Y-m-d', $date);
              ?>
              <input type="date" name="Permission_Thesis_Start_Date" value="{{ $Permission_Thesis_Start_Date }}" class="form-control form-control-sm">
            </div>
          </div> -->

          <!-- <div class="form-group">
            {!! Form::label('', 'Permission_Thesis_Complete_Date', ['class' => 'col-md-4 form-label']) !!}
            <div class="col-md-12">
              <?php
                $date = strtotime($data_edit->Permission_Thesis_Complete_Date);
                $Permission_Thesis_Complete_Date = date('Y-m-d', $date);
              ?>
              <input type="date" name="Permission_Thesis_Complete_Date" value="{{  $Permission_Thesis_Complete_Date }}" class="form-control form-control-sm">
            </div>
          </div> -->


          <br><center><button type="submit" class="btn btn-primary btn-flat">Simpan</button></center>

          {!! Form::close() !!}
      </div>
    </div>
  </div>

<!-- /.row -->

</section>

<?php
  }
  $angkatan = DB::table('acd_student')->where('Student_Id',$data_edit->Student_Id)->first();
?>

<script>
  var student_id=<?php echo $data_edit->Student_Id; ?>;
  var department = <?php echo $data_edit->Department_Id; ?>;
  var term_year = $("#smt_now" ).val();
  var angkatan = <?php echo $angkatan->Entry_Year_Id; ?>;
  var url = {!! json_encode(url('/')) !!};
  $.ajax({
    type:'get',
    url: url + '/proses/tugas_akhir/finddata/finddata',
    data:{'Student_Id':student_id,'department':department,'term_year':term_year,'angkatan':angkatan},
    dataType:'json',//return data will be json
    success:function(data){
        console.log(data);
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
        $(".sks_krs").val(data.data.Applied_Sks);
    },
    error:function(){

    }
  });
</script>
@endsection
