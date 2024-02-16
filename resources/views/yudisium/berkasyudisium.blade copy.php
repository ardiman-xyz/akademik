@extends('layouts._layout')
@section('pageTitle', 'Berkas Perprodi Yudisium')
@section('content')

<?php
$access = auth()->user()->akses();
  $acc = $access;
?>
<style>
  .k-window{
    width:50% !important;
  }
  .k-edit-form-container {
    position: relative;
    width: 100% !important;
}
</style>

<section class="content">

  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Berkas Yudisium</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          <div class="pull-right tombol-gandeng dua">
            @if(in_array('create_yudisium-CanAdd', $acc))<a href="{{ url('proses/yudisium/masterberkasyudisium/data') }}" class="btn btn-success btn-sm">Master Berkas &nbsp;<i class="fa fa-plus"></i></a>@endif
          </div>
          <b>Berkas Yudisium Per Prodi</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        <div class="table-responsive">
          <div id="gridFac" style="width:100%;" class="text-success"></div>
          <div id="hapusEptDialog"></div>
          <script type="text/x-kendo-template" id="templateHapusEptDialog">
              Anda yakin ingin menghapus Prodi <strong>#= Yudisium_Document_Name #</strong>?
          </script>
        </div>
      </div>
    </div>
  </div>
  <br>
  <script type="text/x-kendo-template" id="editPopup">
    #if(isNew()){#
      <div id='gridTambahData'></div>
    #}else{#
      <div class="row">
        <div  class="row col-md-12">
          <label class="col-md-3">Nama Dokumen</label>
          <input class="col-md-6" name='Yudisium_Document_Id' />
        </div>
      </div>
      <div class="row">
        <div  class="row col-md-12">
          <label class="col-md-3">Jumlah</label>
          <input class="col-md-6" name='Copies' />
        </div>
      </div>
    #}#
  </script>
  <!-- create if new -->
  <div id="updateWindow">
      <form action="" id="updateForm" method="POST" enctype="multipart/form-data">
          {{ csrf_field() }}
          <meta name="_token" content="{!! csrf_token() !!}" />
        <div class="form-group row">
              <label class="col-md-4 text-right">Pilih File</label>
              <div class="col-md-5">
                  <input name="file" id="file" type="file" accept=".xls,.xlsx" />
              </div>
        </div>
      </form>
  </div>
</section>

<script>
var hapusEptDialog,
    templateHapusEptDialog,
    templateMasterEptDialog;
