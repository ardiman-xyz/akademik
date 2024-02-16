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

class Functional_position_term_yearController extends Controller
{
  public function __construct()
  {
    $this->middleware('access:CanView', ['except' => ['create','store','edit','update','destroy','Department','Faculty']]);
    $this->middleware('access:CanAdd', ['except' => ['index','show','edit','update','destroy','Department','Faculty']]);
    $this->middleware('access:CanEdit', ['except' => ['index','create','store','show','destroy','Department','Faculty']]);
    $this->middleware('access:CanDelete', ['except' => ['index','create','store','show','edit','update','Department','Faculty']]);
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
       $term_year = Input::get('term_year');

       $select_term_year = DB::table('mstr_term_year')
       ->orderBy('mstr_term_year.Term_Year_Id', 'desc')
       ->get();


       if ($search == null) {
         $data = DB::table('acd_functional_position_term_year')
         ->join('emp_functional_position', 'emp_functional_position.Functional_Position_Id','=','acd_functional_position_term_year.Functional_Position_Id')
         ->join('emp_employee', 'emp_employee.Employee_Id','=','acd_functional_position_term_year.Employee_Id')
         ->leftjoin('mstr_faculty','mstr_faculty.Faculty_Id','=','acd_functional_position_term_year.Faculty_Id')
         ->leftjoin('mstr_department','mstr_department.Department_Id','=','acd_functional_position_term_year.Department_Id')
         ->where('acd_functional_position_term_year.Term_Year_Id', $term_year)
         ->orderBy('mstr_faculty.Faculty_Name', 'asc')
         ->orderBy('mstr_department.Department_Name', 'asc')
         ->orderBy('acd_functional_position_term_year.Functional_Position_Id', 'asc')
         ->paginate($rowpage);
       }else {
         $data = DB::table('acd_functional_position_term_year')
         ->join('emp_functional_position', 'emp_functional_position.Functional_Position_Id','=','acd_functional_position_term_year.Functional_Position_Id')
         ->join('emp_employee', 'emp_employee.Employee_Id','=','acd_functional_position_term_year.Employee_Id')
         ->leftjoin('mstr_faculty','mstr_faculty.Faculty_Id','=','acd_functional_position_term_year.Faculty_Id')
         ->leftjoin('mstr_department','mstr_department.Department_Id','=','acd_functional_position_term_year.Department_Id')
         ->where('acd_functional_position_term_year.Term_Year_Id', $term_year)
         ->where(function($query){
           $search = Input::get('search');
           $query->whereRaw("lower(emp_employee.Full_Name) like '%" . strtolower($search) . "%'");
           $query->orwhereRaw("lower(mstr_faculty.Faculty_Name) like '%" . strtolower($search) . "%'");
           $query->orwhereRaw("lower(mstr_department.Department_Name) like '%" . strtolower($search) . "%'");
           $query->orwhereRaw("lower(emp_functional_position.Functional_Position_Name) like '%" . strtolower($search) . "%'");
         })
         ->orderBy('mstr_faculty.Faculty_Name', 'asc')
         ->orderBy('mstr_department.Department_Name', 'asc')
         ->orderBy('acd_functional_position_term_year.Functional_Position_Id', 'asc')
         ->paginate($rowpage);
       }
       $data->appends(['search'=> $search, 'rowpage'=> $rowpage, 'term_year'=> $term_year]);
       return view('acd_functional_position_term_year/index')->with('select_term_year', $select_term_year)->with('term_year', $term_year)->with('query',$data)->with('search',$search)->with('rowpage',$rowpage);

     }

     /**
      * Show the form for creating a new resource.
      *
      * @return \Illuminate\Http\Response
      */
     public function create()
     {
      //  dd(Auth::user()->email);
       $term_year = Input::get('term_year');
       $search = Input::get('search');
       $page = Input::get('page');
       $rowpage = Input::get('rowpage');
       $mstr_term_year = DB::table('mstr_term_year')->where('Term_Year_Id', $term_year)->get();
       $functional_position = DB::table('emp_functional_position')->get();
       $select_faculty = DB::table('mstr_faculty')->get();
       $select_department = DB::table('mstr_department')->wherenotnull('Faculty_Id')->get();
       $select_employee = DB::table('emp_employee')->get();
       return view('acd_functional_position_term_year/create')->with('term_year', $term_year)->with('mstr_term_year', $mstr_term_year)->with('functional_position', $functional_position)->with('select_faculty', $select_faculty)->with('select_department', $select_department)->with('select_employee', $select_employee)->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);
     }

     public function Department()
     {
       $term_year_id = Input::get('term_year_id');
       $functional_position_id = Input::get('functional_position_id');
       $department_Id = DB::table('acd_functional_position_term_year')->where('term_year_id', $term_year_id)->where('Functional_Position_Id', $functional_position_id)->select('Department_Id');
       $department = DB::table('mstr_department')->wherenotnull('Faculty_Id')->whereNotIn('Department_Id', $department_Id)->get();
       return view('acd_functional_position_term_year/department')->with('department', $department);
     }
     public function Faculty()
     {
       $term_year_id = Input::get('term_year_id');
       $functional_position_id = Input::get('functional_position_id');
       $faculty_id = DB::table('acd_functional_position_term_year')->where('term_year_id', $term_year_id)->where('Functional_Position_Id', $functional_position_id)->select('Faculty_Id');
       $faculty = DB::table('mstr_faculty')->whereNotIn('Faculty_Id', $faculty_id)->get();
       return view('acd_functional_position_term_year/faculty')->with('faculty', $faculty);
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
         'Functional_Position_Id'=>'required|max:6',
         'Term_Year'=>'required',
         'Employee_Id'=>'required',


       ]);
             // $Functional_Position_Term_Year  = Input::get('Functional_Position_Term_Year');
             $Functional_Position_Id = Input::get('Functional_Position_Id');
             $Employee_Id = Input::get('Employee_Id');
             $Term_Year = Input::get('Term_Year');
             $Faculty_Id = Input::get('Faculty_Id');
             $Department_Id = Input::get('Department_Id');

