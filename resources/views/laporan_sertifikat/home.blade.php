@extends('layouts._layout')
@section('pageTitle', 'Pencapaian Mahasiswa')
@section('content')

<?php
$access = auth()->user()->akses();
  $acc = $access;
?>

<style>
  .k-grid tbody .k-button {
    min-width: 40px;
  }
</style>
<section class="content">

  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Pencapaian Mahasiswa</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          <b>Pencapaian Mahasiswa</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        @if (count($errors) > 0)
          @foreach ( $errors->all() as $error )
            <p class="alert alert-danger">{{ $error }}</p>
          @endforeach
        @endif
        <br>
        <div class="row">
            <div class="col-md-2">
                <label for="departmentSelect">Prodi</label>
            </div>
            <div class="col-md-3">
                <select id="departmentSelect" class="form-control">
                </select>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-2">
                <label for="entryyearSelect">Angkatan</label>
            </div>
            <div class="col-md-3">
                <select id="entryyearSelect" class="form-control">
                </select>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-2">
                <label for="entryyearSelect">Tingkatan</label>
            </div>
            <div class="col-md-3">
                <select id="levelSelect" class="form-control">
                </select>
            </div>
        </div>
        <br>
        <div class="table-responsive">
        
          <div id="gridFac" style="width:100%;" class="text-success"></div>
          <div id="hapusEptDialog"></div>
          <script type="text/x-kendo-template" id="templateHapusEptDialog">
              Anda yakin ingin menghapus Prodi <strong>#= Department_Name #</strong>?
          </script>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
var hapusEptDialog,
    templateHapusEptDialog;
