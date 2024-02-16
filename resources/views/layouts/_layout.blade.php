<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="shortcut icon" href="{{ asset('img/logo_univ.png') }}" />
  <title>@yield('pageTitle') - {{ config('app.name', 'Laravel') }}</title>

  {{-- <style media="screen">

  .navbar-nav > li > .dropdown-menu {
    position: absolute;
    background: #0B7D00;
    width: 50%;
  }
  @media (min-width: 992px) {
    .navbar-nav > li > .dropdown-menu {
      position: absolute;
      background: #0B7D00;
      width: 25%;
      left: 75%;
    }
  }
  </style> --}}

    <style>
    .navbar-toggler-icon {
        /* background-image: url( "data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='rgba(0, 0, 0, 0.5)' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10'" ) !important; */
        background: #000 !important;
    }
  </style>

  <script src="{{ asset('js/jquery.min.js') }}"></script>
  <script src="{{ asset('js/kendo.all.min.js') }}"></script>
  <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.js"></script>
 
  <script src="{{ asset('js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('sweatalert/sweetalert.min.js') }}"></script>
  <script src="{{ asset('bootstrap-select/js/bootstrap-select.js') }}"></script>
  <link href="{{ asset('css/customkendo.css') }}" rel="stylesheet">

  <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
  <link href="{{ asset('css/font-awesome.css') }}" rel="stylesheet">
  <link href="{{ asset('css/glyphicon.css') }}" rel="stylesheet">
  <link href="{{ asset('sweatalert/sweetalert.css') }}" rel="stylesheet">
  <link href="{{ asset('bootstrap-select/css/bootstrap-select.css') }}" rel="stylesheet">

  <script src="{{ asset('slim-select/slimselect.min.js') }}"></script>
  <link href="{{ asset('slim-select/slimselect.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom_theme.css') }}" rel="stylesheet">

  <script src="{{ asset('treeview/hummingbird-treeview.js') }}"></script>
  <link href="{{ asset('treeview/hummingbird-treeview.css') }}" rel="stylesheet">

    <link href="{{ asset('css/kendo.common.min.css') }}" rel="stylesheet">
  <link href="{{ asset('css/kendo.metro.min.css') }}" rel="stylesheet">
  <link href="{{ asset('css/kendo.metro.mobile.min.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="{{asset('css/w3.css')}}">

  <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css" rel="stylesheet">

  <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
  <style media="screen">
  .height{
    height: 64px;
  }
  .judul1{
    font-size: 24px;
    color: white;
  }
  .judul2{
    color: white;
  }
  .aktif{
    background-color: rgba(0,0,0,0.2);
  }
  .error-database{
    color:red;
    animation-name: error-database;
    animation-duration: 0.5s;
  }
  

  </style>
