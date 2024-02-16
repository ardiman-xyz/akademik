<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Input;
use DB;
use Redirect;
use Alert;
use Excel;


class LaporandatamahasiswaController extends Controller
{
  public function __construct()
  {
    $this->middleware('access:CanView', ['only' => ['index','getAll','getDepartment','getEntryyear']]);
    $this->middleware('access:CanExport', ['only' => ['exportdata','laporandata']]);
    // $this->middleware('access:CanAdd', ['except' => ['index','show','edit','update','destroy']]);
    // $this->middleware('access:CanEdit', ['except' => ['index','create','store','show','destroy']]);
    // $this->middleware('access:CanDelete', ['except' => ['index','create','store','show','edit','update']]);

  }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index(Request $request)
     {
      return view('laporan_datamahasiswa/index');
    }

    public function getDepartment(){
      $department = DB::table('mstr_department')
      ->wherenotnull('Faculty_Id')->get();
      header('Content-type: application/json');

        echo json_encode($department);
    }

    public function getEntryyear(){
      $entryyear = DB::table('mstr_entry_year')
      ->orderBy('Entry_Year_Code','desc')
      ->get();
      header('Content-type: application/json');

        echo json_encode($entryyear);
    }

    public function getAll(Request $request){
      $department = $request->Department_Id;
      $entryyear = $request->Entry_Year_Id;
      
      if($department == null){
        if($entryyear == null){
          $num_rows = DB::table('acd_student')
        ->get()->count();
        
        $data = DB::table('acd_student as a')
      ->leftjoin('mstr_gender as b','a.Gender_Id','=','b.Gender_Id')
      ->leftjoin('mstr_class_program as c' ,'a.Class_Prog_Id','=','c.Class_Prog_Id')
      ->orderBy('a.Nim', 'asc');
        }else{
          $num_rows = DB::table('acd_student')
        ->where('Entry_Year_Id',$entryyear)
        ->get()->count();
        
        $data = DB::table('acd_student as a')
      ->leftjoin('mstr_gender as b','a.Gender_Id','=','b.Gender_Id')
      ->leftjoin('mstr_class_program as c' ,'a.Class_Prog_Id','=','c.Class_Prog_Id')
      ->where('a.Entry_Year_Id',$entryyear)
      ->orderBy('a.Nim', 'asc');
        }        
      }else{
        if($entryyear == null){
          $num_rows = DB::table('acd_student')
        ->where('Department_Id',$department)
        ->get()->count();
        
        $data = DB::table('acd_student as a')
      ->leftjoin('mstr_gender as b','a.Gender_Id','=','b.Gender_Id')
      ->leftjoin('mstr_class_program as c' ,'a.Class_Prog_Id','=','c.Class_Prog_Id')
      ->where('a.Department_Id',$department)
      ->orderBy('a.Nim', 'asc');
        }else{
          $num_rows = DB::table('acd_student')
        ->where('Department_Id',$department)
        ->where('Entry_Year_Id',$entryyear)
        ->get()->count();
        
        $data = DB::table('acd_student as a')
      ->leftjoin('mstr_gender as b','a.Gender_Id','=','b.Gender_Id')
      ->leftjoin('mstr_class_program as c' ,'a.Class_Prog_Id','=','c.Class_Prog_Id')
      ->where('a.Department_Id',$department)
      ->where('a.Entry_Year_Id',$entryyear)
      ->orderBy('a.Nim', 'asc');
        }         
      }
      
      
      if ($num_rows > 0) {
        $mhs['data'] = $data->get();
  			$mhs['total'] = $num_rows;
        } else {
          $mhs['data'] = [];
          $mhs['total'] = 0;
        }
        header('Content-type: application/json');

        echo json_encode($mhs);
    }

