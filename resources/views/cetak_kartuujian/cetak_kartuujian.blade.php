<!DOCTYPE html>
<html>

<style>
    html {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }

    *, *, *:before, *:after {
        -webkit-box-sizing: inherit;
        -moz-box-sizing: inherit;
        box-sizing: inherit;
    }

    .container {
        /*width: 25cm;*/
        text-align: center;
        font-size: 12px;
    }

    .column-25 {
        float: right;
        position: relative;
        width: 42%;
    }
    .column25 {
        float: left;
        position: relative;
        width: 42%;
    }

    #footer {
        padding-top: 40px;
        font-size: 12px;
        text-align: left;
        display: block;
    }

    .aParent div {
        float: left;
        clear: none;
        padding: 30px;
        padding-top: 10px;
        padding-bottom: 10px;
    }

    .page_break { page-break-after: always; }

      @page {  size: A4; margin: 20px 60px -10px 60px;}
</style>

<?php
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
function tanggal_indo2($tanggal, $cetak_hari = false)
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
	$tgl_indo = $split[2] . '-' . $split[1]  . '-' . $split[0];

	if ($cetak_hari) {
		$num = date('N', strtotime($tanggal));
		return $hari[$num] . ', ' . $tgl_indo;
	}
	return $tgl_indo;
}
?>

@if (count($data) != 0)
    @foreach ($data as $item)
    <?php 
        $std_data = DB::table('acd_student as a')
        ->where('a.Student_Id',$item[0]->Student_Id)
        ->join('mstr_department as b','a.Department_Id','=','b.Department_Id')
        ->first();

        $cektagihan = DB::table('fnc_student_bill')->where('Register_Number',$std_data->Register_Number)->where('Payment_Order',2)->get();
        $cekpembayaran = DB::table('fnc_student_payment')->where('Register_Number',$std_data->Register_Number)->where('Installment_Order',2)->get();
        $tgh = 0;
        $cekdetailtagihan = [];
        $tagihan_now = 0;
        if(count($cektagihan) > 0){
          foreach ($cektagihan as $cektgh) {          
            $cekdetailtagihan = DB::table('fnc_student_bill_detail')->where('Student_Bill_Id',$cektgh->Student_Bill_Id)->get();
            if(count($cekpembayaran) > 0){
              foreach ($cekpembayaran as $cekby) {
                  $tagihan_now = $cekby->Payment_Amount - $cekdetailtagihan[0]->Amount;
                }
              }else{
                $tagihan_now = $cekdetailtagihan[0]->Amount;
              }
            $tgh++;
          }
        }
    ?>
<head>
</head>
<body>
    <div id="header">
      <div id="header">
       <table>
        <tr>
          <td>
            <img src="http://akademik-umkendari.utc-umy.id/img/logo_univ.png" style="width:7%" alt="">
          </td>
          <td></td>
          <td>
            <label class="col-md-8" class="vertical-align: text-top;" style="font-family: Arial, Helvetica, serif ;"><b>UNIVERSITAS MUHAMMADIYAH KENDARI <br>KARTU UJIAN</b></label>
          </td>
        </tr>
       </table>
    </div>
    </div>
    <br>
    <br>
