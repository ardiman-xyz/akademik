@extends('layouts._layout')
@section('pageTitle', 'Berkas Perprodi Cuti')
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
    .k-edit-form-container {
        width: 500px;
    }

    #File_Url .k-clear-selected, #File_Url .k-upload-selected {
        display: none !important;
    }

    #Document_Url .k-clear-selected, #Document_Url .k-upload-selected {
        display: none !important;
    }
</style>

<section class="content">

  <div class="container-fluid-title">
    <div class="title-laporan">
      <h3 class="text-white">Berkas Cuti / Aktif Kembali</h3>
    </div>
  </div>
  <div class="container">
    <div class="panel panel-default bootstrap-admin-no-table-panel">
      <div class="panel-heading-green">
        <div class="bootstrap-admin-box-title right text-white">
          <div class="pull-right tombol-gandeng dua">
            @if(in_array('masterberkascuti-CanEdit', $acc))<a href="{{ url('proses/cuti/masterberkascuti/data') }}" class="btn btn-success btn-sm">Master Berkas &nbsp;<i class="fa fa-plus"></i></a>@endif
          </div>
          <b>&nbsp;</b>
        </div>
      </div>
      <br>
      <div class="bootstrap-admin-panel-content bootstrap-admin-no-table-panel-content">
        <div class="table-responsive">
          <h5>BERKAS Cuti</h5>
          <div id="gridFac" style="width:100%;" class="text-success"></div>
          <div id="hapusEptDialog"></div>
          <script type="text/x-kendo-template" id="templateHapusEptDialog">
              Anda yakin ingin menghapus <strong>#= Vacation_Document_Name #</strong>?
          </script>

          <br>
          <h5>BERKAS Aktif Kembali</h5>
          <div id="gridKembali" style="width:100%;" class="text-success"></div>
          <div id="hapusKembaliDialog"></div>
          <script type="text/x-kendo-template" id="templateHapusKembaliDialog">
              Anda yakin ingin menghapus <strong>#= Vacation_Document_Name #</strong>?
          </script>

          <br>
          <h5>BERKAS Perpanjangan</h5>
          <div id="gridPerpanjangan" style="width:100%;" class="text-success"></div>
          <div id="hapusPerpanjanganDialog"></div>
          <script type="text/x-kendo-template" id="templateHapusPerpanjanganDialog">
              Anda yakin ingin menghapus <strong>#= Vacation_Document_Name #</strong>?
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
          <input class="col-md-6" name='Vacation_Document_Id' />
        </div>
      </div>
      <div class="row">
        <div  class="row col-md-12">
          <label class="col-md-3">Jumlah</label>
          <input class="col-md-6" name='Copies' />
        </div>
      </div>
      <div class="row">
        <div  class="row col-md-12">
          <label class="col-md-3">Formulir</label>
          <div data-container-for="File_Url" class="k-edit-field">
              <div id="File_Url" class="input-width-modal">
                  <input type="file" name="File_Url">
              </div>
          </div>
        </div>
      </div>
    #}#
  </script>
  <!-- create if new -->
  <!-- <div id="updateWindow">
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
  </div> -->
</section>

<script>
var hapusEptDialog,
    templateHapusEptDialog,
    templateMasterEptDialog;