try {
       $u =  DB::table('acd_functional_position_term_year')
       ->insert(
       ['Functional_Position_Id' => $Functional_Position_Id, 
       'Term_Year_Id' => $Term_Year, 
       'Employee_Id' => $Employee_Id, 
       'Faculty_Id' => $Faculty_Id, 
       'Department_Id' => $Department_Id,
       'Created_By'=> Auth::user()->email,
       'Created_Date' => date('Y-m-d H:i:s')]);
       return Redirect::back()->withErrors('Berhasil Menambah Jabatan Struktural');
     } catch (\Exception $e) {
       return Redirect::back()->withErrors('Gagal Menambah Jabatan Struktural');
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
     public function edit($id)
     {
       $search = Input::get('search');
       $page = Input::get('page');
       $rowpage = Input::get('rowpage');
       $data = DB::table('acd_functional_position_term_year')
       ->join('emp_functional_position', 'emp_functional_position.Functional_Position_Id','=','acd_functional_position_term_year.Functional_Position_Id')
       ->join('emp_employee', 'emp_employee.Employee_Id','=','acd_functional_position_term_year.Employee_Id')
       ->leftjoin('mstr_faculty','mstr_faculty.Faculty_Id','=','acd_functional_position_term_year.Faculty_Id')
       ->leftjoin('mstr_department','mstr_department.Department_Id','=','acd_functional_position_term_year.Department_Id')
       ->where('acd_functional_position_term_year.Functional_Position_Term_Year_Id', $id)
       ->select('acd_functional_position_term_year.*','acd_functional_position_term_year.Department_Id','acd_functional_position_term_year.Faculty_Id','emp_functional_position.Functional_Position_Name','emp_functional_position.Functional_Position_Id','emp_employee.Employee_Id','emp_employee.Full_Name','mstr_faculty.Faculty_Name','mstr_department.Department_Name')
       ->get();
       $select_employee = DB::table('emp_employee')->get();
       return view('acd_functional_position_term_year/edit')->with('query_edit',$data)->with('select_employee', $select_employee)->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);

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
         // 'Functional_Position_Id'=>'required|max:6',
         // 'Term_Year'=>'required',
         'Employee_Id'=>'required',


       ]);
             // $Functional_Position_Term_Year  = Input::get('Functional_Position_Term_Year');
             // $Functional_Position_Id = Input::get('Functional_Position_Id');
             $Employee_Id = Input::get('Employee_Id');
             // $Term_Year = Input::get('Term_Year');
             // $Faculty_Id = Input::get('Faculty_Id');
             // $Department_Id = Input::get('Department_Id');

             try {
               $u =  DB::table('acd_functional_position_term_year')
               ->where('Functional_Position_Term_Year_Id',$id)
               ->update(
                ['Employee_Id' => $Employee_Id]);
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
         $q=DB::table('acd_functional_position_term_year')->where('Functional_Position_Term_Year_Id', $id)->delete();
         echo json_encode($q);
     }

     
          /**
      * Remove the specified resource from storage.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function store_kopidata(Request $request)
     {
         $old = DB::table('acd_functional_position_term_year')->where('Term_Year_Id', $request->term_from)->get();
         $cek = DB::table('acd_functional_position_term_year')->where('Term_Year_Id', $request->term_dest)->get();
         if(count($cek) <= 0){
           try {
              foreach ($old as $key) {
                $u =  DB::table('acd_functional_position_term_year')
                    ->insert(
                    ['Functional_Position_Id' => $key->Functional_Position_Id, 
                    'Year_Id' => $key->Year_Id, 
                    'Faculty_Id' => $key->Faculty_Id, 
                    'Department_Id' => $key->Department_Id,
                    'Term_Year_Id' => $request->term_dest, 
                    'Employee_Id' => $key->Employee_Id, 
                    'Created_By'=> Auth::user()->email,
                    'Created_Date' => date('Y-m-d H:i:s')]);
              }
              return Redirect::back()->withErrors('Berhasil Menyimpan Perubahan');
            } catch (\Exception $e) {
              return Redirect::back()->withErrors('Gagal Menyimpan Perubahan');
            }
         }else{
           return Redirect::back()->withErrors('Sudah ada data');
         }
     }
}
