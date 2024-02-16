@extends('layouts._layout')
@section('pageTitle', 'Cetak Transkrip Sementara')
@section('content')
  <?php
  $access = auth()->user()->akses();
            $acc = $access;
  ?>

<style>
@media (min-width:993px){.w3-modal-content{ width:35%; }.w3-hide-large{display:none!important}.w3-sidebar.w3-collapse{display:block!important}}

.div_height{
  height:400px !important;
}
</style>
<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Transkrip Sementara</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          <b>Transkrip Sementara</b>
        </div>
      </div>
      <br>
        {!! Form::open(['url' => route('index2') , 'method' => 'GET', 'name' => 'form', 'role' => 'form', 'style' => 'padding-top:5px;padding-left:15px;padding-right:15px;']) !!}
          <div class="row text-green">
          <label class="col-md-2">NIM Mahasiswa :</label>
            <input type="text" class="form-control form-control-sm col-md-3" name="nim" id="nim" placeholder=" NIM" value="{{$nim}}">
        </div>
        <hr>

        {!! Form::close() !!}
        </div>
        <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
          <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            <div class="col-sm-6">
              @if($student != "")
                <div >
                  <label for="" class="col-sm-5">Nama Mahasiswa</label>
                  <label for="" class="col-sm-5">:
                    @if($student != "")
                      {{ $student->Full_Name }}
                    @endif
                  </label>
                </div>

                <div>
                  <label for="" class="col-sm-5">Cetak Transkrip Sementara</label>
                    @if(in_array('transcript_sementara-CanExport', $acc))
                      @if(count($smt) != 0)
                        <label id="log_mhs" class="col-sm-5"><a href="#" class="btn btn-info btn-sm" style="margin:5px;" >Cetak <i class="fa fa-print"></i> </a></label>
                      @else
                        <button disabled class="btn btn-info btn-sm" target="_blank"><i class="fa fa-print"></i> Cetak </button>
                      @endif
                    @endif
                </div>
                @endif
            </div>
          </div>
          @if (count($errors) > 0)
            @foreach ( $errors->all() as $error )
              <p class="alert alert-danger">{{ $error }}</p>
            @endforeach
          @endif

        <div class="table-responsive">
          <table class="table table-striped table-font-sm">
            <thead class="thead-default thead-green">
              <tr>
                <th>&nbsp</th>
              </tr>
            </thead>
          </table>
          <div style="clear:both; position:relative;" class="div_height">
            <div style="position:absolute; left:0pt; width:50%;">
              <?php
                $smt1 = 0;
                foreach ($smt as $datas_1) if($datas_1['Study_Level_Id'] == 1){
                  $smt1++;
                }
                if($smt1 > 0){
              ?>
              <table border="1px" style="border-collapse : collapse; width:100%; font-size:10px;">
                <thead>
                  <tr>
                    <th colspan='5'><center>SEMESTER I</th>
                  </tr>
                  <tr>
                    <th style="width:4%;"><center>No.</th>
                    <th style="width:16%;"><center>Kode</th>
                    <th style="width:60%;"><center>Nama Matakuliah</th>
                    <th style="width:10%;"><center>SKS</th>
                    <th style="width:10%;"><center>N</th>
                    <th style="width:10%;"><center></th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $a = "1";
                  $sks_1 = 0;
                  $smt1 = 0;
                  foreach ($smt as $datas_1) if($datas_1['Study_Level_Id'] == 1){
                    $smt1++;
                  }
                  if($smt1 > 0){
                    foreach ($smt as $datas_1) if($datas_1['Study_Level_Id'] == 1){
                      $sks_1 = $sks_1 + $datas_1['Sks'];
                    
                    ?>
                        <tr>
                            <!-- <th></th> -->
                            <td><center>{{ $a }}</td>
                            <td>{{ $datas_1['Course_Code'] }}</td>
                            <td>{{ $datas_1['Course_Name'] }}</td>
                            <td><center>{{$datas_1['Sks']}}</td>
                            <td><center>{{ $datas_1['Grade_Letter'] }}</td>
                            <td><center><input type="checkbox" name="Transcript_Id" value="{{ $datas_1['Transcript_Id'] }}" class="changetranskript" <?php if($datas_1['is_Use'] == true) { echo "checked"; } ?>></td>
                        </tr>
                      <?php
                      $a++;
                    }
                  }
                  ?>
                  <tr>
                    <td colspan="3"><center></td>
                    <td><center><?php echo(number_format($sks_1,1)); ?></td>
                    <td><center></td>
                  </tr>
                </tbody>
              </table>
              <?php } ?>

              <!-- smt 2 -->
              <?php
                $smt2 = 0;
                foreach ($smt as $datas_2) if($datas_2['Study_Level_Id'] == 2){
                  $smt2++;
                }
                if($smt2 > 0){
              ?>
              <table border="1px" style="border-collapse : collapse; width:100%; font-size:10px;">
                <thead>
                  <tr>
                    <th colspan='5'><center>SEMESTER II</th>
                  </tr>
                  <tr>
                    <th style="width:4%;"><center>No.</th>
                    <th style="width:16%;"><center>Kode</th>
                      <th style="width:60%;"><center>Nama Matakuliah</th>
                      <th style="width:10%;"><center>SKS</th>
                      <th style="width:10%;"><center>N</th>
                      <th style="width:10%;"><center></th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $a = "1";
                  $sks_2 = 0;
                    foreach ($smt as $datas_2) if($datas_2['Study_Level_Id'] == 2){
                      $sks_2 = $sks_2 + $datas_2['Sks'];
                    
                    ?>
                        <tr>
                            <!-- <th></th> -->
                            <td><center>{{ $a }}</td>
                            <td>{{ $datas_2['Course_Code'] }}</td>
                            <td>{{ $datas_2['Course_Name'] }}</td>
                            <td><center>{{$datas_2['Sks']}}</td>
                            <td><center>{{ $datas_2['Grade_Letter'] }}</td>
                            <td><center><input type="checkbox" name="Transcript_Id" value="{{ $datas_2['Transcript_Id'] }}" class="changetranskript" <?php if($datas_2['is_Use'] == true) { echo "checked"; } ?>></td>
                        </tr>
                      <?php
                      $a++;
                    }
                  ?>
                  <tr>
                    <td colspan="3"><center></td>
                    <td><center><?php echo(number_format($sks_2,1)); ?></td>
                    <td><center></td>
                  </tr>
                </tbody>
              </table>
              <?php } ?>
              <br>

              <!-- smt 3 -->
              <?php
                $smt3 = 0;    
                foreach ($smt as $datas_3) if($datas_3['Study_Level_Id'] == 3){
                  $smt3++;
                }
                if($smt3 > 0){
              ?>
              <table border="1px" style="border-collapse : collapse; width:100%; font-size:10px;">
                <thead>
                  <tr>
                    <th colspan='5'><center>SEMESTER III</th>
                  </tr>
                  <tr>
                    <th style="width:4%;"><center>No.</th>
                    <th style="width:16%;"><center>Kode</th>
                      <th style="width:60%;"><center>Nama Matakuliah</th>
                      <th style="width:10%;"><center>SKS</th>
                      <th style="width:10%;"><center>N</th>
                      <th style="width:10%;"><center></th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $a = "1";
                  $sks_3 = 0;
                    foreach ($smt as $datas_3) if($datas_3['Study_Level_Id'] == 3){
                      $sks_3 = $sks_3 + $datas_3['Sks'];
                    
                    ?>
                        <tr>
                            <!-- <th></th> -->
                            <td><center>{{ $a }}</td>
                            <td>{{ $datas_3['Course_Code'] }}</td>
                            <td>{{ $datas_3['Course_Name'] }}</td>
                            <td><center>{{$datas_3['Sks']}}</td>
                            <td><center>{{ $datas_3['Grade_Letter'] }}</td>
                            <td><center><input type="checkbox" name="Transcript_Id" value="{{ $datas_3['Transcript_Id'] }}" class="changetranskript" <?php if($datas_3['is_Use'] == true) { echo "checked"; } ?>></td>
                        </tr>
                      <?php
                      $a++;
                    }
                  ?>
                  <tr>
                    <td colspan="3"><center></td>
                    <td><center><?php echo(number_format($sks_3,1)); ?></td>
                    <td><center></td>
                  </tr>
                </tbody>
              </table></br>
              <?php } ?>

              <!-- smt4 -->
              <?php
                  $smt4 = 0;
                  foreach ($smt as $datas_4) if($datas_4['Study_Level_Id'] == 4){
                    $smt4++;
                  }
                  if($smt4 > 0){
              ?>
              <table border="1px" style="border-collapse : collapse; width:100%; font-size:10px;">
                <thead>
                  <tr>
                    <th colspan='5'><center>SEMESTER IV</th>
                  </tr>
                  <tr>
                    <th style="width:4%;"><center>No.</th>
                    <th style="width:16%;"><center>Kode</th>
                      <th style="width:60%;"><center>Nama Matakuliah</th>
                      <th style="width:10%;"><center>SKS</th>
                      <th style="width:10%;"><center>N</th>
                      <th style="width:10%;"><center></th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $a = "1";
                  $sks_4 = 0;
                    foreach ($smt as $datas_4) if($datas_4['Study_Level_Id'] == 4){
                      $sks_4 = $sks_4 + $datas_4['Sks'];
                    
                    ?>
                        <tr>
                            <!-- <th></th> -->
                            <td><center>{{ $a }}</td>
                            <td>{{ $datas_4['Course_Code'] }}</td>
                            <td>{{ $datas_4['Course_Name'] }}</td>
                            <td><center>{{$datas_4['Sks']}}</td>
                            <td><center>{{ $datas_4['Grade_Letter'] }}</td>
                            <td><center><input type="checkbox" name="Transcript_Id" value="{{ $datas_4['Transcript_Id'] }}" class="changetranskript" <?php if($datas_4['is_Use'] == true) { echo "checked"; } ?>></td>
                        </tr>
                      <?php
                      $a++;
                    }
                  ?>
                  <tr>
                    <td colspan="3"><center></td>
                    <td><center><?php echo(number_format($sks_4,1)); ?></td>
                    <td><center></td>
                  </tr>
                </tbody>
              </table>
              <?php } ?>
            </div>
            <div style="margin-left:52%;">
              <?php
                  $smt5 = 0;
                  foreach ($smt as $datas_5) if($datas_5['Study_Level_Id'] == 5){
                    $smt5++;
                  }
                  if($smt5 > 0){
              ?>
              <table border="1px" style="border-collapse : collapse; width:100%; font-size:10px;">
                <thead>
                  <tr>
                    <th colspan='5'><center>SEMESTER V</th>
                  </tr>
                  <tr>
                    <th style="width:4%;"><center>No.</th>
                    <th style="width:16%;"><center>Kode</th>
                      <th style="width:60%;"><center>Nama Matakuliah</th>
                      <th style="width:10%;"><center>SKS</th>
                      <th style="width:10%;"><center>N</th>
                      <th style="width:10%;"><center></th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $a = "1";
                  $sks_5 = 0;
                    foreach ($smt as $datas_5) if($datas_5['Study_Level_Id'] == 5){
                      $sks_5 = $sks_5 + $datas_5['Sks'];
                    
                    ?>
                        <tr>
                            <!-- <th></th> -->
                            <td><center>{{ $a }}</td>
                            <td>{{ $datas_5['Course_Code'] }}</td>
                            <td>{{ $datas_5['Course_Name'] }}</td>
                            <td><center>{{$datas_5['Sks']}}</td>
                            <td><center>{{ $datas_5['Grade_Letter'] }}</td>
                            <td><center><input type="checkbox" name="Transcript_Id" value="{{ $datas_5['Transcript_Id'] }}" class="changetranskript" <?php if($datas_5['is_Use'] == true) { echo "checked"; } ?>></td>
                        </tr>
                      <?php
                      $a++;
                    }
                  ?>
                </tbody>
                <tr>
                    <td colspan="3"><center></td>
                    <td><center><?php echo(number_format($sks_5,1)); ?></td>
                    <td><center></td>
                  </tr>
              </table>
              <?php } ?>

              <!-- smt6 -->
              <?php
                $smt6 = 0;
                foreach ($smt as $datas_6) if($datas_6['Study_Level_Id'] == 6){
                  $smt6++;
                }
                if($smt6 > 0){
              ?>
              <table border="1px" style="border-collapse : collapse; width:100%; font-size:10px;">
                <thead>
                  <tr>
                    <th colspan='5'><center>SEMESTER VI</th>
                  </tr>
                  <tr>
                    <th style="width:4%;"><center>No.</th>
                    <th style="width:16%;"><center>Kode</th>
                      <th style="width:60%;"><center>Nama Matakuliah</th>
                      <th style="width:10%;"><center>SKS</th>
                      <th style="width:10%;"><center>N</th>
                      <th style="width:10%;"><center></th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $a = "1";
                  $sks_6 = 0;
                    foreach ($smt as $datas_6) if($datas_6['Study_Level_Id'] == 6){
                      $sks_6 = $sks_6 + $datas_6['Sks'];
                    
                    ?>
                        <tr>
                            <!-- <th></th> -->
                            <td><center>{{ $a }}</td>
                            <td>{{ $datas_6['Course_Code'] }}</td>
                            <td>{{ $datas_6['Course_Name'] }}</td>
                            <td><center>{{$datas_6['Sks']}}</td>
                            <td><center>{{ $datas_6['Grade_Letter'] }}</td>
                            <td><center><input type="checkbox" name="Transcript_Id" value="{{ $datas_6['Transcript_Id'] }}" class="changetranskript" <?php if($datas_6['is_Use'] == true) { echo "checked"; } ?>></td>
                        </tr>
                      <?php
                      $a++;
                    }
                  ?>
                  <tr>
                    <td colspan="3"><center></td>
                    <td><center><?php echo(number_format($sks_6,1)); ?></td>
                    <td><center></td>
                  </tr>
                </tbody>
              </table>
              <?php } ?>

              <!-- smt7 -->
              <?php
                $smt7 = 0;
                foreach ($smt as $datas_7) if($datas_7['Study_Level_Id'] == 7){
                  $smt7++;
                }
                if($smt7 > 0){
              ?>
              <table border="1px" style="border-collapse : collapse; width:100%; font-size:10px;">
                <thead>
                  <tr>
                    <th colspan='5'><center>SEMESTER VII</th>
                  </tr>
                  <tr>
                    <th style="width:4%;"><center>No.</th>
                    <th style="width:16%;"><center>Kode</th>
                      <th style="width:60%;"><center>Nama Matakuliah</th>
                      <th style="width:10%;"><center>SKS</th>
                      <th style="width:10%;"><center>N</th>
                      <th style="width:10%;"><center></th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $a = "1";
                  $sks_7 = 0;
                    foreach ($smt as $datas_7) if($datas_7['Study_Level_Id'] == 7){
                      $sks_7 = $sks_7 + $datas_7['Sks'];
                    
                    ?>
                        <tr>
                            <!-- <th></th> -->
                            <td><center>{{ $a }}</td>
                            <td>{{ $datas_7['Course_Code'] }}</td>
                            <td>{{ $datas_7['Course_Name'] }}</td>
                            <td><center>{{$datas_7['Sks']}}</td>
                            <td><center>{{ $datas_7['Grade_Letter'] }}</td>
                            <td><center><input type="checkbox" name="Transcript_Id" value="{{ $datas_7['Transcript_Id'] }}" class="changetranskript" <?php if($datas_7['is_Use'] == true) { echo "checked"; } ?>></td>
                        </tr>
                      <?php
                      $a++;
                    }
                  ?>
                </tbody>
                <tr>
                    <td colspan="3"><center></td>
                    <td><center><?php echo(number_format($sks_7,1)); ?></td>
                    <td><center></td>
                  </tr>
              </table>
              <?php } ?>

              <!-- smt8 -->
              <?php
                $smt8 = 0;
                foreach ($smt as $datas_8) if($datas_8['Study_Level_Id'] == 8){
                  $smt8++;
                }
                if($smt8 > 0){
              ?>
              <table border="1px" style="border-collapse : collapse; width:100%; font-size:10px;">
                <thead>
                  <tr>
                    <th colspan='5'><center>SEMESTER VIII</th>
                  </tr>
                  <tr>
                    <th style="width:4%;"><center>No.</th>
                    <th style="width:16%;"><center>Kode</th>
                      <th style="width:60%;"><center>Nama Matakuliah</th>
                      <th style="width:10%;"><center>SKS</th>
                      <th style="width:10%;"><center>N</th>
                      <th style="width:10%;"><center></th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $a = "1";
                  $sks_8 = 0;
                    foreach ($smt as $datas_8) if($datas_8['Study_Level_Id'] == 8){
                      $sks_8 = $sks_8 + $datas_8['Sks'];
                    
                    ?>
                        <tr>
                            <!-- <th></th> -->
                            <td><center>{{ $a }}</td>
                            <td>{{ $datas_8['Course_Code'] }}</td>
                            <td>{{ $datas_8['Course_Name'] }}</td>
                            <td><center>{{$datas_8['Sks']}}</td>
                            <td><center>{{ $datas_8['Grade_Letter'] }}</td>
                            <td><center><input type="checkbox" name="Transcript_Id" value="{{ $datas_7['Transcript_Id'] }}" class="changetranskript" <?php if($datas_7['is_Use'] == true) { echo "checked"; } ?>></td>
                        </tr>
                      <?php
                      $a++;
                    }
                  ?>
                  <tr>
                    <td colspan="3"><center></td>
                    <td><center><?php echo(number_format($sks_8,1)); ?></td>
                    <td><center></td>
                  </tr>
                </tbody>
              </table>
              <?php } ?>

              @if($student)
                <div class="tulisan">
                  <?php 
                    if($nilai_d != 0){
                      $nilai_d = $nilai_d/$query_['jml_sks']*100; $nilai_d = number_format($nilai_d,2); 
                    }else{
                      $nilai_d = 0;
                    }
                  ?>
                  <center>SKS Ditempuh = {{$query_['jml_sks']}}</center>
                  <center>IPK = {{ $query_['ipk'] }}</center>
                  <center>% Nilai D = {{ $nilai_d }} %</center>
                  <center>Sisa SKS = <?php if($complete_sks == 0){echo 'Kurikulum Prodi belum di set';}else{echo($complete_sks - $query_['jml_sks']);} ?></center>
                </div>
                <center><a class="btn btn-success btn-sm" id="updatetr"  href="javascript:">Simpan</a>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</section>