</head>
<body class="body">
  @include('sweet::alert')
  <nav class="navbar navbar-expand-lg navbar-dark bg-green">
    <div class="kotak-logo">
      <a class="navbar-brand" href="#" style="margin:0px;padding:4px;width:auto;">
        <img src="{{ url('img/navbar.png')}}" alt="gambar kosong" class="logo-brand isi-kotak-logo" >
      </a>
    </div>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav ml-auto">
        @if (Auth::check())
          <?php
          $access = auth()->user()->akses();
          $acc = $access;
          ?>

              <li class="nav-item <?php if(strpos(Request::url(),'home') != false){ echo 'active' ;};?>">
                <a class="nav-link" href="{{ url('') }}">Home <span class="sr-only">(current)</span></a>
              </li>
              @if(in_array('user-CanView',$acc) || in_array('ubahpassword-CanView',$acc) || in_array('role-CanView',$acc))
              <li class="nav-item dropdown <?php
              if(
                strpos(Request::url(),'administrator/user') != false ||
                // strpos(Request::url(),'administrator/ubahpassword') != false ||
                strpos(Request::url(),'administrator/role') != false
              ){ echo 'active' ;};?>" id="administrator">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Administrator
              </a>
              <div class="dropdown-menu bg-green dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                @if(in_array('role-CanView',$acc)) <a class="administrator dropdown-item <?php if(strpos(Request::url(),'administrator/role') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('administrator/role') }}">Pengaturan Peran User</a> @endif
                @if(in_array('user-CanView',$acc)) <a class="administrator dropdown-item <?php if(strpos(Request::url(),'administrator/user') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('administrator/user') }}">Manajemen User</a> @endif
                 @if(in_array('ubahpassword-CanView',$acc)) <a class="administrator dropdown-item <?php if(strpos(Request::url(),'administrator/ubahpassword') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('administrator/ubahpassword') }}">Ubah Password User</a> @endif
              </div>
              </li>
              @endif
              @if(in_array('faculty-CanView',$acc) || in_array('education_program_type-CanView',$acc) || in_array('department-CanView',$acc) || in_array('class_program-CanView',$acc) || in_array('department_class_program-CanView',$acc) || in_array('concentration-CanView',$acc) || in_array('entry_year-CanView',$acc) || in_array('term_year-CanView',$acc) || in_array('sched_session_group-CanView',$acc) || in_array('employee-CanView',$acc) || in_array('grade_letter-CanView',$acc) || in_array('course_type-CanView',$acc) || in_array('course_group-CanView',$acc) || in_array('graduate_predicate-CanView',$acc) || in_array('religion-CanView',$acc) || in_array('citizenship-CanView',$acc) || in_array('blood_type-CanView',$acc) || in_array('register_status-CanView',$acc) || in_array('high_school_major-CanView',$acc) || in_array('building-CanView',$acc) || in_array('room-CanView',$acc) || in_array('functional_position_term_year-CanView',$acc) || in_array('curriculum_type-CanView',$acc) || in_array('curriculum-CanView',$acc) || in_array('country-CanView',$acc) || in_array('province-CanView',$acc) || in_array('city-CanView',$acc) || in_array('status-CanView',$acc))
              <li class="nav-item dropdown <?php
            if(
              strpos(Request::url(),'master/faculty') != false ||
              strpos(Request::url(),'master/department') != false ||
              strpos(Request::url(),'master/class_program') != false ||
              strpos(Request::url(),'master/department_class_program') != false ||
              strpos(Request::url(),'master/concentration') != false ||
              strpos(Request::url(),'master/education_type') != false ||
              strpos(Request::url(),'master/entry_year') != false ||
              strpos(Request::url(),'master/term_year') != false ||
              strpos(Request::url(),'master/sched_session_group') != false ||
              strpos(Request::url(),'master/employee') != false ||
              strpos(Request::url(),'master/education_program_type') != false ||
              strpos(Request::url(),'master/grade_letter') != false ||
              strpos(Request::url(),'master/course_type') != false ||
              strpos(Request::url(),'master/course_group') != false ||
              strpos(Request::url(),'master/graduate_predicate') != false ||
              strpos(Request::url(),'master/religion') != false ||
              strpos(Request::url(),'master/citizenship') != false ||
              strpos(Request::url(),'master/blood_type') != false ||
              strpos(Request::url(),'master/register_status') != false ||
              strpos(Request::url(),'master/high_school_major') != false ||
              strpos(Request::url(),'master/building') != false ||
              strpos(Request::url(),'master/room') != false ||
              strpos(Request::url(),'master/functional_position_term_year') != false ||
              strpos(Request::url(),'master/curriculum_type') != false ||
              strpos(Request::url(),'master/curriculum') != false ||
              strpos(Request::url(),'master/country') != false ||
              strpos(Request::url(),'master/province') != false ||
              strpos(Request::url(),'master/city') != false ||
              strpos(Request::url(),'master/status') != false
            ){ echo 'active' ;};?>" id="master">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Master
          </a>
          <ul class="dropdown-menu bg-green" aria-labelledby="navbarDropdownMenuLink">
            @if(in_array('faculty-CanView',$acc) || in_array('education_program_type-CanView',$acc) || in_array('department-CanView',$acc) || in_array('class_program-CanView',$acc) || in_array('department_class_program-CanView',$acc) || in_array('concentration-CanView',$acc) || in_array('entry_year-CanView',$acc) || in_array('term_year-CanView',$acc) || in_array('sched_session_group-CanView',$acc) || in_array('employee-CanView',$acc) || in_array('grade_letter-CanView',$acc) || in_array('course_type-CanView',$acc) || in_array('course_group-CanView',$acc) || in_array('graduate_predicate-CanView',$acc) || in_array('status-CanView',$acc))
            <li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle dropdown-submenu" href="#">Akademik</a>
              <ul class="dropdown-menu bg-green">
                @if(in_array('education_program_type-CanView',$acc))<a class=" dropdown-item <?php if(strpos(Request::url(),'master/education_program_type') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('master/education_program_type') }}">Strata Pendidikan</a>@endif
                @if(in_array('faculty-CanView',$acc))<a class=" dropdown-item <?php if(strpos(Request::url(),'master/faculty') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('master/faculty') }}">Fakultas</a>@endif
                @if(in_array('department-CanView',$acc))<a class=" dropdown-item <?php if(strpos(Request::url(),'master/department') != false){ echo 'dropdown-item-active' ;};?>"  href="{{ url('master/department') }}">Program Studi</a>@endif
                @if(in_array('class_program-CanView',$acc))<a class=" dropdown-item <?php if(strpos(Request::url(),'master/class_program') != false){ echo 'dropdown-item-active' ;};?>"  href="{{ url('master/class_program') }}">Program Kelas</a>@endif
                @if(in_array('department_class_program-CanView',$acc))<a class=" dropdown-item <?php if(strpos(Request::url(),'master/department_class_program') != false){ echo 'dropdown-item-active' ;};?>"  href="{{ url('master/department_class_program') }}">Prodi VS Program Kelas</a>@endif
                <!-- @if(in_array('concentration-CanView',$acc))<a class=" dropdown-item <?php if(strpos(Request::url(),'master/concentration') != false){ echo 'dropdown-item-active' ;};?>"  href="{{ url('master/concentration') }}">Konsentrasi</a>@endif -->
                {{-- <a class=" dropdown-item"  href="{{ url('master/education_type') }}">Jenjang Pendidikan</a> --}}
                @if(in_array('entry_year-CanView',$acc))<a class=" dropdown-item <?php if(strpos(Request::url(),'master/entry_year') != false){ echo 'dropdown-item-active' ;};?>"  href="{{ url('master/entry_year') }}">Angkatan</a>@endif
                @if(in_array('term_year-CanView',$acc))<a class=" dropdown-item <?php if(strpos(Request::url(),'master/term_year') != false){ echo 'dropdown-item-active' ;};?>"  href="{{ url('master/term_year') }}">Semester Berlaku</a>@endif
                @if(in_array('sched_session_group-CanView',$acc))<a class=" dropdown-item <?php if(strpos(Request::url(),'master/sched_session_group') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('master/sched_session_group') }}">Group Sesi Jadwal</a>@endif
                @if(in_array('employee-CanView',$acc))<a class=" dropdown-item"<?php if(strpos(Request::url(),'master/employee') != false){ echo 'dropdown-item-active' ;};?> href="{{ url('master/employee') }}">Pegawai / Dosen</a>@endif
                @if(in_array('grade_letter-CanView',$acc))<a class=" dropdown-item <?php if(strpos(Request::url(),'master/grade_letter') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('master/grade_letter') }}">Nilai Huruf</a>@endif
                @if(in_array('course_type-CanView',$acc))<a class=" dropdown-item <?php if(strpos(Request::url(),'master/course_type') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('master/course_type') }}">Jenis Matakuliah</a>@endif
                <!-- @if(in_array('course_group-CanView',$acc))<a class=" dropdown-item <?php if(strpos(Request::url(),'master/course_group') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('master/course_group') }}">Kelompok Matakuliah</a>@endif -->
                @if(in_array('graduate_predicate-CanView',$acc))<a class=" dropdown-item <?php if(strpos(Request::url(),'master/graduate_predicate') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('master/graduate_predicate') }}">Predikat Lulus</a>@endif
                @if(in_array('status-CanView',$acc))<a class=" dropdown-item <?php if(strpos(Request::url(),'master/status') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('master/status') }}">Status Mahasiswa</a>@endif
                @if(in_array('announcement-CanView',$acc))<a class=" dropdown-item <?php if(strpos(Request::url(),'master/announcement') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('master/announcement') }}">Pengumuman</a>@endif
              </ul>
            </li>
            @endif
            @if(in_array('religion-CanView',$acc) || in_array('citizenship-CanView',$acc) || in_array('blood_type-CanView',$acc) || in_array('register_status-CanView',$acc) || in_array('high_school_major-CanView',$acc))
            <!-- <li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle" href="#">Biodata</a> -->
              <!-- <ul class="dropdown-menu bg-green"> -->
                <!-- @if(in_array('religion-CanView',$acc))<a class=" dropdown-item <?php if(strpos(Request::url(),'master/religion') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('master/religion') }}">Agama</a>@endif -->
                <!-- @if(in_array('statuskeluarga-CanView',$acc))<a class=" dropdown-item <?php if(strpos(Request::url(),'master/statuskeluarga') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('master/statuskeluarga') }}">Status Keluarga</a>@endif -->
                <!-- <a class=" dropdown-item" id="Status_Keluarga" onclick="menukosong()" href="#">Status Keluarga</a> -->
                <!-- @if(in_array('citizenship-CanView',$acc))<a class=" dropdown-item <?php if(strpos(Request::url(),'master/citizenship') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('master/citizenship') }}">Kewarganegaraan</a>@endif -->
                <!-- @if(in_array('blood_type-CanView',$acc))<a class=" dropdown-item <?php if(strpos(Request::url(),'master/blood_type') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('master/blood_type') }}">Golongan Darah</a>@endif -->
                <!-- @if(in_array('register_status-CanView',$acc))<a class=" dropdown-item <?php if(strpos(Request::url(),'master/register_status') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('master/register_status') }}">Status Daftar Mhs</a>@endif
                @if(in_array('high_school_major-CanView',$acc))<a class=" dropdown-item <?php if(strpos(Request::url(),'master/high_school_major') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('master/high_school_major') }}">Jurusan di SMA</a>@endif -->
              <!-- </ul> -->
            <!-- </li> -->
            @endif
            @if(in_array('building-CanView',$acc) || in_array('room-CanView',$acc) || in_array('functional_position_term_year-CanView',$acc))
            <li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle" href="#">Ruang</a>
              <ul class="dropdown-menu bg-green">
                @if(in_array('building-CanView',$acc))<a class=" dropdown-item <?php if(strpos(Request::url(),'master/building') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('master/building') }}">Gedung</a>@endif
                @if(in_array('room-CanView',$acc))<a class=" dropdown-item <?php if(strpos(Request::url(),'master/room') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('master/room') }}">Ruang</a>@endif
                <!-- @if(in_array('jabatan-CanView',$acc))
                <a class=" dropdown-item 
                <?php if(strpos(Request::url(),'master/jabatan') != false){ 
                  echo 'dropdown-item-active' ;};
                  ?>
                  " href="{{ url('master/jabatan') }}">Jabatan</a>
                  @endif -->
              </ul>
            </li>
            <!-- <li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle" href="#">Pejabat Fungsional</a> -->
              <!-- <ul class="dropdown-menu bg-green"> -->
                <!-- @if(in_array('jabatan-CanView',$acc))
                <a class=" dropdown-item 
                <?php if(strpos(Request::url(),'master/jabatan') != false){ 
                  echo 'dropdown-item-active' ;};
                  ?>
                  " href="{{ url('master/jabatan') }}">Jabatan</a>
                  @endif -->
                <!-- @if(in_array('functional_position_term_year-CanView',$acc))<a class=" dropdown-item <?php if(strpos(Request::url(),'master/functional_position_term_year') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('master/functional_position_term_year') }}">Pejabat Fungsional</a>@endif -->
                <!-- <a onclick="menukosong()" class=" dropdown-item <?php if(strpos(Request::url(),'master/functional_position_term_year') != false){ echo 'dropdown-item-active' ;};?>" href="#">Pejabat Struktural</a> -->
              <!-- </ul> -->
            <!-- </li> -->
            @endif
            @if(in_array('curriculum_type-CanView',$acc) || in_array('curriculum-CanView',$acc))
            <li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle" href="#">Kurikulum</a>
              <ul class="dropdown-menu bg-green">
                @if(in_array('curriculum_type-CanView',$acc))<a class=" dropdown-item <?php if(strpos(Request::url(),'master/curriculum_type') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('master/curriculum_type') }}">Jenis Kurikulum</a>@endif
                @if(in_array('curriculum-CanView',$acc))<a class=" dropdown-item <?php if(strpos(Request::url(),'master/curriculum') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('master/curriculum') }}">Kurikulum</a>@endif
              </ul>
            </li>
            @endif
            @if(in_array('country-CanView',$acc) || in_array('province-CanView',$acc) || in_array('city-CanView',$acc))
            <!-- <li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle" href="#">Lokasi</a> -->
              <!-- <ul class="dropdown-menu bg-green"> -->
                <!-- @if(in_array('country-CanView',$acc))<a class=" dropdown-item <?php if(strpos(Request::url(),'master/country') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('master/country') }}">Negara</a>@endif -->
                <!-- @if(in_array('province-CanView',$acc))<a class=" dropdown-item <?php if(strpos(Request::url(),'master/province') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('master/province') }}">Provinsi</a>@endif -->
                <!-- @if(in_array('city-CanView',$acc))<a class=" dropdown-item <?php if(strpos(Request::url(),'master/city') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('master/city') }}">Kota / Kabupaten</a>@endif -->
              <!-- </ul> -->
            <!-- </li> -->
            @endif
            {{-- <li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle" href="#">Prestasi Mahasiswa</a>
              <ul class="dropdown-menu bg-green">
                <a class=" dropdown-item" id="Jenis_Prestasi" href="#" onclick="menukosong()">Jenis Prestasi</a>
                <a class=" dropdown-item" href="#" onclick="menukosong()">Peringkat Juara</a>
                <a class=" dropdown-item" href="#" onclick="menukosong()">Tingkat Prestasi</a>
              </ul>
            </li> --}}
            <li>
          </ul>
        </li>
        @endif
        @if(in_array('curriculum_applied-CanView',$acc) || in_array('curriculum_entry_year-CanView',$acc) || in_array('course-CanView',$acc) || in_array('course_curriculum-CanView',$acc) || in_array('course_identic-CanView',$acc) || in_array('grade_department-CanView',$acc))
        <li class="nav-item dropdown <?php
            if(
              strpos(Request::url(),'parameter/curriculum_applied') != false ||
              strpos(Request::url(),'parameter/curriculum_entry_year') != false ||
              strpos(Request::url(),'parameter/course') != false ||
              strpos(Request::url(),'parameter/course_curriculum') != false ||
              strpos(Request::url(),'parameter/course_identic') != false ||
              strpos(Request::url(),'parameter/grade_department') != false
            ){ echo 'active' ;};?>">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Parameter
          </a>
          <div class="dropdown-menu bg-green dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
            @if(in_array('curriculum_applied-CanView',$acc))<a class="dropdown-item <?php if(strpos(Request::url(),'parameter/curriculum_applied') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('parameter/curriculum_applied') }}">Kurikulum Prodi</a>@endif
            @if(in_array('curriculum_entry_year-CanView',$acc))<a class="dropdown-item <?php if(strpos(Request::url(),'parameter/curriculum_entry_year') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('parameter/curriculum_entry_year') }}">Kurikulum Angkatan</a>@endif
            @if(in_array('course-CanView',$acc))<a class="dropdown-item <?php if(strpos(Request::url(),'parameter/course') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('parameter/course') }}">Mata Kuliah</a>@endif
            @if(in_array('course_curriculum-CanView',$acc))<a class="dropdown-item <?php if(strpos(Request::url(),'parameter/course_curriculum') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('parameter/course_curriculum') }}">Mata Kuliah & Kurikulum</a>@endif
            <!-- @if(in_array('course_identic-CanView',$acc))<a class="dropdown-item <?php if(strpos(Request::url(),'parameter/course_identic') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('parameter/course_identic') }}">Mata Kuliah Setara</a>@endif -->
            <!-- <a class="dropdown-item" href="#" onclick="menukosong()">Grade Nilai</a> -->
            @if(in_array('grade_department-CanView',$acc))<a class="dropdown-item <?php if(strpos(Request::url(),'parameter/grade_department') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('parameter/grade_department') }}">Grade Nilai</a>@endif
            @if(in_array('beban_mengajar-CanView',$acc))<a class="dropdown-item <?php if(strpos(Request::url(),'parameter/beban_mengajar') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('parameter/beban_mengajar') }}">Beban Mengajar Dosen</a>@endif
            @if(in_array('komponen_penilaian-CanView',$acc))<a class="dropdown-item <?php if(strpos(Request::url(),'parameter/komponen_penilaian') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('parameter/komponen_penilaian/create') }}">Komponen Penilaian</a>@endif

            {{-- <a class="dropdown-item" href="#" onclick="menukosong()">Prasyarat</a> --}}
          </div>
        </li>
        @endif
        @if(in_array('student-CanView',$acc) || in_array('student_supervision-CanView',$acc) || in_array('student_password-CanView',$acc) || in_array('event_sched-CanView',$acc) || in_array('department_lecturer-CanView',$acc) || in_array('offered_course-CanView',$acc) || in_array('allowed_sks-CanView',$acc) || in_array('sched_session-CanView',$acc) || in_array('offered_course_sched-CanView',$acc) || in_array('offered_course_exam-CanView',$acc) || in_array('studentmundurkeluardo-CanView',$acc))
        <li class="nav-item dropdown <?php
            if(
              strpos(Request::url(),'setting/student') != false ||
              strpos(Request::url(),'setting/studentmundurkeluardo') != false ||
              strpos(Request::url(),'setting/student_supervision') != false ||
              strpos(Request::url(),'setting/student_password') != false ||
              strpos(Request::url(),'setting/event_sched') != false ||
              strpos(Request::url(),'setting/department_lecturer') != false ||
              strpos(Request::url(),'setting/offered_course') != false ||
              strpos(Request::url(),'setting/allowed_sks') != false ||
              strpos(Request::url(),'setting/sched_session') != false ||
              strpos(Request::url(),'setting/offered_course_sched') != false ||
              strpos(Request::url(),'setting/offered_course_exam') != false ||
              strpos(Request::url(),'setting/studentmundurkeluardo') != false
            ){ echo 'active' ;};?>">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Setting
          </a>
          <div class="dropdown-menu bg-green dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
            @if(in_array('student-CanView',$acc))<a class="dropdown-item <?php if(strpos(Request::url(),'setting/student') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('setting/student') }}">Data Mahasiswa</a>@endif
            @if(in_array('rfid-CanView',$acc))<a class="dropdown-item <?php if(strpos(Request::url(),'setting/rfid') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('setting/rfid') }}">Data RFID Mahasiswa</a>@endif
            @if(in_array('studentmundurkeluardo-CanView',$acc))<a class="dropdown-item <?php if(strpos(Request::url(),'setting/studentmundurkeluardo') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('setting/studentmundurkeluardo') }}">Mahasiswa Aktif / Keluar</a>@endif
            @if(in_array('student_password-CanView',$acc))<a class="dropdown-item <?php if(strpos(Request::url(),'setting/student_password') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('setting/student_password') }}">Password Mahasiswa</a>@endif
            {{-- <a class="dropdown-item" href="#" onclick="menukosong()">Kelas Default Mahasiswa</a>
            <a class="dropdown-item" href="#" onclick="menukosong()">Jatah SKS Khusus</a> --}}
            @if(in_array('event_sched-CanView',$acc))<a class="dropdown-item <?php if(strpos(Request::url(),'setting/event_sched') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('setting/event_sched') }}">Jadwal Pengisian</a>@endif
            <!-- @if(in_array('event_sched-CanView',$acc))<a class=" dropdown-item" href="#" onclick="menukosong()">Jadwal Pengisian</a>@endif -->
            <!-- <hr style="display:block; background:white; margin:0px;" > -->
            <!-- @if(in_array('short_term-CanView',$acc))<a class="dropdown-item" href="{{ url('setting/short_term') }}">Option Remidi</a>@endif -->
            {{-- <a class="dropdown-item" href="#" onclick="menukosong()">Option Semester Pendek</a> --}}
            {{-- <a class="dropdown-item" href="#" onclick="menukosong()">Option Remidi 1</a>
            <a class="dropdown-item" href="#" onclick="menukosong()">Option Remidi 2</a> --}}
            <hr style="display:block; background:white; margin:0px;" >
            <!-- @if(in_array('department_lecturer-CanView',$acc))<a class="dropdown-item <?php if(strpos(Request::url(),'setting/department_lecturer') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('setting/department_lecturer') }}">Dosen Prodi</a>@endif -->
            @if(in_array('student_supervision-CanView',$acc))<a class="dropdown-item <?php if(strpos(Request::url(),'setting/student_supervision') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('setting/student_supervision') }}">Bimbingan Mahasiswa</a>@endif
            <hr style="display:block; background:white; margin:0px;" >
            @if(in_array('sched_session-CanView',$acc))<a class="dropdown-item <?php if(strpos(Request::url(),'setting/sched_session') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('setting/sched_session') }}">Sesi Jadwal Kuliah</a>@endif
            @if(in_array('first_sks-CanView',$acc))<a class="dropdown-item <?php if(strpos(Request::url(),'setting/first_sks') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('setting/first_sks') }}">SKS Awal Semester</a>@endif
            @if(in_array('allowed_sks-CanView',$acc))<a class="dropdown-item <?php if(strpos(Request::url(),'setting/allowed_sks') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('setting/allowed_sks') }}">SKS Diijinkan</a>@endif
            @if(in_array('offered_course-CanView',$acc))<a class="dropdown-item <?php if(strpos(Request::url(),'setting/offered_course') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('setting/offered_course') }}">Matakuliah Ditawarkan</a>@endif
            <!-- @if(in_array('offered_course_sched-CanView',$acc))<a class="dropdown-item <?php if(strpos(Request::url(),'setting/offered_course_sched') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('setting/offered_course_sched') }}">Entry Jadwal Kuliah</a>@endif -->
            <!-- <a class="dropdown-item" href="#" onclick="menukosong()">Entry Jadwal Kuliah V2</a> -->
            @if(in_array('offered_course_sched-CanView',$acc))<a class="dropdown-item <?php if(strpos(Request::url(),'setting/offered_course_schedV2') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('setting/offered_course_schedV2') }}">Entry Jadwal Kuliah</a>@endif
            @if(in_array('offered_course_exam-CanView',$acc))<a class="dropdown-item <?php if(strpos(Request::url(),'setting/offered_course_exam') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('setting/offered_course_exam') }}">Jadwal & Peserta Ujian</a>@endif
            <!-- @if(in_array('offered_course_exam-CanView',$acc))<a class=" dropdown-item" href="#" onclick="menukosong()">Jadwal & Peserta Ujian</a>@endif -->
          </div>
        </li>
        @endif
        @if(in_array('krs_matakuliah-CanView',$acc) || in_array('krs_mahasiswa-CanView',$acc) || in_array('khs_matakuliah-CanView',$acc) || in_array('khs_mahasiswa-CanView',$acc) || in_array('transcript_equivalensi-CanView',$acc) || in_array('tugas_akhir-CanView',$acc) || in_array('yudisium-CanView',$acc) || in_array('wisuda-CanView',$acc) || in_array('schedreal-CanView',$acc))
        <li class="nav-item dropdown <?php
            if(
              strpos(Request::url(),'proses/krs_matakuliah') != false ||
              strpos(Request::url(),'proses/krs_mahasiswa') != false ||
              strpos(Request::url(),'proses/khs_matakuliah') != false ||
              strpos(Request::url(),'proses/khs_mahasiswa') != false ||
              strpos(Request::url(),'proses/transcript_equivalensi') != false
            ){ echo 'active' ;};?>">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Proses
          </a>
          <div class="dropdown-menu bg-green dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
            @if(in_array('krs_matakuliah-CanView',$acc))<a class="dropdown-item <?php if(strpos(Request::url(),'proses/krs_matakuliah') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('proses/krs_matakuliah') }}">KRS Per Kelas Matakuliah</a>@endif
            <!-- <a class="dropdown-item" href="#" onclick="menukosong()">KRS Per Mahasiswa</a> -->
            @if(in_array('krs_mahasiswa-CanView',$acc))<a class="dropdown-item <?php if(strpos(Request::url(),'proses/krs_mahasiswa') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('proses/krs_mahasiswa') }}">KRS Per Mahasiswa</a>@endif
            <!-- @if(in_array('krs_paket-CanView',$acc))<a class="dropdown-item <?php if(strpos(Request::url(),'proses/krs_paket') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('proses/krs_paket') }}">KRS Per Paket</a>@endif -->
            @if(in_array('krs_approved-CanView',$acc))<a class="dropdown-item <?php if(strpos(Request::url(),'proses/krs_approved') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('proses/krs_approved') }}">Setujui KRS</a>@endif
            <hr style="display:block; background:white; margin:0px;" >
            <!-- <a class="dropdown-item" href="#" onclick="menukosong()">Nilai Per Kelas Matakuliah</a> -->
            @if(in_array('khs_matakuliah-CanView',$acc))<a class="dropdown-item <?php if(strpos(Request::url(),'proses/khs_matakuliah') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('proses/khs_matakuliah') }}">Nilai Per Kelas Matakuliah</a>@endif
            @if(in_array('khs_mahasiswa-CanView',$acc))<a class="dropdown-item <?php if(strpos(Request::url(),'proses/khs_mahasiswa') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('proses/khs_mahasiswa') }}">Nilai Per Mahasiswa</a>@endif
            @if(in_array('transcript_equivalensi-CanView',$acc))<a class="dropdown-item <?php if(strpos(Request::url(),'proses/transcript_equivalensi') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('proses/transcript_equivalensi') }}">Nilai Mahasiswa Ekuivalensi</a>@endif
              <hr style="display:block; background:white; margin:0px;" >
            @if(in_array('schedreal-CanView',$acc))<a class="dropdown-item <?php if(strpos(Request::url(),'proses/schedreal') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('proses/schedreal') }}">Pertemuan Kuliah</a>@endif
            <!-- <a class="dropdown-item" href="#" onclick="menukosong()">Nilai Mahasiswa Ekuivalensi</a>             -->
            <!-- @if(in_array('transcript_equivalensi-CanView',$acc))<a class="dropdown-item" href="#" onclick="menukosong()">Nilai Mahasiswa Ekuivalensi</a>@endif -->
            {{-- <a class="dropdown-item" href="#" onclick="menukosong()">Edit Transkrip</a> --}}
            <hr style="display:block; background:white; margin:0px;" >

            @if(in_array('krslist-CanView', $acc))<a class="dropdown-item <?php if(strpos(Request::url(),'proses/krslist/index') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('proses/krslist/index') }}">Mahasiswa Kuesioner list</a>@endif
            @if(in_array('berkascuti-CanView',$acc))<a class="dropdown-item <?php if(strpos(Request::url(),'proses/cuti/berkascuti') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('proses/cuti/berkascuti/data') }}">Berkas cuti / aktif kembali</a>@endif
            @if(in_array('cuti-CanView',$acc))<a class="dropdown-item" href="{{ url('proses/cuti') }}">Cuti</a>@endif
            @if(in_array('cuti-CanView',$acc))<a class="dropdown-item" href="{{ url('proses/kembali') }}">Aktif Kembali</a>@endif
            
            {{-- <a class="dropdown-item" href="#" onclick="menukosong()">Kerja Praktek</a> --}}
            @if(in_array('tugas_akhir-CanView',$acc))<a class="dropdown-item <?php if(strpos(Request::url(),'proses/tugas_akhir') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('proses/tugas_akhir') }}">Tugas Akhir</a>@endif
            <!-- <a class=" dropdown-item" href="#" onclick="menukosong()">Tugas Akhir</a> -->
            @if(in_array('yudisium-CanView',$acc))<a class="dropdown-item <?php if(strpos(Request::url(),'proses/yudisium') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('proses/yudisium') }}">Yudisium</a>@endif
            @if(in_array('yudisium-CanView',$acc))<a class="dropdown-item <?php if(strpos(Request::url(),'proses/yudisium/berkasyudisium/data') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('proses/yudisium/berkasyudisium/data') }}">Berkas Yudisium</a>@endif
            <!-- <a class=" dropdown-item" href="#" onclick="menukosong()">Yudisium</a> -->
              <hr style="display:block; background:white; margin:0px;" >
            @if(in_array('periodewisuda-CanView',$acc))<a class=" dropdown-item <?php if(strpos(Request::url(),'proses/periodewisuda') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('proses/periodewisuda') }}">Periode Wisuda</a>@endif

            @if(in_array('wisuda-CanView',$acc))<a class="dropdown-item <?php if(strpos(Request::url(),'proses/wisuda') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('proses/wisuda') }}">Wisuda</a>@endif
            <!-- @if(in_array('schedreal-CanView',$acc))<a class="dropdown-item" href="#" onclick="menukosong()">Pertemuan Kuliah</a>@endif -->
            <!-- @if(in_array('offered_course_exam-CanView',$acc))<a class=" dropdown-item" href="#" onclick="menukosong()">Pertemuan Kuliah</a>@endif -->
          </div>
        </li>
        @endif
        {{-- <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Perubahan Kurikulum
          </a>
          <div class="dropdown-menu bg-green dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
            <a class="dropdown-item" href="#" onclick="menukosong()">Cetak Daftar Ekuivalensi</a>
            <a class="dropdown-item" href="#" onclick="menukosong()">Daftar Daftar Ekuivalensi</a>
          </div>
        </li> --}}
        @if(in_array('transcript_sementara-CanView',$acc) || in_array('transcript_akhir-CanView',$acc) || in_array('presensimhs-CanView',$acc) || in_array('jadwaldanpesertaujian-CanView',$acc))
        <li class="nav-item dropdown <?php
            if(
              strpos(Request::url(),'cetak/transcript_sementara') != false ||
              strpos(Request::url(),'cetak/transcript_akhir') != false ||
              strpos(Request::url(),'cetak/presensimhs') != false ||
              strpos(Request::url(),'cetak/jadwaldanpesertaujian') != false
            ){ echo 'active' ;};?>">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Cetak
          </a>
          <div class="dropdown-menu bg-green dropdown-menu-right dropdown" aria-labelledby="navbarDropdownMenuLink">
            {{-- <a class="dropdown-item" href="#" onclick="menukosong()">KRS</a>
            <a class="dropdown-item" href="#" onclick="menukosong()">KHS</a> --}}
            {{-- <hr style="display:block; background:white; margin:0px;" > --}}
            @if(in_array('peserta_matakuliah-CanView',$acc))<a class="dropdown-item <?php if(strpos(Request::url(),'cetak/peserta_matakuliah') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('cetak/peserta_matakuliah') }}">Cetak Peserta Matakuliah</a>@endif

            @if(in_array('ktm-CanView',$acc))<a class="dropdown-item <?php if(strpos(Request::url(),'cetak/ktm') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('cetak/ktm') }}">Cetak Ktm</a>@endif

            @if(in_array('transcript_sementara-CanView',$acc))<a class="dropdown-item <?php if(strpos(Request::url(),'cetak/transcript_sementara2') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('cetak/transcript_sementara2') }}">Transkrip Sementara</a>@endif
            @if(in_array('transcript_akhir-CanView',$acc))<a class="dropdown-item <?php if(strpos(Request::url(),'cetak/transcript_akhir') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('cetak/transcript_akhir') }}">Transkrip Akhir</a>@endif
            @if(in_array('ijazah-CanView',$acc))<a class="dropdown-item <?php if(strpos(Request::url(),'cetak/ijazah') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('cetak/ijazah') }}">Cetak Ijazah</a>@endif

            <hr style="display:block; background:white; margin:0px;" >
            <!-- @if(in_array('kartuujian-CanView',$acc))<a class="dropdown-item <?php if(strpos(Request::url(),'cetaj/kartuujian') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('cetak/kartuujian') }}">Kartu Ujian Mahasiswa</a>@endif -->
            @if(in_array('presensimhs-CanView',$acc))<a class="dropdown-item <?php if(strpos(Request::url(),'cetak/presensimhs') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('cetak/presensimhs') }}">Presensi Mahasiswa</a>@endif
            @if(in_array('jadwaldanpesertaujian-CanView',$acc))<a class="dropdown-item <?php if(strpos(Request::url(),'cetak/jadwaldanpesertaujian') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('cetak/jadwaldanpesertaujian') }}">Jadwal dan Peserta Ujian</a>@endif

              <!-- <hr style="display:block; background:white; margin:0px;" >
            @if(in_array('panduan-CanView',$acc))<a class="dropdown-item" href="<?php echo env('APP_URL')?>{{ 'panduan/Panduan.pdf' }}" target="_blank" alt="Panduan" Download="Buku Panduan">Download Panduan Simak</a>@endif
            @if(in_array('diagram-CanView',$acc))<a class="dropdown-item" href="<?php echo env('APP_URL')?>{{ 'panduan/Diagram.pdf' }}" target="_blank" alt="Panduan" Download="Diagram Semester Panduan">Download Diagram Persemester</a>@endif -->

            {{-- <hr style="display:block; background:white; margin:0px;" > --}}
            {{-- <a class="dropdown-item" href="#" onclick="menukosong()">Daftar Matakuliah</a>
            <a class="dropdown-item" href="#" onclick="menukosong()">Daftar Matakuliah ditawarkan</a> --}}
            {{-- <hr style="display:block; background:white; margin:0px;" > --}}
            {{-- <a class="dropdown-item" href="#" onclick="menukosong()">Daftar KRS/Nilai Mahasiswa Per Semester</a>
            <a class="dropdown-item" href="#" onclick="menukosong()">Transkrip vs Kurrikulum</a> --}}
            {{-- <hr style="display:block; background:white; margin:0px;" > --}}
            {{-- <a class="dropdown-item" href="#" onclick="menukosong()">Daftar KRS vs Deposit Mahasiswa</a>
            <a class="dropdown-item" href="#" onclick="menukosong()">Daftar Alamat Mahasiswa</a> --}}

            <!-- <a class="dropdown-item dropdown-toggle dropdown-submenu" href="#">Laporan</a>
                <div class="dropdown-menu bg-green dropdown-menu-right">
                  <a class="dropdown-item" href="#" onclick="menukosong()"></a>
                </div> -->
          </div>
        </li>
        @endif
        @if(in_array('laporan_daftar_mahasiswa_krs-CanView',$acc) || in_array('laporan_history_nilaimhs-CanView',$acc) || in_array('laporan_mhskrs-CanView',$acc) || in_array('laporandatamahasiswa-CanView',$acc) || in_array('exportfeeder-CanView',$acc))
        <li class="nav-item dropdown <?php
            if(
              strpos(Request::url(),'laporan/laporan_daftar_mahasiswa_krs') != false ||
              strpos(Request::url(),'laporan/laporan_history_nilaimhs') != false||
                strpos(Request::url(),'laporan/laporan_mhskrs') != false ||
                strpos(Request::url(),'laporan/laporandatamahasiswa') != false ||
                strpos(Request::url(),'laporan/exportfeeder') != false
            ){ echo 'active' ;};?>">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Laporan
          </a>
          <div class="dropdown-menu bg-green dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
            @if(in_array('detail_pengisian_nilai-CanView',$acc))<a class="dropdown-item <?php if(strpos(Request::url(),'laporan/detail_pengisian_nilai') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('laporan/detail_pengisian_nilai') }}">Pengisian Nilai oleh Dosen</a>@endif
            @if(in_array('laporan_daftar_mahasiswa_krs-CanView',$acc))<a class="dropdown-item <?php if(strpos(Request::url(),'laporan/laporan_pembayaran_mahasiswa') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('laporan/laporan_pembayaran_mahasiswa') }}">Pembayaran Mahasiswa</a>@endif
            <!-- @if(in_array('laporan_daftar_mahasiswa_krs-CanView',$acc))<a class="dropdown-item <?php if(strpos(Request::url(),'laporan/laporan_spp') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('laporan/laporan_spp') }}">Laporan Pembayaran SPP</a>@endif -->
            @if(in_array('laporan_daftar_mahasiswa_krs-CanView',$acc))<a class="dropdown-item <?php if(strpos(Request::url(),'laporan/laporan_daftar_mahasiswa_krs') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('laporan/laporan_daftar_mahasiswa_krs') }}">Daftar Mahasiswa KRS</a>@endif
            @if(in_array('laporan_history_nilaimhs-CanView',$acc))<a class="dropdown-item <?php if(strpos(Request::url(),'laporan/laporan_history_nilaimhs') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('laporan/laporan_history_nilaimhs') }}">Histori Nilai Mahasiswa</a>@endif
            @if(in_array('laporan_mhskrs-CanView',$acc))<a class="dropdown-item <?php if(strpos(Request::url(),'laporan/laporan_mhskrs') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('laporan/laporan_mhskrs') }}">Resume Mahasiswa Aktif KRS</a>@endif
            <!-- <a class=" dropdown-item" href="#" onclick="menukosong()">Resume Mahasiswa Aktif KRS</a> -->
            @if(in_array('laporan_dosenmengajar-CanView',$acc))<a class="dropdown-item <?php if(strpos(Request::url(),'laporan/laporan_dosenmengajar') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('laporan/laporan_dosenmengajar') }}">Dosen Mengajar</a>@endif
            @if(in_array('sertifikat-CanView',$acc))<a class="dropdown-item <?php if(strpos(Request::url(),'laporan/sertifikat') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('laporan/sertifikat') }}">Arsip Mahasiswa</a>@endif
            <!-- @if(in_array('laporandatamahasiswa-CanView',$acc))<a class="dropdown-item <?php if(strpos(Request::url(),'laporan/laporandatamahasiswa') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('laporan/laporandatamahasiswa') }}">Laporan Data Mahasiswa</a>@endif -->
            @if(in_array('exportfeeder-CanView',$acc))<a class="dropdown-item <?php if(strpos(Request::url(),'laporan/exportfeeder') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('laporan/exportfeeder') }}">Export</a>@endif
            <a class="dropdown-item <?php if(strpos(Request::url(),'laporan/log_aktivitas') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('laporan/log_aktivitas') }}">Log Aktifitas</a>
            <a id="log_dosen" class="dropdown-item <?php if(strpos(Request::url(),'laporan/login_dosen') != false){ echo 'dropdown-item-active' ;};?>" href="#">Login Dosen</a>
            <a id="log_mhs" class="dropdown-item <?php if(strpos(Request::url(),'laporan/login_mhs') != false){ echo 'dropdown-item-active' ;};?>" href="#">Login Mahasiswa</a>
          </div>
        </li>
        @endif
        <!-- <li class="nav-item dropdown"> -->
          <!-- <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> -->
            <!-- Feeder -->
          <!-- </a> -->
          <!-- <div class="dropdown-menu bg-green dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink"> -->
            <!-- <a class="dropdown-item" href="#" onclick="menukosong()">Konfigurasi WSDL Feeder DIKTI</a> -->
            <!-- <a class="dropdown-item <?php if(strpos(Request::url(),'feeder/conf') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('feeder/conf') }}">Konfigurasi WSDL Feeder DIKTI</a> -->
            <!-- <a class="dropdown-item" href="#" onclick="menukosong()">Sync SIAKAD To Feeder</a> -->
            <!-- <a class="dropdown-item <?php if(strpos(Request::url(),'feeder/sync') != false){ echo 'dropdown-item-active' ;};?>" href="{{ url('feeder/sync') }}">Sync SIAKAD To Feeder</a> -->
          <!-- </div> -->
        <!-- </li> -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-user"></i>
          </a>
          <div class="dropdown-menu bg-green dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                  <div class="navbar-content" style="height:70%;">
                      <div class="row">
                          <div class="col-md-12 col-sm-12 col-xs-12">
                              <center style="padding:15px; color:#032968 ;">{{ Auth::user()->name }}</span><br>
                              <center style="padding:15px; color:#032968 ;">{{ Auth::user()->email }}</span>
                          </div>
                      </div>
                  </div>
                  <div class="navbar-footer" style="background:#00923F; height:30%;">
                      <div class="navbar-footer-content" >
                          <div class="row">
                              <div class="row col-md-12 col-sm-12 col-xs-12">
                                <div class="col-md-6" style="padding:15px 0px 15px 30px;">
                                <center>
                                  <a href="{{ url('administrator/ubahpasswordsaya') }}"  class="btn btn-info btn-flat">
                                      Ubah Password
                                  </a>
                                </center>
                                </div>
                                <div class="col-md-6" style="padding:15px 0px 15px 30px;">
                                  <center>
                                  <a href="{{ route('logout') }}"
                                      class="btn btn-danger btn-flat"
                                      onclick="event.preventDefault();
                                               document.getElementById('logout-form').submit();">
                                      Sign out
                                  </a>
                                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                  </form>
                                  </center>
                                </div>
                              </div>
                          </div>
                      </div>
                  </div>
                  </div>
        </li>

        @else
          <li class="collapse navbar-collapse height">
            {{-- <a class="nav-link" style="color:#1a7a00;" >
              Log In
            </a> --}}
          </li>
        @endif
      </ul>
    </div>
  </nav>

