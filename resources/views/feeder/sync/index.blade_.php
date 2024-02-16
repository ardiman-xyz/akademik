@extends('layouts._layout')
@section('pageTitle', 'Feeder - Configure WSDL')
@section('content')


<?php
$access = auth()->user()->akses();
          $acc = $access;

function js_str($s)
{
    return '"' . addcslashes($s, "\0..\37\"\\") . '"';
}

function js_array($array)
{
    if($array == null){$array = [];}
    $temp = array_map('js_str', $array);
    return '[' . implode(',', $temp) . ']';
}

?>

<section class="content">

  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Feeder - Sync SIAKAD to Feeder</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          <b>Export Data Feeder Sync SIAKAD dalam Format Feeder dalam Format (*.csv)</b>
        </div>
      </div>
    </div>
    <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        <div style="padding:20px;">
            <div class="alert alert-warning">Format Export dalam format .csv, kemudian Jalankan Menu Untuk Migrasi jika dirasa Data Csv yang dibuat sudah OK</div>
            <div class="alert alert-warning">Penting ! Pastikan pada menu Data Master - Program Studi, ID SMS diisi sesuai ID SMS prodi pada Data Ref. Feeder di menu System - Ref. Feeder</div>
            {{ Form::open(array('url' => 'feeder/sync', 'method' => 'get')) }}
            <table class="col-md-12" style="border:solid 1px;">
              <tr>
                <td style="width:50%;">
                  Tahun Akademik / Periode / Angkatan Khusus MHS
                  <input name="default_term_year" type="text" value="{{ $conf->default_term_year }}" style="float:right;">
                </td>
                <td style="width:50%;">
                  Format Tahun Akademik cukup dengan isi 20191 artinya Ganjil dan 20192 Artinya Genap
                </td>
              </tr>
              <tr>
                <td style="width:50%;">
                  <b>Pilih Prodi</b> <br></i>Kosongi / Select semua jika semua prodi</i>
                </td>
                <td style="width:50%;">
                  <b>Pilih Data Format Feeder</b> <br><i>Kosongi / Select semua Data</i>
                </td>
              </tr>
              <tr>
                <td style="width:50%;">
                  <select class="combobox" name="prodi[]" size="10" multiple="multiple" style="min-width:350px;width:100%;">
                    <option value="">---- Pilih Prodi ----</option>
                    @foreach($facultyDepartment as $fd)
                    <optgroup label="{{ $fd->Faculty_Name }}">
                      @foreach($fd->mstrDepartments as $dp)
                        <option <?php if($prodi !=null){ if(in_array($dp->Department_Id, $prodi)){echo 'selected';} } ?> value="{{ $dp->Department_Id }}">&nbsp;&nbsp;&nbsp;&nbsp; {{ $dp->Department_Name }} </option>
                      @endforeach
                    </optgroup>
                    @endforeach
                  </select>
                </td>
                <td style="width:50%;">
                  <select name="tipeData[]" style="min-width:350px;width:100%;" multiple="multiple" size="10" class="combobox">
                    <option value="">--PILIH--</option>
                    <optgroup label="Data Sinkron Web Service">
                      <option  <?php if($tipeData !=null){ if(in_array('mahasiswa', $tipeData)){echo 'selected';} } ?>   value="mahasiswa">Data Mahasiswa</option>
                      <option  <?php if($tipeData !=null){ if(in_array('mata_kuliah', $tipeData)){echo 'selected';} } ?>   value="mata_kuliah">Data Kurikulum Per Program Studi per Periode</option>
                      <option  <?php if($tipeData !=null){ if(in_array('kelas_kuliah', $tipeData)){echo 'selected';} } ?>   value="kelas_kuliah">Kelas Perkuliahan</option>
                      <option  <?php if($tipeData !=null){ if(in_array('ajar_dosen', $tipeData)){echo 'selected';} } ?>   value="ajar_dosen">Dosen Mengajar</option>
                      <option  <?php if($tipeData !=null){ if(in_array('bobot_nilai', $tipeData)){echo 'selected';} } ?>   value="bobot_nilai">Bobot Nilai</option>
                      <option  <?php if($tipeData !=null){ if(in_array('nilai', $tipeData)){echo 'selected';} } ?>   value="nilai">Peserta dan Nilai mahasiswa per kelas per periode</option>
                      <option  <?php if($tipeData !=null){ if(in_array('kuliah_mahasiswa', $tipeData)){echo 'selected';} } ?>   value="kuliah_mahasiswa">Aktivitas Kuliah Mahasiswa</option>
                      <option  <?php if($tipeData !=null){ if(in_array('nilai_transfer', $tipeData)){echo 'selected';} } ?>   value="nilai_transfer">Nilai Transfer / Alih Jenjang</option>
                      <option  <?php if($tipeData !=null){ if(in_array('mahasiswa_lulus_do', $tipeData)){echo 'selected';} } ?>   value="mahasiswa_lulus_do">Mahasiswa Yang Lulus atau DO atau Mengundurkan Diri</option>
                      <option  <?php if($tipeData !=null){ if(in_array('dosen_pembimbing', $tipeData)){echo 'selected';} } ?>   value="dosen_pembimbing">Dosen Pembimbing</option>
                      <option  <?php if($tipeData !=null){ if(in_array('bimbing_mahasiswa', $tipeData)){echo 'selected';} } ?>   value="bimbing_mahasiswa">Pembimbing Tugas Akhir Mahasiswa</option>
                      <option  <?php if($tipeData !=null){ if(in_array('aktivitas_mahasiswa', $tipeData)){echo 'selected';} } ?>   value="aktivitas_mahasiswa">Tugas Akhir Mahasiswa</option>
                      <option  <?php if($tipeData !=null){ if(in_array('anggota_aktivitas_mahasiswa', $tipeData)){echo 'selected';} } ?>   value="anggota_aktivitas_mahasiswa">Anggota aktivitas mahasiswa</option>
                      <option  <?php if($tipeData !=null){ if(in_array('prestasi', $tipeData)){echo 'selected';} } ?>   value="prestasi">Prestasi mahasiswa</option>
                   </optgroup>
                  </select>
                </td>
              </tr>
            </table>
            <br>
            <input id="type" name="type" type="hidden" value="">
            <center>
              <button onclick="$('#type').val('detail');" type="submit" class="btn btn-danger">Lihat Hasil / Upload Download / Sinkronisasi</button>
              <button onclick="$('#type').val('download');" type="submit" class="btn btn-success">DOWNLOAD</button>
            </center>
            {{ Form::close() }}
        </div>
    </div>

    @if($type!=null)
    @if( $type == 'download')
      @if($prodi != null && $term_year != null && $tipeData != null)
      <div class="panel panel-default bootstrap-admin-no-table-panel">
        <div class="panel-heading-green">
          <div class="bootstrap-admin-box-title right text-white">
            <b>PROSES EXPORT:</b>
          </div>
        </div>
      </div>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        <div style="padding:20px;">

          <table class="col-md-12" style="border:solid 1px;">
            <tr>
              <td style="width:5%;">No</td>
              <td style="width:35%;">Format</td>
              <td style="width:40%;">Data</td>
              <td style="width:20%;"></td>
            </tr>
            @php
                $no = 1;
            @endphp
            @foreach ($tipeData as $item)

            <script>

                var prodi =  '<?php echo js_array($prodi) ?>';
                var format = '<?php echo $item ?>';
                var no = '<?php echo $no ?>';
                // console.log(no);

                $.ajax({
                  method: "GET",
                  url: "{!! url('/feeder/sync/create') !!}",
                  data: { prodi: prodi, format: format, no: no },
                  beforeSend: function () {

                  },
                  complete: function () {
                    
                  },
                  success: function (badges) {
                    $('#loading'+badges.no).hide();
                    $('.data'+badges.no).show();
                    $('.datahref'+badges.no).show();
                    $('.datahref'+badges.no).attr("href", "{!! url('/storage/"+badges.file_name+"') !!}");
                  },
                  error: function(xhr, status, error){
                    $('#loading'+badges.no).hide();
                    $('.error'+badges.no).show();
                  }
                });
            </script>


            <tr>
              <td>{{$no}}</td>
              <td>{{$item}}</td>
              <td>
                <div id="loading<?php echo $no; ?>"><center><img style="width:12%;" src="/img/loading.gif" alt=""></center></div>
                <div class="data<?php echo $no; ?>" style="display:none;">
                  Was Created ....   <button onclick="$('.file'+<?php echo $no; ?>).show();" class="btn btn-danger">New Update Upload</button>
                  <form method="get" id="upload_update<?php echo $no; ?>" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input name="no" type="text" value="<?php echo $no; ?>" style="display:none;">
                    <input name="prodi" type="text" value="<?php echo implode(',', $prodi); ?>" style="display:none;">
                    <input name="format" type="text" value="<?php echo $item; ?>" style="display:none;">
                    <input class="file<?php echo $no; ?>" name="csv" type="file" style="display:none;">
                    <button style="display:none;" class="file<?php echo $no; ?> btn btn-primary" type="submit">Simpan</button>
                    <div class="saveupdate<?php echo $no; ?>" style="display:none;">Berhasil Tersimpan</div>
                    <div class="errorupdate<?php echo $no; ?>" style="display:none;">Gagal Tersimpan</div>
                  </form>
                  <script>

                    var prodi =  '<?php echo js_array($prodi) ?>';
                    var format = '<?php echo $item ?>';
                    var no = '<?php echo $no ?>';
                    // console.log(no);
                    $(document).ready(function(){

                      $('#upload_update'+no).on('submit', function(event){
                      event.preventDefault();
                        $.ajax({
                          url:"{!! url('/feeder/sync/new_update') !!}",
                          method:"POST",
                          data:new FormData(this),
                          dataType:'JSON',
                          contentType: false,
                          cache: false,
                          processData: false,
                          success:function(data)
                          {
                            $('.saveupdate'+data.no).show();
                          },
                          error: function(xhr, status, error){
                            $('.errorupdate'+data.no).show();
                          }
                        })
                      });

                    });
                </script>
                
                </div>
                <div class="error<?php echo $no; ?>" style="display:none;">Terjadi Kesalahan, Pastikan Data Simakad Sudah Benar</div>
              </td>
              <td><a href="" class="btn btn-primary datahref<?php echo $no; ?>"  style="display:none;">Download</a></td>
            </tr>    
            @php
                $no++;
            @endphp
            @endforeach
          </table>
        </div>
      </div>
      @else
      <div class="alert alert-warning">Pastikan Sudah Memilih Prodi, Tahun Ajaran, dan Type Format Feeder</div>
      @endif
    @endif

    @if( $type == 'detail')
      @if($prodi != null && $term_year != null)
      <div class="panel panel-default bootstrap-admin-no-table-panel">
        <div class="panel-heading-green">
          <div class="bootstrap-admin-box-title right text-white">
            <b>Daftar Laporan Hasil Ekspor Ke dalam Format Feeder</b>
          </div>
        </div>
      </div>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        <div style="padding:20px;">
          <table class="col-md-12" style="border:solid 1px; background:#e8eef2;">
            <tr>
              <td><b>Tahun Akademik : {{$term_year}}</b></td>
            </tr>
            <tr>
              <td><b>Program Studi  : {{$program_studi}}</b></td>
            </tr>
            <tr>
              <td style="padding:10px;"><div class="alert alert-warning">Silahkan Download File Dibawah ini, kemudian Klik Download / Upload Versi Sendiri / Hapus</div></td>
            </tr>
          </table>
          <br>

          <table class="col-md-12" style="border:solid 1px;">
            <tr style="background:#FFFFAF;">
              <td style="width:5%;"><b>No</b></b></td>
              <td style="width:18%;"><b>Data</b></td>
              <td style="width:18%;"><b>Date Created</b></td>
              <td style="width:18%;"><b>Date Upload Updated</b></td>
              <td style="width:18%;"><b>Log Error Reporting</b></td>
              <td style="width:18%;"></td>
              <td style="width:5%;">
                <input onclick="onchecked()" id="check" type="checkbox">
                <script>
                  function onchecked() {
                    var checkBox = document.getElementById("check");
                    if (checkBox.checked == true){
                      $('.checks').prop('checked', true);
                    } else {
                      $('.checks').prop('checked', false);
                    }
                  }
                </script>
              </td>
            </tr>
            <tr style="background:#FFFFAF;">
              <td colspan="7">
                <b style="color:#BD0000;">Data Sinkron Web Service (Sync File To Feeder DIKTI)</b>
              </td>
            </tr>
            <tr>
              <td>1</td>
              <td>Data Mahasiswa</td>
              <td><?php if($dataDetailNew[0] != null){ ?><a href="{!! url('/storage/'.$dataDetailNew[0]->File_Name.'') !!}">Download</a>&nbsp<a href="" style="color:red;">X</a> <?php } ?></td>
              <td><?php if($dataDetailNew[0] != null){ ?><a href="{!! url('/storage/'.$dataDetailNew[0]->File_Name.'') !!}">Download</a>&nbsp<a href="" style="color:red;">X</a> <?php } ?></td>
              <td></td>
              <td>
                <a onclick="$('.fileMahasiswa').show();" href="javascript:void(0);">New Update Upload</a>
                <form method="get" id="upload_updateMahasiswa" enctype="multipart/form-data">
                  {{ csrf_field() }}
                  <input name="prodi" type="text" value="<?php echo implode(',', $prodi); ?>" style="display:none;">
                  <input name="format" type="text" value="mahasiswa" style="display:none;">
                  <input class="fileMahasiswa" name="csv" type="file" style="display:none;">
                  <button style="display:none;" class="fileMahasiswa btn btn-primary" type="submit">Simpan</button>
                  <div class="saveupdateMahasiswa" style="display:none;">Berhasil Tersimpan</div>
                  <div class="errorupdateMahasiswa" style="display:none;">Gagal Tersimpan</div>
                </form>
                <script>

                  var prodi =  '<?php echo js_array($prodi) ?>';
                  var format = 'mahasiswa';
                  // console.log(no);
                  $(document).ready(function(){

                    $('#upload_updateMahasiswa').on('submit', function(event){
                    event.preventDefault();
                      $.ajax({
                        url:"{!! url('/feeder/sync/new_update') !!}",
                        method:"POST",
                        data:new FormData(this),
                        dataType:'JSON',
                        contentType: false,
                        cache: false,
                        processData: false,
                        success:function(data)
                        {
                          $('.saveupdateMahasiswa').show();
                          location.reload();
                        },
                        error: function(xhr, status, error){
                          $('.errorupdateMahasiswa').show();
                        }
                      })
                    });

                  });
              </script>
              
              </td>
              <td style="width:5%;"><input name="mahasiswa" class="checks" type="checkbox"></td>
            </tr>
            <tr>
              <td>2</td>
              <td>Data Kurikulum Per Program Studi per Periode</td>
              <td><?php if($dataDetailNew[1] != null){ ?><a href="{!! url('/storage/'.$dataDetailNew[1]->File_Name.'') !!}">Download</a>&nbsp<a href="" style="color:red;">X</a> <?php } ?></td>
              <td><?php if($dataDetailNew[1] != null){ ?><a href="{!! url('/storage/'.$dataDetailNew[1]->File_Name.'') !!}">Download</a>&nbsp<a href="" style="color:red;">X</a> <?php } ?></td>
              <td></td>
              <td>
                <a onclick="$('.fileMataKuliah').show();" href="javascript:void(0);">New Update Upload</a>
                <form method="get" id="upload_updateMataKuliah" enctype="multipart/form-data">
                  {{ csrf_field() }}
                  <input name="prodi" type="text" value="<?php echo implode(',', $prodi); ?>" style="display:none;">
                  <input name="format" type="text" value="mahasiswa" style="display:none;">
                  <input class="fileMataKuliah" name="csv" type="file" style="display:none;">
                  <button style="display:none;" class="fileMataKuliah btn btn-primary" type="submit">Simpan</button>
                  <div class="saveupdateMataKuliah" style="display:none;">Berhasil Tersimpan</div>
                  <div class="errorupdateMataKuliah" style="display:none;">Gagal Tersimpan</div>
                </form>
                <script>

                  var prodi =  '<?php echo js_array($prodi) ?>';
                  var format = 'mata_kuliah';
                  // console.log(no);
                  $(document).ready(function(){

                    $('#upload_updateMataKuliah').on('submit', function(event){
                    event.preventDefault();
                      $.ajax({
                        url:"{!! url('/feeder/sync/new_update') !!}",
                        method:"POST",
                        data:new FormData(this),
                        dataType:'JSON',
                        contentType: false,
                        cache: false,
                        processData: false,
                        success:function(data)
                        {
                          $('.saveupdateMataKuliah').show();
                          location.reload();
                        },
                        error: function(xhr, status, error){
                          $('.saveupdateMataKuliah').show();
                        }
                      })
                    });

                  });
              </script>
              
              </td>
              <td style="width:5%;"><input name="mata_kuliah" class="checks" type="checkbox"></td>
            </tr>
            <tr>
              <td>3</td>
              <td>Kelas Perkuliahan</td>
              <td><?php if($dataDetailNew[2] != null){ ?><a href="{!! url('/storage/'.$dataDetailNew[2]->File_Name.'') !!}">Download</a>&nbsp<a href="" style="color:red;">X</a> <?php } ?></td>
              <td><?php if($dataDetailNew[2] != null){ ?><a href="{!! url('/storage/'.$dataDetailNew[2]->File_Name.'') !!}">Download</a>&nbsp<a href="" style="color:red;">X</a> <?php } ?></td>
              <td></td>
              <td>
                <a onclick="$('.fileKelasKuliah').show();" href="javascript:void(0);">New Update Upload</a>
                <form method="get" id="upload_updateKelasKuliah" enctype="multipart/form-data">
                  {{ csrf_field() }}
                  <input name="prodi" type="text" value="<?php echo implode(',', $prodi); ?>" style="display:none;">
                  <input name="format" type="text" value="KelasKuliah" style="display:none;">
                  <input class="fileKelasKuliah" name="csv" type="file" style="display:none;">
                  <button style="display:none;" class="fileKelasKuliah btn btn-primary" type="submit">Simpan</button>
                  <div class="saveupdateKelasKuliah" style="display:none;">Berhasil Tersimpan</div>
                  <div class="errorupdateKelasKuliah" style="display:none;">Gagal Tersimpan</div>
                </form>
                <script>

                  var prodi =  '<?php echo js_array($prodi) ?>';
                  var format = 'kelas_kuliah';
                  // console.log(no);
                  $(document).ready(function(){

                    $('#upload_updateKelasKuliah').on('submit', function(event){
                    event.preventDefault();
                      $.ajax({
                        url:"{!! url('/feeder/sync/new_update') !!}",
                        method:"POST",
                        data:new FormData(this),
                        dataType:'JSON',
                        contentType: false,
                        cache: false,
                        processData: false,
                        success:function(data)
                        {
                          $('.saveupdateKelasKuliah').show();
                          location.reload();
                        },
                        error: function(xhr, status, error){
                          $('.errorupdateKelasKuliah').show();
                        }
                      })
                    });

                  });
              </script>
              
              </td>
              <td style="width:5%;"><input name="kelas_kuliah" class="checks" type="checkbox"></td>
            </tr>
            <tr>
              <td>4</td>
              <td>Dosen Mengajar</td>
              <td><?php if($dataDetailNew[3] != null){ ?><a href="{!! url('/storage/'.$dataDetailNew[3]->File_Name.'') !!}">Download</a>&nbsp<a href="" style="color:red;">X</a> <?php } ?></td>
              <td><?php if($dataDetailNew[3] != null){ ?><a href="{!! url('/storage/'.$dataDetailNew[3]->File_Name.'') !!}">Download</a>&nbsp<a href="" style="color:red;">X</a> <?php } ?></td>
              <td></td>
              <td>
                <a onclick="$('.fileajar_dosen').show();" href="javascript:void(0);">New Update Upload</a>
                <form method="get" id="upload_updateajar_dosen" enctype="multipart/form-data">
                  {{ csrf_field() }}
                  <input name="prodi" type="text" value="<?php echo implode(',', $prodi); ?>" style="display:none;">
                  <input name="format" type="text" value="ajar_dosen" style="display:none;">
                  <input class="fileajar_dosen" name="csv" type="file" style="display:none;">
                  <button style="display:none;" class="fileajar_dosen btn btn-primary" type="submit">Simpan</button>
                  <div class="saveupdateajar_dosen" style="display:none;">Berhasil Tersimpan</div>
                  <div class="errorupdateajar_dosen" style="display:none;">Gagal Tersimpan</div>
                </form>
                <script>

                  var prodi =  '<?php echo js_array($prodi) ?>';
                  var format = 'ajar_dosen';
                  // console.log(no);
                  $(document).ready(function(){

                    $('#upload_updateajar_dosen').on('submit', function(event){
                    event.preventDefault();
                      $.ajax({
                        url:"{!! url('/feeder/sync/new_update') !!}",
                        method:"POST",
                        data:new FormData(this),
                        dataType:'JSON',
                        contentType: false,
                        cache: false,
                        processData: false,
                        success:function(data)
                        {
                          $('.saveupdateajar_dosen').show();
                          location.reload();
                        },
                        error: function(xhr, status, error){
                          $('.errorupdateajar_dosen').show();
                        }
                      })
                    });

                  });
              </script>
              
              </td>
              <td style="width:5%;"><input name="ajar_dosen" class="checks" type="checkbox"></td>
            </tr>
            <tr>
              <td>5</td>
              <td>Bobot Nilai</td>
              <td><?php if($dataDetailNew[4] != null){ ?><a href="{!! url('/storage/'.$dataDetailNew[4]->File_Name.'') !!}">Download</a>&nbsp<a href="" style="color:red;">X</a> <?php } ?></td>
              <td><?php if($dataDetailNew[4] != null){ ?><a href="{!! url('/storage/'.$dataDetailNew[4]->File_Name.'') !!}">Download</a>&nbsp<a href="" style="color:red;">X</a> <?php } ?></td>
              <td></td>
              <td>
                <a onclick="$('.filebobot_nilai').show();" href="javascript:void(0);">New Update Upload</a>
                <form method="get" id="upload_updatebobot_nilai" enctype="multipart/form-data">
                  {{ csrf_field() }}
                  <input name="prodi" type="text" value="<?php echo implode(',', $prodi); ?>" style="display:none;">
                  <input name="format" type="text" value="bobot_nilai" style="display:none;">
                  <input class="filebobot_nilai" name="csv" type="file" style="display:none;">
                  <button style="display:none;" class="filebobot_nilai btn btn-primary" type="submit">Simpan</button>
                  <div class="saveupdatebobot_nilai" style="display:none;">Berhasil Tersimpan</div>
                  <div class="errorupdatebobot_nilai" style="display:none;">Gagal Tersimpan</div>
                </form>
                <script>

                  var prodi =  '<?php echo js_array($prodi) ?>';
                  var format = 'bobot_nilai';
                  // console.log(no);
                  $(document).ready(function(){

                    $('#upload_updatebobot_nilai').on('submit', function(event){
                    event.preventDefault();
                      $.ajax({
                        url:"{!! url('/feeder/sync/new_update') !!}",
                        method:"POST",
                        data:new FormData(this),
                        dataType:'JSON',
                        contentType: false,
                        cache: false,
                        processData: false,
                        success:function(data)
                        {
                          $('.saveupdatebobot_nilai').show();
                          location.reload();
                        },
                        error: function(xhr, status, error){
                          $('.errorupdatebobot_nilai').show();
                        }
                      })
                    });

                  });
              </script>
              
              </td>
              <td style="width:5%;"><input name="bobot_nilai" class="checks" type="checkbox"></td>
            </tr>
            <tr>
              <td>6</td>
              <td>Peserta dan Nilai mahasiswa per kelas per periode</td>
              <td><?php if($dataDetailNew[5] != null){ ?><a href="{!! url('/storage/'.$dataDetailNew[5]->File_Name.'') !!}">Download</a>&nbsp<a href="" style="color:red;">X</a> <?php } ?></td>
              <td><?php if($dataDetailNew[5] != null){ ?><a href="{!! url('/storage/'.$dataDetailNew[5]->File_Name.'') !!}">Download</a>&nbsp<a href="" style="color:red;">X</a> <?php } ?></td>
              <td></td>
              <td>
                <a onclick="$('.filenilai').show();" href="javascript:void(0);">New Update Upload</a>
                <form method="get" id="upload_updatenilai" enctype="multipart/form-data">
                  {{ csrf_field() }}
                  <input name="prodi" type="text" value="<?php echo implode(',', $prodi); ?>" style="display:none;">
                  <input name="format" type="text" value="nilai" style="display:none;">
                  <input class="filenilai" name="csv" type="file" style="display:none;">
                  <button style="display:none;" class="filenilai btn btn-primary" type="submit">Simpan</button>
                  <div class="saveupdatenilai" style="display:none;">Berhasil Tersimpan</div>
                  <div class="errorupdatenilai" style="display:none;">Gagal Tersimpan</div>
                </form>
                <script>

                  var prodi =  '<?php echo js_array($prodi) ?>';
                  var format = 'nilai';
                  // console.log(no);
                  $(document).ready(function(){

                    $('#upload_updatenilai').on('submit', function(event){
                    event.preventDefault();
                      $.ajax({
                        url:"{!! url('/feeder/sync/new_update') !!}",
                        method:"POST",
                        data:new FormData(this),
                        dataType:'JSON',
                        contentType: false,
                        cache: false,
                        processData: false,
                        success:function(data)
                        {
                          $('.saveupdatenilai').show();
                          location.reload();
                        },
                        error: function(xhr, status, error){
                          $('.errorupdatenilai').show();
                        }
                      })
                    });

                  });
              </script>
              
              </td>
              <td style="width:5%;"><input name="nilai" class="checks" type="checkbox"></td>
            </tr>
            <tr>
              <td>7</td>
              <td>Aktivitas Kuliah Mahasiswa</td>
              <td><?php if($dataDetailNew[6] != null){ ?><a href="{!! url('/storage/'.$dataDetailNew[6]->File_Name.'') !!}">Download</a>&nbsp<a href="" style="color:red;">X</a> <?php } ?></td>
              <td><?php if($dataDetailNew[6] != null){ ?><a href="{!! url('/storage/'.$dataDetailNew[6]->File_Name.'') !!}">Download</a>&nbsp<a href="" style="color:red;">X</a> <?php } ?></td>
              <td></td>
              <td>
                <a onclick="$('.filekuliah_mahasiswa').show();" href="javascript:void(0);">New Update Upload</a>
                <form method="get" id="upload_updatekuliah_mahasiswa" enctype="multipart/form-data">
                  {{ csrf_field() }}
                  <input name="prodi" type="text" value="<?php echo implode(',', $prodi); ?>" style="display:none;">
                  <input name="format" type="text" value="kuliah_mahasiswa" style="display:none;">
                  <input class="filekuliah_mahasiswa" name="csv" type="file" style="display:none;">
                  <button style="display:none;" class="filekuliah_mahasiswa btn btn-primary" type="submit">Simpan</button>
                  <div class="saveupdatekuliah_mahasiswa" style="display:none;">Berhasil Tersimpan</div>
                  <div class="errorupdatekuliah_mahasiswa" style="display:none;">Gagal Tersimpan</div>
                </form>
                <script>

                  var prodi =  '<?php echo js_array($prodi) ?>';
                  var format = 'kuliah_mahasiswa';
                  // console.log(no);
                  $(document).ready(function(){

                    $('#upload_updatekuliah_mahasiswa').on('submit', function(event){
                    event.preventDefault();
                      $.ajax({
                        url:"{!! url('/feeder/sync/new_update') !!}",
                        method:"POST",
                        data:new FormData(this),
                        dataType:'JSON',
                        contentType: false,
                        cache: false,
                        processData: false,
                        success:function(data)
                        {
                          $('.saveupdatekuliah_mahasiswa').show();
                          location.reload();
                        },
                        error: function(xhr, status, error){
                          $('.errorupdatekuliah_mahasiswa').show();
                        }
                      })
                    });

                  });
              </script>
              
              </td>
              <td style="width:5%;"><input name="kuliah_mahasiswa" class="checks" type="checkbox"></td>
            </tr>
            <tr>
              <td>8</td>
              <td>Nilai Transfer / Alih Jenjang</td>
              <td><?php if($dataDetailNew[7] != null){ ?><a href="{!! url('/storage/'.$dataDetailNew[7]->File_Name.'') !!}">Download</a>&nbsp<a href="" style="color:red;">X</a> <?php } ?></td>
              <td><?php if($dataDetailNew[7] != null){ ?><a href="{!! url('/storage/'.$dataDetailNew[7]->File_Name.'') !!}">Download</a>&nbsp<a href="" style="color:red;">X</a> <?php } ?></td>
              <td></td>
              <td>
                <a onclick="$('.filenilai_transfer').show();" href="javascript:void(0);">New Update Upload</a>
                <form method="get" id="upload_updatenilai_transfer" enctype="multipart/form-data">
                  {{ csrf_field() }}
                  <input name="prodi" type="text" value="<?php echo implode(',', $prodi); ?>" style="display:none;">
                  <input name="format" type="text" value="nilai_transfer" style="display:none;">
                  <input class="filenilai_transfer" name="csv" type="file" style="display:none;">
                  <button style="display:none;" class="filenilai_transfer btn btn-primary" type="submit">Simpan</button>
                  <div class="saveupdatenilai_transfer" style="display:none;">Berhasil Tersimpan</div>
                  <div class="errorupdatenilai_transfer" style="display:none;">Gagal Tersimpan</div>
                </form>
                <script>

                  var prodi =  '<?php echo js_array($prodi) ?>';
                  var format = 'nilai_transfer';
                  // console.log(no);
                  $(document).ready(function(){

                    $('#upload_updatenilai_transfer').on('submit', function(event){
                    event.preventDefault();
                      $.ajax({
                        url:"{!! url('/feeder/sync/new_update') !!}",
                        method:"POST",
                        data:new FormData(this),
                        dataType:'JSON',
                        contentType: false,
                        cache: false,
                        processData: false,
                        success:function(data)
                        {
                          $('.saveupdatenilai_transfer').show();
                          location.reload();
                        },
                        error: function(xhr, status, error){
                          $('.errorupdatenilai_transfer').show();
                        }
                      })
                    });

                  });
              </script>
              
              </td>
              <td style="width:5%;"><input name="nilai_transfer" class="checks" type="checkbox"></td>
            </tr>
            <tr>
              <td>9</td>
              <td>Mahasiswa Yang Lulus atau DO atau Mengundurkan Diri</td>
              <td><?php if($dataDetailNew[8] != null){ ?><a href="{!! url('/storage/'.$dataDetailNew[8]->File_Name.'') !!}">Download</a>&nbsp<a href="" style="color:red;">X</a> <?php } ?></td>
              <td><?php if($dataDetailNew[8] != null){ ?><a href="{!! url('/storage/'.$dataDetailNew[8]->File_Name.'') !!}">Download</a>&nbsp<a href="" style="color:red;">X</a> <?php } ?></td>
              <td></td>
              <td>
                <a onclick="$('.filemahasiswa_lulus_do').show();" href="javascript:void(0);">New Update Upload</a>
                <form method="get" id="upload_updatemahasiswa_lulus_do" enctype="multipart/form-data">
                  {{ csrf_field() }}
                  <input name="prodi" type="text" value="<?php echo implode(',', $prodi); ?>" style="display:none;">
                  <input name="format" type="text" value="mahasiswa_lulus_do" style="display:none;">
                  <input class="filemahasiswa_lulus_do" name="csv" type="file" style="display:none;">
                  <button style="display:none;" class="filemahasiswa_lulus_do btn btn-primary" type="submit">Simpan</button>
                  <div class="saveupdatemahasiswa_lulus_do" style="display:none;">Berhasil Tersimpan</div>
                  <div class="errorupdatemahasiswa_lulus_do" style="display:none;">Gagal Tersimpan</div>
                </form>
                <script>

                  var prodi =  '<?php echo js_array($prodi) ?>';
                  var format = 'mahasiswa_lulus_do';
                  // console.log(no);
                  $(document).ready(function(){

                    $('#upload_updatemahasiswa_lulus_do').on('submit', function(event){
                    event.preventDefault();
                      $.ajax({
                        url:"{!! url('/feeder/sync/new_update') !!}",
                        method:"POST",
                        data:new FormData(this),
                        dataType:'JSON',
                        contentType: false,
                        cache: false,
                        processData: false,
                        success:function(data)
                        {
                          $('.saveupdatemahasiswa_lulus_do').show();
                          location.reload();
                        },
                        error: function(xhr, status, error){
                          $('.errorupdatemahasiswa_lulus_do').show();
                        }
                      })
                    });

                  });
              </script>
              
              </td>
              <td style="width:5%;"><input name="mahasiswa_lulus_do" class="checks" type="checkbox"></td>
            </tr>
            <tr>
              <td>10</td>
              <td>Dosen Pembimbing</td>
              <td><?php if($dataDetailNew[9] != null){ ?><a href="{!! url('/storage/'.$dataDetailNew[9]->File_Name.'') !!}">Download</a>&nbsp<a href="" style="color:red;">X</a> <?php } ?></td>
              <td><?php if($dataDetailNew[9] != null){ ?><a href="{!! url('/storage/'.$dataDetailNew[9]->File_Name.'') !!}">Download</a>&nbsp<a href="" style="color:red;">X</a> <?php } ?></td>
              <td></td>
              <td>
                <a onclick="$('.filedosen_pembimbing').show();" href="javascript:void(0);">New Update Upload</a>
                <form method="get" id="upload_updatedosen_pembimbing" enctype="multipart/form-data">
                  {{ csrf_field() }}
                  <input name="prodi" type="text" value="<?php echo implode(',', $prodi); ?>" style="display:none;">
                  <input name="format" type="text" value="dosen_pembimbing" style="display:none;">
                  <input class="filedosen_pembimbing" name="csv" type="file" style="display:none;">
                  <button style="display:none;" class="filedosen_pembimbing btn btn-primary" type="submit">Simpan</button>
                  <div class="saveupdatedosen_pembimbing" style="display:none;">Berhasil Tersimpan</div>
                  <div class="errorupdatedosen_pembimbing" style="display:none;">Gagal Tersimpan</div>
                </form>
                <script>

                  var prodi =  '<?php echo js_array($prodi) ?>';
                  var format = 'dosen_pembimbing';
                  // console.log(no);
                  $(document).ready(function(){

                    $('#upload_updatedosen_pembimbing').on('submit', function(event){
                    event.preventDefault();
                      $.ajax({
                        url:"{!! url('/feeder/sync/new_update') !!}",
                        method:"POST",
                        data:new FormData(this),
                        dataType:'JSON',
                        contentType: false,
                        cache: false,
                        processData: false,
                        success:function(data)
                        {
                          $('.saveupdatedosen_pembimbing').show();
                          location.reload();
                        },
                        error: function(xhr, status, error){
                          $('.errorupdatedosen_pembimbing').show();
                        }
                      })
                    });

                  });
              </script>
              
              </td>
              <td style="width:5%;"><input name="dosen_pembimbing" class="checks" type="checkbox"></td>
            </tr>
            <tr>
              <td>11</td>
              <td>Pembimbing Tugas Akhir Mahasiswa</td>
              <td><?php if($dataDetailNew[10] != null){ ?><a href="{!! url('/storage/'.$dataDetailNew[10]->File_Name.'') !!}">Download</a>&nbsp<a href="" style="color:red;">X</a> <?php } ?></td>
              <td><?php if($dataDetailNew[10] != null){ ?><a href="{!! url('/storage/'.$dataDetailNew[10]->File_Name.'') !!}">Download</a>&nbsp<a href="" style="color:red;">X</a> <?php } ?></td>
              <td></td>
              <td>
                <a onclick="$('.filebimbing_mahasiswa').show();" href="javascript:void(0);">New Update Upload</a>
                <form method="get" id="upload_updatebimbing_mahasiswa" enctype="multipart/form-data">
                  {{ csrf_field() }}
                  <input name="prodi" type="text" value="<?php echo implode(',', $prodi); ?>" style="display:none;">
                  <input name="format" type="text" value="bimbing_mahasiswa" style="display:none;">
                  <input class="filebimbing_mahasiswa" name="csv" type="file" style="display:none;">
                  <button style="display:none;" class="filebimbing_mahasiswa btn btn-primary" type="submit">Simpan</button>
                  <div class="saveupdatebimbing_mahasiswa" style="display:none;">Berhasil Tersimpan</div>
                  <div class="errorupdatebimbing_mahasiswa" style="display:none;">Gagal Tersimpan</div>
                </form>
                <script>

                  var prodi =  '<?php echo js_array($prodi) ?>';
                  var format = 'bimbing_mahasiswa';
                  // console.log(no);
                  $(document).ready(function(){

                    $('#upload_updatebimbing_mahasiswa').on('submit', function(event){
                    event.preventDefault();
                      $.ajax({
                        url:"{!! url('/feeder/sync/new_update') !!}",
                        method:"POST",
                        data:new FormData(this),
                        dataType:'JSON',
                        contentType: false,
                        cache: false,
                        processData: false,
                        success:function(data)
                        {
                          $('.saveupdatebimbing_mahasiswa').show();
                          location.reload();
                        },
                        error: function(xhr, status, error){
                          $('.errorupdatebimbing_mahasiswa').show();
                        }
                      })
                    });

                  });
              </script>
              
              </td>
              <td style="width:5%;"><input name="bimbing_mahasiswa" class="checks" type="checkbox"></td>
            </tr>
            <tr>
              <td>12</td>
              <td>Tugas Akhir Mahasiswa</td>
              <td><?php if($dataDetailNew[12] != null){ ?><a href="{!! url('/storage/'.$dataDetailNew[12]->File_Name.'') !!}">Download</a>&nbsp<a href="" style="color:red;">X</a> <?php } ?></td>
              <td><?php if($dataDetailNew[12] != null){ ?><a href="{!! url('/storage/'.$dataDetailNew[12]->File_Name.'') !!}">Download</a>&nbsp<a href="" style="color:red;">X</a> <?php } ?></td>
              <td></td>
              <td>
                <a onclick="$('.fileaktivitas_mahasiswa').show();" href="javascript:void(0);">New Update Upload</a>
                <form method="get" id="upload_updateaktivitas_mahasiswa" enctype="multipart/form-data">
                  {{ csrf_field() }}
                  <input name="prodi" type="text" value="<?php echo implode(',', $prodi); ?>" style="display:none;">
                  <input name="format" type="text" value="aktivitas_mahasiswa" style="display:none;">
                  <input class="fileaktivitas_mahasiswa" name="csv" type="file" style="display:none;">
                  <button style="display:none;" class="fileaktivitas_mahasiswa btn btn-primary" type="submit">Simpan</button>
                  <div class="saveupdateaktivitas_mahasiswa" style="display:none;">Berhasil Tersimpan</div>
                  <div class="errorupdateaktivitas_mahasiswa" style="display:none;">Gagal Tersimpan</div>
                </form>
                <script>

                  var prodi =  '<?php echo js_array($prodi) ?>';
                  var format = 'aktivitas_mahasiswa';
                  // console.log(no);
                  $(document).ready(function(){

                    $('#upload_updateaktivitas_mahasiswa').on('submit', function(event){
                    event.preventDefault();
                      $.ajax({
                        url:"{!! url('/feeder/sync/new_update') !!}",
                        method:"POST",
                        data:new FormData(this),
                        dataType:'JSON',
                        contentType: false,
                        cache: false,
                        processData: false,
                        success:function(data)
                        {
                          $('.saveupdateaktivitas_mahasiswa').show();
                          location.reload();
                        },
                        error: function(xhr, status, error){
                          $('.errorupdateaktivitas_mahasiswa').show();
                        }
                      })
                    });

                  });
              </script>
              
              </td>
              <td style="width:5%;"><input name="aktivitas_mahasiswa" class="checks" type="checkbox"></td>
            </tr>
            <tr>
              <td>13</td>
              <td>Anggota aktivitas mahasiswa</td>
              <td><?php if($dataDetailNew[12] != null){ ?><a href="{!! url('/storage/'.$dataDetailNew[12]->File_Name.'') !!}">Download</a>&nbsp<a href="" style="color:red;">X</a> <?php } ?></td>
              <td><?php if($dataDetailNew[12] != null){ ?><a href="{!! url('/storage/'.$dataDetailNew[12]->File_Name.'') !!}">Download</a>&nbsp<a href="" style="color:red;">X</a> <?php } ?></td>
              <td></td>
              <td>
                <a onclick="$('.fileanggota_aktivitas_mahasiswa').show();" href="javascript:void(0);">New Update Upload</a>
                <form method="get" id="upload_updateanggota_aktivitas_mahasiswa" enctype="multipart/form-data">
                  {{ csrf_field() }}
                  <input name="prodi" type="text" value="<?php echo implode(',', $prodi); ?>" style="display:none;">
                  <input name="format" type="text" value="anggota_aktivitas_mahasiswa" style="display:none;">
                  <input class="fileanggota_aktivitas_mahasiswa" name="csv" type="file" style="display:none;">
                  <button style="display:none;" class="fileanggota_aktivitas_mahasiswa btn btn-primary" type="submit">Simpan</button>
                  <div class="saveupdateanggota_aktivitas_mahasiswa" style="display:none;">Berhasil Tersimpan</div>
                  <div class="errorupdateanggota_aktivitas_mahasiswa" style="display:none;">Gagal Tersimpan</div>
                </form>
                <script>

                  var prodi =  '<?php echo js_array($prodi) ?>';
                  var format = 'anggota_aktivitas_mahasiswa';
                  // console.log(no);
                  $(document).ready(function(){

                    $('#upload_updateanggota_aktivitas_mahasiswa').on('submit', function(event){
                    event.preventDefault();
                      $.ajax({
                        url:"{!! url('/feeder/sync/new_update') !!}",
                        method:"POST",
                        data:new FormData(this),
                        dataType:'JSON',
                        contentType: false,
                        cache: false,
                        processData: false,
                        success:function(data)
                        {
                          $('.saveupdateanggota_aktivitas_mahasiswa').show();
                          location.reload();
                        },
                        error: function(xhr, status, error){
                          $('.errorupdateanggota_aktivitas_mahasiswa').show();
                        }
                      })
                    });

                  });
              </script>
              
              </td>
              <td style="width:5%;"><input name="anggota_aktivitas_mahasiswa" class="checks" type="checkbox"></td>
            </tr>
            <tr>
              <td>14</td>
              <td>Prestasi mahasiswa</td>
              <td><?php if($dataDetailNew[13] != null){ ?><a href="{!! url('/storage/'.$dataDetailNew[13]->File_Name.'') !!}">Download</a>&nbsp<a href="" style="color:red;">X</a> <?php } ?></td>
              <td><?php if($dataDetailNew[13] != null){ ?><a href="{!! url('/storage/'.$dataDetailNew[13]->File_Name.'') !!}">Download</a>&nbsp<a href="" style="color:red;">X</a> <?php } ?></td>
              <td></td>
              <td>
                <a onclick="$('.fileprestasi').show();" href="javascript:void(0);">New Update Upload</a>
                <form method="get" id="upload_updateprestasi" enctype="multipart/form-data">
                  {{ csrf_field() }}
                  <input name="prodi" type="text" value="<?php echo implode(',', $prodi); ?>" style="display:none;">
                  <input name="format" type="text" value="prestasi" style="display:none;">
                  <input class="fileprestasi" name="csv" type="file" style="display:none;">
                  <button style="display:none;" class="fileprestasi btn btn-primary" type="submit">Simpan</button>
                  <div class="saveupdateprestasi" style="display:none;">Berhasil Tersimpan</div>
                  <div class="errorupdateprestasi" style="display:none;">Gagal Tersimpan</div>
                </form>
                <script>

                  var prodi =  '<?php echo js_array($prodi) ?>';
                  var format = 'prestasi';
                  // console.log(no);
                  $(document).ready(function(){

                    $('#upload_updateprestasi').on('submit', function(event){
                    event.preventDefault();
                      $.ajax({
                        url:"{!! url('/feeder/sync/new_update') !!}",
                        method:"POST",
                        data:new FormData(this),
                        dataType:'JSON',
                        contentType: false,
                        cache: false,
                        processData: false,
                        success:function(data)
                        {
                          $('.saveupdateprestasi').show();
                          location.reload();
                        },
                        error: function(xhr, status, error){
                          $('.errorupdateprestasi').show();
                        }
                      })
                    });

                  });
              </script>
              
              </td>
              <td style="width:5%;"><input name="prestasi" class="checks" type="checkbox"></td>
            </tr>
            {{-- <tr style="background:#454545; color:#fff;">
              <td>15</td>
              <td colspan="6">Record</td>
            </tr> --}}
          </table>
          <br>

          <div class="alert alert-danger">PERHATIAN !, Data akan sinkron berdasar data file di kolom terdownload atau yang terupdate, jika tanggal file terupdate lebih baru dari yang terdownload maka file yang tersinkron menggunaan file terupdate, begitu sebaliknya.</div>
          <center><button onclick="sync();" class="buttonSyncronize btn btn-success">Update Sync Tercentang Diatas</button></center>
          <center><div class="loadingSyncronize" style="display:none;"><img style="width:20%;" src="/img/loading-circle.gif" alt=""></div></center>
          <br>
          <center><div class="successSyncronize alert alert-success" style="display:none;"> Proses Sinkronisasi Selesai</div></center>
          <center><div class="errorSyncronize alert alert-danger" style="display:none;">Gagal</div></center>
        </div>
      </div>
      @else
      <div class="alert alert-warning">Pastikan Sudah Memilih Prodi Dan Tahun Ajara n</div>
      @endif
    @endif
    @endif

  </div>

  <script>
    function sync() {
      var prodi =  '<?php echo js_array($prodi) ?>';

      var checkboxes = document.getElementsByClassName('checks');
      var format = [];
      // loop over them all
      for (var i=0; i<checkboxes.length; i++) {
        // And stick the checked ones onto an array...
        if (checkboxes[i].checked) {
          format.push(checkboxes[i].name);
        }
      }


      $(document).ready(function(){
          $.ajax({
            url:"{!! url('/feeder/sync/syncronize') !!}",
            method:"POST",
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            },
            data:{ prodi: prodi, format: format },
            
            beforeSend: function () {
              $('.buttonSyncronize').hide();
              $('.loadingSyncronize').show();
            },
            success:function(data)
            {
              $('.buttonSyncronize').show();
              $('.loadingSyncronize').hide();
              $('.successSyncronize').show();
            },
            error: function(xhr, status, error){
              $('.buttonSyncronize').show();
              $('.loadingSyncronize').hide();
              $('.errorSyncronize').show();
            }
          })
      });
    }
  </script>

</section>


@endsection