@extends('layouts._layout')
@section('pageTitle', 'Cuti')
@section('content')

<style>
    .k-grid-save-changes {
        display: none !important;
    }
</style>
    <section class="content">
        <div class="container-fluid-title">
            <div class="title-laporan">
                <h3 class="text-white">Edit Cuti</h3>
            </div>
        </div>
        <div class="container">
            <div class="panel panel-default bootstrap-admin-no-table-panel">
                <div class="panel-heading-green">
                    <div class="pull-right tombol-gandeng dua">
                        <a href="{{ url('proses/cuti?page=' . $page . '&rowpage=' . $rowpage . '&search=' . $search. '&term_year=' . $request->term_year) }}"
                            class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
                    </div>
                    <div class="bootstrap-admin-box-title right text-white">
                        <b>Edit</b>
                    </div>
                </div>
                <br>
                <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">

                    @if (count($errors) > 0)
                        @foreach ($errors->all() as $error)
                            @if ($error == 'Berhasil Menyimpan Perubahan')
                                <p class="alert alert-success">{{ $error }}</p>
                            @else
                                <p class="alert alert-danger">{{ $error }}</p>
                            @endif
                        @endforeach
                    @endif
                    {!! Form::open([
                        'url' => route('cuti.update', $data_student->Student_Vacation_Id),
                        'method' => 'put',
                        'class' => 'form',
                        'id' => 'myForm',
                        'enctype' => 'multipart/form-data',
                    ]) !!}
                    <div class="form-group">
                        {!! Form::label('', 'Nama Mahasiswa', ['class' => 'col-md-4 form-label']) !!}
                        <div class="col-md-12">
                            <input type="text" readonly name="Full_Name" class="form-control form-control-sm col-md-12"
                                value="{{ $data_student->Full_Name }}">
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('', 'Semester', ['class' => 'col-md-4 form-label']) !!}
                        <div class="col-md-12">
                            <input type="text" readonly name="Semester" class="form-control form-control-sm col-md-12"
                                value="{{ $data_student->Term_Year_Id }}">
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('', 'Alasan', ['class' => 'col-md-4 form-label']) !!}
                        <div class="col-md-12">
                            <input type="text" name="Deskripsi" class="form-control form-control-sm col-md-12"
                                value="{{ $data_student->Description }}">
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('', 'Terima / Tidak', ['class' => 'col-md-4 form-label']) !!}
                        <div class="col-md-12">
                            <select id="acc" class="form-control form-control-sm col-md-12" name="acc">
                                <option value="0">Pilih</option>
                                <option value="1" <?php if ($data_student->Is_Approved == 1) {
                                    echo 'selected';
                                } ?>>Ya</option>
                                <option value="2" <?php if ($data_student->Is_Approved == '0') {
                                    echo 'selected';
                                } ?>>Tidak</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('', 'SK Date', ['class' => 'col-md-4 form-label']) !!}
                        <div class="col-md-12">
                            <?php
                            $date = strtotime($data_student->Sk_Date);
                            $SK_Date = date('Y-m-d', $date);
                            ?>
                            <input type="date" name="Sk_Date" value="{{ $SK_Date }}"
                                class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('', 'SK Number', ['class' => 'col-md-4 form-label']) !!}
                        <div class="col-md-12">
                            <input type="text" name="Sk_Number" min="1" value="{{ $data_student->Sk_Number }}"
                                class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="form-group">
                        {!! Form::label('', 'File', ['class' => 'col-md-4 form-label']) !!}
                        @if ($data_student->File != null)
                            <div class="col-md-12">
                                <a href="{{route('getfile')}}?name={{$data_student->File}}"
                                    target="_blank">{{ $data_student->File }}</a>
                            </div>
                        @endif
                        <div class="col-md-12">
                            <input type="file" name="file" id="" class="form-control"
                                accept=".jpg,.jpeg,.pdf,.png"><br>
                        </div>
                    </div>
                    
                    <div id="gridFac" style="width:100%;" class="text-success"></div>

                    <br>
                    {!! Form::close() !!}
                    <center><button type="" onClick="submitform()" class="btn btn-primary btn-flat">Simpan</button></center>

                </div>
            </div>
        </div>

        <!-- /.row -->

    </section>
    <script>
        $(document).ready(function() {
            var grid = $('#gridFac').kendoGrid({
                dataSource: {
                    transport: {
                        read: function(options) {
                            $.ajax({
                                dataType: 'json',
                                url: "{{ route('api.get.master_berkas_siswa_cuti') }}",
                                type: 'GET',
                                data: {
                                    Student_Vacation_Id: <?php echo $data_student->Student_Vacation_Id; ?>,
                                    Term_Year_Id: <?php echo $request->term_year ?>,
                                },
                                success: function(res) {
                                    options.success(res);
                                },
                                error: function(xhr, ajaxOptions, thrownError) {
                                    swal({
                                            title: thrownError,
                                            text: 'Error!! ' + xhr.status,
                                            type: "error",
                                            confirmButtonColor: "#02991a",
                                            confirmButtonText: "Refresh Serkarang",
                                            cancelButtonText: "Tidak, Batalkan!",
                                            closeOnConfirm: false,
                                        },
                                        function(isConfirm) {
                                            if (isConfirm) {
                                                // window.location.reload(true) // submitting the form when user press yes
                                                var grids = grid.data("kendoGrid");
                                                grids.dataSource.read();
                                                swal.close()
                                            }
                                        });
                                }

                            })
                        }, //read
                        update: function(options) {
                            options.data.Student_Vacation_Id = <?php echo $data_student->Student_Vacation_Id; ?>,
                                $.ajax({
                                    dataType: 'json',
                                    url: "{{ route('api.post.postberkassiswaCuticontroller') }}",
                                    type: 'post',
                                    data: {
                                        data: options.data,
                                    },
                                    success: function(res) {
                                        console.log(res);
                                        if (res.success == false) {
                                            swal('Sorry', res.data, 'warning');
                                        } else {
                                            swal('Ok', res.data, 'success');
                                            options.success(res);
                                            var grids = $('#gridFac').data("kendoGrid");
                                            grids.dataSource.read();
                                        }
                                    },
                                    error: function(xhr, ajaxOptions, thrownError) {
                                        swal({
                                                title: thrownError,
                                                text: 'Error!! ' + xhr.status,
                                                type: "error",
                                                confirmButtonColor: "#02991a",
                                                confirmButtonText: "Refresh Serkarang",
                                                cancelButtonText: "Tidak, Batalkan!",
                                                closeOnConfirm: false,
                                            },
                                            function(isConfirm) {
                                                if (isConfirm) {
                                                    // window.location.reload(true) // submitting the form when user press yes
                                                    var grids = $('#gridFac').data(
                                                        "kendoGrid");
                                                    grids.dataSource.read();
                                                    swal.close()
                                                }
                                            });
                                    }

                                })
                        }, //update
                    }, //transport grid
                    batch: true,
                    schema: {
                        data: 'data',
                        total: 'total',
                        model: {
                            id: "Student_Vacation_Document_Id",
                            fields: {
                                Student_Vacation_Document_Id: {
                                    editable: false
                                },
                                Student_Vacation_Prerequisite_Id: {
                                    editable: false
                                },
                                No: {
                                    editable: false
                                },
                                Vacation_Document_Id: {
                                    editable: false
                                },
                                Vacation_Document_Name: {
                                    editable: false
                                },
                                Copies: {
                                    editable: false
                                },
                                File_Upload: {
                                    editable: false
                                },
                                Is_Accepted: {
                                    type: "boolean"
                                },
                                Notes: {
                                    editable: true
                                },
                                Created_By: {
                                    editable: false
                                },
                            }
                        }
                    },
                    // serverPaging: true,
                    pageSize: 10,
                }, //dataSourceGrid
                sortable: true,
                pageable: {
                    refresh: true,
                    pageSizes: true,
                    buttonCount: 5
                },
                editable: true,
                toolbar: ["save", "cancel"],
                dataBound: onDataBound,
                columns: [{
                        field: "No",
                        title: "No",
                        width: "5%",
                        template: "#= No ? No : ''#",
                    },
                    {
                        field: "Vacation_Document_Id",
                        title: "Nama Dokumen",
                        width: "45%",
                        template: "#= Vacation_Document_Name ? Vacation_Document_Name : ''#",
                    },
                    {
                        field: "Copies",
                        title: "Jumlah",
                        width: "5%",
                        template: "#= Copies ? Copies : ''#",
                    },
                    {
                        field: "File_Upload",
                        title: "File",
                        width: "5%",
                        // template: "#= File_Upload ? File_Upload : ''#",
                        template: "# if (File_Upload != null) {# <a target='_blank' href='https://simak.umkendari.ac.id/getfile?name=cuti/#:Student_Id#/#:File_Upload#'>file</a> #}#", headerAttributes: { style: "text-align: center" }
                    },
                    {
                        field: "Is_Accepted",
                        title: "L/TL",
                        width: "5%",
                        template: "# if( Is_Accepted == 1) {#<span>L<span># } else {#<span style='color:red'>TL<span>#} #",
                        // template: "#= Is_Accepted ? Is_Accepted : ''#" ,
                    },
                    {
                        field: "Notes",
                        title: "Notes",
                        width: "25$",
                        template: "#= Notes ? Notes : ''#",
                    },
                    {
                        field: "Modified_By",
                        title: "Petugas",
                        width: "25$",
                        template: "#= Modified_By ? Modified_By : ''#",
                    }
                ],
            });
            //end kendo grid
            var checkedIds = {};

            function onDataBound(e) {
                var view = this.dataSource.view();
                for (var i = 0; i < view.length; i++) {
                    // console.log(this.tbody.find("tr[data-uid='" + view[i].uid + "']").find(".k-input k-textbox"));
                    if (checkedIds[view[i].id]) {
                        this.tbody.find("tr[data-uid='" + view[i].uid + "']")
                            .addClass("k-state-selected")
                            .find(".k-input k-textbox")
                            .attr("checked", "checked");
                    }
                }
            }
        });
        function submitform() {
          $('#myForm').submit();
          $(".k-grid-save-changes").click()
        }
    </script>
@endsection
