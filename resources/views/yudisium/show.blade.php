@extends('layouts._layout')
@section('pageTitle', 'Yudisium')
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
</style>


<section class="content">

  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Yudisium</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
          <a href="{{ url('proses/yudisium?department='.$department.'&term_year='.$term_year.'&search='.$search.'&rowpage='.$rowpage) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Yudisium</b>
        </div>
      </div>
      <br>

      {!! Form::open(['url' => route('yudisium.show',$Student_Id), 'method' => 'GET', 'name' => 'form', 'role' => 'form', 'style' => 'padding-top:5px;padding-left:15px;padding-right:15px;']) !!}

      <div class="row">
        <!-- <label class="col-md-2" >Department :</label> -->
        <select class="form-control form-control-sm col-md-3 proses" name="proses" >
          <option value="1">Surat Permohonan Yudisium</option>
          <option value="2">Berita Acara Yudisium</option>
          <option value="3">Surat Bebas Pinjaman Lab</option>
          <option value="4">Pengantar Pembayaran Wisuda</option>
          <option value="5">Surat Keterangan Lulus</option>
          <option value="6">Cetak Transkrip</option>
          <option value="7">Cetak Bukti Penyerahan TA</option>
        </select>&nbsp<br>

        <label class="col-sm-5"><a onclick="proses()" class="btn btn-warning btn-sm" style="margin:5px;" target="_blank">Proses/Cetak <i class="fa fa-print"></i> </a></label>

  </div>
  {!! Form::close() !!}


      <br>

      <div class="row" style="padding-top:5px;padding-left:15px;padding-right:15px;">
        <div class="col-sm-6">
            <div >
              <label for="" class="col-sm-5">Nim</label>
              <label for="" class="col-sm-5">{{ $data->Nim }}</label>
            </div>

            <div>
              <label for="" class="col-sm-5">Nama</label>
              <label for="" class="col-sm-5">{{ $data->Full_Name }}</label>
            </div>

            <div>
              <label for="" class="col-sm-5">Tgl Permohonan</label>
              <label for="" class="col-sm-5">{{ $data->apldate }}</label>
            </div>

            <div>
              <label for="" class="col-sm-5">IPK</label>
              <label for="" class="col-sm-5">{{ $data->ipk }}</label>
            </div>

            <div>
              <label for="" class="col-sm-5">JUDUL</label>
              <label for="" class="col-sm-5">{{ $data->Thesis_Title }}</label>
            </div>

            <div>
              <label for="" class="col-sm-5">TITLE</label>
              <label for="" class="col-sm-5">{{ $data->Thesis_Title_Eng }}</label>
            </div>

            <div>
              <label for="" class="col-sm-5">Dosen Pemb 1</label>
              <label for="" class="col-sm-6">{{ $data->pem1 }}</label>
            </div>

            <div>
              <label for="" class="col-sm-5">Dosen Pemb 2</label>
              <label for="" class="col-sm-6">{{ $data->pem2 }}</label>
            </div>

            <div>
              <label for="" class="col-sm-5">Tgl Seminar</label>
              <label for="" class="col-sm-5">{{ $data->Seminar_Date }}</label>
            </div>

            <div>
              <label for="" class="col-sm-5">Tgl Pendadaran</label>
              <label for="" class="col-sm-5">{{ $data->Thesis_Exam_Date }}</label>
            </div>

            <div>
              <label for="" class="col-sm-5">No. Transkrip</label>
              <label for="" class="col-sm-5"></label>
            </div>

        </div>
      </div>


      <!-- The Modal -->
      <div id="myModal" class="modal">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header container-fluid-title">
          <h4 class="modal-title text-white">Data Yudisium</h4>
          <a class="close2 trigger" href="#">
       <i class="fa fa-times" aria-hidden="true"></i>
     </a>

        </div>

        <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">


                <p class="alert alert-danger" id="message" style="display: none"></p>

            {!! Form::open(['url' => route('beritaacara_yudisium.storeberitaacara_yudisium') ,'id' =>'data_yudisium' , 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form' , 'enctype' => 'multipart/form-data' ]) !!}
            {{ csrf_field() }}
            {{ method_field('post') }}
            <input type="text" name="Student_Ids" hidden value="{{ $data->Student_Id }}">


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
                  <label for="" class="col-sm-5">Status Kelulusan</label>
                  <select class="form-control-sm col-sm-5" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" name="status">

                    @foreach($statuslulus as $key=>$dt)
                    <option <?php if($data->Is_Graduated == $key){ echo "selected"; } ?> value="{{ $key }}">{{ $dt }}</option>
                    @endforeach
                  </select>
                </div>

                <div>
                  <label for="" class="col-sm-5">Predikat Kelulusan</label>
                  <select class="form-control-sm col-sm-5" required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')" oninput="setCustomValidity('')" name="graduate_predikate">
                    <option value="0">Pilih...</option>
                    @foreach($graduate_predikat as $datas)
                    <option <?php if($data->Graduate_Predicate_Id == $datas->Graduate_Predicate_Id){ echo "selected"; } ?> value="{{ $datas->Graduate_Predicate_Id }}">{{ $datas->Predicate_Name }}</option>
                    @endforeach
                  </select>
                </div>

                <div>
                  <label for="" class="col-sm-5">Nomor</label>
                  <label for="" class="col-sm-5"><input type="text" name="nomor" value="{{ $sk_yudisium }}"  class="form-control form-control-sm"></label>
                </div>



                <div>
                  <label for="" class="col-sm-5">Tgl Yudisium</label>
                  <label class="form-control-sm col-sm-5"><div>
                    <?php
                      $date = strtotime($data->Yudisium_Date);
                      $tgl_yudisium = date('Y-m-d', $date);
                    ?>
                    <input type="date" name="tgl_yudisium" value="{{ $tgl_yudisium }}" class="form-control form-control-sm">
                  </div></label>
                </div>

                <div>
                  <label for="" class="col-sm-5">PEJABAT PROGRAM STUDI</label>
                </div>

                <div>
                  <label for="" class="col-sm-5">Jabatan Prodi</label>
                  <select class="form-control-sm col-sm-5" name="jabatan2" required>
                    <option value="0">Pilih Jabatan</option>
                    @foreach ( $jabatan as $datajabatan )
                      <option  <?php if($datayudisium->Department_Functionary == $datajabatan->Functional_Position_Id ){ echo "selected"; } ?> value="{{ $datajabatan->Functional_Position_Id }}">{{ $datajabatan->Functional_Position_Name }}</option>
                    @endforeach
                  </select>
                </div>

                <input type="text" name="nik_pjb" hidden class="nik_pjb" value="{{ $datayudisium->Nik }}">
                <div>
                  <label for="" class="col-sm-5">Pejabat Prodi</label>
                  <select id="select" class="form-control-sm col-sm-5 pejabat" name="pejabat" required>
                    <option value="0">Pilih...</option>
                    @foreach($dosen as $datads)
                    <option <?php if($datayudisium->Department_Functionary_Name == $datads->Employee_Id){ echo "selected"; } ?> value="{{ $datads->Employee_Id }}">{{ $datads->Full_Name }}</option>
                    @endforeach
                  </select>
                </div>
            </div>


            <br>@if(in_array('yudisium-CanUpdateBeritaAcara', $acc))<button type="submit"  class="btn btn-primary btn-flat">Simpan</button>@endif
            {{-- @if($data->Is_Graduated != 0) --}}
              <label class="col-sm-5"><a id="cetak1" href="{{ url('proses/yudisium/'.$mhs->Student_Id.'/export?proses=2') }}" class="btn btn-warning btn-sm" style="margin:5px;" target="_blank">Proses/Cetak <i class="fa fa-print"></i> </a></label>
            {{-- @else
              <button disabled class="btn btn-warning btn-sm" target="_blank"><i class="fa fa-print"></i> Proses/Cetak </button>
            @endif --}}
            {{-- <label class="col-sm-5"><a id="cetak1" class="btn btn-warning btn-sm" style="margin:5px;" target="_blank">Proses/Cetak <i class="fa fa-print"></i> </a></label> --}}


            {!! Form::close() !!}


        </div>
      </div>
      </div>

      <!-- The Modal -->
      <div id="myModal2" class="modal">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header container-fluid-title">
          <h4 class="modal-title text-white">Data SKL</h4>
          <a class="close trigger" href="#">
            <i class="fa fa-times" aria-hidden="true"></i>
          </a>
        </div>
        <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">

          <p class="alert alert-danger" id="message2" style="display: none"></p>

            {!! Form::open(['url' => route('skl.store_skl') , 'method' => 'POST','id'=>'dataskl' , 'class' => 'form-horizontal', 'role' => 'form' , 'enctype' => 'multipart/form-data' ]) !!}
            {{ csrf_field() }}
            {{ method_field('post') }}
            <input type="text" name="Student_Ids" hidden value="{{ $data->Student_Id }}">
            <input type="text" name="facult" hidden value="{{ $faculty->Faculty_Id }}">


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
                  <label for="" class="col-sm-5">Nomor</label>
                  <label for="" class="col-sm-5"><input readonly type="text" name="nomor" value="{{ $data->Sk_Num }}"  class="form-control form-control-sm"></label>
                </div>

                <div>
                  <label for="" class="col-sm-5">Tgl Lulus Yudisium</label>
                  <label class="form-control-sm col-sm-5"><div>
                    <?php
                      $date = strtotime($data->Graduate_Date);
                      $tgl_lulus = date('Y-m-d', $date);
                    ?>
                    <input type="date" name="tgl_lulus" value="{{ $tgl_lulus }}" class="form-control form-control-sm">
                  </div></label>
                </div>

                <div>
                  <label for="" class="col-sm-5">PEJABAT FAKULTAS</label>
                </div>

                <div>
                  <label for="" class="col-sm-5">Jabatan Fakutas</label>
                  <select class="form-control-sm col-sm-5" name="jabatanfk">
                    <option value="">Pilih Jabatan</option>
                    @foreach ( $jabatan as $datajabatan )
                      <option  <?php if($datayudisium->Faculty_Functionary == $datajabatan->Functional_Position_Id ){ echo "selected"; } ?> value="{{ $datajabatan->Functional_Position_Id }}">{{ $datajabatan->Functional_Position_Name }}</option>
                    @endforeach
                  </select>
                </div>

                <input type="text" name="nik_pjb" hidden class="nik_pjb" value="{{ $datayudisium->Nik }}">
                <div>
                  <label for="" class="col-sm-5">Pejabat Fakultas</label>
                  <select id="select" class="form-control-sm col-sm-5 pejabat" name="pejabatfk">
                    <option value="0">Pilih...</option>
                    @foreach($dosen as $datads)
                    <option <?php if($datayudisium->Faculty_Functionary_Name == $datads->Employee_Id){ echo "selected"; } ?> value="{{ $datads->Employee_Id }}">{{ $datads->Full_Name }}</option>
                    @endforeach
                  </select>
                </div>
            </div>


            <br>@if(in_array('yudisium-CanUpdateSKL', $acc))<button type="submit" class="btn btn-primary btn-flat">Simpan</button>@endif
            <label class="col-sm-5"><a href="{{ url('proses/yudisium/'.$mhs->Student_Id.'/export?proses=5') }}" class="btn btn-warning btn-sm" style="margin:5px;" target="_blank">Proses/Cetak <i class="fa fa-print"></i> </a></label>
            {!! Form::close() !!}

        </div>
      </div>
      </div>


      <script type="text/javascript">
        function proses() {
          // var statuslulus = <?php echo $data->Is_Graduated; ?>;
          // var predikat = <?php echo $data->Graduate_Predicate_Id; ?>;
          // var jabatan = <?php echo $datayudisium->Department_Functionary; ?>;
          var studentid = <?php echo $data->Student_Id; ?>;
          var department = <?php echo $department; ?>;
          var term_year = <?php echo $term_year; ?>;
          var proses = $(".proses").val();
          var modal = document.getElementById('myModal');
          var modal2 = document.getElementById('myModal2');
          var span = document.getElementsByClassName("close2")[0];
          var span2 = document.getElementsByClassName("close")[0];
          var url = {!! json_encode(url('/')) !!};
          if (proses==2) {
            modal.style.display = "block";

            span.onclick = function() {
                modal.style.display = "none";
                location.reload();
            }
            // if(statuslulus === null && predikat === null && jabatan === null && statuslulus == 0 && predikat == 0 && jabatan == 0){
            //   $("#cetak1").hide();
            // }
          }
          else if (proses==5) {
            modal2.style.display = "block";
            span2.onclick = function() {
                modal2.style.display = "none";
                location.reload();
            }
          } else {
            window.open(url + '/proses/yudisium/' + studentid +'/export?proses='+proses, '_blank');
          }
        }

        $("#data_yudisium").submit(function (e) {
          e.preventDefault();

          $.ajax({
            url: "{{ route('beritaacara_yudisium.storeberitaacara_yudisium') }}",
            type: "POST",
            dataType: "json",
            data: $(this).serialize(),
            success: function (res) {
              $("#message").css('display', 'block').text(res.message);
            }
          })
        });
        $("#dataskl").submit(function (e){
          e.preventDefault();

          $.ajax({
            url: "{{ route('skl.store_skl') }}",
            type: "POST",
            dataType: "json",
            data: $(this).serialize(),
            success: function(res){
              $("#message2").css('display','block').text(res.message)
            }
          })
        });
    </script>


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