<div id="mahasiswa" class="w3-modal">
      <div class="w3-modal-content w3-animate-zoom w3-card-4">
      <header class="w3-container w3-teal"> 
          <span onclick="location.reload()" 
          class="w3-button w3-display-topright">&times;</span>
          <h4>Cetak Transkrip Sementara</h4>
      </header>
      <div class="w3-container">
      </br>
      <!-- <form id="frm-upload" action="{{ route('course_curriculum.store_silabus') }}" method="POST" enctype="multipart/form-data"> -->
          <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            <div  class="row col-md-10">
              <label class="col-md-4">Keperluan</label>
              <input type="text" class="form-control form-control-sm col-md-7" name="keperluan" id="keperluan" placeholder=" syarat ujian" >
            </div>
            <div  class="row col-md-10">
              <label class="col-md-4"></label>  
              <button onclick="cetak()" type="button" class="btn-success btn-sm form-control form-control-sm col-md-7"name="button"  >Cetak</button>    
            </div>
            <div  class="row col-md-10">
              <label class="col-md-4"></label>
              <!-- <button value="up" id="bnt-cancel" onclick="" type="submit" class="btn-danger btn-sm btn form-control form-control-sm col-md-7" style="width:80%; margin-top: 2%;">
                  Batal
                </button>       -->
              </div>
            <!-- </form> -->
            </div>
          </br>
      </div>
      </div>
      <table id="eaea">
      </table>
  </div>