<div class="container-fluid">
        @yield('content')
</div>
<div class="modal fade" id="detail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
<footer class="footer footer-green">
  Copyright © 2022 - All Rights Reserved <br>
  {{env('NAME_Footer')}}
</footer>


  <div id="ubahpembayaran" class="w3-modal">
      <div class="w3-modal-content w3-animate-zoom w3-card-4">
      <header class="w3-container w3-teal"> 
          <span onclick="location.reload()" 
          class="w3-button w3-display-topright">&times;</span>
          <h4>Login Portal Dosen</h4>
      </header>
      <div class="w3-container">
      </br>
      <form id="frm-upload" action="{{ route('course_curriculum.store_silabus') }}" method="POST" enctype="multipart/form-data">
          <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            <div  class="row col-md-10">
              <label class="col-md-4">Email Dosen</label>
              <input type="text" name="email_dosen" id="email_dosen"  value=""  class="form-control form-control-sm col-md-7">              
            </div>
            <div  class="row col-md-10">
              <label class="col-md-4">Password Anda Sekarang</label>
              <input type="password" name="password_anda" id="password_anda"  value=""  class="form-control form-control-sm col-md-7">
            </div>
            <div  class="row col-md-10">
              <label class="col-md-4"></label>  
              <button onclick="log()" type="button" class="btn-success btn-sm form-control form-control-sm col-md-7"name="button"  >Login</button>    
            </div>
            <div  class="row col-md-10">
              <label class="col-md-4"></label>
              <!-- <button value="up" id="bnt-cancel" onclick="" type="submit" class="btn-danger btn-sm btn form-control form-control-sm col-md-7" style="width:80%; margin-top: 2%;">
                  Batal
                </button>       -->
              </div>
            </form>
            </div>
          </br>
      </div>
      </div>
      <table id="eaea">
      </table>
  </div>

  <div id="mahasiswa" class="w3-modal">
      <div class="w3-modal-content w3-animate-zoom w3-card-4">
      <header class="w3-container w3-teal"> 
          <span onclick="location.reload()" 
          class="w3-button w3-display-topright">&times;</span>
          <h4>Login Mahasiswa</h4>
      </header>
      <div class="w3-container">
      </br>
      <form id="frm-upload" action="{{ route('course_curriculum.store_silabus') }}" method="POST" enctype="multipart/form-data">
          <div class="row text-green" style="padding-top:5px;padding-left:15px;padding-right:15px;">
            <div  class="row col-md-10">
              <label class="col-md-4">NIM</label>
              <input type="text" name="username" id="username"  value=""  class="form-control form-control-sm col-md-7">              
            </div>
            <div  class="row col-md-10">
              <label class="col-md-4">Password Anda Sekarang</label>
              <input type="password" name="password_andas" id="password_andas"  value=""  class="form-control form-control-sm col-md-7">
            </div>
            <div  class="row col-md-10">
              <label class="col-md-4"></label>  
              <button onclick="logmhs()" type="button" class="btn-success btn-sm form-control form-control-sm col-md-7"name="button"  >Login</button>    
            </div>
            <div  class="row col-md-10">
              <label class="col-md-4"></label>
              <!-- <button value="up" id="bnt-cancel" onclick="" type="submit" class="btn-danger btn-sm btn form-control form-control-sm col-md-7" style="width:80%; margin-top: 2%;">
                  Batal
                </button>       -->
              </div>
            </form>
            </div>
          </br>
      </div>
      </div>
      <table id="eaea">
      </table>
  </div>