$(document).ready(function() {
  templateHapusEptDialog = kendo.template($("#templateHapusEptDialog").html());

  function onChangeSearchParameter() {
    var grid = $("#gridFac").data("kendoGrid");
    grid.dataSource.read();
  }

//Berkas per prodi
  var grid = $('#gridFac').kendoGrid({ 
    dataSource: {
        transport: {
            read: function(options) {
                $.ajax({
                    dataType: 'json',
                    url: "{{ route('api.get.department') }}",
                    // url: "{{ url('') }}/api/master/get/all_building",
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
        }, //transport grid
        batch: true,
        schema: {
          data: 'data',
          total: 'total',
          model: {
              id: "Department_Id",
              fields: {
                Department_Id: { editable: false },
                Department_Code: { editable: true },
                Education_Prog_Type_Id: { editable: true },
                Department_Name: { editable: true },
                Department_Name_Eng: { editable: true },
                Department_Acronym: { editable: true },
                Department_Dikti_Sk_Number: { editable: true },
                Department_Dikti_Sk_Date: { editable: true },
                Nim_Code: { editable: true },
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
    editable: "false",
    detailInit: detailInit,
    columns: [
            {
                field: "Department_Code",
                title: "Kode Prodi",
                width:"75",
                template: "#= Department_Code ? Department_Code : ''#" ,
            },
            {
                field: "Department_Name",
                title: "Nama Prodi",
                width:"75",
                template: "#= Department_Name ? Department_Name : ''#" ,
            },
            {
                field: "Department_Acronym",
                title: "Akronim Prodi",
                width:"75",
                template: "#= Department_Acronym ? Department_Acronym : ''#" ,
            }
        ],
  })
});

//detail init
  function detailInit(e) {
    
  // $(".k-grid-import").bind('click', function () {
  //   updateWindow.center().open();
  // });

    var detail_init = $("<div class='text-success'/>").appendTo(e.detailCell).kendoGrid({
      dataSource: {
        transport: {
            read: function(options) {
                $.ajax({
                    dataType: 'json',
                    url: "{{ route('api.get.master_berkas_prodi_yudisium') }}",
                    type: 'GET',
                    data: {
                      Department_Id : e.data.Department_Id
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
          create: function(options) {
            
					  options.data.models[0].Yudisium_Document_Id = [];
            options.data.models[0].Copies = [];
            options.data.models[0].array = [];
            $('input:checked').each(function(){
              let tr = $(this).closest('tr');

						options.data.models[0].Yudisium_Document_Id.push(parseInt(tr.find('input[name="Yudisium_Document_Id"]').val()));
						options.data.models[0].Copies.push(parseInt(tr.find('input[name="Copies"]').val()));
					});
            options.data.models[0].Department_Id = e.data.Department_Id
            console.log(options.data.models[0])
                $.ajax({
                    dataType: 'json',
                    url: "{{ route('api.post.master_berkas_prodi_yudisium') }}",
                    type: 'post',
                    data: {
                      data:options.data.models[0]
                    },
                    success: function(res) {
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
            }, //create
          update: function(options) {
                $.ajax({
                    dataType: 'json',
                    url: "{{ route('api.post.master_berkas_prodi_yudisium') }}",
                    type: 'post',
                    data: {
                      data:options.data.models[0],
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
              id: "Yudisium_Prerequisite_Id",
              fields: {
                Yudisium_Prerequisite_Id: { editable: false },
                No: { editable: false },
                Yudisium_Document_Id: { editable: true },
                Yudisium_Document_Name: { editable: true },
                Copies: { editable: true },
              }
          }
        },
        // serverPaging: true,
        pageSize: 10,
    }, //dataSourceGrid
    toolbar: [
        { name: "create", text: "Tambah Data",className:"k-button-primary" }
    ],
    sortable: true,
    pageable: {
        refresh: true,
        pageSizes: true,
        buttonCount: 5
    },
    editable: {
      mode:"popup",
      template: $('#editPopup').html()
    },
    edit: function (d) {
      d.container.find("#gridTambahData").kendoGrid({ 
        dataSource: {
          transport: {
            read: function(options) {
                $.ajax({
                    dataType: 'json',
                    url: "{{ route('api.get.master_berkas_notin_yudisium') }}",
                    type: 'GET',
                    data: {
                      Department_Id : e.data.Department_Id,
                      Yudisium_Document_Id : d.model.Yudisium_Document_Id
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
                id: "Yudisium_Document_Id",
                fields: {
                  Yudisium_Document_Id: { editable: false },
                  No: { editable: false },
                  Yudisium_Document_Name: { editable: true },
                  Discontinued: { type: "boolean" },
                }
            }
          },
          serverPaging: true,
          pageSize: 10,
        }, //dataSourceGrid
        sortable: true,
        // pageable: {
        //     refresh: true,
        //     pageSizes: true,
        //     buttonCount: 5
        // },
        // filterable: {
        //     mode: "row"
        // },
        editable: false,
        columns: [
          {
            field: "Copies", 
            template: '<input type="text" name="Copies" />', width: 110, 
            editable: function(e){ return false; },
              filterable: false
          },
          {
              field: "Yudisium_Document_Name",
              title: "Nama Dokumen",
              width:"75",
              template: "#= Yudisium_Document_Name ? Yudisium_Document_Name : ''#" ,
              // filterable: {
              //   cell: {
              //     operator: "contains",
              //     showOperators: false		                        
              //   }
              // }
          },
          { 
            field: "Pilih", 
            template: '<input name="Yudisium_Document_Id" value="#=data.Yudisium_Document_Id#" type="checkbox" #= Discontinued ? \'checked="checked"\' : "" # class="chkbx" />', width: 110, 
            editable: function(e){ return false; },
            filterable: false
          }
        ],
      })
    //   if(d.model.isNew()) {
    //     // var updateWindow = kendo.template($("#updateWindow").html());
    //     var updateWindow = $("#updateWindow").kendoWindow({ // jendela open function
    //         width: "650px",
    //         title: "Tambah",
    //         visible: false,
    //         actions: [
    //             "Close"
    //         ],
    //         close: onClose,
    //     }).data("kendoWindow");
    //     function onClose(e) {
    //       $('#gridFac').data("kendoGrid").dataSource.read();
    //     }
    //     updateWindow.center().open();
    //   } else {
    //     if(d.model.Yudisium_Document_Id == '' || d.model.Yudisium_Document_Id == null){
    //       d.container.find("input[name=Yudisium_Document_Id]").kendoMultiSelect({
    //         dataTextField: "Yudisium_Document_Name",
    //         dataValueField: "Yudisium_Document_Id",
    //         dataSource: {
    //             transport: {
    //                 read: function(options) {
    //                     $.ajax({
    //                     dataType: 'json',
    //                     url: "{{ route('api.get.master_berkas_notin_yudisium') }}",
    //                     type: 'GET',
    //                     data: {
    //                       Department_Id : e.data.Department_Id,
    //                       Yudisium_Document_Id : d.model.Yudisium_Document_Id
    //                     },
    //                     success: function(res) {
    //                     options.success(res.data);
    //                     }
    //                 })
    //                 },
    //             },
    //         }
    //       });
    //     }else{
          d.container.find("input[name=Yudisium_Document_Id]").kendoDropDownList({
            dataTextField: "Yudisium_Document_Name",
            dataValueField: "Yudisium_Document_Id",
            dataSource: {
                transport: {
                    read: function(options) {
                        $.ajax({
                        dataType: 'json',
                        url: "{{ route('api.get.master_berkas_notin_yudisium') }}",
                        type: 'GET',
                        data: {
                          Department_Id : e.data.Department_Id,
                          Yudisium_Document_Id : d.model.Yudisium_Document_Id
                        },
                        success: function(res) {
                        options.success(res.data);
                        }
                    })
                    },
                },
            }
          });
    //     }
    //   }

    },
    columns: [
            {
                field: "No",
                title: "No",
                width:"75px",
                template: "#= No ? No : ''#" ,
            },
            {
                field: "Yudisium_Document_Id",
                title: "Nama Dokumen",
                width:"75",
                template: "#= Yudisium_Document_Name ? Yudisium_Document_Name : ''#" ,
            },
            {
                field: "Copies",
                title: "Jumlah",
                width:"75",
                template: "#= Copies ? Copies : ''#" ,
            },
            { command: [
                {name: "edit"},
                {
                    name: "customDeleteUser",
                    iconClass: "k-icon k-i-close",
                    text: "Hapus",
                    click: hapusData
                },
              ], 
              title: "&nbsp;", 
              width: "250px" }
        ],
    });

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
                url: "{{ route('api.delete.master_berkas_prodi_yudisium') }}",
                type: "delete",
                data: {
                  data:data.Yudisium_Prerequisite_Id,
                },
                dataType: "json",
                success: function (res) {
                                  if(res.success == true){
                                      var grids = detail_init.data("kendoGrid");
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
                                              var grids = detail_init.data("kendoGrid");
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
  }

  // function klikPilih(){
  //   var tr = $(this).closest("tr");
  //   console.log(tr,$(this))
  //   tr.find("input[name='Copies']").attr('required',true);
  // }

</script>
@endsection