<script type="text/javascript">
$(document).ready(function () {
  $("#updatetr").click(function(e){
    var nim = $('#nim').val();
    var Transcript_Id = [];
    $("input[name='Transcript_Id']:checked").each(function() {
      Transcript_Id.push($(this).val());
    });
    $.ajax({
      dataType: 'json',
      url: "{{ route('use_transcript') }}",
      type: 'get',
      data: {
        Transcript_Id:Transcript_Id,
        Nim:nim,
      },
      success: function(res) {
        console.log(res);
        location.reload();
      }, error: function(xhr, ajaxOptions, thrownError) {
        swal({
            //title: thrownError,
            title: "Mohon Maaf",
            //text: 'Error!! ' + xhr.status,
            text: "Server sedang sibuk...",
            type: "error",
            confirmButtonColor: "#02991a",
            confirmButtonText: "Refresh Sekarang",
            cancelButtonText: "Tidak, Batalkan!",
            closeOnConfirm: false,
        },
        function(isConfirm) {
            if (isConfirm) {
                location.reload();
            // window.location.reload(true) // submitting the form when user press yes
            }
        });
      }
    })
  });

//   $('.changetranskript').change(function () {
//     var id = $('.changetranskript').val();
//     alert(id);
//     $.ajax({
//       dataType: 'json',
//       url: "{{ route('use_transcript') }}",
//       type: 'get',
//       data: {
//         id:id,
//       },
//       success: function(res) {
//         console.log(res);
//       }, error: function(xhr, ajaxOptions, thrownError) {
//         swal({
//             //title: thrownError,
//             title: "Mohon Maaf",
//             //text: 'Error!! ' + xhr.status,
//             text: "Server sedang sibuk...",
//             type: "error",
//             confirmButtonColor: "#02991a",
//             confirmButtonText: "Refresh Sekarang",
//             cancelButtonText: "Tidak, Batalkan!",
//             closeOnConfirm: false,
//         },
//         function(isConfirm) {
//             if (isConfirm) {
//                 location.reload();
//             // window.location.reload(true) // submitting the form when user press yes
//             }
//         });
//       }

//   })
//  });
});
//end document ready
$(document).on('click', '#log_mhs', function (e) {
    document.getElementById("mahasiswa").style.display = "block";
});
function cetak() {
  
    window.open('<?php echo env('APP_URL')?>cetak/transcript_sementara/'+$('#nim').val()+'/export?type=transkripsementara&nim='+$('#nim').val()+'&keperluan='+$('#keperluan').val());
    document.getElementById("mahasiswa").style.display = "none";
    // $("#fom")[0].reset();
}
</script>
@endsection()
