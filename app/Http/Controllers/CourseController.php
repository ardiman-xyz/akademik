<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\Registerst5s;
use Illuminate\Http\Request;
use Input;
use DB;
use Redirect;
use Alert;
use PDF;
use Auth;
use Excel;
use App\Exports\Daftar_matakuliah;
use App\GetDepartment;

class CourseController extends Controller
{
  public function __construct()
  {
    $this->middleware('access:CanView', ['except' => ['create','store','edit','update','destroy','export']]);
    $this->middleware('access:CanAdd', ['except' => ['index','show','edit','update','destroy','export']]);
    $this->middleware('access:CanEdit', ['except' => ['index','create','store','show','destroy','export']]);
    $this->middleware('access:CanDelete', ['except' => ['index','create','store','show','edit','update','export']]);
}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index(Request $request)
     {
       $this->validate($request,[
         'rowpage'=>'numeric|nullable'
       ]);

       $search = Input::get('search');
       $rowpage = Input::get('rowpage');
       $FacultyId = Auth::user()->Faculty_Id;
       $DepartmentId = Auth::user()->Department_Id;

       if ($rowpage == null || $rowpage <= 0) {
         $rowpage = 10;
       }
       $department = Input::get('department');

      $data_all = DB::table('acd_course')
      ->join('mstr_department', 'mstr_department.Department_Id','=','acd_course.Department_Id')
      ->leftjoin('acd_course_type', 'acd_course_type.Course_Type_Id','=','acd_course.Course_Type_Id')
      ->where('acd_course.Department_Id', $request->department)
      ->where(function($query){
        $search = Input::get('search');
        $query->whereRaw("lower(acd_course.Course_Code) like '%" . strtolower($search) . "%'");
        $query->orwhereRaw("lower(acd_course.Course_Name) like '%" . strtolower($search) . "%'");
      })
      ->orderBy('acd_course.Course_Code', 'asc');
      $datac = count($data_all->get());
      $data = $data_all->paginate($rowpage);

      $select_Department_Id = GetDepartment::getDepartment();
      
       $data->appends(['search'=> $search, 'rowpage'=> $rowpage, 'department'=> $department]);
       return view('acd_course/index')
       ->with('select_Department_Id', $select_Department_Id)
       ->with('department', $department)
       ->with('query',$data)
       ->with('search',$search)
       ->with('datac',$datac)
       ->with('rowpage',$rowpage);

     }

     /**
      * Show the form for creating a new resource.
      *
      * @return \Illuminate\Http\Response
      */
     public function create()
     {
       $department = Input::get('department');
       $search = Input::get('search');
       $page = Input::get('page');
       $rowpage = Input::get('rowpage');
       $FacultyId = Auth::user()->Faculty_Id;

// if($FacultyId==""){
  $mstr_department = DB::table('mstr_department')
  ->wherenotnull('Faculty_Id')
  ->where('Department_Id', $department)->get();

   $select_curriculum = DB::table('mstr_curriculum_applied as mca')
   ->join('mstr_curriculum as mc','mca.Curriculum_Id','=','mc.Curriculum_Id')
   ->where('Department_Id',$department)
   ->get();
// } else{
//   $mstr_department = DB::table('mstr_department')
//   ->join('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
//   ->where('mstr_faculty.Faculty_Id', $FacultyId)
//   ->where('Department_Id', $department)->get();
// }

       $select_course_type = DB::table('acd_course_type')->get();
       return view('acd_course/create')->with('department', $department)->with('mstr_department', $mstr_department)->with('select_course_type', $select_course_type)->with('search',$search)->with('page', $page)->with('rowpage', $rowpage)->with('select_curriculum', $select_curriculum);
     }


