@extends('layouts._layout')
@section('pageTitle', 'Cuti')
@section('content')
    <section class="content">

        <style>
            .k-grid-save-changes {
                display: none !important;
            }
        </style>
        <div class="container-fluid-title">
            <div class="title-laporan">
                <h3 class="text-white">Tambah Mahasiswa Cuti</h3>
            </div>
        </div>
        <div class="container">
            <div class="panel panel-default bootstrap-admin-no-table-panel">
                <div class="panel-heading-green">
                    <div class="pull-right tombol-gandeng dua">
                        <a href="{{ url('proses/cuti?page=' . $page . '&rowpage=' . $rowpage . '&search=' . $search . '&term_year=' . $term_year) }}"
                            class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
                    </div>
                    <div class="bootstrap-admin-box-title right text-white">
                        <b>Tambah</b>
                    </div>
                </div>
                <br>
                <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">

                    @if (count($errors) > 0)
                        @foreach ($errors->all() as $error)
                            @if ($error == 'Berhasil Menambah Cuti')
                                <p class="alert alert-success">{{ $error }}</p>
                            @else
                                <p class="alert alert-danger">{{ $error }}</p>
                            @endif
                        @endforeach
                    @endif

                    {!! Form::open([
                        'url' => route('cuti.create'),
                        'method' => 'GET',
                        'name' => 'form',
                        'class' => 'form-horizontal',
                        'role' => 'form',
                    ]) !!}
                    <div class="form-group">
                        {!! Form::label('', 'Prodi', ['class' => 'col-md-4 form-label']) !!}
                        <div class="col-md-12">
                            <input type="text" name="term_year" hidden value="{{ $request->term_year }}"
                                class="form-control form-control-sm">
                            <select id="Department_Id" class="form-control form-control-sm col-md-12" name="Department_Id"
                                onchange="document.form.submit();">
                                <option value="0">Pilih Prodi</option>
                                @foreach ($select_department as $data)
                                    <option <?php if ($request->Department_Id == $data->Department_Id) {
                                        echo 'selected';
                                    } ?> value="{{ $data->Department_Id }}">
                                        {{ $data->Department_Name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {!! Form::close() !!}

                    <form action="{{ route('cuti.store') }}" method="post" id="myForm" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            {!! Form::label('', 'Nama Mahasiswa', ['class' => 'col-md-4 form-label']) !!}
                            <div class="col-md-12">
                                <select id="select" class="form-control form-control-sm col-md-12" name="mahasiswa"
                                    onchange="changestudent(this)">
                                    <option value="0">Pilih Mahasiswa...</option>
                                    @foreach ($select_mahasiswa as $data)
                                        <option <?php if ($mahasiswa == $data->Student_Id) {
                                            echo 'selected';
                                        } ?> value="{{ $data->Student_Id }}">{{ $data->Nim }} |
                                            {{ $data->Full_Name }}</option>
                                    @endforeach
                                </select>
                                <label style="color:red;" id='info_extra'></label>
                            </div>
                        </div>
                        <select hidden class="form-control form-control-sm col-md-12" name="term_year">
                            <option value="0">Pilih Semester...</option>
                            @foreach ($select_term_year as $data)
                                <option <?php if ($term_year == $data->Term_Year_Id) {
                                    echo 'selected';
                                } ?> value="{{ $data->Term_Year_Id }}">
                                    {{ $data->Term_Year_Name }}</option>
                            @endforeach
                        </select>
                        <div class="form-group">
                            <!-- {!! Form::label('', 'Alasan', ['class' => 'col-md-4 form-label']) !!} -->
                            <div class="col-md-12">
                                <select class="form-control form-control-sm col-md-12" hidden name="reason">
                                    @foreach ($select_reason as $data)
                                        <option <?php if ($reason == $data->Vacation_Reason_Id) {
                                            echo 'selected';
                                        } ?> value="{{ $data->Vacation_Reason_Id }}">
                                            {{ $data->Vacation_Reason }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> 
                        
                        <div class="form-group">
                            {!! Form::label('', 'Terima / Tidak', ['class' => 'col-md-4 form-label']) !!}
                            <div class="col-md-12">
                                <select id="acc" class="form-control form-control-sm col-md-12" name="acc">
                                    <option value="0">Pilih</option>
                                    <option value="1">Ya</option>
                                    <option value="2">Tidak</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('', 'Alasan', ['class' => 'col-md-4 form-label']) !!}
                            <div class="col-md-12">
                                <input type="text" name="Deskripsi" min="1" value="{{ old('Deskripsi') }}"
                                    required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')"
                                    oninput="setCustomValidity('')" class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('', 'SK Date', ['class' => 'col-md-4 form-label']) !!}
                            <div class="col-md-12">
                                <?php
                                $date = strtotime(old('SK_Date'));
                                $SK_Date = date('Y-m-d', $date);
                                ?>
                                <input type="date" name="SK_Date" value="{{ old('$SK_Date') }}"
                                    class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('', 'SK Number', ['class' => 'col-md-4 form-label']) !!}
                            <div class="col-md-12">
                                <input type="text" name="Sk_Number" min="1" value="{{ old('Sk_Number') }}"
                                    required oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')"
                                    oninput="setCustomValidity('')" class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('', 'File', ['class' => 'col-md-4 form-label']) !!}
                            <div class="col-md-12">
                                <input type="file" name="file" id="" class="form-control"
                                    accept=".jpg,.jpeg,.pdf,.png"><br>
                            </div>
                        </div>

                        <div id="gridFac" style="width:100%;" class="text-success"></div>
                    </form>
                    <br>
                    <center><button type="" onClick="submitform()" class="btn btn-primary btn-flat">Tambah</button>
                    </center>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            var select = new SlimSelect({
                select: '#select'
            })
            select.selected()
        </script>



        <!-- /.row -->

    </section>
    <script>
        $(document).ready(function() {
            
        });

        function changestudent(student) {
                var grid = $('#gridFac').kendoGrid({
                    dataSource: {
                        transport: {
                            read: function(options) {
                                $.ajax({
                                    dataType: 'json',
                                    url: "{{ route('api.get.master_berkas_siswa_cuti') }}",
                                    type: 'GET',
                                    data: {
                                        Student_Id: student.value,
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
                                options.data.Student_Id = student.value,
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
                            template: "#= File_Upload ? File_Upload : ''#",
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
                            field: "Created_By",
                            title: "Petugas",
                            width: "25$",
                            template: "#= Created_By ? Created_By : ''#",
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

                $.ajax({
                    dataType: 'json',
                    url: "{{ route('api.get.cek_allowed_vacation') }}",
                    type: 'GET',
                    data: {
                        Student_Id: student.value,
                        Term_Year_Id: <?php echo $request->term_year; ?>,
                    },
                    success: function(res) {
                        if(res.extra == true){
                            $('#info_extra').text(res.data);
                            <!-- $("#gridFac").css({ visibility: 'hidden' }) -->
                        }else{
                            $('#info_extra').text('');
                            <!-- $("#gridFac").css({ visibility: 'visible' }) -->
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        console.log(xhr)
                        swal({
                                title: 'Error',
                                text: xhr.responseJSON.data,
                                type: "error",
                                confirmButtonColor: "#02991a",
                                confirmButtonText: "Oke",
                                cancelButtonText: "Tidak, Batalkan!",
                                closeOnConfirm: false,
                            },
                            function(isConfirm) {
                                if (isConfirm) {
                                    window.location.reload(true) 
                                }
                            });
                    }

                })

            }

        function submitform() {
          $('#myForm').submit();
          $(".k-grid-save-changes").click()
        }
    </script>
@endsection
