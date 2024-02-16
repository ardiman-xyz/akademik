@extends('layouts._layout')
@section('pageTitle', 'KHS')
@section('content')

  <?php
  $access = auth()->user()->akses();
            $acc = $access;

function tanggal_indo($tanggal, $cetak_hari = false)
{
	$hari = array ( 1 =>    'Senin',
				'Selasa',
				'Rabu',
				'Kamis',
				'Jumat',
				'Sabtu',
				'Minggu'
			);

	$bulan = array (1 =>   'Januari',
				'Februari',
				'Maret',
				'April',
				'Mei',
				'Juni',
				'Juli',
				'Agustus',
				'September',
				'Oktober',
				'November',
				'Desember'
			);
	$split 	  = explode('-', $tanggal);
	$tgl_indo = $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];

	if ($cetak_hari) {
		$num = date('N', strtotime($tanggal));
		return $hari[$num] . ', ' . $tgl_indo;
	}
	return $tgl_indo;
}
  ?>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">KHS Per Matakuliah</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="pull-right tombol-gandeng dua">
        @if($from == 'khs')
          <a href="{{ url('proses/khs_matakuliah/?term_year='.$term_year.'&class_program='.$class_program.'&department='.$department.'&term_year='.$term_year.'&page='.$currentpage.'&rowpage='.$currentrowpage.'&search='.$currentsearch) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
        @else
          <a href="{{ url('laporan/detail_pengisian_nilai/?term_year='.$term_year.'&class_program='.$class_program.'&department='.$department.'&term_year='.$term_year.'&page='.$currentpage.'&rowpage='.$currentrowpage.'&search='.$currentsearch) }}" class="btn btn-danger btn-sm">Kembali &nbsp;<i class="fa fa-reply"></i></a>
        @endif
        </div>
        <div class="bootstrap-admin-box-title right text-white">
          <b>Detail</b>
        </div>
      </div>
      <br>
          {!! Form::open(['url' => route('khs_matakuliah.show',$Offered_Course_id) , 'method' => 'GET', 'name' => 'form', 'role' => 'form']) !!}
          <!-- <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            <label class="col-md-2">Cari NIM/Mahasiswa :</label>
            <input type="text" name="search"  class="form-control form-control-sm col-md-4" value="{{ $search }}" placeholder="NIM">&nbsp
            <label class="col-md-2" style="text-align:right;">Baris Per halaman :</label>
            <input type="number" name="rowpage"  class="form-control form-control-sm col-md-3" value="{{ $rowpage }}" placeholder="Baris Per halaman">&nbsp
            <input type="submit" name="" style="float:right;" class="btn btn-primary btn-sm" value="Submit">
          </div><br> -->
          {!! Form::close() !!}
        </div>

      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
      <input type="text" value="{{$term_year}}" id="term_year" hidden>
      <input type="text" value="{{$class_program}}" id="class_program" hidden>
      <input type="text" value="{{$department}}" id="department" hidden>
      <input type="text" value="{{$Offered_Course_id}}" id="Offered_Course_id" hidden>
      <input type="text" value="{{$data->Class_Id}}" id="Class_Id" hidden>
      <input type="text" value="{{$data->Course_Id}}" id="Course_Id" hidden>
      <input type="text" value="{{$cekfortranscript->Is_For_Transcript}}" id="Is_For_Transcript" hidden>
      <input type="text" value="{{$cekfortranscript->Transcript_Sks}}" id="Transcript_Sks" hidden>
        <br>
        <div class="row" style="padding-top:5px;padding-left:15px;padding-right:15px;">
          <div class="col-sm-6">
            <div >
              <label class="col-sm-5">Th Akademik/Semester</label>:
              <label class="col-sm-5"> {{ $data->Term_Year_Name }}</label>
            </div>
            <div>
              <label class="col-sm-5">Program Studi</label>:
              <label class="col-sm-5"> {{ $data->Department_Name }}</label>
            </div>
            <div>
              <label class="col-sm-5">Kelas Program</label>:
              <label class="col-sm-5"> {{ $data->Class_Program_Name }}</label>
            </div>
            <div>
              <label class="col-sm-5">Matakuliah</label>:
              <label class="col-sm-5"> {{ $data->Course_Name }}</label>
            </div>
            
              <?php
              if ($jadwalpengisian['Start_Date'] != null || $jadwalpengisian['End_Date'] != null || $jadwalpengisian['Start_Dateuas'] != null || $jadwalpengisian['End_Dateuas'] != null) {
                $start1 = strtotime($jadwalpengisian['Start_Date']);
                if($start1 == false){
                  $startuas = '';
                  $enduas = '';
                  $intervaluas = '';
                }else{
                  $da1 = Date('Y-m-d',$start1);
                  $start = tanggal_indo($da1,false);
                  $end = strtotime($jadwalpengisian['End_Date']);
                  $da2 = Date('Y-m-d',$end);
                  $end = tanggal_indo($da2,false);
                  $interval = $jadwalpengisian['Interval'];
                  $hariakhir = date('d', strtotime($da1));
                  $hariini = date('d', strtotime($now));
                }

                $start1uas = strtotime($jadwalpengisian['Start_Dateuas']);
                if($start1uas == false){
                  $startuas = '';
                  $enduas = '';
                  $intervaluas = '';
                  $da2uas = 0;
                  $hariakhiruas = 0;
                }else{
                  $da1uas = Date('Y-m-d',$start1uas);
                  $startuas = tanggal_indo($da1uas,false);
                  $enduas = strtotime($jadwalpengisian['End_Dateuas']);
                  $da2uas = Date('Y-m-d',$enduas);
                  $enduas = tanggal_indo($da2uas,false);
                  $intervaluas = $jadwalpengisian['Intervaluas'];
                  $hariakhiruas = date('d', strtotime($da1uas));
                  $hariini = date('d', strtotime($now));
                }

                $now = $jadwalpengisian['Now'];
                
              }
              else {
                $start = "";
                $end = "";
                $hariakhir = 0;
                $hariini = 0;
                $hariakhiruas = 0;
                $startuas = 0;
                $enduas = 0;
                $da2 = 0;
                // dd([['start',$start],['last',$end],['interval',$jadwalpengisian['Interval']]]);
              }
            ?>
            <div>
              <label class="col-sm-5">Jadwal Pengisian Nilai UTS</label>:
              <!-- @if ($jadwalpengisian['Start_Date'] != null || $jadwalpengisian['End_Date'] != null) -->
                <?php if($now >= $da2 && $now <= $da2){ ?>
                <label class="col-sm-6" style="background-color:#ffff00;">{{$start}} - {{$end}}</label>
                <?php }elseif ($now > $da2) { ?>
                <label class="col-sm-6" style="background-color:#f55142;">{{$start}} - {{$end}}</label>
                <?php }else{ ?>
                <label class="col-sm-6" style="background-color:#4cb24f;">{{$start}} - {{$end}}</label>
                <?php } ?>
              <!-- @endif -->
            </div>
            <div>
              <label class="col-sm-5">Jadwal Pengisian Nilai UAS</label>:
              <?php if((($hariakhiruas - $hariini) <= 2 && ($hariakhiruas - $hariini) >= 0)){ ?>
              <label class="col-sm-6" style="background-color:#ffff00;">{{$startuas}} - {{$enduas}}</label>
              <?php }elseif ($now > $da2uas) { ?>
              <label class="col-sm-6" style="background-color:#f55142;">{{$startuas}} - {{$enduas}}</label>
              <?php }else{ ?>
              <label class="col-sm-6" style="background-color:#4cb24f;">{{$startuas}} - {{$enduas}}</label>
              <?php } ?>
            </div>
            <div>
              <label class="col-sm-5"><a href="{{ url('proses/khs_matakuliah/bobot/'.$Offered_Course_id.'?term_year='.$term_year.'&class_program='.$class_program.'&department='.$department.'&term_year='.$term_year.'&page='.$currentpage.'&rowpage='.$currentrowpage.'&search='.$currentsearch.'&course_type='.$course_type) }}" class="col-sm-6 btn btn-primary btn-sm">Setting Bobot</i></a></label>
            </div>

            
      <input type="text" value="{{$da2uas}}" id="enduas" hidden>

            @if ($jadwalpengisian['Start_Date'] != null || $jadwalpengisian['End_Date'] != null)
              @if($now > $da2)
              <div>
                <label class="col-sm-6"><a href="#" class="col-sm-5 btn btn-primary btn-sm defaultnilaiuts">Default Nilai UTS</i></a></label>
              </div>
              @endif
            @endif
            @if($now <= $da2uas && $now >= $startuas)
            @else
              @if($da2uas != null)
                @if($now > $da2uas)
                  <div>
                    <label class="col-sm-6"><a href="#" class="col-sm-5 btn btn-primary btn-sm defaultnilaiuas">Default Nilai UAS</i></a></label>
                  </div>
                @endif
              @endif
             @endif
              <div>
                <label class="col-sm-6"><a href="#" class="col-sm-5 btn btn-primary btn-sm publishnilai">Publish Nilai</i></a></label>
              </div>

              <div>
                <label class="col-sm-6"><a href="{{ url('proses/khs_matakuliah/exportdata/'.$Offered_Course_id) }}" class="col-sm-5 btn btn-primary btn-sm">Cetak Nilai Uts/Uas</i></a></label>
              </div>

            @if($message == $mahasiswa)
            <div>
              <!-- <label class="col-sm-5"><a href="#" class="col-sm-6 btn btn-primary btn-sm publishnilai">Publish Nilai</i></a></label> -->
            </div>
            @endif
            @if($messagefinal == $mahasiswa)
            <div>
              <!-- <label class="col-sm-5"><a href="#" class="col-sm-6 btn btn-primary btn-sm">Publish Nilai Final</i></a></label> -->
            </div>
            @endif
          </div>
          <div class="col-sm-6">
            <div>
              <label class="col-sm-5">Kelas</label>:
              <label class="col-sm-5"> {{ $data->Class_Name }}</label>
            </div>
            <div>
              <label class="col-sm-5">Kapasitas</label>:
              <label class="col-sm-5"> {{ $data->Class_Capacity }}</label>
            </div>
            <div>
              <label class="col-sm-5">Terdaftar</label>:
              <label class="col-sm-5"> {{ $countdata }}</label>
            </div>
            <div>
              <label class="col-sm-5">Sisa</label>:
              <label class="col-sm-5"> {{ $data->Class_Capacity - $data->jml_peserta }}</label>
            </div>
          </div>
        </div>
        
        <br>
        <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            <div  class="row col-md-7">
            <label class="col-md-10" style="font-size:11px">* Jika ada Huruf/Ket yang masih NULL silakan klik Publish Nilai</label>
            <label class="col-md-3">Pencarian :</label>
            <input type="text" name="search" id="search" class="form-control form-control-sm col-md-7 col-sm-7" placeholder="Search">
            </div>
          </div>
      <div class="table-responsive" style="font-size:14px;">
                    <div id="grid">

                    </div>
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
<script type="text/javascript">
function fnExcelReport() {
    var tab_text = "<table border='2px'><tr><td colspan='6' align='center'>Daftar Matakuliah</td></tr><tr bgcolor='#87AFC6'>";
    var textRange; var j = 0;
    tab = document.getElementById('tbl'); // id of table

    for (j = 0 ; j < tab.rows.length ; j++) {
        tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
        //tab_text=tab_text+"</tr>";
    }

    tab_text = tab_text + "</table>";


    var ua = window.navigator.userAgent;
    var msie = ua.indexOf("MSIE ");

    if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
    {
        txtArea1.document.open("txt/html", "replace");
        txtArea1.document.write(tab_text);
        txtArea1.document.close();
        txtArea1.focus();
        sa = txtArea1.document.execCommand("SaveAs", true, "Global View Task.xls");
    }
    else //other browser not tested on IE 11
        sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));
    return (sa);
}

    $(document).ready(function() {
      var term_year = $('#term_year').val();
      var class_program = $('#class_program').val();
      var department = $('#department').val();
      var Offered_Course_id = $('#Offered_Course_id').val();
      var Class_Id = $('#Class_Id').val();
      var Course_Id = $('#Course_Id').val();
      var hariakhir = <?php 
                        if ($jadwalpengisian['Start_Date'] != null || $jadwalpengisian['End_Date'] != null){
                          echo $hariakhir; 
                        }else{
                          echo 0;
                        }
                        ?>;
      var hariini = <?php echo $hariini; ?>;
      $("#search").keyup(function() {
            var searchValue = $('#search').val();
            $("#grid").data("kendoGrid").dataSource.filter(
                {
                  logic: "or",
                  filters: [
                    {
                      field: "Nim",
                      operator: "Contains",
                      value: searchValue
                    },
                    {
                      field: "Full_Name",
                      operator: "Contains",
                      value: searchValue
                    }
                  ]                  
                }
            );
        });
        var grid = $('#grid').kendoGrid({ 
            dataSource: {
                transport: {
                    read: function(options) {
                        $.ajax({
                            dataType: 'json',
                            url: "{{url('')}}/proses/khs_matakuliah/getNilaiAkhir/" + Class_Id + "/" + class_program + "/" + Course_Id + "/" + term_year,
                            type: 'GET',
                            data: options.data,
                            success: function(res) {
                            if (res['res']==0) {
                                swal('Maaf','Waktu pengisian nilai tidak di buka silahkan hubungi admin','info');
                            }
                                options.success(res);
                            }, error: function(xhr, ajaxOptions, thrownError) {
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
                                            window.location.reload(true) // submitting the form when user press yes
                                            }
                                        });
                                }

                        })
                    }, //read
                  update: function(options) {
                        $.ajax({
                            dataType: 'json',
                            url: '{{route('khs_matakuliah.updateNilaiAkhir')}}',
                            type: 'get',
                            data: {
                              data: options.data,
                              offer: Offered_Course_id
                            },
                            success: function(res) {
                            if (res['status']==0) {
                                swal(res['message'],'Maaf','warning');
                            }
                                options.success(res);
                                $('#grid').data("kendoGrid").dataSource.read();
                            }, error: function(xhr, ajaxOptions, thrownError) {
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
                                            window.location.reload(true) // submitting the form when user press yes
                                            }
                                        });
                                }

                        })
                    }, //update
                }, //transport grid
                schema: {
                  data: 'data',
                  total: 'total',
                  model: {
                      id: "Nim",
                      fields: {
                        Photo: { editable: false },
                        Nim: { editable: false },
                        Full_Name: { editable: false },
                        Ujian_uts: { type: "number" },
                        Ujian_uas: { type: "number" },
                        Tugas_1: { type: "number" },
                        Tugas_2: { type: "number" },
                        Tugas_3: { type: "number" },
                        Tugas_4: { type: "number" },
                        Tugas_5: { type: "number" },
                        Presence: { type: "number"},
                        Presence_uas: { type: "number"},
                        Uts: { type: "number"},
                        Uas: { type: "number" },
                        UAS_Remidi: { type: "number", editable: false },
                        Total_score: { editable: false,type: "number" },
                        Grade_Letter: { editable: false },
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
            // editable: "inline",
            editable: true,
            // edit: OnEdit,
            edit: function (e) {
                if (!e.model.isNew()) {
                  var presence =   e.model.Presence; 
                  var presence_uas =   e.model.Presence_uas; 
                  var ujian_uts =   e.model.Ujian_uts; 
                  var ujian_uas =   e.model.Ujian_uas; 
                  var disableField2 = e.container.find("input[name=Uas]").data("kendoNumericTextBox");
                  var disableField = e.container.find("input[name=Uts]").data("kendoNumericTextBox");
                  // if(presence == 0) {
                  //       // disableField.enable(false);
                  //       swal({
                  //         title: 'Tidak terdaftar di ujian',
                  //         type: 'warning', 
                  //         showConfirmButton:false,
                  //         showCancelButton: true,
                  //         cancelButtonText: 'oke',
                  //       });
                  //     if(ujian_uts == 0){
                  //       swal({
                  //         title: 'Tidak Ikut di ujian',
                  //         type: 'warning', 
                  //         showConfirmButton:false,
                  //         showCancelButton: true,
                  //         cancelButtonText: 'oke',
                  //       });
                  //     }
                  // }

                  if(presence_uas == 0) {
                      disableField2.enable(false);
                      swal({
                        title: 'Tidak terdaftar di ujian uas',
                        type: 'warning', 
                        showConfirmButton:false,
                        showCancelButton: true,
                        cancelButtonText: 'oke',
                      });
                    // if(ujian_uts == 0){
                    //   swal({
                    //     title: 'Tidak Ikut di ujian uas',
                    //     type: 'warning', 
                    //     showConfirmButton:false,
                    //     showCancelButton: true,
                    //     cancelButtonText: 'oke',
                    //   });
                    // }
                  }
                  // if(ujian_uas == 0) {
                  //   var disableField1 = e.container.find("input[name=Uas]").data("kendoNumericTextBox");
                  //   disableField1.enable(false);
                  // }
                  // if(presence_uas == 0 || presence_uas == '' || presence_uas == null) {
                  //   var disableField3 = e.container.find("input[name=Uas]").data("kendoNumericTextBox");
                  //   disableField3.enable(false);
                  // }
                  // var disableField = e.container.find("input[name=Uts]").data("kendoNumericTextBox");
                  //   disableField.enable(false);
                  // $('[name="Presence"]').attr("readonly", true);
                }
            },
            toolbar: ["save", "cancel"],
            columns: [
                    {
                        field: 'Presence',
                        title: "Presence",
                        width:"75",
                        hidden:true
                    },
                    {
                        field: 'Ujian_uts',
                        title: "Ujian_uts",
                        width:"75",
                        hidden:true
                    },
                    {
                        field: 'Presence_uas',
                        title: "Presence uas",
                        width:"75",
                        hidden:true
                    },
                    {
                        field: 'Ujian_uas',
                        title: "Ujian_uas",
                        width:"75",
                        hidden:true
                    },
                    {
                        field: "Photo",
                        title: "Photo",
                        template: '<center><img width="30px" height="40px" src="https://simakad.umk.ac.id/#= Photo #" alt="image" />'
                    },
                    {
                        field: 'Nim',
                        title: "Nim",
                        width:"75"
                    },
                    {
                        field: "Full_Name",
                        title: "Nama"
                    },{
                        field: "Tugas_1",
                        title: "Tugas 1 ",
                        width:"75"
                    },{
                        field: "Tugas_2",
                        title: "Tugas 2 ",
                        width:"75"
                    },{
                        field: "Tugas_3",
                        title: "Tugas 3 ",
                        width:"75"
                    },{
                        field: "Tugas_4",
                        title: "Tugas 4 "
                    },{
                        field: "Tugas_5",
                        title: "Tugas 5 "
                    },{
                        field: "Uts",
                        title: "UTS "
                    },{
                        field: "Uas",
                        title: "UAS"
                    },{
                        field: "Uas_Remidi",
                        title: "Remidi UAS"
                    },{
                        field: "Total_score",
                        title: "Total Skor "
                    },{
                        field: "Grade_Letter",
                        title: "Huruf / Ket"
                    },
                    // {
                    //     headerTemplate: "<span class='k-icon k-i-gear'></span>",
                    //     headerAttributes: {
                    //         class: "table-header-cell",
                    //         style: "text-align: center"
                    //     },
                    //     attributes: {
                    //         class: "table-cell",
                    //         style: "text-align: center"
                    //     },
                    //     command: [{
                    //       name : "edit",
                    //     }],
                    //     width: "150px"
                    // }
                ],
        })
        function OnEdit(e) {
          if (!e.model.isNew()) { // Make sure it's not a new entry
            var presence =   e.container.find("input[name=Presence]").data("kendoNumericTextBox").value();             
            if(presence == 0 || presence == '') {
                  var disableField = e.container.find("input[name=Uts]").data("kendoNumericTextBox");
                  disableField.enable(false);
            }
          }
        }
    });


    $(document).on('click', '.publishnilai', function (e) {
      var term_year = $('#term_year').val();
      var class_program = $('#class_program').val();
      var department = $('#department').val();
      var Offered_Course_id = $('#Offered_Course_id').val();
      var Class_Id = $('#Class_Id').val();
      var Course_Id = $('#Course_Id').val();
      var Is_For_Transcript = $('#Is_For_Transcript').val();
      var Transcript_Sks = $('#Transcript_Sks').val();
      var enduas = $('#enduas').val();
			swal({
				title: 'Publish Nilai',
					text: "Nilai akan dimasukkan ke KHS Mahasiswa",
					type: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Oke',
					cancelButtonText: 'cancel!',
					confirmButtonClass: 'btn btn-success',
					cancelButtonClass: 'btn btn-danger',
					buttonsStyling: true
				}, function(isConfirm) {
			if (isConfirm) {
							$.ajax({
									url: '{{route('khs_matakuliah.publishNilai')}}',
									type: "get",
									dataType: "json",
									data: {
										"_token": "{{ csrf_token() }}",
                    term_year : term_year,
                    Course_Id : Course_Id,
                    Class_Id : Class_Id,
                    Offered_Course_id : Offered_Course_id,
                    department : department,
                    class_program : class_program,
                    Is_For_Transcript : Is_For_Transcript,
                    Transcript_Sks : Transcript_Sks,
                    enduas : enduas,
									},
									success:function (data) {
                    console.log(data);
                    swal({
                      title: data.message,
                      type: 'success', 
                      showCancelButton: true,
                      cancelButtonText: 'oke',
                    });
                    window.location.reload(true);
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
                                            window.location.reload(true) // submitting the form when user press yes
                                            }
                                        });
                                }
							});
							// $("#hapus").submit();
						}else{
              console.log('cenceled');
            }
					});
	});

    $(document).on('click', '.defaultnilaiuts', function (e) {
      var term_year = $('#term_year').val();
      var class_program = $('#class_program').val();
      var department = $('#department').val();
      var Offered_Course_id = $('#Offered_Course_id').val();
      var Class_Id = $('#Class_Id').val();
      var Course_Id = $('#Course_Id').val();
      var Is_For_Transcript = $('#Is_For_Transcript').val();
      var Transcript_Sks = $('#Transcript_Sks').val();
			swal({
				title: 'Data di set Default',
					text: "Nilai UTS Akan di Set ke Default",
					type: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Oke',
					cancelButtonText: 'cancel!',
					confirmButtonClass: 'btn btn-success',
					cancelButtonClass: 'btn btn-danger',
					buttonsStyling: true
				}, function(isConfirm) {
			if (isConfirm) {
							$.ajax({
									url: '{{route('khs_matakuliah.defaultnilaiuts')}}',
									type: "get",
									dataType: "json",
									data: {
										"_token": "{{ csrf_token() }}",
                    term_year : term_year,
                    Course_Id : Course_Id,
                    Class_Id : Class_Id,
                    Offered_Course_id : Offered_Course_id,
                    department : department,
                    class_program : class_program,
                    Is_For_Transcript : Is_For_Transcript,
                    Transcript_Sks : Transcript_Sks
									},
									success: function (data) {
										swal({
                      title: data.message,
                      type: 'success', showConfirmButton:false,
                      showCancelButton: true,
                      cancelButtonText: 'oke',
                    });
                    window.location.reload(true)
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
                                            window.location.reload(true) // submitting the form when user press yes
                                            }
                                        });
                                }
							});
							// $("#hapus").submit();
						}
					});
	});

    $(document).on('click', '.defaultnilaiuas', function (e) {
      var term_year = $('#term_year').val();
      var class_program = $('#class_program').val();
      var department = $('#department').val();
      var Offered_Course_id = $('#Offered_Course_id').val();
      var Class_Id = $('#Class_Id').val();
      var Course_Id = $('#Course_Id').val();
      var Is_For_Transcript = $('#Is_For_Transcript').val();
      var Transcript_Sks = $('#Transcript_Sks').val();
      var enduas = $('#enduas').val();
			swal({
				title: 'Data di set Default',
					text: "Nilai UAS Akan di Set ke Default",
					type: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Oke',
					cancelButtonText: 'cancel!',
					confirmButtonClass: 'btn btn-success',
					cancelButtonClass: 'btn btn-danger',
					buttonsStyling: true
				}, function(isConfirm) {
			if (isConfirm) {
							$.ajax({
									url: '{{route('khs_matakuliah.defaultnilaiuas')}}',
									type: "get",
									dataType: "json",
									data: {
										"_token": "{{ csrf_token() }}",
                    term_year : term_year,
                    Course_Id : Course_Id,
                    Class_Id : Class_Id,
                    Offered_Course_id : Offered_Course_id,
                    department : department,
                    class_program : class_program,
                    Is_For_Transcript : Is_For_Transcript,
                    Transcript_Sks : Transcript_Sks,
                    enduas : enduas,
									},
									success: function (data) {
                    if(data.success == false){
                      alert(data.message);
                      swal({
                          title: 'Warning',
                          text: 'Warning!! ' + data.message,
                          type: "warning",
                          confirmButtonColor: "#02991a",
                          confirmButtonText: "Ok",
                          cancelButtonText: "Tidak, Batalkan!",
                          closeOnConfirm: false,
                      });
                    }else{
                      // swal({
                      //   title: data.message,
                      //   type: 'success', showConfirmButton:false,
                      //   showCancelButton: true,
                      //   cancelButtonText: 'oke',
                      // },function(isConfirm) {
                      //       if (isConfirm) {
                            window.location.reload(true) // submitting the form when user press yes
                      //       }
                      //   });
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
                                            window.location.reload(true) // submitting the form when user press yes
                                            }
                                        });
                                }
							});
							// $("#hapus").submit();
						}
					});
	});

  //   $(document).on('click', '.publishnilai', function (e) {
  //     var term_year = $('#term_year').val();
  //     var class_program = $('#class_program').val();
  //     var department = $('#department').val();
  //     var Offered_Course_id = $('#Offered_Course_id').val();
  //     var Class_Id = $('#Class_Id').val();
  //     var Course_Id = $('#Course_Id').val();
  //     var Is_For_Transcript = $('#Is_For_Transcript').val();
  //     var Transcript_Sks = $('#Transcript_Sks').val();
	// 		swal({
	// 			title: 'Data Akan Masuk KHS',
	// 				text: "Data Akan Masuk Ke KHS Setelah Di Publish",
	// 				type: 'warning',
	// 				showCancelButton: true,
	// 				confirmButtonColor: '#3085d6',
	// 				cancelButtonColor: '#d33',
	// 				confirmButtonText: 'Publish',
	// 				cancelButtonText: 'cancel!',
	// 				confirmButtonClass: 'btn btn-success',
	// 				cancelButtonClass: 'btn btn-danger',
	// 				buttonsStyling: true
	// 			}, function(isConfirm) {
	// 		if (isConfirm) {
	// 						$.ajax({
	// 								url: '{{route('khs_matakuliah.publishNilai')}}',
	// 								type: "get",
	// 								dataType: "json",
	// 								data: {
	// 									"_token": "{{ csrf_token() }}",
  //                   term_year : term_year,
  //                   Course_Id : Course_Id,
  //                   Class_Id : Class_Id,
  //                   Offered_Course_id : Offered_Course_id,
  //                   department : department,
  //                   class_program : class_program,
  //                   Is_For_Transcript : Is_For_Transcript,
  //                   Transcript_Sks : Transcript_Sks
	// 								},
	// 								success: function (data) {
	// 									swal({
  //                     title: 'Data telah Masuk ke KHS',
  //                     type: 'success', showConfirmButton:false,
  //                     showCancelButton: true,
  //                     cancelButtonText: 'oke',
  //                   });
	// 								},
	// 								error: function(xhr, ajaxOptions, thrownError) {
  //                                   swal({
  //                                           title: thrownError,
  //                                           text: 'Error!! ' + xhr.status,
  //                                           type: "error",
  //                                           confirmButtonColor: "#02991a",
  //                                           confirmButtonText: "Refresh Serkarang",
  //                                           cancelButtonText: "Tidak, Batalkan!",
  //                                           closeOnConfirm: false,
  //                                       },
  //                                       function(isConfirm) {
  //                                           if (isConfirm) {
  //                                           window.location.reload(true) // submitting the form when user press yes
  //                                           }
  //                                       });
  //                               }
	// 						});
	// 						// $("#hapus").submit();
	// 					}
	// 				});
	// });
</script>
</section>
@endsection