<script type="text/javascript">

  function menukosong(){
      swal('Maaf...!', 'Halaman Masih Dalam Pengerjaan / Perbaikan' , 'warning');
  }

  $('.dropdown-menu a.dropdown-toggle').on('click', function(e) {
    if (!$(this).next().hasClass('show')) {
      $(this).parents('.dropdown-menu').first().find('.show').removeClass("show");
    }
    var $subMenu = $(this).next(".dropdown-menu");
    $subMenu.toggleClass('show');


    $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function(e) {
      $('.dropdown-submenu .show').removeClass("show");
    });


    return false;
  });


$(document).on('click', '#log_dosen', function (e) {
    document.getElementById("ubahpembayaran").style.display = "block";
});

function log() {
    window.open('{{env('SIMDOSEN')}}LoginAdmin?id=<?php if(Auth::check()){echo Auth::user()->id;}else{echo('');} ?>&mail='+$('#email_dosen').val()+'&pass='+$('#password_anda').val() );
    document.getElementById("ubahpembayaran").style.display = "none";
    // $("#fom")[0].reset();
}

$(document).on('click', '#log_mhs', function (e) {
    document.getElementById("mahasiswa").style.display = "block";
});
function logmhs() {
    window.open('{{env('SIMAHASISWA')}}LoginAdmin?id=<?php if(Auth::check()){echo Auth::user()->id;}else{echo('');} ?>&username='+$('#username').val()+'&pass='+$('#password_andas').val() );
    document.getElementById("mahasiswa").style.display = "none";
    // $("#fom")[0].reset();
}
</script>


</body>
</html>
