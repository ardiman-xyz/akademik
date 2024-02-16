@extends('layouts._layout')
@section('pageTitle', 'KHS')
@section('content')

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Seting Bobot</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
            <a href="{{ url('proses/khs_matakuliah/'.$offer.'?term_year='.$term_year.'&class_program='.$class_program.'&department='.$department.'&term_year='.$term_year.'&course_type='.$course_type) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
          </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Edit</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-box-title right text-green">
            <!-- <b>Daftar Fakultas</b> -->

              {{-- <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
                 Note:
                <u><i><p> lakukan simpan ulang jika anda melakukan perubahan pada bobot penilaian</p></i></u>
              </div> --}}
              <?php  $off = $data['Offered_Course_id'] ?>
            <form class="" action="{{route('khs_matakuliah.storeSetting',['offer'=>$off])}}" style="margin:0 auto;width:500px;padding:10px;" method="post">
            <input type="text" value="{{$term_year}}" name="term_year" hidden>
            <input type="text" value="{{$class_program}}" name="class_program" hidden>
            <input type="text" value="{{$department}}" name="department" hidden>
                {{csrf_field()}}
                <center>
                    <span class="card">
                        <span class="panel-heading-green"><h5>Silakan diisi Untuk Menentukaan Bobot Nilai</h5>
                        Note:
                        </br>-Total Bobot Harus 100(100%)
                    </br>-Boleh diisi "0" dan tidak berurutan

                        </span>
                        <span class="card-body">
                            <div class="row">
                                <div class="col-sm-2">
                                </div>

                                <div class="col-sm-3">
                                    <label >Presensi</label><br>
                                    <label >Tugas 1</label><br>
                                    <label >Tugas 2</label><br>
                                    <label >Tugas 3</label><br>
                                    <label >Tugas 4</label><br>
                                    <label >Tugas 5</label><br>
                                    <label >UTS   </label><br>
                                    <label >UAS   </label><br>
                                      <hr>
                                    <label >Total(%):   </label><br>
                                </div>
                                <div class="col-sm-3">

                                  <input type="text" id="col_1" name="Presensi"class="k-textbox form-control-sm" value="{{$data['Presensi']}}" style="margin-bottom:2px;height:30px" onchange="colTot(1)"></br >
                                  <input type="text" id="col_2" name="Tugas_1"class="k-textbox form-control-sm" value="{{$data['Tugas_1']}}"style="margin-bottom:2px;height:30px" onchange="colTot(2)"></br >
                                  <input type="text" id="col_3" name="Tugas_2"class="k-textbox form-control-sm" value="{{$data['Tugas_2']}}"style="margin-bottom:2px;height:30px"onchange="colTot(3)"></br>
                                  <input type="text" id="col_4" name="Tugas_3"class="k-textbox form-control-sm" value="{{$data['Tugas_3']}}"style="margin-bottom:2px;height:30px"onchange="colTot(4)"></br >
                                  <input type="text" id="col_5" name="Tugas_4"class="k-textbox form-control-sm" value="{{$data['Tugas_4']}}"style="margin-bottom:2px;height:30px"onchange="colTot(5)"></br >
                                  <input type="text" id="col_6" name="Tugas_5"class="k-textbox form-control-sm" value="{{$data['Tugas_5']}}"style="margin-bottom:2px;height:30px"onchange="colTot(6)"></br >
                                  <input type="text" id="col_7" name="UTS"class="k-textbox form-control-sm" value="{{$data['UTS']}}"style="margin-bottom:2px;height:30px"onchange="colTot(7)"></br >
                                  <input type="text" id="col_8" name="UAS"class="k-textbox form-control-sm" value="40" readonly style="margin-bottom:2px;height:30px"onchange="colTot(8)"></br >
                                  <hr>
                                  <input type="text" id="total" name="total"class="k-textbox form-control-sm" value=""style="margin-bottom:2px;height:30px" readonly></br >
                                  <input type="hidden" name="id" value="{{$data['Student_khs_bobot_id']}}"></br >
                                </div>
                            </div>
                            <button type="submit" name="button"  class="btn btn-success">Simpan</button>
                        </span>

                    </span>



                </center>

            </form>

        <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
          @if (count($errors) > 0)
            @foreach ( $errors->all() as $error )
              <p class="alert alert-danger">{{ $error }}</p>
            @endforeach
          @endif
          <br>

      </div>
    </div>
    </div>
  </div>

<!-- /.row -->

</section>

  <script type="text/javascript">
  $(document).ready( function () {
// $('#myTable').DataTable();
var total = (
    (parseInt($("#col_1").val()== '' ? 0:$("#col_1").val()))   +
             parseInt(($("#col_2").val()== '' ? 0:$("#col_2").val()))  +
             parseInt(($("#col_3").val()== '' ? 0:$("#col_3").val()))  +
             parseInt(($("#col_4").val()== '' ? 0:$("#col_4").val()))  +
             parseInt( ($("#col_5").val()== '' ? 0:$("#col_5").val())) +
             parseInt(($("#col_6").val()== '' ? 0:$("#col_6").val()))  +
             parseInt(($("#col_7").val()== '' ? 0:$("#col_7").val())) +
             parseInt(($("#col_8").val()== '' ? 0:$("#col_8").val()))
              );
$('#total').val(total);
// alert(total);

} );
function colTot( i) {

           if ($("#col_" + i + "").val()) {//jika ada nilainya
               var error = false;

               if (!$.isNumeric($("#col_" + i + "").val())) { //validasi numeric
                   error = true;
               }

               if (Math.round($("#col_" + i + "").val()) != $("#col_" + i + "").val()) { //validasi integer
                   error = true;
               }

               if ($("#col_" + i + "").val() < 0 || $("#col_" + i + "").val() > 100) { //validasi harus 0-100
                   error = true;
               }

               if (error) { //popup warning
                   //$("#Uts_Grade_id" + i + "").css('color', 'red'); //tandai merah
                   $("#col_" + i + "").val(''); //field dikosongkan
                   //$("#colTotal_id" + i + "").val(''); //field dikosongkan
                 //field dikosongkan
                   swal(
                       'Peringantan!',
                       'Nilai harus bilangan bulat dari 0-100',
                       'warning'
                   )
               }
               else {
                   $("#total").val(jmlCol());


               }
               $("#total").val(jmlCol());

           }
           else {
               // $("#Attitude_" + c + "_" + i + "").val(''); //field dikosongkan
               $("#total").val(jmlCol());

           }
       }
       function jmlCol()
       {
           var total = (
               (parseInt($("#col_1").val()== '' ? 0:$("#col_1").val()))   +
                        parseInt(($("#col_2").val()== '' ? 0:$("#col_2").val()))  +
                        parseInt(($("#col_3").val()== '' ? 0:$("#col_3").val()))  +
                        parseInt(($("#col_4").val()== '' ? 0:$("#col_4").val()))  +
                        parseInt( ($("#col_5").val()== '' ? 0:$("#col_5").val())) +
                        parseInt(($("#col_6").val()== '' ? 0:$("#col_6").val()))  +
                        parseInt(($("#col_7").val()== '' ? 0:$("#col_7").val())) +
                        parseInt(($("#col_8").val()== '' ? 0:$("#col_8").val()))
                         );
           return total;
       }
  </script>

@endsection
