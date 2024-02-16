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

class EmployeeController extends Controller
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


       if ($search == null) {
         $data = DB::table('emp_employee')
         ->leftjoin('emp_employee_status', 'emp_employee_status.Employee_Status_Id' , '=' , 'emp_employee.Employee_Status_Id')
         ->orderBy('emp_employee.Nik', 'asc')
         ->paginate($rowpage);
       }else {
         $data = DB::table('emp_employee')
         ->leftjoin('emp_employee_status', 'emp_employee_status.Employee_Status_Id' , '=' , 'emp_employee.Employee_Status_Id')
         ->where(function($query){
           $search = Input::get('search');
           $query->whereRaw("lower(Full_Name) like '%" . strtolower($search) . "%'");
           $query->orwhere('Nik', 'LIKE', '%'.$search.'%');
         })
         ->orderBy('emp_employee.Nik', 'asc')
         ->paginate($rowpage);
       }
       $data->appends(['search'=> $search, 'rowpage'=> $rowpage]);
       return view('emp_employee/index')->with('query',$data)->with('search',$search)->with('rowpage',$rowpage);

     }

     /**
      * Show the form for creating a new resource.
      *
      * @return \Illuminate\Http\Response
      */
     public function create()
     {
       $search = Input::get('search');
       $page = Input::get('page');
       $rowpage = Input::get('rowpage');

       $select_status = DB::table('emp_employee_status')->get();
       return view('emp_employee/create')->with('search',$search)->with('select_status', $select_status)->with('page', $page)->with('rowpage', $rowpage);
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
         'Nik'=>'required',
         'Name'=>'required',
       ]);
             $Nik = Input::get('Nik');
             $Nip = Input::get('Nip');
             $Name = Input::get('Name');
             $First_Title = Input::get('First_Title');
             $Last_Title = input::get('Last_Title');
             $Employee_Status_Id = Input::get('Employee_Status_Id');
             $Email_Corporate = Input::get('Email_Corporate');

             $Full_Name = $First_Title." ".$Name." ".$Last_Title;

try {
       $u =  DB::table('emp_employee')
       ->insert(
       ['Nik' => $Nik, 'Nip' => $Nip, 'Name' => $Name, 'First_Title' => $First_Title, 'Last_Title' => $Last_Title, 'Full_Name' => $Full_Name, 'Employee_Status_Id' => $Employee_Status_Id, 'Email_Corporate' => $Email_Corporate]);
       return Redirect::back()->withErrors('Berhasil Menambah Pegawai / Dosen');
     } catch (\Exception $e) {
       return Redirect::back()->withErrors('Gagal Menambah Pegawai / Dosen');
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
       $search = Input::get('search');
       $page = Input::get('page');
       $rowpage = Input::get('rowpage');
       $data = DB::table('emp_employee')
       ->where('Employee_Id',$id)
       ->orderBy('emp_employee.Nik', 'asc')
       ->get();
       return view('emp_employee/show')->with('query',$data)->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);

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
       $data = DB::table('emp_employee')
       ->where('Employee_Id',$id)
       ->orderBy('emp_employee.Nik', 'asc')
       ->get();
       $select_status = DB::table('emp_employee_status')->get();

       return view('emp_employee/edit')->with('query_edit',$data)->with('select_status', $select_status)->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);

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
         'Nik'=>'required',
         'Name'=>'required',
       ]);
             $Nik = Input::get('Nik');
             $Nip = Input::get('Nip');
             $Name = Input::get('Name');
             $First_Title = Input::get('First_Title');
             $Last_Title = input::get('Last_Title');
             $Employee_Status_Id = Input::get('Employee_Status_Id');
             $Email_Corporate = Input::get('Email_Corporate');

             $Full_Name = $First_Title." ".$Name." ".$Last_Title;

             try {
               $u =  DB::table('emp_employee')
               ->where('Employee_Id',$id)
               ->update(
                ['Nik' => $Nik, 'Nip' => $Nip, 'Name' => $Name, 'First_Title' => $First_Title, 'Last_Title' => $Last_Title, 'Full_Name' => $Full_Name, 'Employee_Status_Id' => $Employee_Status_Id, 'Email_Corporate' => $Email_Corporate]);
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
         $rs=DB::table('emp_employee')->where('Employee_Id', $id)->delete();
         echo json_encode($rs);
     }
 }
