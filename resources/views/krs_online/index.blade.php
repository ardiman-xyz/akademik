@extends('layouts._layout')
@section('pageTitle', 'KRS Online')
@section('content')
    @include('sweet::alert')

    <section class="content">
        <div class="container-fluid-title">
            <div class="title-laporan">
                <h3 class="text-white">KRS Online</h3>
            </div>
        </div>
        <div class="container">
            <div class="panel panel-default bootstrap-admin-no-table-panel">
                <div class="panel-heading-green">
                    <div class="pull-right tombol-gandeng dua">
                        <a href="{{ route('home') }}" id="cancel" name="cancel" class="btn btn-danger btn-sm">Home
                            &nbsp;<i
                                    class="fa fa-reply"></i></a>
                        <a id="tambah_krs" name="tambah_krs" href="{{ route('krsonline_create') }}"
                           class="btn btn-info btn-sm">Tambah KRS <i
                                    class="fa fa-book"></i></a>
                    </div>
                    <div class="bootstrap-admin-box-title right text-white">
                        <b>KRS Online</b>
                    </div>
                </div>
                <br>
                <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">

                    <div class="row">
                        <div class="col-sm-6">
                            <label id="tahun_ajaran" class="col-sm-4">Tahun Ajaran</label>
                            <label id="tahun_ajaran_data" class="col-sm-7">: </label>
                            <label id="status_krs" class="col-sm-4">Status KRS</label>
                            <label id="status_krs_data" class="col-sm-7">: </label>
                            <label id="pembukaan_krs" class="col-sm-4">Pembukaan KRS</label>
                            <label id="pembukaan_krs_data" class="col-sm-7">: </label>
                            <label id="penutupan_krs" class="col-sm-4">Penutupan KRS</label>
                            <label id="penutupan_krs_data" class="col-sm-7">: </label>
                        </div>

                        <div class="col-sm-6">
                            <label id="nama" class="col-sm-4">Nama</label>
                            <label id="nama_data" class="col-sm-7">: </label>
                            <label id="jurusan" class="col-sm-4">Jurusan</label>
                            <label id="jurusan_data" class="col-sm-7">: </label>
                            <label id="total_sks" class="col-sm-4">Total SKS yang diizinkan</label>
                            <label id="total_sks_data" class="col-sm-7">: </label>
                            <label id="sks_dipakai" class="col-sm-4">Sisa SKS</label>
                            <label id="sks_dipakai_data" class="col-sm-7">: </label>
                        </div>
                    </div>

                    <hr>
                    <div id="krsOnlineGrid"></div>
                    <div id="hapusDialog"></div>

                </div>

            </div>
        </div>
    </section>

    <script>

        $(function () {

            //Data untuk status KRS
            $.ajax({
                url: "{{ route('krsonline_status') }}",
                type: "GET",
                dataType: "JSON",
                success: function (result) {

                    if (result.success === 'true') {

                        $('#penutupan_krs_data').html('<label> : ' + result.data.End_Date.substr(0, 10) + '</label>');
                        $('#pembukaan_krs_data').html('<label> : ' + result.data.Start_Date.substr(0, 10) + '</label>');
                        $('#tahun_ajaran_data').html('<label> : ' + result.data.mstr_term_year.Term_Year_Name + '</label>');

                        if (result.data.Is_Open === "1") {
                            $('#status_krs_data').html('<label> : ' + 'BUKA' + '</label>');
                        } else {

                            $('#status_krs_data').html('<label> : ' + 'TUTUP' + '</label>');

                            swal({
                                title: "Masa pengisian KRS ditutup",
                                text: "Masa KRS dibuka tanggal " + result.data.Start_Date.substr(0, 10) + " sampai dengan " + result.data.Start_Date.substr(0, 10),
                                type: "warning",
                                button: true
                            }, function () {
                                window.location.href = "{{ route('home') }}";
                            });

                        }
                    } else {

                        swal({
                            title: result.data,
                            text: "Oppsss...!",
                            type: "warning",
                            button: true
                        }, function () {
                            window.location.href = "{{ route('home') }}";
                        });

                    }
                }
            });

            //Total SKS yang diizinkan
            $.ajax({
                url: "{{ route('krsonline_allowedsks') }}",
                type: "GET",
                dataType: "JSON",
                success: function (result) {

                    if (result.success === 'true') {

                        $.each(result.data, function (index, value) {

                            if (result.success === 'true') {
                                $('#total_sks_data').html('<label> : ' + value.AllowedSKS + '</label>');
                            }

                        })
                    }
                }
            });

            //Nama dan jurusan mahasiswa
            $.ajax({
                url: "{{ route('krsonline_student') }}",
                type: "GET",
                dataType: "JSON",
                success: function (result) {
                    $.each(result.data, function (index, value) {
                        $('#nama_data').html('<label> : ' + value.Full_Name + '</label>');
                        $('#jurusan_data').html('<label> : ' + value.mstr_department['Department_Name'] + '</label>');
                    })
                }
            });

            //Sisa SKS
            $.ajax({
                url: "{{ route('krsonline_used') }}",
                type: "GET",
                dataType: "JSON",
                success: function (result) {
                    $.each(result.data, function (index, value) {
                        $('#sks_dipakai_data').append('<label>' + value + '</label>')
                    })
                }
            });

            //Datasource untuk data KRS ONLINE
            var krsOnlineDataSource = new kendo.data.DataSource({
                transport: {
                    read: {
                        url: "{{ route('krsonline_course') }}",
                        type: "GET",
                        dataType: "json"
                    }
                },
                schema: {
                    data: "data",
                    total: "total",
                    model: {
                        id: "Krs_Id"
                    }
                },
                pageSize: 10,
                serverPaging: true
            });


            //Datagrid untuk data KRS ONLINE
            $("#krsOnlineGrid").kendoGrid({
                dataSource: krsOnlineDataSource,
                columns: [
                    {
                        field: "Course_Code",
                        title: "Kode Mata Kuliah",
                        headerAttributes: {
                            style: "text-align: center"
                        },
                        width: "50px"
                    },
                    {
                        field: "Course_Name",
                        title: "Nama Mata Kuliah",
                        headerAttributes: {
                            style: "text-align: center"
                        },
                        width: "50px"
                    },
                    {
                        field: "Class_Name",
                        title: "Kelas",
                        attributes: {
                            style: "text-align: center"
                        },
                        headerAttributes: {
                            style: "text-align: center"
                        },
                        width: "50px"
                    },
                    {
                        field: "Sks",
                        title: "SKS",
                        attributes: {
                            style: "text-align: center"
                        },
                        headerAttributes: {
                            style: "text-align: center"
                        },
                        width: "50px",
                    },
                    {
                        field: "Amount",
                        title: "Biaya",
                        headerAttributes: {
                            style: "text-align: center"
                        },
                        width: "50px",
                        nullable: false,
                        visible: true
                    },
                    {
                        field: "Pengaturan",
                        title: "Pengaturan",
                        width: "50px",
                        nullable: false,
                        visible: true,
                        attributes: {
                            style: "text-align: center"
                        },
                        command: [
                            {
                                name: "edit",
                                click: editData,
                                text: {
                                    edit: "Ubah",
                                    update: "Simpan",
                                    cancel: "Batal",
                                    width: "50px"
                                }
                            },
                            {
                                name: "customDelete",
                                iconClass: "k-icon k-i-close",
                                text: "Hapus",
                                width: "50px",
                                click: hapusData
                            }
                        ]
                    }
                ],
                noRecords: true,
                pageable: {
                    pageSizes: true,
                    numeric: false,
                    refresh: true
                }
            });

            function editData(e) {
                e.preventDefault();

                var tr = $(e.target).closest("tr"),
                    data = this.dataItem(tr);

                var Krs_Id = data.Krs_id;
                var Term_Year_Id = data.Term_Year_Id;
                var Class_Id = data.Class_Id;
                var Course_Id = data.Course_Id;

                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                    },
                    url: "{{ route('krsonline_edit_course') }}",
                    type: "POST",
                    data: {Krs_Id, Term_Year_Id, Class_Id, Course_Id},
                    dataType: "JSON",
                    success: function (result) {
                        if (result.success === 'false') {

                            swal({
                                title: "Oppsss...!",
                                text: result.data,
                                type: "warning",
                                button: true
                            });

                            $("#krsOnlineGrid").data("kendoGrid").dataSource.read();

                        } else {
                            window.location.href = "{{ route('krsonline_edit') }}" + "/" + data.Krs_id + "/" + data.Course_Id + "/" + data.Class_Id + "/" + data.Term_Year_Id; // "/Krs_Id=" + data.Krs_id + "?Course_Id=" + data.Course_Id;
                        }
                    }
                });

            }

            //Fungsi hapus data KRS ONLINE
            function hapusData(e) {
                e.preventDefault();

                var tr = $(e.target).closest("tr"),
                    data = this.dataItem(tr);

                hapusDialog = $("#hapusDialog").kendoDialog({
                    width: "350px",
                    title: "Hapus Data",
                    content: "Anda Yakin Akan Menghapus Data Ini ?",
                    visible: false,
                    buttonLayout: "stretched",
                    actions: [
                        {
                            text: "Hapus",
                            primary: true,
                            action: function (e) {
                                var Krs_Id = {Krs_Id: data.Krs_id};
                                var Term_Year_Id = {Term_Year_Id: data.Term_Year_Id};

                                $.ajax({
                                    headers: {
                                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                                    },
                                    url: "{{ route('krsonline_delete') }}",
                                    type: "POST",
                                    data: {Krs_Id, Term_Year_Id},
                                    dataType: "JSON",
                                    success: function (result) {

                                        if (result.success === 'false') {

                                            swal({
                                                title: "Oppsss...!",
                                                text: result.data,
                                                type: "warning",
                                                button: true
                                            });

                                        } else {

                                            swal({
                                                title: "Berhasil...!",
                                                text: result.data,
                                                type: "success",
                                                button: true
                                            });

                                            $("#krsOnlineGrid").data("kendoGrid").dataSource.read();
                                        }
                                    }
                                });
                            }
                        },
                        {text: "Batal"}
                    ]
                }).data("kendoDialog");

                hapusDialog.open();
            }
        })

    </script>

@endsection
