@extends('layouts._layout')
@section('pageTitle', 'Event Sched')
@section('content')
    <section class="content">

        <div class="container-fluid-title">
            <div class="title-laporan">
                <h3 class="text-white">Tambah Jadwal Pengisian</h3>
            </div>
        </div>
        <div class="container">
            <div class="panel panel-default bootstrap-admin-no-table-panel">
                <div class="panel-heading-green">
                    <div class="pull-right tombol-gandeng dua">
                        <a href="{{ url('setting/event_sched?event_id=' . $event_id . '&page=' . $page . '&rowpage=' . $rowpage . '&search=' . $search) }}"
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
                            @if ($error == 'Berhasil Menambah Jadwal Pengisian')
                                <p class="alert alert-success">{{ $error }}</p>
                            @else
                                <p class="alert alert-danger">{{ $error }}</p>
                            @endif
                        @endforeach
                    @endif

                    {!! Form::open([
                        'url' => route('event_sched.store'),
                        'method' => 'POST',
                        'class' => 'form-horizontal',
                        'role' => 'form',
                    ]) !!}

                    <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
                        <div class="row col-md-7">
                            <input type="hidden" name="Event_Id" min="1" value="{{ $event_id }}" required
                                oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')"
                                oninput="setCustomValidity('')" class="form-control form-control-sm col-md-8">
                        </div>
                    </div>
                    <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
                        <div class="row col-md-7">
                            <label class="col-md-4">Jenis Pengisian :</label>
                            @foreach ($event as $event)
                                <input type="text" value="{{ $event->Event_Name }}" disabled
                                    class="form-control form-control-sm col-md-8">
                            @endforeach
                        </div>
                    </div>
                    <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
                        <div class="row col-md-7">
                            <label class="col-md-4">Semua Prodi</label>
                            <input type="checkbox" id="all_prodi" name="all_prodi" value="true">
                        </div>
                    </div>


                    <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
                        <div class="row col-md-7">
                            <label class="col-md-4">Nama Program Studi :</label>
                            <select class="form-control form-control-sm col-md-8" name="Department_Id[]" id="select2"
                                multiple>
                                <!-- <option value="">Pilih Prodi</option> -->
                                @foreach ($select_department as $data)
                                    <option value="{{ $data->Department_Id }}" <?php if (old('Department_Id') == $data->Department_Id) {
                                        echo 'selected';
                                    } ?>>
                                        {{ $data->Department_Name }}</option>
                                @endforeach
                            </select>
                            <script type="text/javascript">
                                var select = new SlimSelect({
                                    select: '#select2'
                                })
                                select.selected()
                            </script>
                        </div>
                    </div>
                    <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
                        <div class="row col-md-7">
                            <label class="col-md-4">Program Kelas :</label>
                            <select class="form-control form-control-sm col-md-8" name="Class_Prog_Id" id="Class_Prog_Id">
                                <!-- <option value="">Pilih Prodi</option> -->
                                @foreach ($select_class_prog as $data)
                                    <option value="{{ $data->Class_Prog_Id }}" <?php if (old('Class_Prog_Id') == $data->Class_Prog_Id) {
                                        echo 'selected';
                                    } ?>>
                                        {{ $data->Class_Program_Name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
                        <div class="row col-md-7">
                            <label class="col-md-4">Semester Berlaku :</label>
                            <select class="form-control form-control-sm col-md-8" name="Term_Year_Id">
                                <option value="">Semester</option>
                                @foreach ($select_term_year as $data)
                                    <option value="{{ $data->Term_Year_Id }}" <?php if (old('Term_Year_Id') == $data->Term_Year_Id) {
                                        echo 'selected';
                                    } ?>>
                                        {{ $data->Term_Year_Name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
                        <div class="row col-md-7">
                            <label class="col-md-4">Buka?</label>
                            <select class="form-control form-control-sm col-md-8" name="Is_Open">
                                <option value="1" <?php if (old('Is_Open') == 1) {
                                    echo 'selected';
                                } ?>>YA</option>
                                <option value="0" <?php if (old('Is_Open') == 0) {
                                    echo 'selected';
                                } ?>>TIDAK</option>
                            </select>
                        </div>
                    </div>

                    @if ($event_id == 1 || $event_id == 3 || $event_id == 4 || $event_id == 5 || $event_id == 6)
                        <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
                            <div class="row col-md-7">
                                <label class="col-md-4">Tanggal Mulai :</label>
                                <!-- <input type="date" name="Start_Date"   class="form-control form-control-sm col-md-8" value="{{ old('Start_Date') }}"> -->
                                <div class="input-group date form-control form-control-sm col-md-8" id="datetimepicker1"
                                    data-target-input="nearest">
                                    <input type="text" name="Start_Date"
                                        class="form-control form-control-sm col-md-8 datetimepicker-input"
                                        placeholder="yyyy-mm-dd hh:ss" required
                                        oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')"
                                        oninput="setCustomValidity('')" data-target="#datetimepicker1" />
                                    <span class="input-group-addon" data-target="#datetimepicker1"
                                        data-toggle="datetimepicker">
                                        <span class="fa fa-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
                            <div class="row col-md-7">
                                <label class="col-md-4">Tanggal Akhir :</label>
                                <!-- <input type="date" name="End_Date"   class="form-control form-control-sm col-md-8"  value="{{ old('End_Date') }}"> -->
                                <div class="input-group date form-control form-control-sm col-md-8" id="datetimepicker2"
                                    data-target-input="nearest">
                                    <input type="text" name="End_Date"
                                        class="form-control form-control-sm col-md-8 datetimepicker-input"
                                        placeholder="yyyy-mm-dd hh:ss" required
                                        oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')"
                                        oninput="setCustomValidity('')" data-target="#datetimepicker2" />
                                    <span class="input-group-addon" data-target="#datetimepicker2"
                                        data-toggle="datetimepicker">
                                        <span class="fa fa-calendar"></span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        @if ($event_id == 1)
                            <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
                                <div class="row col-md-7">
                                    <label class="col-md-4">Tanggal Akhir Pembayaran:</label>
                                    <!-- <input type="date" name="End_Date_Cost"   class="form-control form-control-sm col-md-8"  value="{{ old('End_Date_Cost') }}"> -->
                                    <div class="input-group date form-control form-control-sm col-md-8"
                                        id="datetimepicker3" data-target-input="nearest">
                                        <input type="text" name="End_Date_Cost"
                                            class="form-control form-control-sm col-md-8 datetimepicker-input"
                                            placeholder="yyyy-mm-dd hh:ss" required
                                            oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')"
                                            oninput="setCustomValidity('')" data-target="#datetimepicker3" />
                                        <span class="input-group-addon" data-target="#datetimepicker3"
                                            data-toggle="datetimepicker">
                                            <span class="fa fa-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif

                    @if ($event_id == 0)
                        <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
                            <div class="row col-md-7">
                                <label class="col-md-4">Jumlah Hari:</label>
                                <!-- <input type="date" name="End_Date_Cost"   class="form-control form-control-sm col-md-8"  value="{{ old('End_Date_Cost') }}"> -->
                                <div class="input-group date form-control form-control-sm col-md-8" id="datetimepicker3"
                                    data-target-input="nearest">
                                    <input type="text" name="Days" class="form-control form-control-sm col-md-8 "
                                        placeholder="7" required
                                        oninvalid="this.setCustomValidity('Data Tidak Boleh Kosong')"
                                        oninput="setCustomValidity('')" />
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <br>
                    <center><button type="submit" class="btn btn-primary btn-flat">Tambah</button></center>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment-with-locales.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>
        <script src="https://rawgit.com/tempusdominus/bootstrap-4/master/build/js/tempusdominus-bootstrap-4.js"></script>
        <link href="https://rawgit.com/tempusdominus/bootstrap-4/master/build/css/tempusdominus-bootstrap-4.css"
            rel="stylesheet" />

        <script type="text/javascript">
            $(function() {
                $('#datetimepicker1').datetimepicker({
                    format: 'YYYY-MM-DD HH:mm',
                });
                $('#datetimepicker2').datetimepicker({
                    format: 'YYYY-MM-DD HH:mm',
                });
                $('#datetimepicker3').datetimepicker({
                    format: 'YYYY-MM-DD HH:mm',
                });
            });
        </script>



        <!-- /.row -->

    </section>
    <!-- <script type="text/javascript">
        function handleChange(checkbox) {
            if (checkbox.checked == true) {
                list = document.getElementsByClassName("fakultas");
                for (index = 0; index < list.length; ++index) {
                    list[index].setAttribute("disabled", "disabled");
                    list[index].checked = false;
                }
            } else {
                list = document.getElementsByClassName("fakultas");
                for (index = 0; index < list.length; ++index) {
                    list[index].removeAttribute("disabled");
                }
            }
        }

        function Change(checkbox) {
            var id = $(checkbox).val();
            if (checkbox.checked == true) {
                list = document.getElementsByClassName("prodi" + id);
                for (index = 0; index < list.length; ++index) {
                    // list[index].setAttribute("disabled","disabled");
                    list[index].checked = true;
                    // list[index].setAttribute("checked","checked");
                }
            } else {
                list = document.getElementsByClassName("prodi" + id);
                for (index = 0; index < list.length; ++index) {
                    // list[index].removeAttribute("disabled");
                    list[index].checked = false;
                    // list[index].removeAttribute("checked");
                }
            }
        }

        function ubah(id) {
            if ($('.prodi' + id + ':checked').length == $('.prodi' + id + '').length) {
                list = document.getElementsByClassName("fac" + id);
                for (index = 0; index < list.length; ++index) {
                    list[index].checked = true;
                }
            } else {
                list = document.getElementsByClassName("fac" + id);
                for (index = 0; index < list.length; ++index) {
                    list[index].checked = false;
                }
            }
        }
        $("form").submit(function() {
            $("input").removeAttr("disabled");
        });
    </script> -->
@endsection
