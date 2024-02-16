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
use Auth;
use App\GetDepartment;

class Curriculum_entry_yearController extends Controller
{
  public function __construct()
  {
    $this->middleware('access:CanView', ['except' => ['create','store','edit','update','destroy','class_program']]);
    $this->middleware('access:CanAdd', ['except' => ['index','show','edit','update','destroy','class_program']]);
    $this->middleware('access:CanEdit', ['except' => ['index','create','store','show','destroy','class_program']]);
    $this->middleware('access:CanDelete', ['except' => ['index','create','store','show','edit','update','class_program']]);
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

       $FacultyId = Auth::user()->Faculty_Id;
       $DepartmentId = Auth::user()->Department_Id;
       $search = Input::get('search');
       $rowpage = Input::get('rowpage');
       if ($rowpage == null || $rowpage <= 0) {
         $rowpage = 10;
       }
       $term_year1 = Input::get('term_year');
       if($term_year1 == null){
        $term_year =  $request->session()->get('term_year');
       }else{
        $term_year = Input::get('term_year');
       }
       $department = Input::get('department');

       $select_term_year = DB::table('mstr_term_year')
       ->orderBy('mstr_term_year.Term_Year_Id', 'desc')
       ->get();

       if($FacultyId==""){
         if($DepartmentId == ""){
          $select_department = DB::table('mstr_department')
          ->wherenotnull('Faculty_Id')
          ->orderBy('mstr_department.department_code', 'asc')
          ->get();

          if ($search == null) {
            $data = DB::table('acd_curriculum_entry_year')
            ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_curriculum_entry_year.Term_Year_Id')
            ->join('mstr_department','mstr_department.Department_Id','=','acd_curriculum_entry_year.Department_Id')
            ->join('mstr_entry_year','mstr_entry_year.Entry_Year_Id','=','acd_curriculum_entry_year.Entry_Year_Id')
            ->select('acd_curriculum_entry_year.*','mstr_entry_year.*')

            ->where('acd_curriculum_entry_year.Term_Year_Id', $term_year)
            ->where('acd_curriculum_entry_year.Department_Id', $department)
            ->groupBy('mstr_entry_year.Entry_Year_Id')
            ->orderBy('acd_curriculum_entry_year.Term_Year_Id', 'asc')
            ->paginate($rowpage);

          }else {
            $data = DB::table('acd_curriculum_entry_year')
            ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_curriculum_entry_year.Term_Year_Id')
            ->join('mstr_department','mstr_department.Department_Id','=','acd_curriculum_entry_year.Department_Id')
            ->join('mstr_entry_year','mstr_entry_year.Entry_Year_Id','=','acd_curriculum_entry_year.Entry_Year_Id')

            ->select('acd_curriculum_entry_year.*','mstr_entry_year.*')
            ->where('acd_curriculum_entry_year.Term_Year_Id', $term_year)
            ->where('acd_curriculum_entry_year.Department_Id', $department)
            ->whereRaw("lower(Entry_Year_Name) like '%" . strtolower($search) . "%'")
            ->groupBy('mstr_entry_year.Entry_Year_Id')
            ->orderBy('acd_curriculum_entry_year.Term_Year_Id', 'asc')
            ->paginate($rowpage);
          }
         }else{
          $select_department = DB::table('mstr_department')
          ->wherenotnull('Faculty_Id')
          ->where('Department_Id',$DepartmentId)
          ->orderBy('mstr_department.department_code', 'asc')
          ->get();

          if ($search == null) {
            $data = DB::table('acd_curriculum_entry_year')
            ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_curriculum_entry_year.Term_Year_Id')
            ->join('mstr_department','mstr_department.Department_Id','=','acd_curriculum_entry_year.Department_Id')
            ->join('mstr_entry_year','mstr_entry_year.Entry_Year_Id','=','acd_curriculum_entry_year.Entry_Year_Id')
            ->select('acd_curriculum_entry_year.*','mstr_entry_year.*')

            ->where('acd_curriculum_entry_year.Term_Year_Id', $term_year)
            ->where('acd_curriculum_entry_year.Department_Id', $department)
            ->groupBy('mstr_entry_year.Entry_Year_Id')
            ->orderBy('acd_curriculum_entry_year.Term_Year_Id', 'asc')
            ->paginate($rowpage);

          }else {
            $data = DB::table('acd_curriculum_entry_year')
            ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_curriculum_entry_year.Term_Year_Id')
            ->join('mstr_department','mstr_department.Department_Id','=','acd_curriculum_entry_year.Department_Id')
            ->join('mstr_entry_year','mstr_entry_year.Entry_Year_Id','=','acd_curriculum_entry_year.Entry_Year_Id')

            ->select('acd_curriculum_entry_year.*','mstr_entry_year.*')
            ->where('acd_curriculum_entry_year.Term_Year_Id', $term_year)
            ->where('acd_curriculum_entry_year.Department_Id', $department)
            ->whereRaw("lower(Entry_Year_Name) like '%" . strtolower($search) . "%'")
            ->groupBy('mstr_entry_year.Entry_Year_Id')
            ->orderBy('acd_curriculum_entry_year.Term_Year_Id', 'asc')
            ->paginate($rowpage);
          }
         }
       }else{
         if($DepartmentId == ""){
          $select_department = DB::table('mstr_department')
          ->join('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
          ->where('mstr_faculty.Faculty_Id', $FacultyId)
          ->orderBy('mstr_department.department_code', 'asc')
          ->get();
          if ($search == null) {
            $data = DB::table('acd_curriculum_entry_year')
            ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_curriculum_entry_year.Term_Year_Id')
            ->join('mstr_department','mstr_department.Department_Id','=','acd_curriculum_entry_year.Department_Id')
            ->join('mstr_entry_year','mstr_entry_year.Entry_Year_Id','=','acd_curriculum_entry_year.Entry_Year_Id')
            ->select('acd_curriculum_entry_year.*','mstr_entry_year.*')
            ->join('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
            ->where('mstr_faculty.Faculty_Id', $FacultyId)
            ->where('acd_curriculum_entry_year.Term_Year_Id', $term_year)
            ->where('acd_curriculum_entry_year.Department_Id', $department)
            ->groupBy('mstr_entry_year.Entry_Year_Id')
            ->orderBy('acd_curriculum_entry_year.Term_Year_Id', 'asc')
            ->paginate($rowpage);

          }else {
            $data = DB::table('acd_curriculum_entry_year')
            ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_curriculum_entry_year.Term_Year_Id')
            ->join('mstr_department','mstr_department.Department_Id','=','acd_curriculum_entry_year.Department_Id')
            ->join('mstr_entry_year','mstr_entry_year.Entry_Year_Id','=','acd_curriculum_entry_year.Entry_Year_Id')
            ->join('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
            ->where('mstr_faculty.Faculty_Id', $FacultyId)
            ->select('acd_curriculum_entry_year.*','mstr_entry_year.*')
            ->where('acd_curriculum_entry_year.Term_Year_Id', $term_year)
            ->where('acd_curriculum_entry_year.Department_Id', $department)
            ->whereRaw("lower(Entry_Year_Name) like '%" . strtolower($search) . "%'")
            ->groupBy('mstr_entry_year.Entry_Year_Id')
            ->orderBy('acd_curriculum_entry_year.Term_Year_Id', 'asc')
            ->paginate($rowpage);
          }
         }else{
          $select_department = DB::table('mstr_department')
          ->join('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
          ->where('mstr_faculty.Faculty_Id', $FacultyId)
          ->where('mstr_department.Department_Id',$DepartmentId)
          ->orderBy('mstr_department.department_code', 'asc')
          ->get();
          if ($search == null) {
            $data = DB::table('acd_curriculum_entry_year')
            ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_curriculum_entry_year.Term_Year_Id')
            ->join('mstr_department','mstr_department.Department_Id','=','acd_curriculum_entry_year.Department_Id')
            ->join('mstr_entry_year','mstr_entry_year.Entry_Year_Id','=','acd_curriculum_entry_year.Entry_Year_Id')
            ->select('acd_curriculum_entry_year.*','mstr_entry_year.*')
            ->join('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
            ->where('mstr_faculty.Faculty_Id', $FacultyId)
            ->where('acd_curriculum_entry_year.Term_Year_Id', $term_year)
            ->where('acd_curriculum_entry_year.Department_Id', $department)
            ->groupBy('mstr_entry_year.Entry_Year_Id')
            ->orderBy('acd_curriculum_entry_year.Term_Year_Id', 'asc')
            ->paginate($rowpage);

          }else {
            $data = DB::table('acd_curriculum_entry_year')
            ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_curriculum_entry_year.Term_Year_Id')
            ->join('mstr_department','mstr_department.Department_Id','=','acd_curriculum_entry_year.Department_Id')
            ->join('mstr_entry_year','mstr_entry_year.Entry_Year_Id','=','acd_curriculum_entry_year.Entry_Year_Id')
            ->join('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
            ->where('mstr_faculty.Faculty_Id', $FacultyId)
            ->select('acd_curriculum_entry_year.*','mstr_entry_year.*')
            ->where('acd_curriculum_entry_year.Term_Year_Id', $term_year)
            ->where('acd_curriculum_entry_year.Department_Id', $department)
            ->whereRaw("lower(Entry_Year_Name) like '%" . strtolower($search) . "%'")
            ->groupBy('mstr_entry_year.Entry_Year_Id')
            ->orderBy('acd_curriculum_entry_year.Term_Year_Id', 'asc')
            ->paginate($rowpage);
          }
         }  
       }

       $select_department = GetDepartment::getDepartment();

       $class_prog = DB::table('mstr_class_program')->get();
       $get_department = DB::table('mstr_department')->where('Department_Id',$department)->first();
       $get_termyear = DB::table('mstr_term_year')->where('Term_Year_Id',$term_year)->first();
       $data->appends(['search'=> $search, 'rowpage'=> $rowpage, 'term_year'=> $term_year, 'department'=> $department]);
       return view('acd_curriculum_entry_year/index')->with('query',$data)->with('class_prog', $class_prog)->with('search',$search)->with('rowpage',$rowpage)->with('select_term_year', $select_term_year)->with('term_year', $term_year)->with('select_department', $select_department)->with('department', $department)->with('get_department', $get_department)->with('get_termyear', $get_termyear);
     }
     // public function modal()
     // {
     //   return view('mstr_term_year/modal');
     // }
     /**
      * Show the form for creating a new resource.
      *
      * @return \Illuminate\Http\Response
      */
     public function create(Request $request)
     {
         $search = Input::get('search');
         $page = Input::get('page');
         $rowpage = Input::get('rowpage');
         $department_id = Input::get('department');
         $term_year_id = Input::get('term_year');
         $FacultyId = Auth::user()->Faculty_Id;

         $Term_Year = DB::table('mstr_term_year')->where('Term_Year_Id', $term_year_id)->get();

    // if($FacultyId==""){
      $Department = DB::table('mstr_department')
      ->where('Department_Id', $department_id)->get();
    // }else{
    //   $Department = DB::table('mstr_department')
    //   ->join('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
    //   ->where('mstr_faculty.Faculty_Id', $FacultyId)
    //   ->where('Department_Id', $department_id)->get();
    // }

         $entry_year = DB::table('mstr_entry_year')->orderBy('Entry_Year_Name','desc')->get();
         $cur_entry_year = DB::table('acd_curriculum_entry_year')
         ->where('Department_Id',$request->department)
         ->where('Term_Year_Id',$request->term_year)
         ->where('Entry_Year_Id',$request->Entry_Year_Id)
         ->pluck('Class_Prog_Id');
         
         $class_program = DB::table('mstr_class_program')
         ->wherenotin('Class_Prog_Id',$cur_entry_year)
         ->get();
         $curriculum = DB::table('mstr_curriculum_applied as mca')
         ->join('mstr_curriculum as mc','mca.Curriculum_Id','=','mc.Curriculum_Id')
         ->where('Department_Id',$department_id)
         ->get();

         return view('acd_curriculum_entry_year/create')
         ->with('request', $request)
         ->with('curriculum', $curriculum)
         ->with('entry_year', $entry_year)
         ->with('class_program', $class_program)
         ->with('Term_Year', $Term_Year)
         ->with('Department', $Department)
         ->with('Department_Id', $department_id)
         ->with('Term_Year_Id', $term_year_id)
         ->with('search',$search)
         ->with('page', $page)
         ->with('rowpage', $rowpage);
     }
     public function class_program()
     {
       $FacultyId = Auth::user()->Faculty_Id;

         $Term_Year_Id = Input::get('term_year_id');
         $Department_Id = Input::get('department_id');
         $Entry_Year_Id = Input::get('entry_year_id');

         if($FacultyId==""){
           $entry_year = DB::table('acd_curriculum_entry_year')->where('Term_Year_Id', $Term_Year_Id)
           ->where('Department_Id', $Department_Id)
           ->where('Entry_Year_Id', $Entry_Year_Id)->select('Class_Prog_Id');
         }else{
           $entry_year = DB::table('acd_curriculum_entry_year')->where('Term_Year_Id', $Term_Year_Id)
           ->where('Department_Id', $Department_Id)
           ->join('mstr_department','mstr_department.Department_Id','=','acd_curriculum_entry_year.Department_Id')
           ->join('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
           ->where('mstr_faculty.Faculty_Id', $FacultyId)
           ->where('Entry_Year_Id', $Entry_Year_Id)->select('Class_Prog_Id');
         }

         $data = DB::table('mstr_class_program')->WhereNotIn('Class_Prog_Id', $entry_year)->get();
         return view('acd_curriculum_entry_year/class_program')->with('data', $data);

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
         'Term_Year_Id'=>'required',
         'Department_Id' => 'required',
         // 'Entry_Year_Id' => 'required',
         'Class_Prog_Id' => 'required',
         'Curriculum_Id' => 'required',
       ]);
             $Department_Id = Input::get('Department_Id');
             $Entry_Year_Id = Input::get('Entry_Year_Id');
             $Term_Year_Id = Input::get('Term_Year_Id');
             $Class_Prog_Id = Input::get('Class_Prog_Id');
             $Curriculum_Id = Input::get('Curriculum_Id');

try{
       $u =  DB::table('acd_curriculum_entry_year')
       ->insert(
       ['Department_Id' => $Department_Id,'Entry_Year_Id' => $Entry_Year_Id,'Term_Year_Id' => $Term_Year_Id,'Class_Prog_Id' => $Class_Prog_Id,'Curriculum_Id' => $Curriculum_Id]);
       return Redirect::to('/parameter/curriculum_entry_year?term_year='.$Term_Year_Id.'&department='.$Department_Id)->withErrors('Berhasil Menambah Kurikulum Angkatan');
       return Redirect::back()->withErrors('Berhasil Menambah Kurikulum Angkatan');
     } catch (\Exception $e) {
       return Redirect::back()->withErrors('Gagal Menambah Kurikulum Angkatan');
     }
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
       $search = Input::get('search');
       $page = Input::get('page');
       $rowpage = Input::get('rowpage');

       $data = DB::table('acd_curriculum_entry_year')
       ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_curriculum_entry_year.Term_Year_Id')
       ->join('mstr_entry_year','mstr_entry_year.Entry_Year_Id','=','acd_curriculum_entry_year.Entry_Year_Id')
       ->join('mstr_department','mstr_department.Department_Id','=','acd_curriculum_entry_year.Department_Id')
       ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_curriculum_entry_year.Class_Prog_Id')
       ->where('Curriculum_Entry_Year_Id', $id)->first();
       if($data == null) { return view('404'); }
       $curriculum = DB::table('mstr_curriculum')->get();
       return view('acd_curriculum_entry_year/edit')->with('query_edit', $data)->with('select_curriculum', $curriculum)->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);
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
         'Curriculum_Id'=>'required',
       ]);
             $Curriculum_Id = Input::get('Curriculum_Id');
             try {
               DB::table('acd_curriculum_entry_year')
               ->where('Curriculum_Entry_Year_Id',$id)
               ->update(
               ['Curriculum_Id' => $Curriculum_Id]);
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
         $q=DB::table('acd_curriculum_entry_year')->where('Curriculum_Entry_Year_Id', $id)->delete();
         echo json_encode($q);
     }

  public function copy_data(Request $request)
  {
    // dd($request->all());
    //   "semester" => "3"
    //   "term_year" => "20201"
    //   "term_year_destination" => "20201"

    try{
      $get_data = DB::table('acd_curriculum_entry_year')->where([['Term_Year_Id',$request->term_year],['Department_Id',$request->prodi]])->get();
      if($request->term_year_destination == $request->term_year){
        return response()->json([
                "success" => false,
                "message" => 'Pilih Semester Tujuan',
            ], 200);
      }
      $get_data_destination = DB::table('acd_curriculum_entry_year')->where([['Term_Year_Id',$request->term_year_destination],['Department_Id',$request->prodi]])->count();
      if($get_data_destination > 0){
        return response()->json([
                "success" => false,
                "message" => 'Sudah ada data pada semester '.$request->term_year_destination,
            ], 200);
      }
      foreach ($get_data as $key) {
        $u =  DB::table('acd_curriculum_entry_year')
        ->insert([
          'Department_Id' => $key->Department_Id,
          'Entry_Year_Id' => $key->Entry_Year_Id,
          'Term_Year_Id' => $request->term_year_destination,
          'Class_Prog_Id' => $key->Class_Prog_Id,
          'Curriculum_Id' => $key->Curriculum_Id
        ]);
      }
      return response()->json([
          "success" => true,
          "message" => 'Berhasil Kopi data',
      ], 200);
    } catch (\Exception $e) {
      return Redirect::back()->withErrors('Gagal Mengkopi Kurikulum Angkatan');
    }
  }
 }
