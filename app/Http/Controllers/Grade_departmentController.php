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
use App\Http\Controllers\ApiStrukturalController;

class Grade_departmentController extends Controller
{

  public function __construct()
  {
    $this->middleware('access:CanView', ['except' => ['create','store','edit','update','destroy']]);
    $this->middleware('access:CanAdd', ['except' => ['index','show','edit','update','destroy']]);
    $this->middleware('access:CanEdit', ['except' => ['index','create','store','show','destroy']]);
    $this->middleware('access:CanDelete', ['except' => ['index','create','store','show','edit','update']]);

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
       if ($rowpage == null || $rowpage <= 0) {
         $rowpage = 10;
       }
       $department = Input::get('department');
       $FacultyId = Auth::user()->Faculty_Id;
       $DepartmentId = Auth::user()->Department_Id;

       $term_year1 = Input::get('term_year');
       if($term_year1 == null){
        $term_year =  $request->session()->get('term_year');
       }else{
        $term_year = Input::get('term_year');
       }
       
       $select_term_year = DB::table('mstr_term_year')
       ->orderBy('mstr_term_year.Term_Year_Id', 'desc')
       ->get();

       $select_entry_year = DB::table('mstr_entry_year')
       ->orderBy('mstr_entry_year.Entry_Year_Id', 'desc')
       ->get();

       if($FacultyId==""){
        if($DepartmentId == ""){
          $select_Department_Id = DB::table('mstr_department')
         ->wherenotnull('Faculty_Id')
         ->orderBy('mstr_department.Department_Id', 'desc')
         ->get();
        }else{
          $select_Department_Id = DB::table('mstr_department')
         ->wherenotnull('Faculty_Id')
         ->where('Department_Id',$DepartmentId)
         ->orderBy('mstr_department.Department_Id', 'desc')
         ->get();
        }
       }else{
         if($DepartmentId == ""){
          $select_Department_Id = DB::table('mstr_department as a')
          ->join('mstr_faculty as b','a.Faculty_Id','b.Faculty_Id')
          ->wherenotnull('a.Faculty_Id')
          ->where('a.Faculty_Id',$FacultyId)
          ->orderBy('a.Department_Id', 'desc')
          ->get();
         }else{
          $select_Department_Id = DB::table('mstr_department as a')
          ->join('mstr_faculty as b','a.Faculty_Id','b.Faculty_Id')
          ->wherenotnull('a.Faculty_Id')
          ->where('a.Faculty_Id',$FacultyId)
          ->where('a.Department_Id',$DepartmentId)
          ->orderBy('a.Department_Id', 'desc')
          ->get();
         }
       }


      if ($search == null) {
        $data = DB::table('acd_grade_department')
        ->join('mstr_department', 'mstr_department.Department_Id','=','acd_grade_department.Department_Id')
        ->join('acd_grade_letter', 'acd_grade_letter.Grade_Letter_Id','=','acd_grade_department.Grade_Letter_Id')
        // ->where('acd_grade_department.Department_Id', $department)
        ->where('acd_grade_department.Entry_Year_Id','!=' ,null)
        ->where('acd_grade_department.Entry_Year_Id', $request->entry_year)
        ->groupby('acd_grade_department.Entry_Year_Id','acd_grade_department.Grade_Letter_Id')
        ->orderBy('acd_grade_letter.Grade_Letter', 'asc')
        ->paginate($rowpage);
      }else {
        $data = DB::table('acd_grade_department')
        ->join('mstr_department', 'mstr_department.Department_Id','=','acd_grade_department.Department_Id')
        ->join('acd_grade_letter', 'acd_grade_letter.Grade_Letter_Id','=','acd_grade_department.Grade_Letter_Id')
        // ->where('acd_grade_department.Department_Id', $department)
        ->where('acd_grade_department.Entry_Year_Id','!=' ,null)
        ->where('acd_grade_department.Entry_Year_Id', $request->entry_year)
        ->whereRaw("lower(acd_grade_letter.Grade_Letter) like '%" . strtolower($search) . "%'")
        ->groupby('acd_grade_department.Entry_Year_Id','acd_grade_department.Grade_Letter_Id')
        ->orderBy('acd_grade_letter.Grade_Letter', 'asc')
        ->paginate($rowpage);
      }

      $data->appends(['search'=> $search, 'rowpage'=> $rowpage, 'department'=> $department]);
      return view('acd_grade_department/index')
      ->with('select_Department_Id', $select_Department_Id)
      ->with('department', $department)
      ->with('term_year', $term_year)
      ->with('term_year', $term_year)
      ->with('select_term_year', $select_term_year)
      ->with('query',$data)
      ->with('search',$search)
      ->with('select_entry_year',$select_entry_year)
      ->with('request',$request)
      ->with('rowpage',$rowpage);

     }