$(document).ready(function() {
  templateHapusEptDialog = kendo.template($("#templateHapusEptDialog").html());

  $("#departmentSelect").kendoDropDownList({
    filter: "startswith",
    optionLabel: "Pilih Prodi",
    dataTextField: "Department_Name",
    dataValueField: "Department_Id",
    dataSource: {
        transport: {
            read: function(options) {
                $.ajax({
                dataType: 'json',
                url: "{{ route('api.get.department') }}",
                type: 'GET',
                data: options.data,
                success: function(res) {
                  options.success(res.data);
                }
            })
            },
        },
    },
    index: 0,
    change: function() {
        onChangeSearchParameter();
    }
  }).data("kendoDropDownList");
  $("#entryyearSelect").kendoDropDownList({
    filter: "startswith",
    optionLabel: "Pilih Angkatan",
    dataTextField: "Entry_Year_Id",
    dataValueField: "Entry_Year_Id",
    dataSource: {
        transport: {
            read: function(options) {
                $.ajax({
                dataType: 'json',
                url: "{{ route('api.get.entryyear') }}",
                type: 'GET',
                data: options.data,
                success: function(res) {
                  options.success(res.data);
                }
            })
            },
        },
    },
    index: 0,
    change: function() {
        onChangeSearchParameter();
    }
  }).data("kendoDropDownList");
  $("#levelSelect").kendoDropDownList({
    filter: "startswith",
    optionLabel: "Semua Tingkatan",
    dataTextField: "Description",
    dataValueField: "Achievement_Level_Id",
    dataSource: {
        transport: {
            read: function(options) {
                $.ajax({
                dataType: 'json',
                url: "{{ route('api.get.echievementlevel') }}",
                type: 'GET',
                data: options.data,
                success: function(res) {
                  options.success(res.data);
                }
            })
            },
        },
    },
    index: 0,
    change: function() {
        onChangeSearchParameter();
    }
  }).data("kendoDropDownList");

  function onChangeSearchParameter() {
    var grid = $("#gridFac").data("kendoGrid");
    grid.dataSource.read();
  }

  var grid = $('#gridFac').kendoGrid({ 
    dataSource: {
        transport: {
            read: function(options) {
                $.ajax({
                    dataType: 'json',
                    url: "{{ route('api.get.getstudentsertifikat') }}",
                    // url: "{{ url('') }}/api/master/get/all_building",
                    type: 'GET',
                    data: {
                      Department_Id:$("#departmentSelect").data("kendoDropDownList").value(),
                      Entry_Year_Id:$("#entryyearSelect").data("kendoDropDownList").value(),
                      Achievement_Level_Id:$("#levelSelect").data("kendoDropDownList").value(),
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
        }, //transport grid
        batch: true,
        schema: {
          data: 'data',
          total: 'total',
          model: {
              id: "Student_Id",
              fields: {
                Student_Id: { editable: false },
                Full_Name: { editable: false },
                Nim: { editable: false },
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
    editable: "inline",
    detailInit: detailInit,
    toolbar: [
        { template: "<input type='text' id='txtSearchString' class='k-textbox' placeholder='Cari sesuatu ...'>" },
    ],
    columns: [
            {
                field: "Student_Id",
                title: "Student_Id",
                width:"0%",
                hidden:true
            },
            {
                field: "Nim",
                title: "Nim",
                width:"20%",
                template: "#= Nim ? Nim : ''#" ,
            },
            {
                field: "Full_Name",
                title: "Full_Name",
                width:"80%"
            }
        ],
  })

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

function detailInit(e) {
    var detail_init = $("<div class='text-success'/>").appendTo(e.detailCell).kendoGrid({
        dataSource: {          
            transport: {
              read: function(options) {
                $.ajax({
                    dataType: 'json',
                    url: "{{ route('api.get.sertifikat') }}",
                    type: 'GET',
                    data: {
                      Student_Id:e.data.Student_Id,
                    },
                    success: function(res) {
                      options.success(res);
                      // console.log(res);
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
            },
            schema: {
            data: 'data',
            total: 'total',
            model: {
                id: "Student_Achievement_Id",
                fields: {
                    Student_Achievement_Id: { editable: false },
                    Student_Id: { editable: true },
                    Achievement_Name: { editable: true },
                    Description: { editable: true },
                    Achievement_Date: { editable: true },
                    Achievement_Level_Id: { editable: true },
                    Full_Name: { editable: true },
                    Nim: { editable: true },
                    Achievement_Level_Id: { editable: true },
                    Level_Name: { editable: true },
                }
            }
            },
            pageSize: 10,
        },
        sortable: true,
        pageable: {
            refresh: true,
            pageSizes: true,
            buttonCount: 5
        },
        editable: "inline",
        detailInit: detailInit2,
        columns: [
            {
                field: "Achievement_Name",
                title: "Achievement_Name",
                width:"25%"
            },
            {
                field: "Level_Name",
                title: "Tingkat",
                width:"25%",
            },
            {
                field: "Description",
                title: "Description",
                width:"50%", 
            },
            // { command: [
            //     {name: "edit",text:'',iconClass: "k-icon k-i-edit"},
            //     {
            //         name: "customDeleteUser",
            //         iconClass: "k-icon k-i-trash",
            //         text: "",
            //     },
            // ], 
            // title: "&nbsp;", 
            // width: "25%" }
        ],
    });
}

function detailInit2(e) {
    var detail_init = $("<div class='text-success'/>").appendTo(e.detailCell).kendoGrid({
        dataSource: {          
            transport: {
              read: function(options) {
                $.ajax({
                    dataType: 'json',
                    url: "{{ route('api.get.uploadsertifikat') }}",
                    type: 'GET',
                    data: {
                      Student_Achievement_Id:e.data.Student_Achievement_Id,
                    },
                    success: function(res) {
                      options.success(res);
                      // console.log(res);
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
            },
            schema: {
            data: 'data',
            total: 'total',
            model: {
                id: "Student_Achievement_Document_Id",
                fields: {
                    Student_Achievement_Document_Id: { editable: false },
                    Student_Achievement_Id: { editable: false },
                    Achievement_Document_Type_Id: { editable: false },
                    Document_Upload: { editable: false },
                    Type: { editable: false },
                }
            }
            },
            pageSize: 10,
        },
        sortable: true,
        pageable: {
            refresh: true,
            pageSizes: true,
            buttonCount: 5
        },
        editable: "inline",
        columns: [
            {
                field: "Type",
                title: "Type",
                width:"25%"
            },
            {
                field: "Document_Upload",
                title: "Document_Upload",
                width:"75%", 
                template: "# if (Document_Upload != null) {# <a href='{{ env('SIMAHASISWA') }}#= Document_Upload #' target='_blank'>#: Document_Upload #</a> #}#", 
                headerAttributes: { style: "text-align: center" }
            }
            // { command: [
            //     {name: "edit",text:'',iconClass: "k-icon k-i-edit"},
            //     {
            //         name: "customDeleteUser",
            //         iconClass: "k-icon k-i-trash",
            //         text: "",
            //     },
            // ], 
            // title: "&nbsp;", 
            // width: "25%" }
        ],
    });
}

function hapusDataUser(t) {
    t.preventDefault();

    var tr = $(t.target).closest("tr"),
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
                        url: "{{ route('api.delete.department') }}",
                        type: "delete",
                        data: {
                          data:data.Department_Id,
                        },
                        dataType: "json",
                        success: function (res) {
                            if(res.success == true){
                                $("#gridFac").data("kendoGrid").dataSource.read();
                            }else{
                                swal({
                                    //title: thrownError,
                                    title: "Mohon Maaf",
                                    text: res.data,
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
                                        $("#gridFac").data("kendoGrid").dataSource.read();
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