     /**
      * Store a newly created resource in storage.
      *
      * @param  \Illuminate\Http\Request  $request
      * @return \Illuminate\Http\Response
      */
     public function store(Request $request)
     {
       $this->validate($request,[
         'Department_Id'=>'required|max:6',
         'Course_Code'=>'required|unique:acd_course',
         'Course_Name'=>'required',
         'Course_Type_Id'=>'required',],
         ['Course_Code.unique'=> 'Kode Matakuliah Sudah Dipakai',
          'Course_Type_Id.required' => 'Jenis Matakuliah harus diisi'
       ]);
             // $Functional_Position_Course_Code  = Input::get('Functional_Position_Course_Code');
             $Department_Id = Input::get('Department_Id');
             $Course_Type_Id = Input::get('Course_Type_Id');
             $Course_Code = Input::get('Course_Code');
             $Course_Name = Input::get('Course_Name');
             $Course_Name_Eng = Input::get('Course_Name_Eng');


    // try{
      $cek_course =  DB::table('acd_course')->where([['Course_Code',$Course_Code],['Department_Id',$Department_Id]])->get();
      if($cek_course->count() > 0){
        return Redirect::back()->withErrors('Kode sudah ada');
      }else{
        $u =  DB::table('acd_course')
        ->insertgetId(
         ['Department_Id' => $Department_Id, 'Course_Code' => $Course_Code, 'Course_Type_Id' => $Course_Type_Id, 'Course_Name' => $Course_Name, 'Course_Name_Eng' => $Course_Name_Eng]);

        $cccount = DB::table('acd_course_group')->select('Course_Group_Id')->count();

         if($cccount == 0){
           $notif = "Kelompok mata kuliah belum ada data ";
           $ccc="";
         }else{
           $cc = DB::table('acd_course_group')->select('Course_Group_Id')->first();
           $ccc=$cc->Course_Group_Id;
         }
         // dd($cc);

          $Datetimenow = Date('Y-m-d');

         DB::table('acd_course_curriculum')
         ->insert(
         ['Department_Id' => $Department_Id,'Class_Prog_Id' => 5,'Curriculum_Id' => $request->Curriculum_Id,'Study_Level_Id' => 0,'Course_Id' => $u,'Is_For_Transcript' => true, 'Is_Required' => true, 'Course_Group_Id' => $ccc, 'Curriculum_Type_Id' => 1, 'Is_Valid' => false, 'Created_Date' => $Datetimenow,'Created_By' => auth()->user()->email,'Created_Date' => date('Y-m-d H:i:s') ]);

         return Redirect::back()->withErrors('Berhasil Menambah Matakuliah');
      }
     // } catch (\Exception $e) {
     //   return Redirect::back()->withErrors('Gagal Menambah Matakuliah');
     // }
     }

