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
use Auth;

class Sched_sessionController extends Controller
{
  public function __construct()
  {
    $this->middleware('access:CanView', ['except' => ['create','store','edit','update','destroy','session']]);
    $this->middleware('access:CanAdd', ['except' => ['index','show','edit','update','destroy','session']]);
    $this->middleware('access:CanEdit', ['except' => ['index','create','store','show','destroy','session']]);
    $this->middleware('access:CanDelete', ['except' => ['index','create','update','store','show','edit','session']]);
}
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index(Request $request)
     {
       // $search = Input::get('search');
       // $rowpage = Input::get('rowpage');
       $Sched_Session_Group_Id = Input::get('sched_session_group_id');
       $Sched_Type_Id = Input::get('sched_type_id');

       // if ($rowpage == null) {
       //   $rowpage = 10;
       // }
       $select_department = GetDepartment::getDepartment();
        $arr = [];
        $q = 0;
        foreach ($select_department as $key) {
          $arr[$q] = $key->Department_Id;
          $q++;
        }
        
        $term_year1 = $request->term_year;
        if($term_year1 == null){
          $term_year =  $request->session()->get('term_year');
        }
        $select_term_year = DB::table('mstr_term_year')->orderBy('Term_Year_Id','desc')->get();

       $select_sched_session_group = DB::table('acd_sched_session_group')
       ->wherein('Department_Id',$arr)
        ->orwhere('Department_Id',null)
       ->orderBy('Sched_Session_Group_Name', 'asc')
       ->get();
       $select_sched_type = DB::table('mstr_sched_type')
       ->orderBy('Sched_Type_Name', 'asc')
       ->get();

         $order = DB::table('acd_sched_session')
         ->join('acd_sched_session_group','acd_sched_session_group.Sched_Session_Group_Id','=','acd_sched_session.Sched_Session_Group_Id')
         ->join('mstr_sched_type','mstr_sched_type.Sched_Type_Id','=','acd_sched_session.Sched_Type_Id')
         ->join('mstr_day', 'mstr_day.Day_Id' ,'=' , 'acd_sched_session.Day_Id')
         ->where('acd_sched_session.Sched_Session_Group_Id', $Sched_Session_Group_Id)
         ->where('acd_sched_session.Sched_Type_Id', $Sched_Type_Id)
         ->where('acd_sched_session.Term_Year_Id', $request->term_year)
         ->orderBy('Order_Id', 'asc')
         ->orderBy('acd_sched_session.Day_Id', 'asc')
         ->get();


         $data = DB::table('acd_sched_session')
         ->join('acd_sched_session_group','acd_sched_session_group.Sched_Session_Group_Id','=','acd_sched_session.Sched_Session_Group_Id')
         ->join('mstr_sched_type','mstr_sched_type.Sched_Type_Id','=','acd_sched_session.Sched_Type_Id')
         ->join('mstr_day', 'mstr_day.Day_Id' ,'=' , 'acd_sched_session.Day_Id')
         ->where('acd_sched_session.Sched_Session_Group_Id', $Sched_Session_Group_Id)
         ->where('acd_sched_session.Sched_Type_Id', $Sched_Type_Id)
         ->where('acd_sched_session.Term_Year_Id', $request->term_year)
         ->select('acd_sched_session.*','mstr_day.Day_Name'
           // DB::raw('(SELECT Group_Concat(Sched_Session_Id SEPARATOR "|")  FROM acd_sched_session as schedsession WHERE schedsession.Sched_Session_Group_Id = Sched_Session_Group_Id AND schedsession.Sched_Type_Id = Sched_Type_Id GROUP BY schedsession.Order_Id ORDER BY schedsession.Order_Id) as sched_id'),
           // DB::raw('(SELECT Group_Concat(Time_Start SEPARATOR "|") FROM acd_sched_session as schedsession WHERE schedsession.Sched_Session_Group_Id = Sched_Session_Group_Id AND schedsession.Sched_Type_Id = Sched_Type_Id GROUP BY schedsession.Order_Id ORDER BY schedsession.Order_Id) as start_time'),
           // DB::raw('(SELECT Group_Concat(Time_End SEPARATOR "|") FROM acd_sched_session as schedsession WHERE schedsession.Sched_Session_Group_Id = Sched_Session_Group_Id AND schedsession.Sched_Type_Id = Sched_Type_Id GROUP BY schedsession.Order_Id ORDER BY schedsession.Order_Id) as end_time')
           )
         ->groupBy('acd_sched_session.Day_Id')
         ->orderBy('acd_sched_session.Day_Id', 'asc')
         ->orderBy('Order_Id', 'asc')
         ->get();



       // $data->appends(['search'=> $search, 'rowpage'=> $rowpage, 'Sched_Session_Group_Id'=> $Sched_Session_Group_Id, 'Sched_Type_Id'=> $Sched_Type_Id]);
       return view('acd_sched_session/index')
       ->with('query',$data)
       ->with('select_term_year',$select_term_year)
       ->with('request',$request)
       ->with('order', $order)
       ->with('select_sched_session_group', $select_sched_session_group)
       ->with('select_sched_type', $select_sched_type)
       ->with('Sched_Session_Group_Id', $Sched_Session_Group_Id)
       ->with('Sched_Type_Id', $Sched_Type_Id);
     }
     // public function modal()
     // {
     //   return view('mstr_faculty/modal');
     // }
     /**
      * Show the form for creating a new resource.
      *
      * @return \Illuminate\Http\Response
      */
     public function create(Request $request)
     {
         $Sched_Session_Group_Id = Input::get('sched_session_group_id');
         $Sched_Type_Id = Input::get('sched_type_id');


         $data = DB::table('acd_sched_session')
         ->join('acd_sched_session_group','acd_sched_session_group.Sched_Session_Group_Id','=','acd_sched_session.Sched_Session_Group_Id')
         ->join('mstr_sched_type','mstr_sched_type.Sched_Type_Id','=','acd_sched_session.Sched_Type_Id')
         ->join('mstr_day', 'mstr_day.Day_Id' ,'=' , 'acd_sched_session.Day_Id')
         ->where('acd_sched_session.Sched_Session_Group_Id', $Sched_Session_Group_Id)
         ->where('acd_sched_session.Sched_Type_Id', $Sched_Type_Id)
         ->where('acd_sched_session.Term_Year_Id', $request->term_year)
         ->first();

         $type = DB::table('mstr_sched_type')->where('Sched_Type_Id', $Sched_Type_Id)->first();
         $session_group = DB::table('acd_sched_session_group')->where('Sched_Session_Group_Id', $Sched_Session_Group_Id)->first();

         $select_day = DB::table('mstr_day')->get();
         return view('acd_sched_session/create')
         ->with('request', $request)
         ->with('session_group', $session_group)
         ->with('type', $type)
         ->with('data', $data)
         ->with('Sched_Session_Group_Id', $Sched_Session_Group_Id)
         ->with('Sched_Type_Id',$Sched_Type_Id)
         ->with('select_day', $select_day);
     }