$(document).ready(function() {
  templateHapusEptDialog = kendo.template($("#templateHapusEptDialog").html());
  templateHapusKembaliDialog = kendo.template($("#templateHapusKembaliDialog").html());
  templateHapusPerpanjanganDialog = kendo.template($("#templateHapusPerpanjanganDialog").html());

  //Berkas Cuti
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

  //Berkas Aktif Kemabali
  var grid = $('#gridKembali').kendoGrid({ 
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
    detailInit: detailInitKembali,
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

  //Berkas Perpanjangan
  var grid = $('#gridPerpanjangan').kendoGrid({ 
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
    detailInit: detailInitPerpanjangan,
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

  //detail init cuti
  function detailInit(e) {
    var detail_init = $("<div class='text-success'/>").appendTo(e.detailCell).kendoGrid({
      dataSource: {
        transport: {
            read: function(options) {
                $.ajax({
                    dataType: 'json',
                    url: "{{ route('api.get.master_berkas_prodi_cuti') }}",
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
            
					  options.data.models[0].Vacation_Document_Id = [];
            options.data.models[0].Copies = [];
            options.data.models[0].array = [];
            $('input:checked').each(function(){
              let tr = $(this).closest('tr');

						options.data.models[0].Vacation_Document_Id.push(parseInt(tr.find('input[name="Vacation_Document_Id"]').val()));
						options.data.models[0].Copies.push(parseInt(tr.find('input[name="Copies"]').val()));
					});
            options.data.models[0].Department_Id = e.data.Department_Id
            console.log(options.data.models[0])
                $.ajax({
                    dataType: 'json',
                    url: "{{ route('api.post.master_berkas_prodi_cuti') }}",
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
                    url: "{{ route('api.post.master_berkas_prodi_cuti') }}",
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
              id: "Student_Vacation_Prerequisite_Id",
              fields: {
                Student_Vacation_Prerequisite_Id: { editable: false },
                No: { editable: false },
                Vacation_Document_Id: { editable: true },
                Vacation_Document_Name: { editable: true },
                Copies: { editable: true },
                File_Name: { editable: true },
                File_Url: { editable: true },
              }
          }
        },
        // serverPaging: true,
        // pageSize: 10,
    }, //dataSourceGrid
    toolbar: [
        { name: "create", text: "Tambah Data",className:"k-button-primary" }
    ],
    // sortable: true,
    // pageable: {
    //     refresh: true,
    //     pageSizes: true,
    //     buttonCount: 5
    // },
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
                    url: "{{ route('api.get.master_berkas_notin_cuti') }}",
                    type: 'GET',
                    data: {
                      Department_Id : e.data.Department_Id,
                      Vacation_Document_Id : d.model.Vacation_Document_Id
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
                id: "Vacation_Document_Id",
                fields: {
                  Vacation_Document_Id: { editable: false },
                  No: { editable: false },
                  Vacation_Document_Name: { editable: true },
                  Document_File: { editable: false },
                  Discontinued: { type: "boolean" },
                  File_Url: { nullable: true },
                  File_Name: { nullable: true }
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
            title: "Jumlah",
            template: '<input type="text" name="Copies" />', width: 110, 
            editable: function(e){ return false; },
              filterable: false
          },
          {
              field: "Vacation_Document_Name",
              title: "Nama Dokumen",
              width:"75",
              template: "#= Vacation_Document_Name ? Vacation_Document_Name : ''#" ,
              // filterable: {
              //   cell: {
              //     operator: "contains",
              //     showOperators: false		                        
              //   }
              // }
          },
          { 
            field: "Pilih", 
            template: '<input name="Vacation_Document_Id" value="#=data.Vacation_Document_Id#" type="checkbox" #= Discontinued ? \'checked="checked"\' : "" # class="chkbx" />', width: 110, 
            editable: function(e){ return false; },
            filterable: false
          }
        ],
      })
      d.container.find("input[name=Vacation_Document_Id]").kendoDropDownList({
        dataTextField: "Vacation_Document_Name",
        dataValueField: "Vacation_Document_Id",
        dataSource: {
            transport: {
                read: function(options) {
                    $.ajax({
                    dataType: 'json',
                    url: "{{ route('api.get.master_berkas_notin_cuti') }}",
                    type: 'GET',
                    data: {
                      Department_Id : e.data.Department_Id,
                      Vacation_Document_Id : d.model.Vacation_Document_Id
                    },
                    success: function(res) {
                    options.success(res.data);
                    }
                })
                },
            },
        }
      });
      d.container.find("[name='File_Url']").kendoUpload({
                async: {
                    saveUrl: "{{ url('') }}/api/proses/cuti/upload/" + d.model.Student_Vacation_Prerequisite_Id + "/upload_dokumen",
                    autoUpload: false
                },
                multiple: false,
                validation: {
                    allowedExtensions: [".pdf","docx"],
                    maxFileSize: 4000000
                },
                upload: onUpload,
                success: function (res) {
                    d.model.File_Url = res.response.File_Url;
                    d.model.File_Name = res.response.File_Name;
                    d.model.dirty = true;
                    d.model.dirtyFields['File_Url'] = true;
                    d.model.dirtyFields['File_Name'] = true;
                    
                    var grids = detail_init.data("kendoGrid");
                        grids.saveChanges();
                },
                localization: {
                    select: "Pilih file...",
                    clearSelectedFiles: "Bersihkan",
                    uploadSelectedFiles: "Unggah",
                    invalidMaxFileSize: "Ukuran file terlalu besar. Max 4MB",
                    invalidFileExtension: "Tipe file tidak di izinkan."
                }
            });
    },
    save: function (e) {
        if ($("[name='File_Url']").closest(".k-upload").hasClass("k-upload-empty")) {
            console.log("Dokumen SK belum diset!");
        } else {
            if($("#File_Url .k-upload-selected").length > 0){
                e.preventDefault();
                $("#File_Url .k-upload-selected").click();
            }
        }
    },
    columns: [
            {
                field: "No",
                title: "No",
                width:"10%",
                template: "#= No ? No : ''#" ,
            },
            {
                field: "Vacation_Document_Id",
                title: "Nama Dokumen",
                width:"50%",
                template: "#= Vacation_Document_Name ? Vacation_Document_Name : ''#" ,
            },
            {field: "File_Name", width:"10%", title: "Dokument", template: "# if (File_Name != null) {# <a target='_blank' href='{{route('getfile')}}?name=#:File_Url#'>#: File_Name #</a> #}#", headerAttributes: { style: "text-align: center" }},
            {
                field: "Copies",
                title: "Jumlah",
                width:"10%",
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
              width: "20%" }
        ],
    });

    function onUpload(e) {
        var xhr = e.XMLHttpRequest;

        if (xhr) {
            xhr.addEventListener("readystatechange", function (e) {
                if (xhr.readyState == 1) {
                    xhr.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr("content"));
                }
            });
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
                url: "{{ route('api.delete.master_berkas_prodi_cuti') }}",
                type: "delete",
                data: {
                  data:data.Student_Vacation_Prerequisite_Id,
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

    //detail init aktif kembali
  function detailInitKembali(e) {
    var detail_init = $("<div class='text-success'/>").appendTo(e.detailCell).kendoGrid({
      dataSource: {
        transport: {
            read: function(options) {
                $.ajax({
                    dataType: 'json',
                    url: "{{ route('api.get.master_berkas_prodi_kembali') }}",
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
            
					  options.data.models[0].Vacation_Document_Id = [];
            options.data.models[0].Copies = [];
            options.data.models[0].array = [];
            $('input:checked').each(function(){
              let tr = $(this).closest('tr');

						options.data.models[0].Vacation_Document_Id.push(parseInt(tr.find('input[name="Vacation_Document_Id"]').val()));
						options.data.models[0].Copies.push(parseInt(tr.find('input[name="Copies"]').val()));
					});
            options.data.models[0].Department_Id = e.data.Department_Id
            console.log(options.data.models[0])
                $.ajax({
                    dataType: 'json',
                    url: "{{ route('api.post.master_berkas_prodi_kembali') }}",
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
                    url: "{{ route('api.post.master_berkas_prodi_kembali') }}",
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
              id: "Student_Vacation_Prerequisite_Id",
              fields: {
                Student_Vacation_Prerequisite_Id: { editable: false },
                No: { editable: false },
                Vacation_Document_Id: { editable: true },
                Vacation_Document_Name: { editable: true },
                Copies: { editable: true },
              }
          }
        },
        // serverPaging: true,
        // pageSize: 10,
    }, //dataSourceGrid
    toolbar: [
        { name: "create", text: "Tambah Data",className:"k-button-primary" }
    ],
    // sortable: true,
    // pageable: {
    //     refresh: true,
    //     pageSizes: true,
    //     buttonCount: 5
    // },
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
                    url: "{{ route('api.get.master_berkas_notin_kembali') }}",
                    type: 'GET',
                    data: {
                      Department_Id : e.data.Department_Id,
                      Vacation_Document_Id : d.model.Vacation_Document_Id
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
                id: "Vacation_Document_Id",
                fields: {
                  Vacation_Document_Id: { editable: false },
                  No: { editable: false },
                  Vacation_Document_Name: { editable: true },
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
            title: "Jumlah",
            template: '<input type="text" name="Copies" />', width: 110, 
            editable: function(e){ return false; },
              filterable: false
          },
          {
              field: "Vacation_Document_Name",
              title: "Nama Dokumen",
              width:"75",
              template: "#= Vacation_Document_Name ? Vacation_Document_Name : ''#" ,
              // filterable: {
              //   cell: {
              //     operator: "contains",
              //     showOperators: false		                        
              //   }
              // }
          },
          { 
            field: "Pilih", 
            template: '<input name="Vacation_Document_Id" value="#=data.Vacation_Document_Id#" type="checkbox" #= Discontinued ? \'checked="checked"\' : "" # class="chkbx" />', width: 110, 
            editable: function(e){ return false; },
            filterable: false
          }
        ],
      })
      d.container.find("input[name=Vacation_Document_Id]").kendoDropDownList({
        dataTextField: "Vacation_Document_Name",
        dataValueField: "Vacation_Document_Id",
        dataSource: {
            transport: {
                read: function(options) {
                    $.ajax({
                    dataType: 'json',
                    url: "{{ route('api.get.master_berkas_notin_kembali') }}",
                    type: 'GET',
                    data: {
                      Department_Id : e.data.Department_Id,
                      Vacation_Document_Id : d.model.Vacation_Document_Id
                    },
                    success: function(res) {
                    options.success(res.data);
                    }
                })
                },
            },
        }
      });
    },
    columns: [
            {
                field: "No",
                title: "No",
                width:"10%",
                template: "#= No ? No : ''#" ,
            },
            {
                field: "Vacation_Document_Id",
                title: "Nama Dokumen",
                width:"60%",
                template: "#= Vacation_Document_Name ? Vacation_Document_Name : ''#" ,
            },
            {
                field: "Copies",
                title: "Jumlah",
                width:"10%",
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
              width: "20%" }
        ],
    });

    function hapusData(q) {
      q.preventDefault();

      var tr = $(q.target).closest("tr"),
        data = this.dataItem(tr);

      hapusKembaliDialog = $("#hapusKembaliDialog").kendoDialog({
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
                url: "{{ route('api.delete.master_berkas_prodi_kembali') }}",
                type: "delete",
                data: {
                  data:data.Student_Vacation_Prerequisite_Id,
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

      hapusKembaliDialog.content(templateHapusKembaliDialog(data));
      hapusKembaliDialog.open();
    }
  }

    //detail init Pepanjangan
  function detailInitPerpanjangan(e) {
    var detail_init = $("<div class='text-success'/>").appendTo(e.detailCell).kendoGrid({
      dataSource: {
        transport: {
            read: function(options) {
                $.ajax({
                    dataType: 'json',
                    url: "{{ route('api.get.master_berkas_prodi_perpanjangan') }}",
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
            
					  options.data.models[0].Vacation_Document_Id = [];
            options.data.models[0].Copies = [];
            options.data.models[0].array = [];
            $('input:checked').each(function(){
              let tr = $(this).closest('tr');

						options.data.models[0].Vacation_Document_Id.push(parseInt(tr.find('input[name="Vacation_Document_Id"]').val()));
						options.data.models[0].Copies.push(parseInt(tr.find('input[name="Copies"]').val()));
					});
            options.data.models[0].Department_Id = e.data.Department_Id
            console.log(options.data.models[0])
                $.ajax({
                    dataType: 'json',
                    url: "{{ route('api.post.master_berkas_prodi_perpanjangan') }}",
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
                    url: "{{ route('api.post.master_berkas_prodi_perpanjangan') }}",
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
              id: "Student_Vacation_Prerequisite_Id",
              fields: {
                Student_Vacation_Prerequisite_Id: { editable: false },
                No: { editable: false },
                Vacation_Document_Id: { editable: true },
                Vacation_Document_Name: { editable: true },
                Copies: { editable: true },
              }
          }
        },
        // serverPaging: true,
        // pageSize: 10,
    }, //dataSourceGrid
    toolbar: [
        { name: "create", text: "Tambah Data",className:"k-button-primary" }
    ],
    // sortable: true,
    // pageable: {
    //     refresh: true,
    //     pageSizes: true,
    //     buttonCount: 5
    // },
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
                    url: "{{ route('api.get.master_berkas_notin_kembali') }}",
                    type: 'GET',
                    data: {
                      Department_Id : e.data.Department_Id,
                      Vacation_Document_Id : d.model.Vacation_Document_Id
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
                id: "Vacation_Document_Id",
                fields: {
                  Vacation_Document_Id: { editable: false },
                  No: { editable: false },
                  Vacation_Document_Name: { editable: true },
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
            title: "Jumlah",
            template: '<input type="text" name="Copies" />', width: 110, 
            editable: function(e){ return false; },
              filterable: false
          },
          {
              field: "Vacation_Document_Name",
              title: "Nama Dokumen",
              width:"75",
              template: "#= Vacation_Document_Name ? Vacation_Document_Name : ''#" ,
              // filterable: {
              //   cell: {
              //     operator: "contains",
              //     showOperators: false		                        
              //   }
              // }
          },
          { 
            field: "Pilih", 
            template: '<input name="Vacation_Document_Id" value="#=data.Vacation_Document_Id#" type="checkbox" #= Discontinued ? \'checked="checked"\' : "" # class="chkbx" />', width: 110, 
            editable: function(e){ return false; },
            filterable: false
          }
        ],
      })
      d.container.find("input[name=Vacation_Document_Id]").kendoDropDownList({
        dataTextField: "Vacation_Document_Name",
        dataValueField: "Vacation_Document_Id",
        dataSource: {
            transport: {
                read: function(options) {
                    $.ajax({
                    dataType: 'json',
                    url: "{{ route('api.get.master_berkas_notin_kembali') }}",
                    type: 'GET',
                    data: {
                      Department_Id : e.data.Department_Id,
                      Vacation_Document_Id : d.model.Vacation_Document_Id
                    },
                    success: function(res) {
                    options.success(res.data);
                    }
                })
                },
            },
        }
      });
    },
    columns: [
            {
                field: "No",
                title: "No",
                width:"10%",
                template: "#= No ? No : ''#" ,
            },
            {
                field: "Vacation_Document_Id",
                title: "Nama Dokumen",
                width:"60%",
                template: "#= Vacation_Document_Name ? Vacation_Document_Name : ''#" ,
            },
            {
                field: "Copies",
                title: "Jumlah",
                width:"10%",
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
              width: "20%" }
        ],
    });

    function hapusData(q) {
      q.preventDefault();

      var tr = $(q.target).closest("tr"),
        data = this.dataItem(tr);

      hapusKembaliDialog = $("#hapusKembaliDialog").kendoDialog({
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
                url: "{{ route('api.delete.master_berkas_prodi_perpanjangan') }}",
                type: "delete",
                data: {
                  data:data.Student_Vacation_Prerequisite_Id,
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

      hapusPerpanjanganDialog.content(templateHapusPerpanjanganDialog(data));
      hapusPerpanjanganDialog.open();
    }
  }

  // function klikPilih(){
  //   var tr = $(this).closest("tr");
  //   console.log(tr,$(this))
  //   tr.find("input[name='Copies']").attr('required',true);
  // }

</script>
@endsection