<div id="header">
    <div class="aParent">
        <div>
            @if (strtoupper($examTypeName->Exam_Type_Code == 'UTS'))
                <table border="1" cellspacing="0" cellpadding="2" style="width:35%; font-size:11px;">
                    <tbody>
                    <tr>
                        <td>KARTU UJIAN TENGAH SEMESTER</td>
                    </tr>
                    <tr>
                        <td>TAHUN AKADEMIK : {{ $termyear->Term_Year_Name }}
                        </td>
                    </tr>
                    </tbody>
                </table>
                <center><h5></h5></center>
            @elseif(strtoupper($examTypeName->Exam_Type_Code == 'UAS'))
                <table border="1" cellspacing="0" cellpadding="2" style="width:35%; font-size:11px;">
                    <tbody>
                    <tr>
                        <td>KARTU UJIAN AKHIR SEMESTER</td>
                    </tr>
                    <tr>
                        <td>TAHUN AKADEMIK : {{ $termyear->Term_Year_Name }}</td>
                    </tr>
                    </tbody>
                </table>
                <center><h5></h5></center>
                @elseif(strtoupper($examTypeName->Exam_Type_Code == 'REMIDI'))
                <table border="1" cellspacing="0" cellpadding="2" style="width:35%; font-size:11px;">
                    <tbody>
                    <tr>
                        <td>KARTU UJIAN REMIDI</td>
                    </tr>
                    <tr>
                        <td>TAHUN AKADEMIK : {{ $termyear->Term_Year_Name }}</td>
                    </tr>
                    </tbody>
                </table>
                <center><h5></h5></center>
            @endif
        </div>
        <div>
            <p></p>
        </div>
        <div>
            <table style="width:100%; font-size:12px;">
                @if (count($item) != 0)
                    <tr>
                        <td width="15%">Nama Mahasiswa</td>
                        <td width="60%"> : {{ $std_data->Full_Name }}
                            <!-- @if($tagihan_now > 0)
                            *
                            @endif -->
                        </td>
                    </tr>
                    <tr>
                        <td width="15%">NIM</td>
                        <td width="60%"> : {{ $std_data->Nim }}</td>
                    </tr>
                    <tr>
                        <td width="15%">Program Studi</td>
                        <td width="60%"> : {{ $std_data->Department_Name }}</td>
                    </tr>
                    <!-- <tr>
                        <td width="15%">Tahun Ajaran</td>
                        <td width="60%"> : {{ $termyear->Term_Year_Name }}</td>
                    </tr> -->
                @endif
            </table>
        </div>
    </div>
    <br>
    <br>
</div>

