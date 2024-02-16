@extends('layouts._layout')
@section('pageTitle', 'Edit KRS')
@section('content')
    @include('sweet::alert')

    <section class="content">
        <div>
            <div class="container-fluid-title">
                <div class="title-laporan">
                    <h3 class="text-white">Edit Mata Kuliah</h3>
                </div>
            </div>

            <div class="container">
                <div class="panel panel-default bootstrap-admin-no-table-panel">
                    <div class="panel-heading-green">
                        <div class="pull-right tombol-gandeng dua">
                            <a href="{{ route('krs_online') }}" id="cancel" name="cancel" class="btn btn-danger btn-sm">KRS
                                Online
                                &nbsp;<i
                                        class="fa fa-reply"></i></a>
                        </div>
                        <div class="bootstrap-admin-box-title right text-white">
                            <b>Edit Mata Kuliah</b>
                        </div>
                    </div>
                    <br>

                    <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
                        <div class="row">
                            <div class="col-md-12 col-md-8">

                                <form class="needs-validation" id="editKrsOnlineForm" name="editKrsOnlineForm"
                                      method="POST" url="{{ route('krsonline_edit_update') }}">

                                    <div class="form-group">
                                        <div class="col-md-12 control-label">
                                            <label for="mata_kuliah">Mata Kuliah</label>
                                            <input type="text" class="form-control" id="mata_kuliah" name="mata_kuliah"
                                                   placeholder="Mata Kuliah" readonly>
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
                                            <td><a href="{{ route('krs_online') }}" class="btn btn-warning btn-flat">Kembali</a>
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
            var Krs_Id = "{{ Request::segment(3) }}";
            var Course_Id = "{{ Request::segment(4) }}";
            var Class_Id = "{{ Request::segment(5) }}";
            var Term_Year_Id = "{{ Request::segment(6) }}";

            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                },
                url: "{{ route('krsonline_edit_course') }}",
                type: "POST",
                data: {Krs_Id, Course_Id, Class_Id, Term_Year_Id},
                dataType: "JSON",
                success: function (result) {

                    $.each(result.data, function (index, value) {
                        $("#mata_kuliah").val(value.acd_course.Course_Name);
                        $("#total_sks").val(value.Sks);
                        $("#total_biaya").val(value.Amount);
                    });
                }

            });

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('krsonline_edit_class') }}",
                type: "POST",
                data: {Course_Id},
                dataType: "JSON",
                success: function (result) {

                    $.each(result.data, function (index, value) {
                        $('#daftar_kelas').append('<option value="' + value.Class_Id + '">' + value.Class_Name + '</option>')
                    });

                    $('#daftar_kelas').change(function () {
                        var Class_Id = $(this).val();
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: "{{ route('krsonline_edit_class_info') }}",
                            type: "POST",
                            data: {Course_Id, Class_Id},
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
            });

            $('#editKrsOnlineForm').on("submit", function (e) {
                e.preventDefault();

                var Krs_Id = "{{ Request::segment(3) }}";
                var Course_Id = "{{ Request::segment(4) }}";
                var Class_Id = "{{ Request::segment(5) }}";
                var Term_Year_Id = "{{ Request::segment(6) }}";

                var classid = $('#daftar_kelas option:selected').val();

                if (classid.length <= 0) {

                    swal({
                        title: "Oppsss...!",
                        text: "Pilihan kelas tidak boleh kosong !",
                        type: "warning",
                        button: true
                    });

                } else {

                    $.ajax({
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                        },
                        url: "{{ route('krsonline_edit_update') }}",
                        type: "POST",
                        data: {Krs_Id, Course_Id, Class_Id, Term_Year_Id},
                        dataType: "JSON",
                        success: function (result) {

                            if (result.success == 'false') {

                                swal({
                                    title: "Oppsss...!",
                                    text: result.data,
                                    type: "warning",
                                    button: true
                                }, function () {
                                    window.location.href = "{{ route('krs_online') }}";
                                });

                            } else {

                                swal({
                                    title: "Berhasil...!",
                                    text: result.data,
                                    type: "success",
                                    button: true
                                }, function () {
                                    window.location.href = "{{ route('krs_online') }}";
                                });

                            }
                        }
                    });

                }

            });

        })
    </script>

@endsection