     /**
      * Display the specified resource.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function show($id)
     {

     }

     /**
      * Show the form for editing the specified resource.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function edit($id)
     {
       $search = Input::get('search');
       $page = Input::get('page');
       $rowpage = Input::get('rowpage');
       $FacultyId = Auth::user()->Faculty_Id;

      $data = DB::table('acd_course')
      ->join('mstr_department', 'mstr_department.Department_Id','=','acd_course.Department_Id')
      ->join('acd_course_type', 'acd_course_type.Course_Type_Id','=','acd_course.Course_Type_Id')
      ->where('acd_course.Course_Id', $id)
      ->get();


       $select_course_type = DB::table('acd_course_type')->get();
       return view('acd_course/edit')->with('query_edit',$data)->with('select_course_type', $select_course_type)->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);

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
       $this->validate($request,[
         'Course_Code'=>'required',
         'Course_Name'=>'required',
         'Course_Type_Id'=>'required',


       ]);
             $Course_Type_Id = Input::get('Course_Type_Id');
             $Course_Code = Input::get('Course_Code');
             $Course_Name = Input::get('Course_Name');
             $Course_Name_Eng = Input::get('Course_Name_Eng');
             $Department_Id = Input::get('Department_Id');

             try {
              // dd($Course_Code,$Department_Id,$request->all());
                $cek_id = DB::table('acd_course')->where([['Course_Id',$id]])->first();
               $cek_course =  DB::table('acd_course')->where([['Course_Code',$Course_Code],['Department_Id',$Department_Id]])->first();
               if($cek_course){
                if($cek_id->Course_Code == $cek_course->Course_Code){
                  $u =  DB::table('acd_course')
                 ->where('Course_Id',$id)
                 ->update(
                   ['Course_Type_Id' => $Course_Type_Id, 'Course_Name' => $Course_Name, 'Course_Name_Eng' => $Course_Name_Eng]);
                }else{
                 return Redirect::back()->withErrors('Kode sudah ada');                  
                }
               }else{
                 $u =  DB::table('acd_course')
                 ->where('Course_Id',$id)
                 ->update(
                   ['Course_Code' => $Course_Code, 'Course_Type_Id' => $Course_Type_Id, 'Course_Name' => $Course_Name, 'Course_Name_Eng' => $Course_Name_Eng]);                 
               }
               return Redirect::back()->withErrors('Berhasil Menyimpan Perubahan');
             } catch (\Exception $e) {
               return Redirect::back()->withErrors('Gagal Menyimpan Perubahan');
             }
     }

     /**
      * Remove the specified resource from storage.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function destroy(Request $request,$id)
     {
         $q=DB::table('acd_course')->where('Course_Id', $id)->delete();
         echo json_encode($q);
     }
     public function export($type = "excel")
     {
       $department = Input::get('department');
       $search = Input::get('search');
       $FacultyId = Auth::user()->Faculty_Id;

      if($FacultyId==""){
        if ($search == null) {
          $data = DB::table('acd_course')
          ->join('mstr_department', 'mstr_department.Department_Id','=','acd_course.Department_Id')
          ->join('acd_course_type', 'acd_course_type.Course_Type_Id','=','acd_course.Course_Type_Id')
          ->where('acd_course.Department_Id', $department)
          ->orderBy('acd_course.Course_Code', 'asc')
          ->get();
        }else {
          $data = DB::table('acd_course')
          ->join('mstr_department', 'mstr_department.Department_Id','=','acd_course.Department_Id')
          ->join('acd_course_type', 'acd_course_type.Course_Type_Id','=','acd_course.Course_Type_Id')
          ->where('acd_course.Department_Id', $department)
          ->where(function($query){
            $search = Input::get('search');
            $query->whereRaw("lower(acd_course.Course_Code) like '%" . strtolower($search) . "%'");
            $query->orwhereRaw("lower(acd_course.Course_Name) like '%" . strtolower($search) . "%'");
          })
          ->orderBy('acd_course.Course_Code', 'asc')
          ->get();
        }
      }else{
        if ($search == null) {
          $data = DB::table('acd_course')
          ->join('mstr_department', 'mstr_department.Department_Id','=','acd_course.Department_Id')
          ->join('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
          ->where('mstr_faculty.Faculty_Id', $FacultyId)
          ->join('acd_course_type', 'acd_course_type.Course_Type_Id','=','acd_course.Course_Type_Id')
          ->where('acd_course.Department_Id', $department)
          ->orderBy('acd_course.Course_Code', 'asc')
          ->get();
        }else {
          $data = DB::table('acd_course')
          ->join('mstr_department', 'mstr_department.Department_Id','=','acd_course.Department_Id')
          ->join('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
          ->where('mstr_faculty.Faculty_Id', $FacultyId)
          ->join('acd_course_type', 'acd_course_type.Course_Type_Id','=','acd_course.Course_Type_Id')
          ->where('acd_course.Department_Id', $department)
          ->where(function($query){
            $search = Input::get('search');
            $query->whereRaw("lower(acd_course.Course_Code) like '%" . strtolower($search) . "%'");
            $query->orwhereRaw("lower(acd_course.Course_Name) like '%" . strtolower($search) . "%'");
          })
          ->orderBy('acd_course.Course_Code', 'asc')
          ->get();
        }
      }

      $faculty=DB::table('acd_course')
      ->join('mstr_department','mstr_department.Department_Id','=','acd_course.Department_Id')
      ->leftjoin('mstr_faculty','mstr_faculty.Faculty_Id','=','mstr_department.Faculty_Id')
      ->select('mstr_faculty.Faculty_Name')->where('acd_course.Department_Id',$department)->first();

       View()->share(['data'=>$data, 'faculty'=>$faculty]);
       $pdf = PDF::loadView('acd_course/export');
       return $pdf->stream('matakuliah.pdf');
       // return view('acd_course/index')->with('department', $department)->with('query',$data)->with('search',$search);
     }

     public function exportexcel()
     {
       $department = Input::get('department');
       $search = Input::get('search');

        Excel::create('Kelas', function ($excel) use($department){
             $items = DB::table('acd_course')
             ->where('Department_Id',$department)->get();

             if ($items->count() == 0) {
                $data = [
                    [
                        'Department_Code' => '',
                        'Course_Type_Id' => '',
                        'Course_Code' => '',
                        'Course_Name' => '',
                        'Course_Name_Eng' => '',
                    ]
                ];
            }

            $i = 1;
            foreach ($items as $item) {
                $data[] = [
                    'Department_Code' => $item->Department_Id,
                    'Course_Type_Id' => $item->Course_Type_Id,
                    'Course_Code' => $item->Course_Code,
                    'Course_Name' => $item->Course_Name,
                    'Course_Name_Eng' => $item->Course_Name_Eng,
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
                    'C' => 40,
                    'D' => 40,
                ]);

                $sheet->setHorizontalCentered(true);

                for ($i = 1; $i <= $num_rows; $i++) {
                    $sheet->row($i, function ($row) {
                        $row->setValignment('center');
                    });
                }

                $sheet->cells('A1:E1', function ($cells) {
                    $cells->setBackground('#97D86E');
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });
            });
        })->export('xls');
     }

    public function import_export()
    {
      $department = Input::get('department');
      $mstr_department = DB::table('mstr_department')->where('Department_Id',$department)->get();
        return view('acd_course/import_export')->with('mstr_department',$mstr_department)->with('department',$department);
    }

    public function import_excel(Request $request)
    {
      $department = Input::get('department');
      $data_course = DB::table('acd_course')->where('Department_Id',$department)->select('Course_Code')->get()->toarray();
      $course_code = [];
      $i = 0;
      foreach ($data_course as $item) {
        $course_code[$i] = $item->Course_Code;
        $i++;
      }
        if($request->hasFile('import_file'))
        {
            Excel::load($request->file('import_file')->getRealPath(), function ($reader) use($department,$course_code)
            {
                foreach ($reader->toArray() as $row)
                {
                  $stat = 0;
                  $data=[];
                  if(in_array(($row['course_code']), $course_code) == false){
                      if ($row['course_code'] != null && $row['course_code'] != "") {
                        $check_course_type = DB::table('acd_course_type')->where('Course_Type_Code',$row['course_type_code'])->first();
                        $check_dept = DB::table('mstr_department')->where('Department_Id',$department)->first();
                        if($row['department_code'] == $check_dept->Department_Code){
                          $data['Department_Id'] = $department;
                          $data['Course_Type_Id'] = $check_course_type->Course_Type_Id;
                          $data['Course_Code'] = $row['course_code'];
                          $data['Course_Name'] = $row['course_name'];
                          $data['Course_Name_Eng'] = $row['course_name_eng'];

                          DB::table('acd_course')->insert($data);
                        }
                      }
                  }else{
                    continue;
                  }
                }
            });
        }

        return redirect()->to('/parameter/course?department='.$department)->withErrors('Berhasil Memasukkan Data Ke Database');
    }
 }
