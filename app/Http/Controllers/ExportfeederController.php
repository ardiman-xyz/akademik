<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Input;
use Excel;
use Auth;
use App\GetDepartment;

class ExportfeederController extends Controller
{
      public function __construct()
  {
    $this->middleware('access:CanView', ['only' => ['index','exportdata']]);

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jenis = Input::get('jenis');
        $department = Input::get('prodi');
        $entry_year = Input::get('entry_year');
        $curriculum = Input::get('curriculum');
        $FacultyId = Auth::user()->Faculty_Id;
        $DepartmentId = Auth::user()->Department_Id;

        $select_department = GetDepartment::getDepartment();

        $select_curriculum = DB::table('mstr_curriculum')
       ->orderBy('mstr_curriculum.Curriculum_Name', 'desc')
       ->get();
        $select_entry = DB::table('mstr_entry_year')
       ->orderBy('Entry_Year_Name', 'desc')
       ->get();

        return view('laporan_exportfeeder/index')->with('entry_year',$entry_year)->with('select_entry',$select_entry)->with('curriculum',$curriculum)->with('select_curriculum',$select_curriculum)->with('select_department',$select_department)->with('department',$department)->with('jenis',$jenis);
    }

    public function exportdata($id1, $id2, $id3){
        $jenis = $id1;
        $prodi = $id2;
        $entry = $id3;
        
        if($jenis == 1){
            Excel::create('Laporan Data Mahasiswa', function ($excel) use($prodi, $entry){ 
            if($prodi == 99999){
                if($entry == 1){
                    $items = DB::table('acd_student as a')
                   ->leftjoin('mstr_gender as b','a.Gender_Id','=','b.Gender_Id')
                   ->leftjoin('mstr_class_program as c' ,'a.Class_Prog_Id','=','c.Class_Prog_Id')
                   ->leftjoin('mstr_religion as d','a.Religion_Id','=','d.Religion_Id')
                   ->leftjoin('mstr_department as e','a.Department_Id','=','e.Department_Id')
                   ->leftjoin('mstr_status as f','a.Status_Id','=','f.Status_Id')
                   ->leftjoin('acd_student_address as g','a.Student_Id','=','g.Student_Id')
                   ->leftjoin('mstr_register_status as h','a.Register_Status_Id','=','h.Register_Status_Id')
                    ->select('a.*','b.*','c.*','d.*','e.*','f.*','h.*','g.Student_Address_Id','g.Address_Category_Id','g.City_Id','g.Country_Id','g.District_Id','g.Sub_District','g.Address','g.Zip_Code','g.Rw','g.Rt','g.Phone_Home','g.Description','g.Dusun')
                   ->orderBy('a.Entry_Year_Id', 'desc')
                   ->orderBy('a.Nim', 'asc')
                   ->get();
                }else{
                    $items = DB::table('acd_student as a')
                    ->leftjoin('mstr_gender as b','a.Gender_Id','=','b.Gender_Id')
                    ->leftjoin('mstr_class_program as c' ,'a.Class_Prog_Id','=','c.Class_Prog_Id')
                    ->leftjoin('mstr_religion as d','a.Religion_Id','=','d.Religion_Id')
                    ->leftjoin('mstr_department as e','a.Department_Id','=','e.Department_Id')
                    ->leftjoin('mstr_status as f','a.Status_Id','=','f.Status_Id')
                    ->leftjoin('acd_student_address as g','a.Student_Id','=','g.Student_Id')
                    ->leftjoin('mstr_register_status as h','a.Register_Status_Id','=','h.Register_Status_Id')
                    ->where('a.Entry_Year_Id',$entry)
                    ->select('a.*','b.*','c.*','d.*','e.*','f.*','h.*','g.Student_Address_Id','g.Address_Category_Id','g.City_Id','g.Country_Id','g.District_Id','g.Sub_District','g.Address','g.Zip_Code','g.Rw','g.Rt','g.Phone_Home','g.Description','g.Dusun')
                    ->orderBy('a.Nim', 'asc')->get();
                } 
            }else{
                if($entry == 1){
                    $items = DB::table('acd_student as a')
                   ->leftjoin('mstr_gender as b','a.Gender_Id','=','b.Gender_Id')
                   ->leftjoin('mstr_class_program as c' ,'a.Class_Prog_Id','=','c.Class_Prog_Id')
                   ->leftjoin('mstr_religion as d','a.Religion_Id','=','d.Religion_Id')
                   ->leftjoin('mstr_department as e','a.Department_Id','=','e.Department_Id')
                   ->leftjoin('mstr_status as f','a.Status_Id','=','f.Status_Id')
                   ->leftjoin('acd_student_address as g','a.Student_Id','=','g.Student_Id')
                   ->leftjoin('mstr_register_status as h','a.Register_Status_Id','=','h.Register_Status_Id')
                   ->where('a.Department_Id',$prodi)   
                    ->select('a.*','b.*','c.*','d.*','e.*','f.*','h.*','g.Student_Address_Id','g.Address_Category_Id','g.City_Id','g.Country_Id','g.District_Id','g.Sub_District','g.Address','g.Zip_Code','g.Rw','g.Rt','g.Phone_Home','g.Description','g.Dusun')            
                   ->orderBy('a.Entry_Year_Id', 'desc')
                   ->orderBy('a.Nim', 'asc')
                   ->get();
                }else{
                    $items = DB::table('acd_student as a')
                    ->leftjoin('mstr_gender as b','a.Gender_Id','=','b.Gender_Id')
                    ->leftjoin('mstr_class_program as c' ,'a.Class_Prog_Id','=','c.Class_Prog_Id')
                    ->leftjoin('mstr_religion as d','a.Religion_Id','=','d.Religion_Id')
                    ->leftjoin('mstr_department as e','a.Department_Id','=','e.Department_Id')
                    ->leftjoin('mstr_status as f','a.Status_Id','=','f.Status_Id')
                    ->leftjoin('acd_student_address as g','a.Student_Id','=','g.Student_Id')
                    ->leftjoin('mstr_register_status as h','a.Register_Status_Id','=','h.Register_Status_Id')
                    ->where('a.Department_Id',$prodi)
                    ->where('a.Entry_Year_Id',$entry)
                    ->select('a.*','b.*','c.*','d.*','e.*','f.*','h.*','g.Student_Address_Id','g.Address_Category_Id','g.City_Id','g.Country_Id','g.District_Id','g.Sub_District','g.Address','g.Zip_Code','g.Rw','g.Rt','g.Phone_Home','g.Description','g.Dusun')
                    ->orderBy('a.Nim', 'asc')
                    ->get();
                } 
            }

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

                // if ($cetak_hari) {
                //     $num = date('N', strtotime($tanggal));
                //     return $hari[$num] . ', ' . $tgl_indo;
                // }
                return $tgl_indo;
            }

             if ($items->count() == 0) {
                $data = [
                    [
                        'NO' => '',
                        'NIM' => '',
                        'Nama' => '',
                        'Tempat Lahir' => '',
                        'Tanggal Lahir' => '',
                        'Jenis Kelamin' => '',
                        'Agama' => '',
                        'ID KK' => '',
                        'Jenis Pendaftaran' => '',
                        'Tgl Masuk Kuliah' => '',
                        'Mulai Semester' => '',
                        'Jalan' => '',
                        'RT' => '',
                        'RW' => '',
                        'Dusun' => '',
                        'Kelurahan' => '',
                        'Jenis Tinggal' => '',
                        'Telepon Rumah' =>'',
                        'No HP' => '',
                        'Email' => '',
                        'Terima KPS' => '',
                        'No KPS' => '',
                        'Status' => '',
                        'Nama Ayah' => '',
                        'Tgl Lahir Ayah' => '',
                        'Pendidikan Ayah' => '',
                        'Pekerjaan Ayah' => '',
                        'Penghasilan Ayah' => '',
                        'Nama Ibu' => '',
                        'Tgl Lahir Ibu' => '',
                        'Pendidikan Ibu' => '',
                        'Pekerjaan Ibu' => '',
                        'Penghasilan Ibu' => '',
                        'Nama Wali' => '',
                        'Tgl Lahir Wali' => '',
                        'Pendidikan Wali' => '',
                        'Pekerjaan Wali' => '',
                        'Penghasilan Wali' => '',
                        'NIK' => '',
                        'Kode Prodi' => '',
                    ]
                ];
            }

            $i = 1;
            foreach ($items as $item) {
                $date = strtotime($item->Birth_Date);
                $da = Date('Y-m-d',$date);
                $birth = tanggal_indo($da,true);
                $ayah = DB::table('acd_student_parent')
                      ->leftjoin('mstr_education_type','acd_student_parent.Education_Type_Id','mstr_education_type.Education_Type_Id')
                      ->leftjoin('mstr_job_category','acd_student_parent.Job_Category_Id','mstr_job_category.Job_Category_Id')
                      ->where('Student_Id',$item->Student_Id)
                      ->where('Parent_Type_Id',1)
                      ->first();
                $ibu = DB::table('acd_student_parent')
                      ->leftjoin('mstr_education_type','acd_student_parent.Education_Type_Id','mstr_education_type.Education_Type_Id')
                      ->leftjoin('mstr_job_category','acd_student_parent.Job_Category_Id','mstr_job_category.Job_Category_Id')
                      ->where('Student_Id',$item->Student_Id)
                      ->where('Parent_Type_Id',2)
                      ->first();
                $wali = DB::table('acd_student_parent')
                      ->leftjoin('mstr_education_type','acd_student_parent.Education_Type_Id','mstr_education_type.Education_Type_Id')
                      ->leftjoin('mstr_job_category','acd_student_parent.Job_Category_Id','mstr_job_category.Job_Category_Id')
                      ->where('Student_Id',$item->Student_Id)
                      ->where('Parent_Type_Id',3)
                      ->first();

                    //   dd($item->Entry_Term_Id);
                      if($item->Entry_Term_Id != null){
                        $mstr_term_year = DB::table('mstr_term_year')->where('Term_Year_Id',$item->Entry_Year_Id.$item->Entry_Term_Id)->first();
                        $datekuliah = strtotime($mstr_term_year->Start_Date);
                        $dakuliah = Date('Y-m-d',$datekuliah);
                        $birthkuliah = tanggal_indo($dakuliah,false);
                        $masukkuliah = $birthkuliah;
                      }else{
                        $masukkuliah = '';
                      }
                      
                      $dataortu = [];
                      if($ayah != null){
                        $dateayah = strtotime($ayah->Birth_Date);
                        $daayah = Date('Y-m-d',$dateayah);
                        $birthayah = tanggal_indo($daayah,false);
                        $dataortu['ayah']['name'] = $ayah->Full_Name;
                        $dataortu['ayah']['tgllahir'] = $birthayah;
                        $dataortu['ayah']['pendidikan'] = $ayah->Education_Type_Name;
                        $dataortu['ayah']['pekerjaan'] = $ayah->Job_Category_Name;
                        $dataortu['ayah']['penghasilan'] = $ayah->Income;
                      }else{
                        $dataortu['ayah']['name'] = '';
                        $dataortu['ayah']['tgllahir'] = '';
                        $dataortu['ayah']['tgllahir'] = '';
                        $dataortu['ayah']['pendidikan'] = '';
                        $dataortu['ayah']['pekerjaan'] = '';
                        $dataortu['ayah']['penghasilan'] = '';
                      }
                      if($ibu != null){
                        $dateibu = strtotime($ibu->Birth_Date);
                        $daibu = Date('Y-m-d',$dateibu);
                        $birthibu = tanggal_indo($daibu,false);
                        $dataortu['ibu']['name'] = $ibu->Full_Name;
                        $dataortu['ibu']['tgllahir'] = $birthibu;
                        $dataortu['ibu']['pendidikan'] = $ibu->Education_Type_Name;
                        $dataortu['ibu']['pekerjaan'] = $ibu->Job_Category_Name;
                        $dataortu['ibu']['penghasilan'] = $ibu->Income;
                      }else{
                        $dataortu['ibu']['name'] = '';
                        $dataortu['ibu']['tgllahir'] = '';
                        $dataortu['ibu']['tgllahir'] = '';
                        $dataortu['ibu']['pendidikan'] = '';
                        $dataortu['ibu']['pekerjaan'] = '';
                        $dataortu['ibu']['penghasilan'] = '';
                      }
                      if($wali != null){
                        if($wali->Full_Name != null){
                          $datewali = strtotime($wali->Birth_Date);
                          $dawali = Date('Y-m-d',$datewali);
                          $birthwali = tanggal_indo($dawali,false);
                        }else{
                          $birthwali = '';
                        }
                        $dataortu['wali']['name'] = $wali->Full_Name;
                        $dataortu['wali']['tgllahir'] = $birthwali;
                        $dataortu['wali']['pendidikan'] = $wali->Education_Type_Name;
                        $dataortu['wali']['pekerjaan'] = $wali->Job_Category_Name;
                        $dataortu['wali']['penghasilan'] = $wali->Income;
                      }else{
                        $dataortu['wali']['name'] = '';
                        $dataortu['wali']['tgllahir'] = '';
                        $dataortu['wali']['tgllahir'] = '';
                        $dataortu['wali']['pendidikan'] = '';
                        $dataortu['wali']['pekerjaan'] = '';
                        $dataortu['wali']['penghasilan'] = '';
                      }

                $data[] = [
                    'NO' => $i,
                    'NIM' => $item->Nim,
                    'Nama' => $item->Full_Name,
                    'Tempat Lahir' => $item->Birth_Place,
                    'Tanggal Lahir' => $birth,
                    'Jenis Kelamin' => ($item->Gender_Id == null ? '' : $item->Gender_Type),
                    'Agama' => $item->Religion_Name,
                    'ID KK' => '',
                    'Jenis Pendaftaran' => $item->Register_Status_Name,
                    'Tgl Masuk Kuliah' => $masukkuliah,
                    'Mulai Semester' => $item->Entry_Year_Id.$item->Entry_Term_Id,
                    'Jalan' => ($item->Address == null ? '' : $item->Address),
                    'RT' => $item->Rt,
                    'RW' => $item->Rw,
                    'Dusun' => $item->Dusun,
                    'Kelurahan' => $item->Sub_District,
                    'Kode Pos' => $item->Zip_Code,
                    'Jenis Tinggal' => $item->Residence_Type_Id,
                    'Telepon Rumah' => $item->Phone_Home,
                    'No HP' => $item->Phone_Mobile,
                    'Email' => $item->Email_General,
                    'Terima KPS' => $item->Recieve_Kps,
                    'No KPS' => $item->Kps_Number,
                    'Status' => $item->Status_Id,
                    'Nama Ayah' => $dataortu['ayah']['name'],
                    'Tgl Lahir Ayah' => $dataortu['ayah']['tgllahir'],
                    'Pendidikan Ayah' => $dataortu['ayah']['pendidikan'],
                    'Pekerjaan Ayah' => $dataortu['ayah']['pekerjaan'],
                    'Penghasilan Ayah' => $dataortu['ayah']['penghasilan'],
                    'Nama Ibu' => $dataortu['ibu']['name'],
                    'Tgl Lahir Ibu' => $dataortu['ibu']['tgllahir'],
                    'Pendidikan Ibu' => $dataortu['ibu']['pendidikan'],
                    'Pekerjaan Ibu' => $dataortu['ibu']['pekerjaan'],
                    'Penghasilan Ibu' => $dataortu['ibu']['penghasilan'],
                    'Nama Wali' => $dataortu['wali']['name'],
                    'Tgl Lahir Wali' => $dataortu['wali']['tgllahir'],
                    'Pendidikan Wali' => $dataortu['wali']['pendidikan'],
                    'Pekerjaan Wali' => $dataortu['wali']['pekerjaan'],
                    'Penghasilan Wali' => $dataortu['wali']['penghasilan'],
                    'NIK' => $item->Nik,
                    'Kode Prodi' => $item->Department_Code,
                ];

                $i++;
            }

            $excel->sheet('Data Mahasiswa', function ($sheet) use ($data) {
                $sheet->fromArray($data, null, 'A1');

                $num_rows = sizeof($data) + 1;

                for ($i = 1; $i <= $num_rows; $i++) {
                    $rows[$i] = 18;
                }

                $rows[1] = 30;

                $sheet->setAutoSize(true);

                $sheet->setStyle([
                    'font' => [
                        'name' => 'Arial',
                        'size' => 10
                    ]
                ]);

                $sheet->setAllBorders('none');

                $sheet->setHeight($rows);

                $sheet->setWidth([
                    'A' => 6,
                    'B' => 12,
                    'C' => 12,
                    'D' => 30,
                    'E' => 16,
                    'F' => 18,
                ]);

                $sheet->setHorizontalCentered(true);

                for ($i = 1; $i <= $num_rows; $i++) {
                    $sheet->row($i, function ($row) {
                        $row->setValignment('center');
                    });
                }

                $sheet->setBorder('A1:AO' . (sizeof($data) + 1), 'thin');

                $sheet->setHorizontalCentered(true);

                $sheet->cells('A1:K1', function ($cells) {
                    $cells->setBackground('#FF3939');
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });
                $sheet->cells('L1:O1', function ($cells) {
                    $cells->setBackground('#97D86E');
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });
                $sheet->cells('P1', function ($cells) {
                    $cells->setBackground('#FF3939');
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });
                $sheet->cells('Q1:U1', function ($cells) {
                    $cells->setBackground('#97D86E');
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });
                $sheet->cells('V1', function ($cells) {
                    $cells->setBackground('#FF3939');
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });
                $sheet->cells('W1', function ($cells) {
                    $cells->setBackground('#97D86E');
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });
                $sheet->cells('X1', function ($cells) {
                    $cells->setBackground('#FF3939');
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });
                $sheet->cells('Y1:AC1', function ($cells) {
                    $cells->setBackground('#97D86E');
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });
                $sheet->cells('AD1', function ($cells) {
                    $cells->setBackground('#FF3939');
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });
                $sheet->cells('AE1:AO1', function ($cells) {
                    $cells->setBackground('#97D86E');
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });
                // $sheet->cells('Q1', function ($cells) {
                //     $cells->setBackground('#F0FF00');
                //     $cells->setFontWeight('bold');
                //     $cells->setAlignment('center');
                // });
                // $sheet->cells('R1:T1', function ($cells) {
                //     $cells->setBackground('#97D86E');
                //     $cells->setFontWeight('bold');
                //     $cells->setAlignment('center');
                // });
            });
        })->export('xls');
        }
        elseif($jenis == 2){
            Excel::create('Kelas', function ($excel) use($prodi){       
             $items = DB::table('acd_offered_course as a')
            ->leftjoin('acd_course as b','a.Course_Id','=','b.Course_Id')
            ->leftjoin('mstr_department as c','a.Department_Id','=','c.Department_Id')
            ->join('acd_course_curriculum as d','a.Course_Id','=','d.Course_Id')
            ->leftjoin('mstr_class as e','a.Class_Id','=','e.Class_Id')
            ->where('a.Department_Id',$prodi)
            ->groupBy('a.Course_Id','a.Class_Id')
            ->orderBy('d.Study_Level_Id', 'asc')
            ->orderBy('e.Class_Name', 'asc')->get();

             if ($items->count() == 0) {
                $data = [
                    [
                        'NO' => '',
                        'Kode MK' => '',
                        'Matakuliah' => '',
                        'Semester' => '',
                        'Kelas' => '',
                        'Kode Prodi' => '',
                        'Program Studi' => '',
                    ]
                ];
            }

            $i = 1;
            foreach ($items as $item) {
                $data[] = [
                    'NO' => $i,
                    'Kode MK' => $item->Course_Code,
                    'Matakuliah' => $item->Course_Name,
                    'Semester' => $item->Study_Level_Id,
                    'Kelas' => $item->Class_Name,
                    'Kode Prodi' => $item->Course_Code,
                    'Program Studi' => $item->Department_Name,
                ];

                $i++;
            }

            $excel->sheet('Kelas', function ($sheet) use ($data) {
                $sheet->fromArray($data, null, 'A1');

                $num_rows = sizeof($data) + 1;

                for ($i = 1; $i <= $num_rows; $i++) { 
                    $rows[$i] = 18;
                }

                $rows[1] = 30;

                $sheet->setAutoSize(true);

                $sheet->setStyle([
                    'font' => [
                        'name' => 'Arial',
                        'size' => 10
                    ]
                ]);

                $sheet->setAllBorders('none');

                $sheet->setHeight($rows);

                $sheet->setWidth([
                    'A' => 6,
                    'B' => 12,
                    'C' => 40,
                    'D' => 12,
                    'E' => 16,
                    'F' => 18,
                ]);
                
                $sheet->setHorizontalCentered(true);

                for ($i = 1; $i <= $num_rows; $i++) { 
                    $sheet->row($i, function ($row) {
                        $row->setValignment('center');
                    });
                }
                
                $sheet->setBorder('A1:G' . (sizeof($data) + 1), 'thin');

                $sheet->setHorizontalCentered(true);

                $sheet->cells('A1:G1', function ($cells) {
                    $cells->setBackground('#97D86E');
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });
            });
        })->export('xls');
        }
        elseif($jenis == 3){
            Excel::create('KRS', function ($excel) use($prodi){       
             $items = DB::table('acd_student_krs as a')
             ->join('acd_course_curriculum as b','a.Course_Id','b.Course_Id')
             ->join('mstr_department as c','c.Department_Id','=','b.Department_Id')
             ->join('acd_course as d','a.Course_Id','=','d.Course_Id')
             ->join('acd_offered_course as e','b.Course_Id','=','e.Course_Id')
             ->join('mstr_class as f','e.Class_Id','=','f.Class_Id')
             ->join('acd_student as g','a.Student_Id','=','g.Student_Id')
            ->where('b.Department_Id',$prodi)
            // ->groupBy('a.Krs_Id')
            ->groupBy('e.Course_Id','e.Class_Id')
            ->orderBy('a.Student_Id', 'asc')
            ->orderBy('b.Study_Level_Id', 'asc')->get();

             if ($items->count() == 0) {
                $data = [
                    [
                        'NO' => '',
                        'NIM' => '',
                        'Mahasiswa' => '',
                        'Kode MK' => '',
                        'Nama MK' => '',
                        'Kelas' => '',
                        'Semester' => '',
                        'Kode Prodi' => '',
                        'Program Studi' => '',
                    ]
                ];
            }

            $i = 1;
            foreach ($items as $item) {
                $data[] = [
                    'NO' => $i,
                    'NIM' => $item->Nim,
                    'Mahasiswa' => $item->Full_Name,
                    'Kode MK' => $item->Course_Code,
                    'Nama MK' => $item->Course_Name,
                    'Kelas' => $item->Class_Name,
                    'Semester' => $item->Study_Level_Id,
                    'Kode Prodi' => $item->Department_Code,
                    'Program Studi' => $item->Department_Name,
                ];

                $i++;
            }

            $excel->sheet('KRS', function ($sheet) use ($data) {
                $sheet->fromArray($data, null, 'A1');

                $num_rows = sizeof($data) + 1;

                for ($i = 1; $i <= $num_rows; $i++) { 
                    $rows[$i] = 18;
                }

                $rows[1] = 30;

                $sheet->setAutoSize(true);

                $sheet->setStyle([
                    'font' => [
                        'name' => 'Arial',
                        'size' => 10
                    ]
                ]);

                $sheet->setAllBorders('none');

                $sheet->setHeight($rows);

                $sheet->setWidth([
                    'A' => 6,
                    'B' => 12,
                    'C' => 40,
                    'D' => 12,
                    'E' => 16,
                    'F' => 18,
                ]);
                
                $sheet->setHorizontalCentered(true);

                for ($i = 1; $i <= $num_rows; $i++) { 
                    $sheet->row($i, function ($row) {
                        $row->setValignment('center');
                    });
                }
                
                $sheet->setBorder('A1:I' . (sizeof($data) + 1), 'thin');

                $sheet->setHorizontalCentered(true);

                $sheet->cells('A1:I1', function ($cells) {
                    $cells->setBackground('#97D86E');
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });
            });
        })->export('xls');
        }
        elseif($jenis == 4){
            Excel::create('KHS-Nilai', function ($excel) use($prodi){       
             $items = DB::table('acd_student_khs as h')
             ->leftjoin('acd_student_krs as a','a.Krs_Id','h.Krs_Id')
             ->join('acd_course_curriculum as b','a.Course_Id','b.Course_Id')
             ->join('mstr_department as c','c.Department_Id','=','b.Department_Id')
             ->join('acd_course as d','a.Course_Id','=','d.Course_Id')
             ->join('acd_offered_course as e','b.Course_Id','=','e.Course_Id')
             ->join('mstr_class as f','e.Class_Id','=','f.Class_Id')
             ->join('acd_student as g','a.Student_Id','=','g.Student_Id')
             ->join('acd_grade_department as i','h.Grade_Letter_Id','=','i.Grade_letter_Id')
             ->join('acd_grade_letter as j','h.Grade_letter_Id','=','j.Grade_Letter_Id')
            ->where('b.Department_Id',$prodi)
            ->where('i.Department_Id',$prodi)
            ->groupBy('h.Krs_Id')
            ->orderBy('a.Student_Id', 'asc')
            ->orderBy('b.Study_Level_Id', 'asc')
            ->get();

             if ($items->count() == 0) {
                $data = [
                    [
                        'NO' => '',
                        'NIM' => '',
                        'Mahasiswa' => '',
                        'Kode MK' => '',
                        'Nama MK' => '',
                        'Kelas' => '',
                        'Semester' => '',
                        'Nilai Angka' =>'',
                        'Nilai Huruf' => '',
                        'Nilai Indeks' => '',
                        'Kode Prodi' => '',
                        'Program Studi' => '',
                    ]
                ];
            }

            $i = 1;
            foreach ($items as $item) {
                $data[] = [
                    'NO' => $i,
                    'NIM' => $item->Nim,
                    'Mahasiswa' => $item->Full_Name,
                    'Kode MK' => $item->Course_Code,
                    'Nama MK' => $item->Course_Name,
                    'Kelas' => $item->Class_Name,
                    'Semester' => $item->Study_Level_Id,
                    'Nilai Angka' =>$item->Weight_Value,
                    'Nilai Huruf' => $item->Grade_Letter,
                    'Nilai Indeks' => $item->Bnk_Value,
                    'Kode Prodi' => $item->Department_Code,
                    'Program Studi' => $item->Department_Name,
                ];

                $i++;
            }

            $excel->sheet('KHS-Nilai', function ($sheet) use ($data) {
                $sheet->fromArray($data, null, 'A1');

                $num_rows = sizeof($data) + 1;

                for ($i = 1; $i <= $num_rows; $i++) { 
                    $rows[$i] = 18;
                }

                $rows[1] = 30;

                $sheet->setAutoSize(true);

                $sheet->setStyle([
                    'font' => [
                        'name' => 'Arial',
                        'size' => 10
                    ]
                ]);

                $sheet->setAllBorders('none');

                $sheet->setHeight($rows);

                $sheet->setWidth([
                    'A' => 6,
                    'B' => 12,
                    'C' => 30,
                    'D' => 12,
                    'E' => 30,
                    'F' => 18,
                ]);
                
                $sheet->setHorizontalCentered(true);

                for ($i = 1; $i <= $num_rows; $i++) { 
                    $sheet->row($i, function ($row) {
                        $row->setValignment('center');
                    });
                }
                
                $sheet->setBorder('A1:L' . (sizeof($data) + 1), 'thin');

                $sheet->setHorizontalCentered(true);

                $sheet->cells('A1:L1', function ($cells) {
                    $cells->setBackground('#97D86E');
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });
            });
        })->export('xls');
        }
        elseif($jenis == 5){
            Excel::create('Nilai Transfer', function ($excel) use($prodi){       
             $items = DB::table('acd_transcript as a')
             ->join('acd_student as b','a.Student_Id','=','b.Student_Id')
             ->join('acd_grade_letter as d','a.Grade_Letter_Id','d.Grade_Letter_Id')
             ->join('mstr_department as e','b.Department_Id','=','e.Department_Id')
             ->join('acd_course as f','a.Course_Id','=','f.Course_Id')
             ->leftjoin('acd_course_curriculum as g','a.Course_Id','g.Course_Id')
            ->where('b.Department_Id',$prodi)
            ->where('e.Department_Id',$prodi)
            ->where('b.Nim','LIKE','%P%')
            ->groupby('a.Transcript_Id')
            ->orderBy('b.Nim', 'asc')
            ->orderBy('g.Study_Level_Id', 'asc')
            ->get();

             if ($items->count() == 0) {
                $data = [
                    [
                        'NO' => '',
                        'NIM' => '',
                        'Mahasiswa' => '',
                        'Kode MK Asal' => '',
                        'Nama MK Asal' => '',
                        'Sks Asal' => '',
                        'Nilai Huruf Asal' => '',
                        'Kode MK Diakui' =>'',
                        'Nama MK Diakui' => '',
                        'Nilai Huruf Diakui' => '',
                        'Nilai Angka Diakui' => '',
                        'SKS Diakui' => '',
                        'Kode Prodi' => '',
                        'Program Studi' => '',
                    ]
                ];
            }

            $i = 1;
            foreach ($items as $item) {
                $data[] = [
                    'NO' => $i,
                    'NIM' => $item->Nim,
                    'Mahasiswa' => $item->Full_Name,
                    'Kode MK Asal' => $item->Course_Code_Transfer,
                    'Nama MK Asal' => $item->Course_Name_Transfer,
                    'Sks Asal' => $item->Sks_Transfer,
                    'Nilai Huruf Asal' => $item->Grade_Letter_Transfer,
                    'Kode MK Diakui' =>$item->Course_Code,
                    'Nama MK Diakui' => $item->Course_Name,
                    'Nilai Huruf Diakui' => $item->Grade_Letter,
                    'Nilai Angka Diakui' => $item->Weight_Value,
                    'SKS Diakui' => $item->Sks,
                    'Kode Prodi' => $item->Department_Code,
                    'Program Studi' => $item->Department_Name,
                ];

                $i++;
            }

            $excel->sheet('Nilai Transfer', function ($sheet) use ($data) {
                $sheet->fromArray($data, null, 'A1');

                $num_rows = sizeof($data) + 1;

                for ($i = 1; $i <= $num_rows; $i++) { 
                    $rows[$i] = 18;
                }

                $rows[1] = 30;

                $sheet->setAutoSize(true);

                $sheet->setStyle([
                    'font' => [
                        'name' => 'Arial',
                        'size' => 10
                    ]
                ]);

                $sheet->setAllBorders('none');

                $sheet->setHeight($rows);

                $sheet->setWidth([
                    'A' => 6,
                    'B' => 12,
                    'C' => 30,
                    'D' => 12,
                    'E' => 30,
                    'F' => 18,
                ]);
                
                $sheet->setHorizontalCentered(true);

                for ($i = 1; $i <= $num_rows; $i++) { 
                    $sheet->row($i, function ($row) {
                        $row->setValignment('center');
                    });
                }
                
                $sheet->setBorder('A1:N' . (sizeof($data) + 1), 'thin');

                $sheet->setHorizontalCentered(true);

                $sheet->cells('A1:N1', function ($cells) {
                    $cells->setBackground('#97D86E');
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });
            });
        })->export('xls');
        }
        elseif($jenis == 6){
            Excel::create('Ajar Dosen', function ($excel) use($prodi){       
             $items = DB::table('acd_offered_course as a')
             ->join('acd_offered_course_lecturer as b','a.Offered_Course_id','b.Offered_Course_id')
             ->leftjoin('acd_course as c','a.Course_Id','=','c.Course_Id')
             ->leftjoin('emp_employee as d','b.Employee_Id','=','d.Employee_Id')
             ->leftjoin('acd_course_curriculum as e','a.Course_Id','=','e.Course_Id')
             ->leftjoin('mstr_class as f','a.Class_Id','=','f.Class_Id')
             ->leftjoin('mstr_department as g','a.Department_Id','=','g.Department_Id')
             ->where('a.Department_Id',$prodi)
            //  ->select('c.First_Title','c.LasT(Course_Id) FROM acd_sched_real WHERE a.Course_Id = acd_sched_real.Course_Id) as Jml_temu'))
            //  ->select('b.Nidn','b.First_Title','b.Last_Title','b.Name','d.Applied_Sks','e.Course_Code',
            //             'e.Course_Name','d.Study_Level_Id','f.Class_Name','g.Department_Code','g.Department_Name',
            //             DB::raw('(SELECT COUNT(Course_Id) FROM acd_sched_real WHERE h.Course_Id = acd_sched_real.Course_Id) as Jml_temu'))
            ->groupby('a.Offered_Course_id')
            ->orderBy('e.Study_Level_Id', 'asc')
            ->get();

             if ($items->count() == 0) {                 
                $data = [
                    [
                        'NO' => '',
                        'NIDN' => '',
                        'Dosen' => '',
                        'SKS Ajar' => '',
                        'Rencana Tatapmuka' => '',
                        'Tatap Muka Real' => '',
                        'Kode MK' =>'',
                        'Nama MK' => '',
                        'Semester' => '',
                        'Kelas' => '',
                        'Kode Prodi' => '',
                        'Program Studi' => '',
                    ]
                ];
            }

            $i = 1;
            foreach ($items as $item) {
                $f = $item->First_Title;
                 $l = $item->Last_Title;
                 $n = $item->Name;
                 $fn = $f.' '.$n.' '.$l;
                $data[] = [
                    'NO' => $i,
                    'NIDN' => $item->Nidn,
                    'Dosen' => $fn,
                    'SKS Ajar' => $item->Applied_Sks,
                    'Rencana Tatapmuka' => '',
                    'Tatap Muka Real' => DB::table('acd_sched_real')->where([['Class_Id',$item->Class_Id],['Course_Id',$item->Course_Id]])->count(),
                    'Kode MK' =>$item->Course_Code,
                    'Nama MK' => $item->Course_Name,
                    'Semester' => $item->Study_Level_Id,
                    'Kelas' => $item->Class_Name,
                    'Kode Prodi' => $item->Department_Code,
                    'Program Studi' => $item->Department_Name,
                ];

                $i++;
            }

            $excel->sheet('Ajar Dosen', function ($sheet) use ($data) {
                $sheet->fromArray($data, null, 'A1');

                $num_rows = sizeof($data) + 1;

                for ($i = 1; $i <= $num_rows; $i++) { 
                    $rows[$i] = 18;
                }

                $rows[1] = 30;

                $sheet->setAutoSize(true);

                $sheet->setStyle([
                    'font' => [
                        'name' => 'Arial',
                        'size' => 10
                    ]
                ]);

                $sheet->setAllBorders('none');

                $sheet->setHeight($rows);

                $sheet->setWidth([
                    'A' => 6,
                    'B' => 12,
                    'C' => 40,
                    'D' => 12,
                    'E' => 12,
                    'F' => 12,
                    'I' => 10,
                    'K' => 10,
                ]);
                
                $sheet->setHorizontalCentered(true);

                for ($i = 1; $i <= $num_rows; $i++) { 
                    $sheet->row($i, function ($row) {
                        $row->setValignment('center');
                    });
                }

                $sheet->getStyle('A1:L1')->getAlignment()->setWrapText(true);
                
                $sheet->setBorder('A1:L' . (sizeof($data) + 1), 'thin');

                $sheet->setHorizontalCentered(true);

                $sheet->cells('A1:L1', function ($cells) {
                    $cells->setBackground('#97D86E');
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });
            });
        })->export('xls');
        }        
        elseif($jenis == 8){
            Excel::create('Ajar Dosen', function ($excel) use($prodi){       
             $items = DB::table('acd_offered_course as a')
             ->join('acd_offered_course_lecturer as b','a.Offered_Course_id','b.Offered_Course_id')
             ->leftjoin('acd_course as c','a.Course_Id','=','c.Course_Id')
             ->leftjoin('emp_employee as d','b.Employee_Id','=','d.Employee_Id')
             ->leftjoin('acd_course_curriculum as e','a.Course_Id','=','e.Course_Id')
             ->leftjoin('mstr_class as f','a.Class_Id','=','f.Class_Id')
             ->leftjoin('mstr_department as g','a.Department_Id','=','g.Department_Id')
             ->where('a.Department_Id',$prodi)
            //  ->select('c.First_Title','c.LasT(Course_Id) FROM acd_sched_real WHERE a.Course_Id = acd_sched_real.Course_Id) as Jml_temu'))
            //  ->select('b.Nidn','b.First_Title','b.Last_Title','b.Name','d.Applied_Sks','e.Course_Code',
            //             'e.Course_Name','d.Study_Level_Id','f.Class_Name','g.Department_Code','g.Department_Name',
            //             DB::raw('(SELECT COUNT(Course_Id) FROM acd_sched_real WHERE h.Course_Id = acd_sched_real.Course_Id) as Jml_temu'))
            ->groupby('a.Offered_Course_id')
            ->orderBy('e.Study_Level_Id', 'asc')
            ->get();

             if ($items->count() == 0) {                 
                $data = [
                    [
                        'NO' => '',
                        'NIDN' => '',
                        'Dosen' => '',
                        'SKS Ajar' => '',
                        'Rencana Tatapmuka' => '',
                        'Tatap Muka Real' => '',
                        'Kode MK' =>'',
                        'Nama MK' => '',
                        'Semester' => '',
                        'Kelas' => '',
                        'Kode Prodi' => '',
                        'Program Studi' => '',
                    ]
                ];
            }

            $i = 1;
            foreach ($items as $item) {
                $f = $item->First_Title;
                 $l = $item->Last_Title;
                 $n = $item->Name;
                 $fn = $f.' '.$n.' '.$l;
                $data[] = [
                    'NO' => $i,
                    'NIDN' => $item->Nidn,
                    'Dosen' => $fn,
                    'SKS Ajar' => $item->Applied_Sks,
                    'Rencana Tatapmuka' => '',
                    'Tatap Muka Real' => DB::table('acd_sched_real')->where([['Class_Id',$item->Class_Id],['Course_Id',$item->Course_Id]])->count(),
                    'Kode MK' =>$item->Course_Code,
                    'Nama MK' => $item->Course_Name,
                    'Semester' => $item->Study_Level_Id,
                    'Kelas' => $item->Class_Name,
                    'Kode Prodi' => $item->Department_Code,
                    'Program Studi' => $item->Department_Name,
                ];

                $i++;
            }

            $excel->sheet('Ajar Dosen', function ($sheet) use ($data) {
                $sheet->fromArray($data, null, 'A1');

                $num_rows = sizeof($data) + 1;

                for ($i = 1; $i <= $num_rows; $i++) { 
                    $rows[$i] = 18;
                }

                $rows[1] = 30;

                $sheet->setAutoSize(true);

                $sheet->setStyle([
                    'font' => [
                        'name' => 'Arial',
                        'size' => 10
                    ]
                ]);

                $sheet->setAllBorders('none');

                $sheet->setHeight($rows);

                $sheet->setWidth([
                    'A' => 6,
                    'B' => 12,
                    'C' => 40,
                    'D' => 12,
                    'E' => 12,
                    'F' => 12,
                    'I' => 10,
                    'K' => 10,
                ]);
                
                $sheet->setHorizontalCentered(true);

                for ($i = 1; $i <= $num_rows; $i++) { 
                    $sheet->row($i, function ($row) {
                        $row->setValignment('center');
                    });
                }

                $sheet->getStyle('A1:L1')->getAlignment()->setWrapText(true);
                
                $sheet->setBorder('A1:L' . (sizeof($data) + 1), 'thin');

                $sheet->setHorizontalCentered(true);

                $sheet->cells('A1:L1', function ($cells) {
                    $cells->setBackground('#97D86E');
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });
            });
        })->export('xls');
        }
        else{

        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
