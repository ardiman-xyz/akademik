@extends('layouts._layout')
@section('pageTitle', 'Yudisium')
@section('content')

  <?php
  $access = auth()->user()->akses();
            $acc = $access;
  ?>

<section class="content">
  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">YUDISIUM</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          <div class="pull-right tombol-gandeng dua">
            <?php $page = ""; if(isset($_GET['page'])) { $page = $_GET['page']; }; ?>
            <?php
            foreach ($select_department as $data) {
            if ($department == $data->Department_Id) {
              ?>
                @if(in_array('create_yudisium-CanAdd', $acc))<a href="{{ url('proses/yudisium/create/?department='.$department.'&term_year='.$term_year) }}" class="btn btn-success btn-sm">Tambah Data &nbsp;<i class="fa fa-plus"></i></a>@endif
                @if(in_array('create_yudisium-CanAdd', $acc))<a href="{{ route('importyudisium') }}?department={{$department}}&term_year={{$term_year}}" class="btn btn-info btn-sm">Import &nbsp;<i class="fa fa-plus"></i></a>@endif
              <?php } }
              ?>
                <a href="{{ url('proses/yudisium/export/exportyudisium?department='.$department.'&term_year='.$term_year) }}" class="btn btn-warning btn-sm">Export &nbsp;<i class="fa fa-print"></i></a>
          </div>
          <b>Yudisium</b>
        </div>
      </div>
      <br>
      @if (count($errors) > 0)
          @foreach ( $errors->all() as $error )
            <p class="alert alert-danger">{{ $error }}</p>
          @endforeach
        @endif
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        {!! Form::open(['url' => route('yudisium.index') , 'method' => 'GET', 'name' => 'form', 'role' => 'form', 'style' => 'padding-top:5px;padding-left:15px;padding-right:15px;']) !!}
          <div class="row text-green">
            <!-- <label class="col-md-2" >Department :</label> -->
            <div  class="row col-md-7">
            <label class="col-md-3">Prodi :</label>
            <select class="form-control form-control-sm col-md-9" name="department" id="department"  onchange="document.form.submit();">
              <option value="0">Pilih Program Studi</option>
              @foreach ( $select_department as $data )
                <option <?php if($department == $data->Department_Id){ echo "selected"; } ?> value="{{ $data->Department_Id }}">{{ $data->Department_Name }}</option>
              @endforeach
            </select>
          </div>
          <div  class="row col-md-5">
            <label value="0" class="col-md-5">Tahun/Semester :</label>
            <select class="form-control form-control-sm col-md-7" name="term_year" id="term_year"  onchange="document.form.submit();">
                <option value="0" selected>Semua</option>
                @foreach ( $select_term_year as $data )
                <option <?php if($term_year == $data->Term_Year_Id){ echo "selected"; } ?> value="{{ $data->Term_Year_Id }}">{{ $data->Term_Year_Name }}</option>
              @endforeach
            </select>
          </div>
          </div>
          <br>
        {!! Form::close() !!}
        <div class="table-responsive">
          <div id="gridFac" style="width:100%;" class="text-success"></div>
          <div id="hapusEptDialog"></div>
          <script type="text/x-kendo-template" id="templateHapusEptDialog">
              Data Sudah Terisi, Anda yakin ingin menghapus data Yudisium Siswa (<strong>#= Nim #</strong>) <strong>#= Full_Name #</strong>?
          </script>
        </div>
      </div>
    </div>
  </div>
  <br>
</section>

<script>
var hapusEptDialog,
    templateHapusEptDialog,
    templateHapusMasterDialog;
$(document).ready(function() {
  templateHapusEptDialog = kendo.template($("#templateHapusEptDialog").html());
  function onChangeSearchParameter() {
    var grid = $("#gridFac").data("kendoGrid");
    grid.dataSource.read();
  }

//Berkas per siswa
  var grid = $('#gridFac').kendoGrid({ 
    dataSource: {
        transport: {
          read: function(options) {
              $.ajax({
                  dataType: 'json',
                  url: "{{ route('api.get.getstudentyudisium') }}",
                  // url: "{{ url('') }}/api/master/get/all_building",
                  data:{
                    Department_Id : $('#department').val(),
                    Term_Year_Id : $('#term_year').val()
                  },
                  type: 'GET',
                  success: function(res) {
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
            options.data.models[0].Graduate_Date= kendo.toString(options.data.models[0].Graduate_Date, "yyyy-MM-dd")
            options.data.models[0].Transcript_Date= kendo.toString(options.data.models[0].Transcript_Date, "yyyy-MM-dd")
            options.data.Term_Year_Id= $('#term_year').val();
            $.ajax({
                dataType: 'json',
                url: "{{ route('api.post.postdatasiswayudisiumcontroller') }}",
                type: 'post',
                data: {
                  data:options.data,
                },
                success: function(res) {
                if (res.success == false) {
                    swal('Sorry',res.data,'warning');
                    var grids = $('#gridFac').data("kendoGrid");
                      grids.dataSource.read();
                }else{
                  if(res.warning == true){
                    swal('Sukses, Tapi',res.data,'warning');
                    options.success(res);
                  }else{
                    swal('Ok',res.data,'success');
                    options.success(res);
                  }
                  var grids = $('#gridFac').data("kendoGrid");
                      grids.dataSource.read();
                }
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
                                // window.location.reload(true) // submitting the form when user press yes
                                    var grids = $('#gridFac').data("kendoGrid");
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
              id: "Yudisium_Id",
              fields: {
                Yudisium_Id: { editable: false },
                Nim: { editable: false },
                Full_Name: { editable: false },
                Graduate_Date: { editable: true,type:'date' },
                Transcript_Date: { editable: true,type:'date' },
                National_Certificate_Number: { editable: true },
                Transcript_Num: { editable: true },
                Skpi_Number: { editable: true },
                Graduate_Predicate_Id: { editable: true},
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
    toolbar: ["save", "cancel",{ template: "<input type='text' id='txtSearchString' class='k-textbox' placeholder='Cari sesuatu ...'>" }],
    detailInit: detailInit,
    columns: [
            {
                field: "Nim",
                title: "Nim",
                width:"10%",
                template: "#= Nim ? Nim : ''#" ,
            },
            {
                field: "Full_Name",
                title: "Nama Mahasiswa",
                width:"20%",
                template: "#= Full_Name ? Full_Name : ''#" ,
            },
            {
                field: "Graduate_Date",
                title: "Graduate_Date",
                width:"9%",
                template: "#= Graduate_Date ? (kendo.toString(kendo.parseDate(Graduate_Date), 'yyyy-MM-dd')):''#"
            },
            {
                field: "Transcript_Date",
                title: "Transcript_Date",
                width:"9%",
                template: "#= Transcript_Date ? (kendo.toString(kendo.parseDate(Transcript_Date), 'yyyy-MM-dd')):''#"
            },
            {
                field: "National_Certificate_Number",
                title: "National_Certificate_Number",
                width:"14%",
                template: "#= National_Certificate_Number ? National_Certificate_Number : ''#" ,
            },
            {
                field: "Transcript_Num",
                title: "Transcript_Num",
                width:"14%",
                template: "#= Transcript_Num ? Transcript_Num : ''#" ,
            },
            {
                field: "Skpi_Number",
                title: "Skpi_Number",
                width:"14%",
                template: "#= Skpi_Number ? Skpi_Number : ''#" ,
            },
            {
                field: "Graduate_Predicate_Id",
                title: "Predicate",
                width:"14%",
                template: "#= Graduate_Predicate_Id != null ? Predicate_Name : ''#" ,
                editor:GetRelationships
            },
            { command: [
                {
                    name: "customDeleteUser",
                    iconClass: "k-icon k-i-close",
                    text: "Hapus",
                    click: hapusData
                },
              ], 
              title: "&nbsp;", 
              width:"10%",
            }
        ],
  })//end kendo grid
  function GetRelationships(container, options) {
        $('<input name="' + options.field + '"/>')
            .appendTo(container)
            .kendoDropDownList({
                dataTextField: "Predicate_Name",
                dataValueField: "Graduate_Predicate_Id",
                value: options.Graduate_Predicate_Id, // THIS IS THE CHANGE I MADE
                dataSource: {
                    transport: {
                        read: {
                            dataType: "json",
                            url: "{{route('api.get.GetPredicate')}}",
                        }
                    }
                },
                change: function (e) {
                    var dataItem = e.sender.dataItem()
                    options.model.set("Graduate_Predicate_Id", this.value());
                    options.model.set("Predicate_Name", this.text());
                }
            });
    }
  $("#txtSearchString").keyup(function () {
      var searchValue = $('#txtSearchString').val();
      var grid = $("#gridFac").data("kendoGrid");
      grid.dataSource.filter({
          logic: "or",
          filters: [
              {
                  field: "Nim",
                  operator: "contains",
                  value: searchValue
              },
              {
                  field: "Full_Name",
                  operator: "contains",
                  value: searchValue
              }
          ]
      });
  });
});

//detail init
  function detailInit(e) {
    // console.log(e.data.Student_Id);
    var detail_init = $("<div class='text-success'/>").appendTo(e.detailCell).kendoGrid({
      dataSource: {
        transport: {
            read: function(options) {
                $.ajax({
                    dataType: 'json',
                    url: "{{ route('api.get.master_berkas_siswa_yudisium') }}",
                    type: 'GET',
                    data: {
                      Department_Id : e.data.Department_Id,
                      Student_Id : e.data.Student_Id,
                    },
                    success: function(res) {
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
            options.data.Department_Id = e.data.Department_Id
            options.data.Student_Id = e.data.Student_Id
              $.ajax({
                  dataType: 'json',
                  url: "{{ route('api.post.postberkassiswayudisiumcontroller') }}",
                  type: 'post',
                  data: {
                    data:options.data,
                  },
                  success: function(res) {
                    console.log(res);
                  if (res.success == false) {
                      swal('Sorry',res.data,'warning');
                  }else{
                      swal('Ok',res.data,'success');
                      options.success(res);
                      var grids = detail_init.data("kendoGrid");
                          grids.dataSource.read();
                  }
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
                                  // window.location.reload(true) // submitting the form when user press yes
                                      var grids = detail_init.data("kendoGrid");
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
              id: "Yudisium_Student_Document_Id",
              fields: {
                Yudisium_Student_Document_Id: { editable: false },
                Yudisium_Prerequisite_Id: { editable: false },
                No: { editable: false },
                Yudisium_Document_Id: { editable: false },
                Yudisium_Document_Name: { editable: false },
                Copies: { editable: true },
                File_Upload: { editable: false },
                Is_Accepted: {
                      type: "boolean"
                  },
                Notes: { editable: true },
                Created_By: { editable: false },
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
    columns: [
            {
                field: "No",
                title: "No",
                width:"5%",
                template: "#= No ? No : ''#" ,
            },
            {
                field: "Yudisium_Document_Id",
                title: "Nama Dokumen",
                width:"45%",
                template: "#= Yudisium_Document_Name ? Yudisium_Document_Name : ''#" ,
            },
            {
                field: "Copies",
                title: "Jumlah",
                width:"5%",
                template: "#= Copies ? Copies : ''#" ,
            },
            {
                field: "File_Upload",
                title: "File",
                width:"5%",
                template: "#= File_Upload ? File_Upload : ''#" ,
            },
            {
                field: "Is_Accepted",
                title: "L/TL",
                width:"5%",
                template: "# if( Is_Accepted == 1) {#<span>L<span># } else {#<span style='color:red'>TL<span>#} #" ,
                // template: "#= Is_Accepted ? Is_Accepted : ''#" ,
            },
            {
                field: "Notes",
                title: "Notes",
                width:"25$",
                template: "#= Notes ? Notes : ''#" ,
            },
            {
                field: "Created_By",
                title: "Petugas",
                width:"25$",
                template: "#= Created_By ? Created_By : ''#" ,
            }
        ],
    });
    
    var checkedIds = {};
    function onDataBound(e) {
      var view = this.dataSource.view();
      for(var i = 0; i < view.length;i++){
        // console.log(this.tbody.find("tr[data-uid='" + view[i].uid + "']").find(".k-input k-textbox"));
          if(checkedIds[view[i].id]){
              this.tbody.find("tr[data-uid='" + view[i].uid + "']")
              .addClass("k-state-selected")
              .find(".k-input k-textbox")
              .attr("checked","checked");
          }
      }
    }
  }
  
  function hapusData(q) {
    q.preventDefault();

    var tr = $(q.target).closest("tr"),
      data = this.dataItem(tr);

    hapusEptDialog = $("#hapusEptDialog").kendoDialog({
      width: "350px",
      title: "Hapus Data",
      visible: false,
      buttonLayout: "stretched",
      actions: [
        {
          text: "Hapus",
          primary: true,
          action: function (e) {
            $.ajax({
              headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                },
              url: "{{ route('api.delete.deletesiswayudisiumcontroller') }}",
              type: "delete",
              data: {
                data:data.Yudisium_Id,
              },
              dataType: "json",
              success: function (res) {
                if(res.success == true){
                    var grids = $('#gridFac').data("kendoGrid");
                        grids.dataSource.read();
                }else{
                    swal({
                        //title: thrownError,
                        title: "Mohon Maaf",
                        text: res.message,
                        type: "error",
                        confirmButtonColor: "#b20000",
                        confirmButtonText: "Batal",
                        // cancelButtonText: "Tidak, Batalkan!",
                        // closeOnConfirm: false,
                        // showCancelButton: true,
                        // cancelButtonText: 'cancel!',
                    },
                    function(isConfirm) {
                        if (isConfirm) {
                            var grids = $('#gridFac').data("kendoGrid");
                            grids.dataSource.read();
                        }
                    }); 
                }
              }
            });
          }
        },
        {text: "Batal"}
      ]
    }).data("kendoDialog");

    hapusEptDialog.content(templateHapusEptDialog(data));
    hapusEptDialog.open();
  }
</script>
@endsection
