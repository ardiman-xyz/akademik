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

class Allowed_sksController extends Controller
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
       $FacultyId = Auth::user()->Faculty_Id;
       $DepartmentId = Auth::user()->Department_Id;

       if ($rowpage == null || $rowpage <= 0) {
         $rowpage = 10;
       }
       $department = input::get('department');

      $select_Department_Id = GetDepartment::getDepartment();

      if ($search == null) {
        $data = DB::table('acd_allowed_sks')
        ->join('mstr_department', 'mstr_department.Department_Id','=','acd_allowed_sks.Department_Id')

        ->where('acd_allowed_sks.Department_Id', $department)
        ->orderBy('Ip_Max', 'asc')
        ->paginate($rowpage);
      }else {
        $data = DB::table('acd_allowed_sks')
        ->join('mstr_department', 'mstr_department.Department_Id','=','acd_allowed_sks.Department_Id')

        ->where('acd_allowed_sks.Department_Id', $department)
        ->where('mstr_department.Department_Name', 'LIKE', '%'.$search.'%')
        ->orderBy('Ip_Max', 'asc')
        ->paginate($rowpage);
      }


       $data->appends(['search'=> $search, 'rowpage'=> $rowpage, 'department'=> $department]);
       return view('acd_allowed_sks/index')->with('select_Department_Id', $select_Department_Id)->with('department', $department)->with('query',$data)->with('search',$search)->with('rowpage',$rowpage);

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
       $mstr_department = DB::table('mstr_department')->where('Department_Id', $department)->get();

       $acd_allowed_sks = DB::table('acd_allowed_sks')->where('Department_Id', $department)->select('Employee_Id');
       $select_employee_id = DB::table('emp_employee')->WhereNotIn('Employee_Id', $acd_allowed_sks)->get();
       return view('acd_allowed_sks/create')->with('department', $department)->with('mstr_department', $mstr_department)->with('select_employee_id', $select_employee_id)->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);
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
         'Department_Id'=>'required',
         'Ip_Max'=>'required|numeric',
         'Sks_Max'=>'required|numeric',
       ],['Ip_Max.numeric' => 'Indeks Prestasi Maksimum harus berupa angka',
     'Sks_Max.numeric' => 'SKS maksimum Maksimum harus berupa angka']);
             $Department_Id = Input::get('Department_Id');
             $Ip_Max = Input::get('Ip_Max');
             $Sks_Max = Input::get('Sks_Max');


       $u =  DB::table('acd_allowed_sks')
       ->insert(
       [ 'Department_Id' => $Department_Id,'Ip_Max' => $Ip_Max, 'Sks_Max' => $Sks_Max]);
       return Redirect::back()->withErrors('Berhasil Menambah SKS Diijinkan');
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
        $data = DB::table('acd_allowed_sks')
        ->join('mstr_department', 'mstr_department.Department_Id','=','acd_allowed_sks.Department_Id')
        ->where('acd_allowed_sks.Allowed_Sks_Id', $id)
        ->orderBy('Ip_Max', 'asc')
        ->get();
        return view('acd_allowed_sks/edit')->with('query_edit',$data)->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);

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
          'Ip_Max'=>'required',
          'Sks_Max'=>'required',

        ]);
              $Department_Id = Input::get('Department_Id');
              $Ip_Max = Input::get('Ip_Max');
              $Sks_Max = Input::get('Sks_Max');

              try {
                $u =  DB::table('acd_allowed_sks')
                ->where('Allowed_Sks_Id',$id)
                ->update(
                ['Ip_Max' => $Ip_Max, 'Sks_Max' => $Sks_Max]);
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
     public function destroy($id)
     {
         $q=DB::table('acd_allowed_sks')->where('Allowed_Sks_Id', $id)->delete();
         echo json_encode($q);
     }
 }
