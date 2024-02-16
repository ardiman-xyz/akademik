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
                            <a href="{{ url('proses/krs_mahasiswa') }}" id="cancel" name="cancel" class="btn btn-danger btn-sm">KRS
                                Online
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
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-12 control-label">
                                            <label for="total_sks">Total SKS</label>
                                            <input type="text" class="form-control" id="total_sks" name="total_sks"
                                                   placeholder="Total SKS" readonly>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-md-12 control-label">
                                            <label for="total_biaya">Total Biaya</label>
                                            <input type="text" class="form-control" id="total_biaya" name="total_biaya"
                                                   placeholder="Total biaya" readonly>
                                        </div>
                                    </div>

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
                                            <td><a href="{{ url('proses/krs_mahasiswa') }}" class="btn btn-warning btn-flat">Kembali</a>
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

            $.ajax({
                url: "{{ route('krsonline_courselist') }}",
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
                                    url: "{{ route('krsonline_class') }}" + "/" + courseid,
                                    type: "POST",
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
                                                url: "{{ route('krsonline_classinfoo') }}" + "/" + courseid + "/" + classid,
                                                type: "POST",
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
                            e.preventDefault();

                            var nims = "{{ $_GET['nim'] }}";
                            var term_years = "{{ $_GET['term_year'] }}";

                            var data = $('#createKrsOnlineForm').serializeArray();
                            var courseid = $('#mata_kuliah option:selected').val();
                            var classid = $('#daftar_kelas option:selected').val();

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
                                        url: "{{ route('krsonline_prerequisitecourse') }}" + "/" + courseid + "/" + classid,
                                        type: "POST",
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
                                                  // window.location.href = "{{ url('proses/krs_online') }}"+"/create?nim="+ nims+"&term_year="+ term_years;

                                                });

                                            } else {


											 $.ajax({
                                        headers: {
                                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                                        },
                                        url: "{{ route('krsonline_prerequisiteclass') }}" + "/" + courseid + "/" + classid,
                                        type: "POST",
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
                                                  window.location.href = "{{ url('proses/krs_mahasiswa') }}"+"?nim="+ nims+"&term_year="+ term_years;

                                                });

                                            } else {
                                              data.push({ name : "nim", value : "{{ $_GET['nim'] }}" });
                                              data.push({ name : "term_year", value : "{{ $_GET['term_year'] }}" });
                                                $.ajax({
                                                    headers: {
                                                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                                                    },
                                                    url: "{{ route('krsonline_store') }}",
                                                    type: "POST",
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
                                                              window.location.href = "{{ url('proses/krs_mahasiswa') }}"+"?nim="+ nims+"&term_year="+ term_years;

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
                        });
                    }
                }
            });

        })
    </script>

@endsection
