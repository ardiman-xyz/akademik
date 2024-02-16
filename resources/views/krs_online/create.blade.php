@extends('layouts._layout')
@section('pageTitle', 'Buat KRS')
@section('content')
    @include('sweet::alert')

    <section class="content">
        <div>
            <div class="container-fluid-title">
                <div class="title-laporan">
                    <h3 class="text-white">Tambah Mata Kuliah</h3>
                </div>
            </div>

            <div class="container">

              {{-- <input type="text" name="nims" hidden value="{{ $nim }}">
              <input type="text" name="term_years" hidden value="{{ $term_year }}"> --}}

                <div class="panel panel-default bootstrap-admin-no-table-panel">
                    <div class="panel-heading-green">
                        <div class="pull-right tombol-gandeng dua">
                            <a href="{{ url('proses/krs_mahasiswa?nim='.$nim.'&term_year='.$term_year) }}" id="cancel" name="cancel" class="btn btn-danger btn-sm">Kembali
                                &nbsp;<i
                                        class="fa fa-reply"></i></a>
                        </div>
                        <div class="bootstrap-admin-box-title right text-white">
                            <b>Tambah Mata Kuliah</b>
                        </div>
                    </div>
                    <br>

                    <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
                        <div class="row">
                            <div class="col-md-12 col-md-8">

                                <form class="needs-validation" id="createKrsOnlineForm" name="createKrsOnlineForm"
                                      method="POST" url="{{ route('krsonline_store') }}">

                                    <div class="form-group">
                                        <div class="col-md-12 control-label">
                                            <label for="mata_kuliah">Mata Kuliah</label>
                                            <select type="text" class="form-control" id="mata_kuliah" name="mata_kuliah"
                                                    placeholder="mata_kuliah">
                                                <option id="pilih_matakuliah" value="">Pilih mata kuliah</option>
                                            </select>
                                        </div>
                                        <script type="text/javascript">
                                            var select = new SlimSelect({
                                            placeholder: 'Pilih Matakuliah',
                                            select: '#mata_kuliah'
                                            })
                                            select.selected()
                                        </script>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-12 control-label">
                                            <label for="total_sks">Total SKS</label>
                                            <input type="text" class="form-control" id="total_sks" name="total_sks"
                                                   placeholder="Total SKS" readonly>
                                        </div>
                                    </div>

                                    <!-- <div class="form-group">
                                        <div id="tampil_biaya" class="col-md-12 control-label">
                                            <label  for="total_biaya">Biaya per SKS</label>
                                            <input type="text" class="form-control" id="total_biaya" name="total_biaya"
                                                   placeholder="Biaya persks" readonly>
                                        </div>
                                    </div> -->

                                    <!-- <div class="form-group">
                                        <div class="col-md-12 control-label">
                                            <label for="matkul_biaya">Biaya Matakuliah</label>
                                            <input type="text" class="form-control" id="matkul_biaya" name="matkul_biaya"
                                                   placeholder="Biaya persks" readonly>
                                        </div>
                                    </div> -->

                                    <!-- <div class="form-group" id="beasiswaa">
                                      <div class="col-md-12 row text-green">
                                        <label class="col-md-2">Dispensasi Bayar </label>
                            							<input type="text" class="form-control form-control-sm col-md-2" id="beasiswa" name="beasiswa"
                                                 placeholder="Total biaya" readonly>
                                        <label class="col-md-1" >Sebesar </label>
                                        <input type="text" class="form-control form-control-sm col-md-1" id="beasiswadisc" name="beasiswadisc"
                                               placeholder="Total biaya" readonly>
                                        <label class="col-md-2" >% </label>
                                      </div>
                                        {{-- <div class="col-md-12 control-label">
                                            <label class="col-md-2"  id="lb_beasiswa" name="lb_beasiswa" for="beasiswa">Diskon</label>
                                            <input  type="text" class="col-md-2 form-control" id="beasiswa" name="beasiswa"
                                                   placeholder="Total biaya" readonly>
                                        </div> --}}
                                    </div> -->

                                    <!-- <div class="form-group">
                                        <div class="col-md-12 control-label">
                                            <label for="total_biaya2">Total Biaya Harus Dibayar</label>
                                            <input type="text" class="form-control" id="total_biaya2" name="total_biaya2"
                                                   placeholder="Total biaya" readonly>
                                        </div>
                                    </div> -->

                                    <div class="form-group">
                                        <div class="col-md-12 control-label">
                                            <label for="daftar_kelas">Pilih Kelas</label>
                                            <select type="text" class="form-control" id="daftar_kelas"
                                                    name="daftar_kelas"
                                                    placeholder="Pilih kelas">
                                                <option value="">Pilih Kelas</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-12 control-label">
                                            <label for="kapasitas_kelas">Kapasitas</label>
                                            <input type="text" class="form-control" id="kapasitas_kelas"
                                                   name="kapasitas_kelas"
                                                   placeholder="Kapasitas Kelas" readonly>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-12 control-label">
                                            <label for="sisa_kapasitas">Terdaftar</label>
                                            <input type="text" class="form-control" id="sisa_kapasitas"
                                                   name="sisa_kapasitas"
                                                   placeholder="Mahasiswa terdaftar" readonly>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-12 control-label">
                                            <label for="sisa_kuota">Sisa Kuota</label>
                                            <input type="text" class="form-control" id="sisa_kuota" name="sisa_kuota"
                                                   placeholder="Sisa kuota" readonly>
                                        </div>
                                    </div>

                                    <br>
                                    <table align="center">
                                        <tr>
                                            <td>
                                                <button type="submit" id="submit" class="btn btn-primary btn-flat">
                                                    Simpan
                                                </button>
                                            </td>
                                        </tr>
                                    </table>
                                    <br>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        $(function () {
          var nim = $('#nim').val();
          var term_year = $('#term_year').val();
          $("#beasiswaa").hide();
	

	console.log();

            $.ajax({
                url: "https://akademik.umkendari.ac.id/api/krs_online/course_list/create",
                type: "GET",
                data: {'nim':'{{ $_GET['nim'] }}','term_year':'{{ $_GET['term_year'] }}'},
                dataType: "JSON",
                success: function (result) {

                    if (result.success === 'true') {
                        $.each(result.data, function (index, value) {
                            $('#mata_kuliah').append('<option value="' + value.course_id + '">' + value.course_code + ' - ' + value.course_name + '</option>');
                        });

                        $('#mata_kuliah').change(function () {
                            var courseid = $(this).val();
                            $.each(result.data, function (index, value) {
                                if (value.course_id == courseid) {
                                    $("#total_sks").val(value.applied_sks);
                                    $("#total_biaya").val(value.amount_per_sks);
                                }
                            });

                            if (courseid !== undefined) {

                              $.ajax({

                                  headers: {
                                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                  },
                                  url: "https://akademik.umkendari.ac.id/api/krs_online/coursecost/" +  courseid,
                                  type: "GET",
                                  data: {'nim':'{{ $_GET['nim'] }}','term_year':'{{ $_GET['term_year'] }}'},
                                  dataType: "JSON",
                                  success: function (result) {
                                    if (result.success === 'true') {
                                        $.each(result.data, function (index, value) {
                                        if(value.is_sks == 0){
                                            $("#tampil_biaya").hide();
                                        }else{
                                            $("#tampil_biaya").show();
                                        }
                                          if(value.keterangan!=null){
                                            $("#beasiswaa").show();
                                            $("#beasiswa").val(value.keterangan);
                                            $("#matkul_biaya").val(value.amountfull);
                                            $("#total_biaya2").val(value.amount);
                                            $("#beasiswadisc").val(value.discount);
                                          }else{
                                            $("#beasiswaa").hide();
                                            $("#matkul_biaya").val(value.amountfull);
                                            $("#total_biaya2").val(value.amount);
                                            $("#beasiswadisc").val(value.discount);
                                          }
                                        });
                                      }
                                  }
                              })

                                $.ajax({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    url: "https://akademik.umkendari.ac.id/api/krs_online/class/" +  courseid,
                                    type: "GET",
                                    data: {'nim':'{{ $_GET['nim'] }}','term_year':'{{ $_GET['term_year'] }}'},
                                    dataType: "JSON",
                                    success: function (result) {
                                        $('#daftar_kelas').empty();

                                        $('#daftar_kelas').append('<option value="">Pilih Kelas</option>');
                                        $.each(result.data, function (index, value) {
                                          // $('#daftar_kelas').empty();
                                            $('#daftar_kelas').append('<option value="' + value.Class_Id + '">' + value.Class_Name + '</option>')

                                        });

                                        $('#daftar_kelas').change(function () {
                                            var classid = $(this).val();
                                            $.ajax({
                                                headers: {
                                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                },
                                                url: "https://akademik.umkendari.ac.id/api/krs_online/classinfo/" + courseid + "/" + classid,
                                                type: "GET",
                                                data: {'nim':'{{ $_GET['nim'] }}','term_year':'{{ $_GET['term_year'] }}'},
                                                dataType: "JSON",
                                                success: function (result) {
                                                    $.each(result.data, function (index, value) {
                                                        $("#kapasitas_kelas").val(value.Capacity);
                                                        $("#sisa_kapasitas").val(value.Used);
                                                        $("#sisa_kuota").val(value.Free)
                                                    });
                                                }
                                            })

                                        });
                                    }
                                })
                            }
                        });

                        $('#createKrsOnlineForm').on("submit", function (e) {

                            var nims = "{{ $_GET['nim'] }}";
                            var term_years = "{{ $_GET['term_year'] }}";

                            var data = $('#createKrsOnlineForm').serializeArray();
                            var courseid = $('#mata_kuliah option:selected').val();
                            var classid = $('#daftar_kelas option:selected').val();

                            var nimmd5 = '<?php echo md5($_GET['nim']); ?>';
                            var nim = '{{ $_GET['nim'] }}';
                            var url = {!! json_encode(url('/')) !!};
                            <?php
                            function acak($panjang)
                            {
                                $karakter= 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz123456789';
                                $string = '';
                                for ($i = 0; $i < $panjang; $i++) {
                              $pos = rand(0, strlen($karakter)-1);
                              $string .= $karakter{$pos};
                                }
                                return $string;
                            }
                            //cara memanggilnya
                            $hasil_1= acak(125);

                              $cookie_name = "TESTTOKEN";
                              $cookie_value = $hasil_1;
                            ?>
                            var cookie_name = "<?php echo $cookie_name; ?>";
                            var cookie_value = "<?php echo $cookie_value; ?>";
                            setCookie(cookie_name,cookie_value,60);

                            e.preventDefault();

                            if (courseid.length <= 0) {

                                swal({
                                    title: "Mata kuliah tidak boleh kosong",
                                    text: "Oppsss...!",
                                    type: "warning",
                                    button: true
                                });

                            } else {
                                if (classid.length <= 0) {

                                    swal({
                                        title: "Kelas tidak boleh kosong",
                                        text: "Oppsss...!",
                                        type: "warning",
                                        button: true
                                    });

                                } else {
                                        $.ajax({
                                          headers: {
                                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                                          },
                                          url: "https://akademik.umkendari.ac.id/api/krs_online/prerequisitecourse/" + courseid + "/" + classid,
                                          type: "GET",
                                          data: {'nim':'{{ $_GET['nim'] }}','term_year':'{{ $_GET['term_year'] }}'},
                                          dataType: "JSON",
                                          success: function (result) {

                                            if (result.success == 'false') {

                                              swal({
                                                title: result.data,
                                                text: "Oppsss...!",
                                                type: "warning",
                                                button: true
                                              }, function () {
                                              });

                                            } else {


                                              $.ajax({
                                                headers: {
                                                  "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                                                },
                                                url: "https://akademik.umkendari.ac.id/api/krs_online/prerequisiteclass/" + courseid + "/" + classid,
                                                type: "GET",
                                                data: {'nim':'{{ $_GET['nim'] }}','term_year':'{{ $_GET['term_year'] }}'},
                                                dataType: "JSON",
                                                success: function (result) {

                                                  if (result.success == 'false') {

                                                    swal({
                                                      title: result.data,
                                                      text: "Oppsss...!",
                                                      type: "warning",
                                                      button: true
                                                    }, function () {
                                                    });

                                                  } else {
                                                          var token = "<?php echo $hasil_1;
                                                          ?>";
                                                          data.push({ name : "token", value : token });
                                                          data.push({ name : "nim", value : "{{ $_GET['nim'] }}" });
                                                          data.push({ name : "term_year", value : "{{ $_GET['term_year'] }}" });
console.log(data);



                                                            $.ajax({
                                                    headers: {
                                                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                                                    },
                                                    url: "https://akademik.umkendari.ac.id/api/krs_online/store/",
                                                    type: "GET",
                                                    data: data,
                                                    dataType: "JSON",
                                                    success: function (result) {

                                                        if (result.success == 'false') {

                                                            swal({
                                                                title: result.data,
                                                                text: "Oppsss...!",
                                                                type: "warning",
                                                                button: true
                                                            }, function () {
                                                            });

                                                        } else {



                                                            swal({
                                                                title: result.data,
                                                                text: "Berhasil...!",
                                                                type: "success",
                                                                button: true
                                                            }, function () {
                                                                window.location.href = "{{ url('proses/krs_mahasiswa') }}"+"?nim="+ nims+"&term_year="+ term_years;
                                                            });

                                                        }
                                                    }
                                                });
                                                  }
                                                }
                                              });



                                            }
                                          }
                                        });










                                }
                            }
                            function setCookie(cname, cvalue, exdays) {
                              var d = new Date();
                              d.setTime(d.getTime() + (exdays*1000));
                              var expires = "expires="+ d.toUTCString();
                              document.cookie = cname + "=" + cvalue + ";" + expires + ";domain=.umy.ac.id;path=/";
                          }

function mycallback(responseJSON) {
    alert(result);
}
                        });
                    }
                }
            });

        })
    </script>

@endsection
