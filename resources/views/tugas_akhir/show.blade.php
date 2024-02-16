@extends('layouts._layout')
@section('pageTitle', 'Tugas Akhir')
@section('content')

  <?php
  $access = auth()->user()->akses();
            $acc = $access;
  ?>

  <style>
/* The Modal (background) */
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 9999; /* Sit on top */
    padding-top: 5%; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
}

.close2 {
  font-size: 28px;
  display: block;
  float: right;
  color: #fff;
}
.close {
  font-size: 28px;
  display: block;
  float: right;
  color: #fff;
}
.close3 {
  font-size: 28px;
  display: block;
  float: right;
  color: #fff;
}
.close4 {
  font-size: 28px;
  display: block;
  float: right;
  color: #fff;
}
.close5 {
  font-size: 28px;
  display: block;
  float: right;
  color: #fff;
}
.close6 {
  font-size: 28px;
  display: block;
  float: right;
  color: #fff;
}
.close7 {
  font-size: 28px;
  display: block;
  float: right;
  color: #fff;
}
table,td {
    border-collapse: collapse;
    border: 0px solid black;
    border-bottom: 1px solid #ddd;
}
.customheader{
  padding-left: 7%;
}
</style>

  <section class="content">

    <div class="container-fluid-title">
      <div class="title-laporan">
        <h3 class="text-white">Tugas Akhir</h3>
      </div>
    </div>
    <div class="container">
      <div class="panel panel-default bootstrap-admin-no-table-panel">
        <div class="panel-heading-green">
          <div class="pull-right tombol-gandeng dua">
            <a href="{{ url('proses/tugas_akhir?department='.$department.'&term_year='.$term_year) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
          </div>
          <div class="bootstrap-admin-box-title right text-white">
            <b>Yudisium</b>
          </div>
        </div>
        <br>

        {!! Form::open(['url' => route('tugas_akhir.show',$Thesis_Id), 'method' => 'GET', 'name' => 'form', 'role' => 'form', 'style' => 'padding-top:5px;padding-left:15px;padding-right:15px;']) !!}

        <div class="row">
          <!-- <label class="col-md-2" >Department :</label> -->
          <select class="form-control form-control-sm col-md-3 proses" name="proses" >
            <option value="1">Surat Permohonan TA</option>
            <option value="2">Surat Ijin TA</option>
            <option value="3">Lembar Monitoring TA</option>
            <option value="4">Surat mohon Seminar TA</option>
            <option value="5">Undangan Seminar TA</option>
            <option value="6">Berita Acara Seminar TA</option>
            <option value="7">Daftar Hadir Seminar TA</option>
            <option value="8">Permohonan Pendadaran</option>
            <option value="9">Pengantar Pembayaran Pendadaran</option>
            <option value="10">Undangan Pendadaran</option>
            <option value="11">Form Nilai</option>
            <option value="12">Form Berita Acara</option>
            <option value="13">Form Lembar Revisi</option>
            <option value="14">Form Lembar Pertanyaan</option>
            <option value="15">Form Nilai Skripsi</option>

          </select>&nbsp<br>

          <label class="col-sm-5"><a class="btn btn-warning btn-sm" onclick="proses()" style="margin:5px;" target="_blank">Proses/Cetak <i class="fa fa-print"></i> </a></label>

        </div>
        {!! Form::close() !!}

        <br>
        <div class="row" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            <table>
              <tr>
                <td width="20%">Program Studi</td>
                <td width="80%">{{ $data->Department_Name }}</td>
              </tr>
              <tr>
                <td width="20%">Angkatan</td>
                <td width="40%">{{ $data->Entry_Year_Name }}</td>
              </tr>
              <tr>
                <td width="20%">NIM</td>
                <td width="40%">{{ $data->Nim }}</td>
              </tr>
              <tr>
                <td width="20%">Nama Mahasiswa</td>
                <td width="40%">{{ $data->Full_Name }}</td>
              </tr>
              <tr>
                <td width="20%">Tgl Permohonan TA</td>
                <td width="40%">{{ $data->Application_Date }}</td>
              </tr>
              <tr>
                <td width="20%">Tanggal Mulai TA</td>
                <td width="40%"></td>
              </tr>
              <tr>
                <td width="20%">Pembimbing 1</td>
                <td width="40%">{{ $data->pem1 }}</td>
              </tr>
              <tr>
                <td width="20%">Pembimbing 2</td>
                <td width="40%">{{ $data->pem2 }}</td>
              </tr>
              <tr>
                <td width="20%">Judul TA</td>
                <td width="40%">{{ $data->Thesis_Title }}</td>
              </tr>
              <tr>
                <td width="20%">Judul TA English</td>
                <td width="40%">{{ $data->Thesis_Title_Eng }}</td>
              </tr>
              <tr>
                <td width="20%">Tanggal Seminar</td>
                <td width="40%"></td>
              </tr>
              <tr>
                <td width="20%">Nilai</td>
                <td width="40%"></td>
              </tr>
              <tr>
                <td width="20%">Penguji 1</td>
                <td width="40%">{{ $data->exam1 }}</td>
              </tr>
              <tr>
                <td width="20%">Penguji 2</td>
                <td width="40%">{{ $data->exam2 }}</td>
              </tr>
              <tr>
                <td width="20%">Penguji 3</td>
                <td width="40%">{{ $data->exam3 }}</td>
              </tr>
            </table>
            <br><br><br>

          <!-- Modal Surat Ijin TA -->
          <div id="Srtijin" class="modal">
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header container-fluid-title customheader">
              <h4 class="modal-title text-white">Surat Ijin TA</h4>
              <a class="close2 trigger" href="#">
           <i class="fa fa-times" aria-hidden="true"></i>
         </a>

            </div>

            <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
                    <p class="alert alert-danger" id="message" style="display: none"></p>

                    {!! Form::open(['url' => route('tugas_akhir.store_srtijinta') , 'method' => 'POST','id'=>'srtijinta' , 'class' => 'form-horizontal', 'role' => 'form' , 'enctype' => 'multipart/form-data' ]) !!}
                    {{ csrf_field() }}
                    {{ method_field('post') }}
                <input type="text" name="id" hidden value="{{ $data->Thesis_Id }}">


                  <?php
                    // $date = date("Y-m-d");
                    $date =strtotime($data->Permission_Thesis_Date);
                    $tgl_lulus = date('Y-m-d',$date);
                    $mulai = strtotime($data->Permission_Thesis_Start_Date);
                    $akhir = strtotime($data->Permission_Thesis_Complete_Date);
                    $tgl_lulus = date('Y-m-d', $mulai);
                    $tgl_selesai = date('Y-m-d', $akhir);
                  ?>

                  <table>
                    <tr>
                      <td width="5%"></td>
                      <td width="20%"><label>Nim</label></td>
                      <td width="60%"><label name="nim">{{ $data->Nim }}</label></td>
                    </tr>
                    <tr>
                      <td></td>
                      <td><label>Nama</label></td>
                      <td><label name="nama">{{ $data->Full_Name }}</label></td>
                    </tr>
                    <tr>
                      <td></td>
                      <td><label>No. Surat</label></td>
                      <td><label><input type="text" name="nosrt" value="{{ $data->Permission_Thesis_Page }}"></input></label></td>
                    </tr>
                    <tr>
                      <td></td>
                      <td><label>Tgl Surat</label></td>
                      <td><label><input type="date" name="tgl_surat" value="{{ $tgl_lulus }}" class="form-control form-control-sm"></input></label></td>
                    </tr>
                    <tr>
                      <td></td>
                      <td><label>Nama Perusahaan</label></td>
                      <td><label><input type="text" name="nm_perusahaan" value="{{ $data->Company_Name }}"></input></label></td>
                    </tr>
                    <tr>
                      <td></td>
                      <td><label>Alamat Perusahaan</label></td>
                      <td><label><textarea rows="4" cols="50" name="alt_perusahaan">{{ $data->Company_Address }}</textarea></label></td>
                    </tr>
                    <tr>
                      <td></td>
                      <td><label>Proyek</label></td>
                      <td><label><textarea name="proyek" rows="4" cols="50">{{ $data->Permission_Thesis_Project_Name }}</textarea></label></td>
                    </tr>
                    <tr>
                      <td></td>
                      <td><label>Jabatan Pimpinan</label></td>
                      <td><label><input type="text" name="jbt_pimpinan" value="{{ $data->Functionary_Company }}"></input></label></td>
                    </tr>
                    <tr>
                      <td></td>
                      <td><label>c.q.</label></td>
                      <td><label><input type="text" name="cq" value="{{ $data->Cq_Functionary_Company }}"></input></label></td>
                    </tr>
                    <tr>
                      <td></td>
                      <td><label>Teks Lama Ijin TA</label></td>
                      <td><label><input type="text" placeholder="isi dengan ""-"" jika kosong" name="txt_lamaijin" value="{{ $data->Permission_Thesis_Long_Text }}"></input></label></td>
                    </tr>
                    <tr>
                      <td></td>
                      <td><label>Tanggal ijin TA</label></td>
                      <td><label><input type="date" name="mulai" value="{{ $tgl_lulus }}" class="form-control form-control-sm"></input> s/d  <input type="date" name="selesai" value="{{ $tgl_selesai }}" class="form-control form-control-sm"></input></label></td>
                    </tr>
                  </table>

                    <br>@if(in_array('tugas_akhir-CanEdit', $acc))<button type="submit" class="btn btn-primary btn-flat">Simpan</button>@endif
                    <label class="col-sm-5"><a href="{{ url('proses/tugas_akhir/'.$data->Student_Id.'/export?proses=2') }}" class="btn btn-warning btn-sm" style="margin:5px;" target="_blank">Proses/Cetak <i class="fa fa-print"></i> </a></label>

                    {!! Form::close() !!}
            </div>
          </div>
          </div>

          <!-- Modal Surat  Pemohonan Seminar -->
          <div id="srt_permohonseminar" class="modal">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header container-fluid-title customheader">
                <h4 class="modal-title text-white">Surat  Permohonan Seminar TA</h4>
                <a class="close3 trigger" href="#">
                  <i class="fa fa-times" aria-hidden="true"></i>
                </a>
              </div>
              <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
                <p class="alert alert-danger" id="suratmohonseminar" style="display: none"></p>

                {!! Form::open(['url' => route('tugas_akhir.store_srtmohonseminarta') , 'method' => 'POST','id'=>'srtmohonseminar' , 'class' => 'form-horizontal', 'role' => 'form' , 'enctype' => 'multipart/form-data' ]) !!}
                {{ csrf_field() }}
                {{ method_field('post') }}
                <input type="text" name="id" hidden value="{{ $data->Thesis_Id }}">

                <?php
                $date = strtotime($data->Seminar_App_Date);
                $tgl_lulus = date('Y-m-d', $date);

                if($employee != null){
                  $employ = $employee->Full_Name;
                }else{
                  $employ = "";
                }
                ?>

                <table>
                  <tr>
                    <td width="5%"></td>
                    <td width="20%"><label>Nim</label></td>
                    <td width="60%"><label name="nim">{{ $data->Nim }}</label></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td><label>Nama</label></td>
                    <td><label name="nama">{{ $data->Full_Name }}</label></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td><label>Tgl Surat</label></td>
                    <td><label><input type="date" name="tgl_surat" value="{{ $tgl_lulus }}" class="form-control form-control-sm"></label></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td><label>Judul TA</label></td>
                    <td><label><textarea rows="4" cols="50" name="judul">{{ $data->Thesis_Title }}</textarea></label></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td><label>Judul TA English</label></td>
                    <td><label><textarea rows="4" cols="50" name="judul_eng" >{{ $data->Thesis_Title_Eng }}</textarea></label></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td colspan="2"><b><label style="font-siZe:15px;" for="" class="col-sm-6">PEJABAT PROGRAM STUDI</label></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td><label>Nama Jabatan</label></td>
                    <td><label><input type="text" disabled value="{{ $ttd }}"></label></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td><label>Nama Pejabat</label></td>
                    <td><label><input type="text" disabled value="{{ $employ }}"></label></td>
                  </tr>
                </table>

                  <br>@if(in_array('tugas_akhir-CanEdit', $acc))<button type="submit" class="btn btn-primary btn-flat">Simpan</button>@endif
                  <label class="col-sm-5"><a href="" class="btn btn-warning btn-sm" style="margin:5px;" target="_blank">Proses/Cetak <i class="fa fa-print"></i> </a></label>

                  {!! Form::close() !!}
                </div>
              </div>
            </div>
          </div>

          <!-- Modal Undangan Seminar TA -->
          <div id="udg_seminarta" class="modal">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header container-fluid-title customheader">
                <h4 class="modal-title text-white">Undangan Seminar TA</h4>
                <a class="close4 trigger" href="#">
                  <i class="fa fa-times" aria-hidden="true"></i>
                </a>
              </div>
              <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">

                <p class="alert alert-danger" id="messageudggagal" style="display: none"></p>
                <p class="alert alert-success" id="messageudgsukses" style="display: none"></p>

                {!! Form::open(['url' => route('tugas_akhir.store_undanganseminar') , 'method' => 'POST','id'=>'undanganseminar' , 'class' => 'form-horizontal', 'role' => 'form' , 'enctype' => 'multipart/form-data' ]) !!}
                {{ csrf_field() }}
                {{ method_field('post') }}
                <input type="text" name="id" hidden value="{{ $data->Thesis_Id }}">

                <table>
                  <tr>
                    <td width="5%"></td>
                    <td width="20%"><label>Nim</label></td>
                    <td width="60%"><label name="nim">{{ $data->Nim }}</label></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td><label>Nama</label></td>
                    <td><label name="nama">{{ $data->Full_Name }}</label></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td><label>Tanggal / Jam</label></td>
                    <td><label><input type="datetime-local" name="tgl_jam" value="{{ str_replace(' ', 'T', $data->Seminar_Date) }}" class="form-control form-control-sm"></label></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td><label>Ruang</label></td>
                    <td><label><select  class="form-control form-control-sm" name="Room_Id" id="Room_Id" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')">
                      <option value="">Pilih Ruang</option>
                      @foreach ( $select_room as $rm )
                        <option <?php if( $data->Seminar_Room  == $rm->Room_Id){ echo "selected"; } ?> value="{{ $rm->Room_Id }}">{{ $rm->Room_Name }}</option>
                      @endforeach
                    </select></td>
                  </tr>
                </table>

                  <br>@if(in_array('tugas_akhir-CanEdit', $acc))<button type="submit" class="btn btn-primary btn-flat">Simpan</button>@endif
                  <label class="col-sm-5"><a href="{{ url('proses/tugas_akhir/'.$data->Student_Id.'/export?proses=5') }}" class="btn btn-warning btn-sm" style="margin:5px;" target="_blank">Proses/Cetak <i class="fa fa-print"></i> </a></label>

                  {!! Form::close() !!}
                </div>
              </div>
            </div>
          </div>

          <!-- Modal Permohonan Pendadaran -->
          <div id="permohonan_pendadaran" class="modal">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header container-fluid-title customheader">
                <h4 class="modal-title text-white">Permohonan Pendadaran</h4>
                <a class="close5 trigger" href="#">
                  <i class="fa fa-times" aria-hidden="true"></i>
                </a>
              </div>
              <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">

                <p class="alert alert-danger" id="messageudggagal1" style="display: none"></p>
                <p class="alert alert-success" id="messageudgsukses1" style="display: none"></p>

                {!! Form::open(['url' => route('tugas_akhir.store_permohonan_pendadaran') , 'method' => 'POST','id'=>'permohonan_pendadaranform' , 'class' => 'form-horizontal', 'role' => 'form' , 'enctype' => 'multipart/form-data' ]) !!}
                {{ csrf_field() }}
                {{ method_field('post') }}
                <input type="text" name="id" hidden value="{{ $data->Thesis_Id }}">

                <table>
                  <tr>
                    <td></td>
                    <td><label>Nim</label></td>
                    <td  colspan="4"><label name="nim">{{ $data->Nim }}</label></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td><label>Nama</label></td>
                    <td colspan="4"><label name="nama">{{ $data->Full_Name }}</label></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td><label>Tanggal / Jam</label></td>
                    <td  colspan="4"><label><input type="datetime-local" name="tgl_jam" value="{{ str_replace(' ', 'T', $data->Seminar_Date) }}" class="form-control form-control-sm"></label></td>
                  </tr>

                  <?php
                    $ipk = 0;
                    if ($khs->Bnk != null && $khs->Sks_Trough != null) {
                      $ipk = $khs->Bnk / $khs->Sks_Trough;
                    }
                  ?>
                  <tr>
                    <td width="5%"></td>
                    <td width="15%"><label>IPK</label></td>
                    <td width="10%"><label><input id="bnk" type="text" name="bnk" value="{{ $khs->Bnk }}" class="form-control form-control-sm"></td>
                    <td wid="5%"><label> / </label></td>
                    <td width="10%"><label><input id="sks" type="text" value="{{ $khs->Sks_Trough }}" name="sks" value="" class="form-control form-control-sm"></td>
                    <td width="55%"><label><input id="ipk" type="disabled" name="" value="{{ $ipk }}"></label></td>
                  </tr>
                  <script type="text/javascript">
                    $('#bnk').keyup(function () {
                      var bnk = this.value;
                      var sks = $('#sks').val();
                      var ipk = 0;
                      if (bnk != 0 && sks!= 0) {
                        ipk = bnk / sks;
                      }
                      $('#ipk').val(ipk);
                    });

                    $('#sks').keyup(function () {
                      var sks = this.value;
                      var bnk = $('#bnk').val();
                      var ipk = 0;
                      if (bnk != 0 && sks!= 0) {
                        ipk = bnk / sks;
                      }
                      $('#ipk').val(ipk);
                    });
                  </script>

                  <tr>
                    <td></td>
                    <td><label>Judul TA</label></td>
                    <td  colspan="4"><label><textarea rows="4" cols="50" name="judul">{{ $data->Thesis_Title }}</textarea></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td><label>Judul TA English</label></td>
                    <td  colspan="4"><label><textarea rows="4" cols="50" name="judul_eng">{{ $data->Thesis_Title_Eng }}</textarea></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td colspan="6"><b><label style="font-siZe:15px;" for="" class="col-sm-6">PEJABAT PROGRAM STUDI</label></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td><label>Nama Jabatan</label></td>
                    <td  colspan="4"><label><input type="text" disabled value="{{ $ttd }}"></td>
                  </tr>

                  <?php
                  if($employee != null){
                    $employ = $employee->Full_Name;
                  }else{
                    $employ = "";
                  }
                  ?>
                  <tr>
                    <td></td>
                    <td><label>Nama Pejabat</label></td>
                    <td  colspan="4"><label><input type="text" disabled value="{{ $employ }}"></td>
                  </tr>
                </table>

                  <br>@if(in_array('tugas_akhir-CanEdit', $acc))<button type="submit" class="btn btn-primary btn-flat">Simpan</button>@endif
                  <label class="col-sm-5"><a href="{{ url('proses/tugas_akhir/'.$data->Student_Id.'/export?proses=8') }}" class="btn btn-warning btn-sm" style="margin:5px;" target="_blank">Proses/Cetak <i class="fa fa-print"></i> </a></label>

                  {!! Form::close() !!}
                </div>
              </div>
            </div>
          </div>

          <!-- Modal Pengantar Pembayaran -->
          <div id="pengantar_pembayaran" class="modal">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header container-fluid-title customheader">
                <h4 class="modal-title text-white">Pengantar Pembayaran Pendadaran</h4>
                <a class="close6 trigger" href="#">
                  <i class="fa fa-times" aria-hidden="true"></i>
                </a>
              </div>
              <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">

                <p class="alert alert-danger" id="messageudggagal2" style="display: none"></p>
                <p class="alert alert-success" id="messageudgsukses2" style="display: none"></p>

                {!! Form::open(['url' => route('tugas_akhir.export',$data->Student_Id) , 'method' => 'GET','target'=>'_blank','id'=>'pengantar_pembayaran' , 'class' => 'form-horizontal', 'role' => 'form' , 'enctype' => 'multipart/form-data' ]) !!}
                <!-- <input type="text" name="id" hidden value="{{ $data->Thesis_Id }}"> -->
                <input type="hidden" name="proses" value="9">
                <div class="col-sm-6">
                  <div >
                    <label for="" class="col-sm-5">Nim</label>
                    <label for="" name="nim" class="col-sm-5">{{ $data->Nim }}</label>
                  </div>

                  <div>
                    <label for="" class="col-sm-5">Nama</label>
                    <label for="" name="nama" class="col-sm-5">{{ $data->Full_Name }}</label>
                  </div>
                  <div>
                    <label for="" class="col-sm-5">Jumlah</label>
                    <label for="" name="nama" class="col-sm-5"><input type="text" name="jumlah" class="form-control form-control-sm"></label>
                  </div>
                  <div>
                    <label for="" class="col-sm-5">Terbilang</label>
                    <label for="" name="nama" class="col-sm-5"><input type="text" name="terbilang" class="form-control form-control-sm"></label>
                  </div>
                  <div>
                    <label for="" class="col-sm-5">Petugas</label>
                    <label for="" name="nama" class="col-sm-5"><input type="text" name="petugas" class="form-control form-control-sm"></label>
                  </div>

                  <label class="col-sm-5"><input class="btn btn-warning btn-sm" style="margin:5px;" type="submit" name="" value="Cetak"></label>


                  {!! Form::close() !!}
                </div>
              </div>
            </div>
          </div>

          <!-- Modal Undangan Pendadaran -->
          <div id="undangan_pendadaran" class="modal">
            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header container-fluid-title customheader">
                <h4 class="modal-title text-white">Undangan Pendadaran</h4>
                <a class="close7 trigger" href="#">
                  <i class="fa fa-times" aria-hidden="true"></i>
                </a>
              </div>
              <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">

                <p class="alert alert-danger" id="messageudggagal3" style="display: none"></p>
                <p class="alert alert-success" id="messageudgsukses3" style="display: none"></p>

                {!! Form::open(['url' => route('tugas_akhir.store_undangan_pendadaran') , 'method' => 'POST','id'=>'undangan_pendadaranform' , 'class' => 'form-horizontal', 'role' => 'form' , 'enctype' => 'multipart/form-data' ]) !!}
                {{ csrf_field() }}
                {{ method_field('post') }}
                <input type="text" name="id" hidden value="{{ $data->Thesis_Id }}">

                <?php
                $date = strtotime($data->Invitation_Thesis_Exam);
                $tgl_undangan = date('Y-m-d', $date);
                ?>

                <table>
                  <tr>
                    <td></td>
                    <td><label>Nim</label></td>
                    <td  colspan="4"><label name="nim">{{ $data->Nim }}</label></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td><label>Nama</label></td>
                    <td colspan="4"><label name="nama">{{ $data->Full_Name }}</label></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td><label>Tanggal / Jam</label></td>
                    <td><label><input type="datetime-local" name="tgl_jam" value="{{ str_replace(' ', 'T', $data->Thesis_Exam_Date) }}" class="form-control form-control-sm"></label></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td><label>Ruang</label></td>
                    <td><label><select  class="form-control form-control-sm" name="Room_Id" id="Room_Id" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')">
                      <option value="">Pilih Ruang</option>
                      @foreach ( $select_room as $rm )
                        <option <?php if( $data->Thesis_Exam_Room  == $rm->Room_Id){ echo "selected"; } ?> value="{{ $rm->Room_Id }}">{{ $rm->Room_Name }}</option>
                      @endforeach
                    </select></label></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td><label>Dosen Penguji 1</label></td>
                    <td><label>  <select class="form-control form-control-sm" name="penguji1">
                        <option value="">-- Pilih --</option>
                        @foreach($emp_employee as $emplo)
                          <option <?php if($data->examiner1 == $emplo->Employee_Id){ echo "selected"; } ?> value="{{ $emplo->Employee_Id }}">{{ $emplo->Full_Name }}</option>
                        @endforeach
                      </select></label></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td><label>Dosen Penguji 2</label></td>
                    <td><label>  <select class="form-control form-control-sm" name="penguji2">
                      <option value="">-- Pilih --</option>
                      @foreach($emp_employee as $emplo)
                        <option <?php if($data->examiner2 == $emplo->Employee_Id){ echo "selected"; } ?> value="{{ $emplo->Employee_Id }}">{{ $emplo->Full_Name }}</option>
                      @endforeach
                    </select></label></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td><label>Dosen Penguji 3</label></td>
                    <td><label>    <select class="form-control form-control-sm" name="penguji3">
                        <option value="">-- Pilih --</option>
                        @foreach($emp_employee as $emplo)
                          <option <?php if($data->examiner3 == $emplo->Employee_Id){ echo "selected"; } ?> value="{{ $emplo->Employee_Id }}">{{ $emplo->Full_Name }}</option>
                        @endforeach
                      </select></label></td>
                  </tr>
                  <tr>
                    <td></td>
                    <td><label>Tanggal Undangan</label></td>
                    <td><label><input type="date" name="tgl_undangan" value="{{ $tgl_undangan }}" class="form-control form-control-sm"></label></td>
                  </tr>
                </table>

                  <br>@if(in_array('tugas_akhir-CanEdit', $acc))<button type="submit" class="btn btn-primary btn-flat">Simpan</button>@endif
                  <label class="col-sm-5"><a href="{{ url('proses/tugas_akhir/'.$data->Student_Id.'/export?proses=10') }}" class="btn btn-warning btn-sm" style="margin:5px;" target="_blank">Proses/Cetak <i class="fa fa-print"></i> </a></label>

                  {!! Form::close() !!}
              </div>
            </div>
          </div>

        </div>
      </div>

    </div>
    <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
      @if (count($errors) > 0)
        @foreach ( $errors->all() as $error )
          <p class="alert alert-danger">{{ $error }}</p>
        @endforeach
      @endif
      <div class="table-responsive">
      </div>
    </div>
  </div>

  <script type="text/javascript">
  function proses() {
    var studentid = <?php echo $data->Student_Id; ?>;
    var department = <?php echo $department; ?>;
    var proses = $(".proses").val();
    var srtijin = document.getElementById('Srtijin');
    var srtseminar = document.getElementById('srt_permohonseminar');
    var udg_seminarta = document.getElementById('udg_seminarta');
    var permohonan_pendadaran = document.getElementById('permohonan_pendadaran');
    var pengantar_pembayaran = document.getElementById('pengantar_pembayaran');
    var undangan_pendadaran = document.getElementById('undangan_pendadaran');
    var span = document.getElementsByClassName("close2")[0];
    var span3 = document.getElementsByClassName("close3")[0];
    var span4 = document.getElementsByClassName("close4")[0];
    var span5 = document.getElementsByClassName("close5")[0];
    var span6 = document.getElementsByClassName("close6")[0];
    var span7 = document.getElementsByClassName("close7")[0];
    var url = {!! json_encode(url('/')) !!};
    if (proses==2) {
      srtijin.style.display = "block";
      span.onclick = function() {
          srtijin.style.display = "none";
          location.reload();
      }
    }
    else if (proses==4) {
      srtseminar.style.display = "block";
      span3.onclick = function() {
        srtseminar.style.display = "none";
        location.reload();
      }
    }
    else if (proses==5) {
      udg_seminarta.style.display = "block";
      span4.onclick = function() {
          udg_seminarta.style.display = "none";
          location.reload();
      }
    }
    else if (proses==8) {
      permohonan_pendadaran.style.display = "block";
      span5.onclick = function() {
          permohonan_pendadaran.style.display = "none";
          location.reload();
      }
    }
    else if (proses==9) {
      pengantar_pembayaran.style.display = "block";
      span6.onclick = function() {
          pengantar_pembayaran.style.display = "none";
          location.reload();
      }
    }
    else if (proses==10) {
      undangan_pendadaran.style.display = "block";
      span7.onclick = function() {
          undangan_pendadaran.style.display = "none";
          location.reload();
      }
    }else {
      window.open(url + '/proses/tugas_akhir/' + studentid +'/export?proses='+proses, '_blank');
    }
  }

    $("#srtijinta").submit(function (e) {
      e.preventDefault();

      $.ajax({
        url: "{{ route('tugas_akhir.store_srtijinta') }}",
        type: "POST",
        dataType: "json",
        data: $(this).serialize(),
        success: function (res) {
          $("#message").css('display', 'block').text(res.message);
        }
      })
    });
      $("#srtmohonseminar").submit(function (e) {
        e.preventDefault();

        $.ajax({
          url: "{{ route('tugas_akhir.store_srtmohonseminarta') }}",
          type: "POST",
          dataType: "json",
          data: $(this).serialize(),
          success: function (res) {
            $("#suratmohonseminar").css('display', 'block').text(res.message);
          }
        })
    });
    $("#undanganseminar").submit(function (e) {
      e.preventDefault();
      $.ajax({
        url: "{{ route('tugas_akhir.store_undanganseminar') }}",
        type: "POST",
        dataType: "json",
        data: $(this).serialize(),
        success: function (res) {
          if (res.message == "Berhasil menyimpan data") {
            $("#messageudgsukses").css('display', 'block').text(res.message);
          }else{
            $("#messageudggagal").css('display', 'block').text(res.message);
          }
        }
      })
    });

    $("#permohonan_pendadaranform").submit(function (e) {
      e.preventDefault();
      $.ajax({
        url: "{{ route('tugas_akhir.store_permohonan_pendadaran') }}",
        type: "POST",
        dataType: "json",
        data: $(this).serialize(),
        success: function (res) {
          if (res.message == "Berhasil menyimpan data") {
            $("#messageudgsukses1").css('display', 'block').text(res.message);
          }else{
            $("#messageudggagal1").css('display', 'block').text(res.message);
          }
        }
      })
    });

    $("#undangan_pendadaranform").submit(function (e) {
      e.preventDefault();
        console.log($(this).serialize());
      $.ajax({
        url: "{{ route('tugas_akhir.store_undangan_pendadaran') }}",
        type: "POST",
        dataType: "json",
        data: $(this).serialize(),
        success: function (res) {
          if (res.message == "Berhasil menyimpan data") {
            $("#messageudgsukses3").css('display', 'block').text(res.message);
          }else{
            $("#messageudggagal3").css('display', 'block').text(res.message);
          }
        }
      })
    });


</script>
</section>
@endsection