     public function session(Request $request)
     {
       $Sched_Session_Group_Id = Input::get('sched_session_group_id');
       $Sched_Type_Id = Input::get('sched_type_id');
       $Day_Id = Input::get('day_id');

       $data = DB::table('acd_sched_session')
       ->join('acd_sched_session_group','acd_sched_session_group.Sched_Session_Group_Id','=','acd_sched_session.Sched_Session_Group_Id')
       ->join('mstr_sched_type','mstr_sched_type.Sched_Type_Id','=','acd_sched_session.Sched_Type_Id')
       ->join('mstr_day', 'mstr_day.Day_Id' ,'=' , 'acd_sched_session.Day_Id')
       ->where('acd_sched_session.Sched_Session_Group_Id', $Sched_Session_Group_Id)
       ->where('acd_sched_session.Sched_Type_Id', $Sched_Type_Id)
       ->where('acd_sched_session.Term_Year_Id', $request->term_year)
       ->where('acd_sched_session.Day_Id', $Day_Id)
       ->select('Order_Id')->get();

       return view('acd_sched_session/session')->with('query', $data);


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
         'Sched_Session_Group_Id' => 'required',
         'Sched_Type_Id' => 'required',

         'Day_Id'=>'required',
         'Order_Id' => 'required',
         'Time_Start' => 'required',
         'Time_End' => 'required',

       ]);
             $Sched_Session_Group_Id = Input::get('Sched_Session_Group_Id');
             $Sched_Type_Id = Input::get('Sched_Type_Id');
             $Day_Id = Input::get('Day_Id');
             $Order_Id = Input::get('Order_Id');
             $Time_Start = Input::get('Time_Start');
             $Time_End = Input::get('Time_End');

             $day = DB::table('mstr_day')->where('Day_Id', $Day_Id)->first();
             $Description = $day->Day_Name." ".$Time_Start." - ".$Time_End;



       $u =  DB::table('acd_sched_session')
       ->insert([
        'Sched_Session_Group_Id' => $Sched_Session_Group_Id, 
        'Sched_Type_Id' => $Sched_Type_Id, 
        'Day_Id' => $Day_Id, 
        'Order_Id' => $Order_Id, 
        'Term_Year_Id' => $request->term_year, 
        'Time_Start' => $Time_Start, 
        'Time_End' => $Time_End,
        'Description' => $Description,
        'Created_Date' => Date('Y-m-d'), 
        'Created_By' => Auth::user()->email
      ]);
       return Redirect::back()->withErrors('Berhasil Menambah Sesi Jadwal');
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
     public function edit(Request $request,$id)
     {
       $data = DB::table('acd_sched_session')
       ->join('acd_sched_session_group','acd_sched_session_group.Sched_Session_Group_Id','=','acd_sched_session.Sched_Session_Group_Id')
       ->join('mstr_sched_type','mstr_sched_type.Sched_Type_Id','=','acd_sched_session.Sched_Type_Id')
       ->join('mstr_day', 'mstr_day.Day_Id' ,'=' , 'acd_sched_session.Day_Id')
       ->where('acd_sched_session.Sched_Session_Id', $id)
       ->first();

       return view('acd_sched_session/edit')->with('data', $data)->with('request', $request);
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
         'Time_Start' => 'required',
         'Time_End' => 'required',
         'Day_Id' => 'required',
       ]);
             $Day_Id = Input::get('Day_Id');
             $Time_Start = Input::get('Time_Start');
             $Time_End = Input::get('Time_End');

             $day = DB::table('mstr_day')->where('Day_Id', $Day_Id)->first();
             $Description = $day->Day_Name." ".$Time_Start." - ".$Time_End;



       $u =  DB::table('acd_sched_session')
       ->where('Sched_Session_Id', $id)
       ->update(
       ['Time_Start' => $Time_Start, 'Time_End' => $Time_End,'Description' => $Description]);
       return Redirect::back()->withErrors('Berhasil Menyimpan Perubahan');
     }

     /**
      * Remove the specified resource from storage.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function destroy(Request $request,$id)
     {
         $q=DB::table('acd_sched_session')->where('Sched_Session_Id', $id)->delete();
         echo json_encode($q);
     }
}