<div id="content">
    <table border="1px" style="border-collapse : collapse; width:100%; font-size:13px;  margin: 30px 0px 0px 0px">
        <thead>
        <tr>
            <th style="width:2px;" align="center">No</th>
            <th style="width:30px;" align="center">Waktu</th>
{{--            <th style="width:40px;" align="center">Kode</th>--}}
            <th style="width:130px;" align="center">Mata Kuliah</th>
            <th style="width:30px;" align="center">Kelas</th>
            <th style="width:30px;" align="center">Ruangan</th>
            <th style="width:50px;" align="center">Jam</th>
            <th style="width:50px;" align="center">Ttd</th>
        </tr>
        </thead>
        <tbody>

        

            <?php $no = 1; 
            ?>
                @if(count($item) == 0)
                @else
                @foreach ($item as $key)  
                <?php 
                if($key->Exam_Start_Date == "" || $key->Exam_Start_Date == null){
                    $startdate = "";
                }else{
                    $startdate = date('H:i', strtotime($key->Exam_Start_Date)); 
                }
                ?>
                <tr>
                    <td style="" align="center">{{ $no++ }}</td>
                    <td style="width:80px;"
                        align="center">                                                                              
                        <?php 
                        if($key->Exam_Start_Date == "" || $key->Exam_Start_Date == null){
                            $s_time = "";
                        }else{
                            $start = explode(" ",$key->Exam_Start_Date);
                            $s_date = $start[0];
                            $s_time = explode(":",$start[1]);
                            unset($s_time[1]);
                            $s_time = implode(".",$s_time);
                        }

                          $dates = strtotime($date);
                          $da = Date('Y-m-d',$dates);
                          $datenow = tanggal_indo($da,false);

                          $id_dosen = explode('|',$key->id_dosen);
                          $name_dosen = '';
                            // dd($data);
                            foreach ($id_dosen as $keydsn) {
                                if ($keydsn != null) {
                                  $anu = DB::table('emp_employee')->where('acd_department_lecturer.Employee_Id',$keydsn)
                                  ->join('acd_department_lecturer','acd_department_lecturer.Employee_Id','=','emp_employee.Employee_Id')
                                  ->select('acd_department_lecturer.Department_Id','acd_department_lecturer.Employee_Id')
                                  ->first();
                                    $dosennya = DB::table('emp_employee')->where('Employee_Id',$anu->Employee_Id)->first();
                                    $firstitle = $dosennya->First_Title;
                                    $name = $dosennya->Name;
                                    $lasttitle = $dosennya->Last_Title;

                                    $lnm = strtolower($name); $ucnm = ucwords($lnm);
                                    $name_dosen = $name_dosen.' '.$firstitle." ".$ucnm." ".$lasttitle;
                                }
                            }
                        ?>
                        {{ tanggal_indo2($s_date,true) }}
                        </td>

                    <?php 
                    $totalpertemuan = DB::table('acd_sched_real')
                        ->where('Course_Id',$key->Course_Id)
                        ->where('Term_Year_Id',$key->Term_Year_Id)
                        ->where('Class_Prog_Id',$key->Class_Prog_Id)
                        ->where('Class_Id',$key->Class_Id)
                        ->count();  
                    $totalpertemuans = DB::table('acd_sched_real')
                        ->where('Course_Id',$key->Course_Id)
                        ->where('Term_Year_Id',$key->Term_Year_Id)
                        ->where('Class_Prog_Id',$key->Class_Prog_Id)
                        ->where('Class_Id',$key->Class_Id)
                        ->get();  
                    
                    $t_p = 0;
                    foreach ($totalpertemuans as $keys) {
                        $total = DB::table('acd_sched_real_detail')
                        ->where([['Sched_Real_Id',$keys->Sched_Real_Id],['Student_Id',$key->Student_Id]])
                        ->first();
                        if($total != null){
                            $t_p++;
                        }
                    }

                    if($totalpertemuan <= 0){
                    $persen = 0;
                    }else{
                    $persen = round(($t_p/$totalpertemuan) * 100,2);
                    }
                    ?>
                    <td style="width:100px;" align="left">{{$key->Course_Name}} ({{$persen}}%)
                    <!-- <br>{{$name_dosen}} -->
                    </td>
                    <td style="width:30px;" align="center">{{$key->Class_Name}}</td>
                    <td style="width:30px;" align="center">{{$key->Room_Code}}</td>
                    <td style="width:50px;"
                        align="center">{{ $startdate }}</td>
                    <?php 
                        $totalpertemuan = DB::table('acd_sched_real')
                        ->where('Course_Id',$key->Course_Id)
                        ->where('Term_Year_Id',$key->Term_Year_Id)
                        ->where('Class_Prog_Id',$key->Class_Prog_Id)
                        ->where('Class_Id',$key->Class_Id)
                        ->count();  
                        $totalpertemuans = DB::table('acd_sched_real')
                        ->where('Course_Id',$key->Course_Id)
                        ->where('Term_Year_Id',$key->Term_Year_Id)
                        ->where('Class_Prog_Id',$key->Class_Prog_Id)
                        ->where('Class_Id',$key->Class_Id)
                        ->get();  

                        $Register_Number = DB::table('acd_student')->where('Student_Id',$key->Student_Id)->select('Register_Number')->first();

                        $studentbill = DB::select('CALL usp_GetStudentBill(?,?,?)',array($Register_Number->Register_Number,'',''));
                            $i = 0;
                            $ListTagihan = [];
                            $total=0;
                            $biaya = 0;
                            if($studentbill!=null){
                            foreach ($studentbill as $keyx) if($keyx->Term_Year_Bill_id == $key->Term_Year_Id){
                                $ListTagihan[$i]['Amount'] = $keyx->Amount;
                                $ListTagihan[$i]['Cost_Item_Name'] = $keyx->Cost_Item_Name;
                                $ListTagihan[$i]['Cost_Item_Id'] = $keyx->Cost_Item_Id;
                                $i++;
                            }

                            $sumAmount =0;
                                    foreach ($ListTagihan as $tagihan) {
                                    $sumAmount += $tagihan['Amount'];
                                    }
                            $biaya = number_format($sumAmount,'0',',','.');
                        }

                        $t_p = 0;
                        foreach ($totalpertemuans as $keys) {
                            $total = DB::table('acd_sched_real_detail')
                            ->where([['Sched_Real_Id',$keys->Sched_Real_Id],['Student_Id',$key->Student_Id]])
                            ->first();
                            if($total != null){
                                $t_p++;
                            }
                        }

                        if($totalpertemuan <= 0){
                        $persen = 0;
                        }else{
                        $persen = round(($t_p/$totalpertemuan) * 100,2);
                        }
                    ?>
                    <td height="20" style="<?php if(strtoupper($examTypeName->Exam_Type_Code == 'UAS')){ if($key->Offered_Course_Exam_Id != null){if($persen < 75 || $biaya > 0 ){ ?>background-color:black;color:white;<?php }else{ ?>background-color:none;<?php }}} ?>">
                    @if(strtoupper($examTypeName->Exam_Type_Code == 'UAS'))
                    @if($key->Offered_Course_Exam_Id != null)
                    @if($persen < 75)
                    *
                    @elseif($biaya > 0)
                    **
                    @elseif($biaya > 0 && $persen < 75)
                    ***
                    @endif
                    @endif
                    @endif</td>
                </tr>
                @endforeach
            @endif
            
        </tbody>

        <?php
        $cekbiaya = DB::table('acd_student_krs')
        ->where('Is_Remediasi',1)
        ->where('Student_Id',$std_data->Student_Id)
        ->get();
        $totalbiayaremidi = 0;

        $a = 1;
        foreach ($cekbiaya as $key) {
            $totalbiayaremidi = $totalbiayaremidi + $key->Amount_Rem;
            $a++;
        }

        $sudahdibayar = DB::table('fnc_student_payment')
        ->where('Register_Number',$std_data->Register_Number)
        ->where('Is_Remediasi',1)
        ->get();

        if($sudahdibayar->count() > 0){
            $s = 1;
            $sdh_bayar = 0;
            foreach ($sudahdibayar as $keys) {
            $sdh_bayar = $sdh_bayar + $keys->Payment_Amount;
            }
        }else{
            $sdh_bayar = 0;
        }

        if($cekbiaya->count()){
            $tagihan = $sdh_bayar - $totalbiayaremidi;
        }else{
            $tagihan = 'Data Not Fount';
        }
        ?>
        <p>Kartu ini harus dibawa pada saat ujian. Mohon memperhatikan tata tertib ujian.</p>
        @if($tagihan < 0)
        <p style="color:red">Tagihan Remidi = {{$tagihan}}</p>
        @endif
    </table>

    <table style="width:100%; text-align: right; font-size: 12px">
        <tr>
            <td style="width:20%"></td>
            <td style="width:25%"></td>
            <td style="width:10%"></td>
            <td style="width:35%">
                <center><label>Yogyakarta, {{ $date }}<br>Ka. Bag. Akademik</label><br>
            </td>
            <td style="width:10%"></td>
        </tr>
        <tr>
            <td style="width:20%">
              @if(is_file(public_path('foto_mhs/'.$std_data->Entry_Year_Id.'/'.$std_data->Nim.'.jpg')))
                <img width="113" height="151" src="<?php echo env('APP_URL')?>{{ 'foto_mhs/'.$std_data->Entry_Year_Id.'/'.$std_data->Nim.'.jpg' }}" alt="">
              @else
                <img style="align:center;" width="113" height="151" src="<?php echo env('APP_URL')?>{{ 'img/noimage.png' }}" alt="">
              @endif
            </td>
            <td style="width:25%"></td>
            <td style="width:10%"></td>
            <td style="width:35%">
                <center><label>
                </label><br>
            </td>
            <td style="width:10%"></td>
        </tr>
    </table>
    <br>
      @if($examTypeName->Exam_Type_Code == 'UAS')
        * &nbsp;&nbsp;&nbsp;&nbsp;Presensi Kurang Dari 75%<br>
        ** &nbsp;&nbsp;Masih ada tagihan<br>
        *** Presensi Kurang Dari 75% dan  Masih ada tagihan
    @endif