    public function exportdata($id1, $id2){
      // dd($department);   
      $department = $id1;
      $entryyear = $id2;
      Excel::create('Data Mahasiswa', function ($excel) use($department,$entryyear) {
       if($department == '0'){
        if($entryyear == '0'){        
        $items = DB::table('acd_student as a')
      ->leftjoin('mstr_gender as b','a.Gender_Id','=','b.Gender_Id')
      ->leftjoin('mstr_class_program as c' ,'a.Class_Prog_Id','=','c.Class_Prog_Id')
      ->orderBy('a.Nim', 'asc')
      ->get();
        }else{        
        $items = DB::table('acd_student as a')
      ->leftjoin('mstr_gender as b','a.Gender_Id','=','b.Gender_Id')
      ->leftjoin('mstr_class_program as c' ,'a.Class_Prog_Id','=','c.Class_Prog_Id')
      ->where('a.Entry_Year_Id',$entryyear)
      ->orderBy('a.Nim', 'asc')->get();;
        }        
      }else{
        if($entryyear == '0'){
        $items = DB::table('acd_student as a')
      ->leftjoin('mstr_gender as b','a.Gender_Id','=','b.Gender_Id')
      ->leftjoin('mstr_class_program as c' ,'a.Class_Prog_Id','=','c.Class_Prog_Id')
      ->where('a.Department_Id',$department)
      ->orderBy('a.Nim', 'asc')->get();;
        }else{
        $items = DB::table('acd_student as a')
      ->leftjoin('mstr_gender as b','a.Gender_Id','=','b.Gender_Id')
      ->leftjoin('mstr_class_program as c' ,'a.Class_Prog_Id','=','c.Class_Prog_Id')
      ->where('a.Department_Id',$department)
      ->where('a.Entry_Year_Id',$entryyear)
      ->orderBy('a.Nim', 'asc')->get();
        }         
      }

            if ($items->count() == 0) {
                $data = [
                    [
                        'NO' => '',
                        'NIM' => '',
                        'NAMA' => '',
                        'JENIS KELAMIN' => '',
                        'KELAS PROGRAM' => ''
                    ]
                ];
            }

            $i = 1;
            foreach ($items as $item) {
                $data[] = [
                    'NO' => $i,
                    'NIM' => $item->Nim,
                    'NAMA' => $item->Full_Name,
                    'JENIS KELAMIN' => ($item->Gender_Id == null ? '' : $item->Gender_Type),
                    'KELAS PROGRAM' => $item->Class_Program_Name
                ];

                $i++;
            }

            $excel->sheet('Laporan Data Mahasiswa', function ($sheet) use ($data) {
                $sheet->fromArray($data, null, 'B5');

                $num_rows = sizeof($data) + 5;

                for ($i = 6; $i <= $num_rows; $i++) { 
                    $rows[$i] = 18;
                }

                $rows[5] = 30;

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
                    'B' => 6,
                    'C' => 12,
                    'D' => 30,
                    'E' => 16,
                    'F' => 18,
                ]);

                $sheet->mergeCells('B2:F2');
                $sheet->mergeCells('B3:F3');

                $nameuniv = env('NAME_UNIV');
                $sheet->setCellValue('B2', $nameuniv);
                $sheet->setCellValue('B3', 'DATA MAHASISWA');

                $sheet->cells('B2', function ($cells) {
                    $cells->setAlignment('center'); 
                    $cells->setValignment('center');
                    $cells->setFont([
                        'size' => '15',
                        'bold' => true
                    ]);

                    $cells->setFontFamily('Cambria');
                });

                $sheet->cells('B3', function ($cells) {
                    $cells->setAlignment('center'); 
                    $cells->setValignment('center');
                    $cells->setFont([
                        'size' => '15',
                        'bold' => true
                    ]);

                    $cells->setFontFamily('Cambria');
                });

                $sheet->setHorizontalCentered(true);

                for ($i = 1; $i <= $num_rows; $i++) { 
                    $sheet->row($i, function ($row) {
                        $row->setValignment('center');
                    });
                }
                
                $sheet->setBorder('B5:F' . (sizeof($data) + 5), 'thin');

                $sheet->setHorizontalCentered(true);

                $sheet->cells('B5:F5', function ($cells) {
                    $cells->setBackground('#dddddd');
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });
            });
        })->export('xls');
    }

    public function laporandata($id1, $id2){
      // dd($department);   
      $department = $id1;
      $entryyear = $id2;
      Excel::create('Laporan Data Mahasiswa', function ($excel) use($department,$entryyear) {
       if($department == '0'){
        if($entryyear == '0'){  
        $items = DB::table('mstr_department as a')
                 ->leftjoin('acd_student as b','a.Department_Id','=','b.Department_Id')
                 ->groupby('b.Entry_Year_Id','a.Department_Id')
                 ->wherenotnull('a.Faculty_Id')
                 ->select('a.Department_Name',
                    'a.Department_Id',
                    DB::raw("Count(Student_Id) as jumlah"),
                    // DB::raw("count(Student_Id) as L"),
                    // DB::raw("(SELECT COUNT(acd_student.Gender_Id) FROM acd_student where acd_student.Gender_Id = 2) as P"),
                    'b.Entry_Year_Id')
                 ->orderby('b.Entry_Year_Id','desc')
                 ->get();    
      $years = DB::table('mstr_department as a')
                 ->leftjoin('acd_student as b','a.Department_Id','=','b.Department_Id')
                 ->groupby('b.Entry_Year_Id')
                 ->select('b.Entry_Year_Id')
                 ->wherenotnull('a.Faculty_Id')
                 ->orderby('b.Entry_Year_Id','desc')
                 ->get(); 

      //   $items = DB::table('acd_student as a')
      // ->leftjoin('mstr_gender as b','a.Gender_Id','=','b.Gender_Id')
      // ->leftjoin('mstr_class_program as c' ,'a.Class_Prog_Id','=','c.Class_Prog_Id')
      // ->leftjoin('mstr_department as d','a.Department_Id','=','d.Department_Id')
      // ->orderBy('a.Nim', 'asc')
      // ->get();
        }else{               
            $items = DB::table('mstr_department as a')
                 ->leftjoin('acd_student as b','a.Department_Id','=','b.Department_Id')
                 ->groupby('b.Entry_Year_Id','a.Department_Id')
                 ->where('b.Entry_Year_Id',$entryyear)
                 ->wherenotnull('a.Faculty_Id')
                 ->select('a.Department_Name',
                    'a.Department_Id',
                    DB::raw("Count(Student_Id) as jumlah"),
                    // DB::raw("count(Student_Id) as L"),
                    // DB::raw("(SELECT COUNT(acd_student.Gender_Id) FROM acd_student where acd_student.Gender_Id = 2) as P"),
                    'b.Entry_Year_Id')
                 ->orderby('b.Entry_Year_Id','desc')
                 ->get();    
      $years = DB::table('mstr_department as a')
                 ->leftjoin('acd_student as b','a.Department_Id','=','b.Department_Id')
                 ->groupby('b.Entry_Year_Id')
                 ->wherenotnull('a.Faculty_Id')
                 ->where('b.Entry_Year_Id',$entryyear)
                 ->select('b.Entry_Year_Id')
                 ->orderby('b.Entry_Year_Id','desc')
                 ->get(); 
        }        
      }else{
        if($entryyear == '0'){
            $items = DB::table('mstr_department as a')
                 ->leftjoin('acd_student as b','a.Department_Id','=','b.Department_Id')
                 ->groupby('b.Entry_Year_Id','a.Department_Id')
                 ->where('b.Department_Id',$department)
                 ->wherenotnull('a.Faculty_Id')
                 ->select('a.Department_Name',
                    'a.Department_Id',
                    DB::raw("Count(Student_Id) as jumlah"),
                    // DB::raw("count(Student_Id) as L"),
                    // DB::raw("(SELECT COUNT(acd_student.Gender_Id) FROM acd_student where acd_student.Gender_Id = 2) as P"),
                    'b.Entry_Year_Id')
                 ->orderby('b.Entry_Year_Id','desc')
                 ->get();    
      $years = DB::table('mstr_department as a')
                 ->leftjoin('acd_student as b','a.Department_Id','=','b.Department_Id')
                 ->groupby('b.Entry_Year_Id')
                 ->where('b.Department_Id',$department)
                 ->wherenotnull('a.Faculty_Id')
                 ->select('b.Entry_Year_Id')
                 ->orderby('b.Entry_Year_Id','desc')
                 ->get(); 
        }else{
             $items = DB::table('mstr_department as a')
                 ->leftjoin('acd_student as b','a.Department_Id','=','b.Department_Id')
                 ->groupby('b.Entry_Year_Id','a.Department_Id')
                 ->where('b.Department_Id',$department)
                 ->where('b.Entry_Year_Id',$entryyear)
                 ->wherenotnull('a.Faculty_Id')
                 ->select('a.Department_Name',
                    'a.Department_Id',
                    DB::raw("Count(Student_Id) as jumlah"),
                    // DB::raw("count(Student_Id) as L"),
                    // DB::raw("(SELECT COUNT(acd_student.Gender_Id) FROM acd_student where acd_student.Gender_Id = 2) as P"),
                    'b.Entry_Year_Id')
                 ->orderby('b.Entry_Year_Id','desc')
                 ->get();    
      $years = DB::table('mstr_department as a')
                 ->leftjoin('acd_student as b','a.Department_Id','=','b.Department_Id')
                 ->groupby('b.Entry_Year_Id')
                 ->where('b.Department_Id',$department)
                 ->where('b.Entry_Year_Id',$entryyear)
                 ->wherenotnull('a.Faculty_Id')
                 ->select('b.Entry_Year_Id')
                 ->orderby('b.Entry_Year_Id','desc')
                 ->get(); 
        }
                 
      }

            if ($items->count() == 0) {
                $data = [
                    [
                        'NO' => '',
                        'Angkatan' => '',
                        'Prodi' => '',
                        'Jumlah mahasiswa' => '',
                        'P' => '',
                        'L' => ''
                    ]
                ];
            }

            $i = 1;
            // $tahun = (array) $years;
            foreach ($items as $item) {
              // if($item->Entry_Year_Id){
                $data[] = [
                    'NO' => $i,
                    'Angkatan' => $item->Entry_Year_Id,
                    'Prodi' => $item->Department_Name,
                    'Jumlah mahasiswa' => $item->jumlah,
                    'P' => DB::table('acd_student')->where([['Department_Id',$item->Department_Id],['Entry_Year_Id',$item->Entry_Year_Id],['Gender_Id',1]])->count(),
                    'L' => DB::table('acd_student')->where([['Department_Id',$item->Department_Id],['Entry_Year_Id',$item->Entry_Year_Id],['Gender_Id',2]])->count()
                ];
              // }else{
              //   $data[] = [
              //       'NO' => null,
              //       'Angkatan' => null,
              //       'Prodi' => null,
              //       'Jumlah mahasiswa' => DB::table('acd_student')->where([['Entry_Year_Id',$item->Entry_Year_Id]])->count(),
              //       'L' => DB::table('acd_student')->where([['Entry_Year_Id',$item->Entry_Year_Id],['Gender_Id',1]])->count(),
              //       'P' => DB::table('acd_student')->where([['Entry_Year_Id',$item->Entry_Year_Id],['Gender_Id',2]])->count()
              //   ];
              // }
              $i++;

            }

            $excel->sheet('Laporan Data Mahasiswa', function ($sheet) use ($data) {
                $sheet->fromArray($data, null, 'B5');

                $num_rows = sizeof($data) + 5;

                for ($i = 6; $i <= $num_rows; $i++) { 
                    $rows[$i] = 18;
                }

                $rows[5] = 30;

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
                    'B' => 6,
                    'C' => 12,
                    'D' => 60,
                    'E' => 18,
                    'F' => 10,
                    'G' => 10,
                ]);

                $sheet->mergeCells('B2:G2');
                $sheet->mergeCells('B3:G3');
                
                $nameuniv = env('NAME_UNIV');
                $sheet->setCellValue('B2', $nameuniv);
                $sheet->setCellValue('B3', 'DATA MAHASISWA');

                $sheet->cells('E6:G1000', function ($cells) {
                    $cells->setAlignment('center'); 
                    $cells->setValignment('center');
                });

                $sheet->cells('B2', function ($cells) {
                    $cells->setAlignment('center'); 
                    $cells->setValignment('center');
                    $cells->setFont([
                        'size' => '15',
                        'bold' => true
                    ]);

                    $cells->setFontFamily('Cambria');
                });

                $sheet->cells('B3', function ($cells) {
                    $cells->setAlignment('center'); 
                    $cells->setValignment('center');
                    $cells->setFont([
                        'size' => '15',
                        'bold' => true
                    ]);

                    $cells->setFontFamily('Cambria');
                });

                $sheet->setHorizontalCentered(true);

                for ($i = 1; $i <= $num_rows; $i++) { 
                    $sheet->row($i, function ($row) {
                        $row->setValignment('center');
                    });
                }
                
                $sheet->setBorder('B5:G' . (sizeof($data) + 5), 'thin');

                $sheet->setHorizontalCentered(true);

                $sheet->cells('B5:G5', function ($cells) {
                    $cells->setBackground('#dddddd');
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });
            });
        })->export('xls');
    }

}