     /**
      * Show the form for creating a new resource.
      *
      * @return \Illuminate\Http\Response
      */
     public function create(Request $request)
     {
       $department = Input::get('department');
       $search = Input::get('search');
       $page = Input::get('page');
       $rowpage = Input::get('rowpage');
       $term_year = Input::get('term_year');
       $FacultyId = Auth::user()->Faculty_Id;

      $grade_department = DB::table('acd_grade_department')
      ->where('acd_grade_department.department_id', $department)
      ->where('Term_Year_Id',$term_year)
      ->select('Grade_Letter_Id');

      $mstr_department = DB::table('mstr_department')
      ->wherenotnull('Faculty_Id')
      ->where('Department_Id', $department)->get();

      $mstr_term_year = DB::table('mstr_term_year')
      ->where('Term_Year_Id', $term_year)->first();


       $select_grade_letter = DB::table('acd_grade_letter')->WhereNotIn('Grade_Letter_Id', $grade_department)->orderby('Grade_Letter','asc')->get();
       return view('acd_grade_department/create')
       ->with('department', $department)
       ->with('mstr_department', $mstr_department)
       ->with('select_grade_letter', $select_grade_letter)
       ->with('search',$search)
       ->with('page', $page)
       ->with('request', $request)
       ->with('term_year', $term_year)
       ->with('mstr_term_year', $mstr_term_year)
       ->with('rowpage', $rowpage);
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
          // 'Department_Id'=>'required|max:6',
          'Grade_Letter_Id'=>'required',
        ]);
        // dd($request->all());

        try {
          $departments = DB::table('mstr_department')->get();
          foreach ($departments as $department) {
            $check = DB::table('acd_grade_department')->where([
              ['Department_Id',$department->Department_Id],
              ['Entry_Year_Id',$request->entry_year],
              ['Grade_Letter_Id',$request->Grade_Letter_Id],
            ])->first();
            if(!$check){
              $u =  DB::table('acd_grade_department')
              ->insert([
                'Department_Id' => $department->Department_Id, 
                'Entry_Year_Id' => $request->entry_year, 
                'Grade_Letter_Id' => $request->Grade_Letter_Id, 
                'Weight_Value' => $request->Weight_Value, 
                'Predicate' => $request->Predicate, 
                'Predicate_Eng' => $request->Predicate_Eng, 
                'Scale_Numeric_Max' => $request->Scale_Numeric_Max, 
                'Scale_Numeric_Min' => $request->Scale_Numeric_Min,
                'Created_Date'=>Date('Y-m-d'),
                'Created_By'=>Auth::user()->email
              ]);
            }
          }
          return Redirect::to('/parameter/grade_department?entry_year='.$request->entry_year)->withErrors('Berhasil Menambah Grade Nilai');
        } catch (\Exception $e) {
          return Redirect::back()->withErrors('Gagal Menambah Grade Nilai');
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

     }

     /**
      * Show the form for editing the specified resource.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function edit(Request $request, $id)
     {
       $search = Input::get('search');
       $page = Input::get('page');
       $rowpage = Input::get('rowpage');
       $term_year = Input::get('term_year');
       $data = DB::table('acd_grade_department')
       ->join('mstr_department', 'mstr_department.Department_Id','=','acd_grade_department.Department_Id')
       ->join('acd_grade_letter', 'acd_grade_letter.Grade_Letter_Id','=','acd_grade_department.Grade_Letter_Id')
       ->where('acd_grade_department.Grade_Department_Id', $id)
       ->get();
       foreach ($data as $k) {
         $department_id = $k->Department_Id;
       }
       $grade_letter = DB::table('acd_grade_department')->where('Grade_Department_Id', $id)->where('Term_Year_Id',$term_year)->select('Grade_Letter_Id');
       $grade_department = DB::table('acd_grade_department')->where('department_id', $department_id)->WhereNotIn('Grade_Letter_Id', $grade_letter)->select('Grade_Letter_Id');
       $select_grade_letter = DB::table('acd_grade_letter')->WhereNotIn('Grade_Letter_Id', $grade_department)->get();
       return view('acd_grade_department/edit')
       ->with('query_edit',$data)
       ->with('term_year',$term_year)
       ->with('select_grade_letter', $select_grade_letter)
       ->with('search',$search)
       ->with('page', $page)
       ->with('request', $request)
       ->with('rowpage', $rowpage);

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
          'Grade_Letter_Id'=>'required',
        ]);
        // dd($request->all());

        try {
          $departments = DB::table('mstr_department')->get();
          foreach ($departments as $department) {
            $check = DB::table('acd_grade_department')->where([
              ['Department_Id',$department->Department_Id],
              ['Entry_Year_Id',$request->entry_year],
              ['Grade_Letter_Id',$request->Grade_Letter_Id],
              ])->first();
            if($check){
              $u =  DB::table('acd_grade_department')
              ->where([
                ['Department_Id',$department->Department_Id],
                ['Entry_Year_Id',$request->entry_year],
                ['Grade_Letter_Id',$request->Grade_Letter_Id],
                ])
              ->update([
                'Grade_Letter_Id' => $request->Grade_Letter_Id, 
                'Weight_Value' => $request->Weight_Value, 
                'Predicate' => $request->Predicate, 
                'Predicate_Eng' => $request->Predicate_Eng, 
                'Scale_Numeric_Max' => $request->Scale_Numeric_Max, 
                'Scale_Numeric_Min' => $request->Scale_Numeric_Min
              ]);
            }else{
              $u =  DB::table('acd_grade_department')
              ->insert([
                'Department_Id' => $department->Department_Id, 
                'Entry_Year_Id' => $request->entry_year, 
                'Grade_Letter_Id' => $request->Grade_Letter_Id, 
                'Weight_Value' => $request->Weight_Value, 
                'Predicate' => $request->Predicate, 
                'Predicate_Eng' => $request->Predicate_Eng, 
                'Scale_Numeric_Max' => $request->Scale_Numeric_Max, 
                'Scale_Numeric_Min' => $request->Scale_Numeric_Min,
                'Modified_Date'=>Date('Y-m-d'),
                'Modified_By'=>Auth::user()->email
              ]);
            }
          }
          return Redirect::to('/parameter/grade_department?entry_year='.$request->entry_year)->withErrors('Berhasil Menyimpan Perubahan');
        } catch (\Exception $e) {
          return Redirect::back()->withErrors('Gagal Menyimpan Perubahan');
        }
     }

     public function copydata(Request $request){
      $department = $request->department;
      $term_year = $request->term_year;
      $course_type = $request->course_type;
      $select_department = DB::table('mstr_department')
      ->where('Department_Id',$department)
      ->first();

      $select_term_year_all = DB::table('mstr_term_year')
     //  ->where('Term_Year_Id',$term_year)
     ->orderby('Term_Year_Id','desc')
      ->get();
      
      $select_term_year = DB::table('mstr_term_year')
      ->where('Term_Year_Id',$term_year)
      ->first();

      $select_course_type = DB::table('acd_course_type')
      ->get();

      $get_department = DB::table('mstr_department')
      ->where('Faculty_Id','!=',null)
      ->get();

      return view('acd_grade_department/copydata')
      ->with('department',$department)
      ->with('course_type',$course_type)
      ->with('select_department',$select_department)
      ->with('select_term_year',$select_term_year)
      ->with('select_term_year_all',$select_term_year_all)
      ->with('select_course_type',$select_course_type)
      ->with('get_department',$get_department)
      ->with('term_year',$term_year);
    }

    public function storecopydata(Request $request)
     {
       $dept_asal = $request->dept_asal;
       $term_asal = $request->term_asal;
       $term_dest = $request->term_dest;
       $dept_dest = $request->dept_dest;
       if($term_dest == '0'){
         return Redirect::back()->withErrors('Semester Tujuan Kosong');
       }

       $komponen_awal = DB::table('acd_grade_department')
       ->where('acd_grade_department.Department_Id', $dept_asal)
       ->where('acd_grade_department.Term_Year_Id', $term_asal)
       ->get();
       try{
       foreach($dept_dest as $key){
        $count_komponen_awal = DB::table('acd_grade_department')
        ->where('acd_grade_department.Department_Id', $key)
        ->where('acd_grade_department.Term_Year_Id', $dept_dest)
       ->count();
        if($count_komponen_awal == 0){
          foreach ($komponen_awal as $kmp) {
            $data = [ 'Department_Id'=>$key, 
              'Term_Year_Id' => $term_dest, 
              'Grade_Letter_Id' => $kmp->Grade_Letter_Id, 
              'Weight_Value' => $kmp->Weight_Value, 
              'Predicate' => $kmp->Predicate, 
              'Predicate_Eng' => $kmp->Predicate_Eng, 
              'Scale_Numeric_Max' => $kmp->Scale_Numeric_Max, 
              'Scale_Numeric_Min' => $kmp->Scale_Numeric_Min, 
              'Order_Id' => $kmp->Order_Id, 
              'Created_By' => auth()->user()->email,
              'Created_Date' => date('Y-m-d H:i:s')];
            $insert =  DB::table('acd_grade_department')
                    ->insert($data);
          }
        }
       }
       return Redirect::back()->withErrors('Berhasil Kopi Grade Nilai');
       } catch (\Exception $e) {
          return Redirect::back()->withErrors('Gagal Kopi Grade Nilai');
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
         $q=DB::table('acd_grade_department')->where('Grade_Department_Id', $id)->delete();
        echo json_encode($q);
     }


     public function update_department(Request $request){
      // dd($request->all());
      $departments = DB::table('mstr_department')->get();
      $data = [];
      foreach ($departments as $department) {
        $check = DB::table('acd_grade_department')->where([
          ['Department_Id',$department->Department_Id],
          ['Entry_Year_Id',$request->entry_year]
          ])->get();
        if(count($data) <= count($check)){
          $data = $check;
        }
      }

      foreach ($departments as $department) {
        $check = DB::table('acd_grade_department')->where([
          ['Department_Id',$department->Department_Id],
          ['Entry_Year_Id',$request->entry_year]
          ])->get();
          // dd(count($data),count($check));
        if(count($data) > count($check)){
          $delete_data = DB::table('acd_grade_department')->where([
            ['Department_Id',$department->Department_Id],
            ['Entry_Year_Id',$request->entry_year]
            ])->get();

            foreach ($data as $key) {
              $data_insert = [ 
                'Department_Id'=>$department->Department_Id, 
                'Term_Year_Id' => $key->Term_Year_Id,
                'Entry_Year_Id' => $key->Entry_Year_Id,
                'Grade_Letter_Id' => $key->Grade_Letter_Id,
                'Weight_Value' => $key->Weight_Value,
                'Predicate' => $key->Predicate,
                'Predicate_Eng' => $key->Predicate_Eng,
                'Scale_Numeric_Max' => $key->Scale_Numeric_Max,
                'Scale_Numeric_Min' => $key->Scale_Numeric_Min,
                'Order_Id' => $key->Order_Id,
                'Created_By' => auth()->user()->email,
                'Created_Date' => date('Y-m-d H:i:s')
              ];
  
              $insert =  DB::table('acd_grade_department')->insert($data_insert);
            }
        } 
          
          // dd('stop');
      }
      // dd('stop');
      // return Redirect::back()->withErrors('Berhasil Update data');
      return redirect()->to('parameter/grade_department?entry_year='.$request->entry_year);
     }



 }
