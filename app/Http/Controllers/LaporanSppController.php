<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\Registerst5s;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Input;
use DB;
use Redirect;
use Alert;
use PDF;
use Auth;
use Excel;
use App\Exports\Daftar_mhskrs;
use App\Exports\Daftar_mhskrsnonaktif;
use App\Exports\Daftar_mhskrscuti;
use App\Mhs_krs;
use App\GetDepartment;

class LaporanSppController extends Controller
{
  public function __construct()
  {
    // $this->middleware('access:CanView', ['only' => ['index','show']]);
    // $this->middleware('access:CanViewnonaktif', ['only' => ['showmhsnonaktif']]);
    // $this->middleware('access:CanExport', ['only' => ['exportexcelnonaktif','exportexcel']]);
  }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $thsemester = Input::get('thsemester');

      $term_year1 = Input::get('term_year');
       if($term_year1 == null){
        $Term_Year_Id =  $request->session()->get('term_year');
       }else{
        $Term_Year_Id = Input::get('term_year');
       }

      $Class_Prog_Id = Input::get('prog_kelas');
      $entry_year = Input::get('angkatan');
      $FacultyId = Auth::user()->Faculty_Id;
      $DepartmentId = Auth::user()->Department_Id;

      $select_term_year = DB::table('mstr_term_year')
      ->orderBy('mstr_term_year.Term_Year_Name', 'desc')
      ->get();

      $select_class_program = DB::table('mstr_department_class_program')
      ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','mstr_department_class_program.Class_Prog_Id')
      ->groupBy('mstr_class_program.Class_Program_Name')
      ->orderBy('mstr_class_program.Class_Program_Name', 'desc')
      ->get();

      $select_entry_year = DB::table('mstr_entry_year')
      ->orderBy('Entry_Year_Name','desc')->get();

      // $query = DB::table('mstr_department')->get();

      $select_department = GetDepartment::getDepartment();

      if($FacultyId == ""){
        if($DepartmentId == ""){
            if ($Term_Year_Id==null) {
                $query = DB::table('mstr_department as md')->wherenotnull('md.Faculty_Id')
                    ->join('mstr_faculty as mf','md.Faculty_Id','=','mf.Faculty_Id')
                    ->select(DB::Raw('
                        md.Department_Id as dep_Id,
                        md.Department_Name as Prodi,
                        mf.Faculty_Name as Fak,
                        md.Department_Id as dep_Id,
                        md.Department_Name as Prodi,
                        (0) as Total,
                        (0) as Total_L,
                        (0) as Total_P
                    '))
                    ->get();
            }elseif($Class_Prog_Id==null && $entry_year==null){
                $query = DB::table('mstr_department as md')->wherenotnull('md.Faculty_Id')
                    ->join('mstr_faculty as mf','md.Faculty_Id','=','mf.Faculty_Id')
                    ->select(
                        DB::Raw('
                            md.Department_Id as dep_Id,
                            md.Department_Name as Prodi,
                            mf.Faculty_Name as Fak,
                            (SELECT COUNT(distinct(fnc_student_payment.Register_Number)) FROM fnc_student_payment join acd_student on acd_student.Register_Number = fnc_student_payment.Register_Number WHERE acd_student.Department_Id = md.Department_Id AND fnc_student_payment.Cost_Item_Id = 1 AND fnc_student_payment.Term_Year_Id = '.$Term_Year_Id.' or acd_student.Department_Id = md.Department_Id AND fnc_student_payment.Cost_Item_Id = 89 AND fnc_student_payment.Term_Year_Id = '.$Term_Year_Id.') as Total,
                            (SELECT COUNT(distinct(fnc_student_payment.Register_Number)) FROM fnc_student_payment join acd_student on acd_student.Register_Number = fnc_student_payment.Register_Number WHERE acd_student.Department_Id = md.Department_Id AND fnc_student_payment.Cost_Item_Id = 1 AND acd_student.Gender_Id = 1 AND fnc_student_payment.Term_Year_Id = '.$Term_Year_Id.' or acd_student.Department_Id = md.Department_Id AND fnc_student_payment.Cost_Item_Id = 89 AND acd_student.Gender_Id = 1 AND fnc_student_payment.Term_Year_Id = '.$Term_Year_Id.') as Total_L,
                            (SELECT COUNT(distinct(fnc_student_payment.Register_Number)) FROM fnc_student_payment join acd_student on acd_student.Register_Number = fnc_student_payment.Register_Number WHERE acd_student.Department_Id = md.Department_Id AND fnc_student_payment.Cost_Item_Id = 1 AND acd_student.Gender_Id = 2 AND fnc_student_payment.Term_Year_Id = '.$Term_Year_Id.' or acd_student.Department_Id = md.Department_Id AND fnc_student_payment.Cost_Item_Id = 89 AND acd_student.Gender_Id = 2 AND fnc_student_payment.Term_Year_Id = '.$Term_Year_Id.') as Total_P
                        ')
                    )
                // ->groupby('mstr_department.Department_Id')
                ->orderby('md.Faculty_Id')
                ->get();
            }
        }else{
            
        }
      }else{
        if($DepartmentId == ""){
            
        }else{
            
        }
      }
    // View()->share(['query'=>$query]);

    // $pdf = PDF::loadView('cetak/export_spp');
    // return $pdf->stream('Cetak_Spp.pdf');
      return view('laporan_spp/index')->with('query', $query)->with('prog_kelas', $Class_Prog_Id)->with('thsemester', $thsemester)->with('term_year', $Term_Year_Id)->with('entry_year', $entry_year)->with('select_term_year', $select_term_year)->with('select_class_program', $select_class_program)->with('select_entry_year', $select_entry_year);
    }

    
     public function exportexcel(Request $request,$department, $term_year)
     {
         $prodi = DB::table('mstr_department')->where('Department_Id',$department)->first();
         $items = DB::table('fnc_student_payment as a')
         ->join('acd_student as b','a.Register_Number','=','b.Register_Number')
         ->join('mstr_gender as c','b.Gender_Id','=','c.Gender_Id')
         ->where('b.Department_Id',$department)
         ->where('a.Cost_Item_Id',1)
         ->where('a.Term_Year_Id',$term_year)
         ->groupby('a.Register_Number')
         ->orderby('b.Nim','asc')
         ->get();
         if(count($items) == 0){
            return redirect::back()->withErrors('Data Kosong.');
         }

        Excel::create('SPP-'.$prodi->Department_Name.'-'.$term_year, function ($excel) use($department,$term_year,$items){

             if ($items->count() == 0) {
                $data = [
                    [
                        'Regnum' => '',
                        'Nim' => '',
                        'Jenis Kelamin' => '',
                        'Nama' => '',
                    ]
                ];
            }

            $i = 1;
            foreach ($items as $item) {
                $data[] = [
                    'Regnum' => $item->Register_Number,
                    'Nim' => $item->Nim,
                    'Jenis Kelamin' => $item->Gender_Type,
                    'Nama' => $item->Full_Name,
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

                $sheet->setHeight($rows);

                $sheet->setWidth([
                    'A' => 20,
                    'B' => 20,
                    'C' => 20,
                    'D' => 40,
                ]);

                $sheet->setHorizontalCentered(true);

                for ($i = 1; $i <= $num_rows; $i++) {
                    $sheet->row($i, function ($row) {
                        $row->setValignment('center');
                    });
                }

                $sheet->cells('A1:D1', function ($cells) {
                    $cells->setBackground('#97D86E');
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });
            });
        })->export('xls');
     }
}