</div>
<!-- <p>@if($tagihan_now > 0)
    * Masih memiliki tagihan pembayaran. 
    @endif</p> -->
    <!-- <p style="font-size: 8px" align="center">TATA TERTIB UTS DAN UAS<br>
        INSTITUT TEKNOLOGI NASIONAL YOGYAKARTA
    </p>
    <i style="font-size: 8px">
        <ol>1.	Peserta ujian adalah mahasiswa ITNY yang terdaftar sebagai mahasiswa aktif pada semester berjalan dan telah memiliki kartu ujian yang telah disahkan Bagian Akademik.</ol>
        <ol>2.	Setiap kali mengikuti ujian, peserta diwajibkan membawa kartu ujian dan KTM, serta menempati ruang dan kursi yang telah ditentukan.</ol>
        <ol>3.	Setiap peserta ujian wajib untuk menandatangani daftar hadir ujian (rangkap 2) dengan menunjukan kartu ujian yang disertai foto yang telah disahkan di Bagian Akademik kepada pengawas ujian.</ol>
        <ol>4.	Peserta ujian berlaku sopan selama ujian berlangsung. Dilarang memakai sandal, kaos tanpa krah, jaket, topi, celana sobek, merokok dan lain-lain.</ol>
        <ol>5.	Peserta ujian dilarang menggunakan laptop/kamera/alat sejenis/alat komunikasi apapun di ruang ujian.</ol>
        <ol><b>6.	Peserta ujian hadir 10 menit sebelum ujian dimulai. Bagi peserta yang terlambat lebih dari 30 menit tidak diperbolehkan mengikuti ujian.<b></ol>
        <ol>7.	Sebelum ujian dimulai, buku, catatan, dan alat-alat lain yang tidak diperlukan diletakkan di depan ruang ujian kecuali bersifat ujian terbuka.</ol>
        <ol>8.	Peserta ujian yang tidak mengikuti ujian pada waktunya, tidak diadakan ujian tersendiri ataupun ujian susulan, kecuali karena menjalankan tugas dari ITNY.</ol>
        <ol>9.	Selama ujian berlangsung setiap peserta ujian tidak diperkenankan berbicara dengan sesama peserta ujian, saling meminjamkan catatan/peralatan ujian berbuat curang, mencontek dan meninggalkan ruang ujian.</ol>
        <ol>10.	Peserta yang mengundurkan diri diharuskan menyerahkan pekerjaannya kepada pengawas ujian berikut soal ujian dan tetap diwajibkan menandatangani presensi daftar hadir, soal ujian boleh diminta kembali setelah ujian berakhir.</ol>
        <ol>11.	Setelah ujian berakhir setiap peserta diwajibkan segera menyerahkan kertas pekerjaannya kepada pengawas, pekerjaan yang diserahkan di luar ruang ujian dinyatakan tidak sah (tidak berlaku)</ol>
        <ol>12.	Pengawas mempunyai wewenang untuk menegur, memperingatkan dan mencatat kecurangan yang dilakukan oleh peserta ujian, serta berhak memindahkan tempat ujian bila dipandang perlu.</ol>
        <ol>13.	Bagi peserta ujian yang melanggar tata tertib akan dikenai sanksi akademik.</ol>
    </i>
    <p align="center" style="font-size: 8px">Demikian tata tertib ini dibuat dan dilaksanakan dengan penuh rasa tanggungjawab, agar tidak merugikan peserta ujian sendiri.<br>
        BAGIAN AKADEMIK</p> -->
<div class="page_break"></div>
</body>
    @endforeach
@endif
</html>
