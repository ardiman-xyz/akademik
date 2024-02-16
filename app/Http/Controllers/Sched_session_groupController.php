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
use App\GetDepartment;

class Sched_session_groupController extends Controller
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
         $data = DB::table('acd_sched_session_group')
         ->leftjoin('mstr_department','acd_sched_session_group.Department_Id','=','mstr_department.Department_Id')
         ->orderBy('acd_sched_session_group.Sched_Session_Group_Code', 'asc')
         ->paginate($rowpage);
       }else {
         $data = DB::table('acd_sched_session_group')
         ->leftjoin('mstr_department','acd_sched_session_group.Department_Id','=','mstr_department.Department_Id')
         ->whereRaw("lower(Sched_Session_Group_Name) like '%" . strtolower($search) . "%'")
         ->orderBy('acd_sched_session_group.Sched_Session_Group_Code', 'asc')
         ->paginate($rowpage);
       }
       $data->appends(['search'=> $search, 'rowpage'=> $rowpage]);
       return view('acd_sched_session_group/index')->with('query',$data)->with('search',$search)->with('rowpage',$rowpage);

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

        $select_department = GetDepartment::getDepartment();
        return view('acd_sched_session_group/create')->with('search',$search)->with('page', $page)->with('rowpage', $rowpage)->with('select_department', $select_department);
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
         'Sched_Session_Group_Code'=>'required|max:6',
         'Sched_Session_Group_Name'=>'required',
       ]);
             $Sched_Session_Group_Code = Input::get('Sched_Session_Group_Code');
             $Sched_Session_Group_Name = Input::get('Sched_Session_Group_Name');

try {
       $u =  DB::table('acd_sched_session_group')
       ->insert(
       ['Sched_Session_Group_Code' => $Sched_Session_Group_Code, 'Sched_Session_Group_Name' => $Sched_Session_Group_Name, 'Department_Id' => $request->Department_Id]);
       return Redirect::back()->withErrors('Berhasil Menambah Grup Sesi Jadwal');
     } catch (\Exception $e) {
       return Redirect::back()->withErrors('Gagal Menyimpan Grup Sesi Jadwal');
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
       $data = DB::table('acd_sched_session_group')
       ->where('Sched_Session_Group_Id',$id)
       ->orderBy('acd_sched_session_group.Sched_Session_Group_Code', 'asc')
       ->get();
       return view('acd_sched_session_group/show')->with('query',$data)->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);

     }

     /**
      * Show the form for editing the specified resource.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function edit(Request $request,$id)
     {
       $search = Input::get('search');
       $page = Input::get('page');
       $rowpage = Input::get('rowpage');
       $select_department = GetDepartment::getDepartment();
       $data = DB::table('acd_sched_session_group')
       ->where('Sched_Session_Group_Id',$id)
       ->orderBy('acd_sched_session_group.Sched_Session_Group_Code', 'asc')
       ->get();
       return view('acd_sched_session_group/edit')
       ->with('select_department',$select_department)
       ->with('request',$request)
       ->with('query_edit',$data)
       ->with('search',$search)
       ->with('page', $page)
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
         'Sched_Session_Group_Code'=>'required|max:6',
         'Sched_Session_Group_Name'=>'required',
       ]);
             $Sched_Session_Group_Code = Input::get('Sched_Session_Group_Code');
             $Sched_Session_Group_Name = Input::get('Sched_Session_Group_Name');

             try {
               $u =  DB::table('acd_sched_session_group')
               ->where('Sched_Session_Group_Id',$id)
               ->update([
                'Sched_Session_Group_Code' => $Sched_Session_Group_Code, 
                'Sched_Session_Group_Name' => $Sched_Session_Group_Name,
                'Department_Id' => $request->Department_Id
              ]);
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
      $rs=DB::table('acd_sched_session_group')->where('Sched_Session_Group_Id', $id)->delete();
      echo json_encode($rs);
     }
}
