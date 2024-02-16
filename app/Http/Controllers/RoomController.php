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

class RoomController extends Controller
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
       $building = Input::get('building');

       $select_gedung = DB::table('mstr_building')
       ->orderBy('mstr_building.Building_Code', 'asc')
       ->get();

       $term_year1 = Input::get('term_year');
       if($term_year1 == null){
        $term_year =  $request->session()->get('term_year');
       }else{
        $term_year = Input::get('term_year');
       }

       $select_term_year = DB::table('mstr_term_year')
       ->orderBy('mstr_term_year.Term_Year_Name', 'desc')
       ->get();


       if ($search == null) {
         $data = DB::table('mstr_room')
         ->join('mstr_building','mstr_building.Building_Id','=','mstr_room.Building_Id')
         ->join('acd_sched_session_group','acd_sched_session_group.Sched_Session_Group_Id','=','mstr_room.Sched_Session_Group_Id')
         ->where('mstr_room.Building_Id', $building)
         ->where('mstr_room.Term_Year_Id', $term_year)
         ->orderBy('mstr_room.Room_Code', 'asc')
         ->paginate($rowpage);
       }else {
         $data = DB::table('mstr_room')
         ->join('mstr_building','mstr_building.Building_Id','=','mstr_room.Building_Id')
         ->join('acd_sched_session_group','acd_sched_session_group.Sched_Session_Group_Id','=','mstr_room.Sched_Session_Group_Id')
         ->where('mstr_room.Building_Id', $building)
         ->where('mstr_room.Term_Year_Id', $term_year)
         ->whereRaw("lower(Room_Name) like '%" . strtolower($search) . "%'")
         ->orderBy('mstr_room.Room_Code', 'asc')
         ->paginate($rowpage);
       }
       $data->appends(['search'=> $search, 'rowpage'=> $rowpage, 'building'=> $building]);
       return view('mstr_room/index')
       ->with('term_year', $term_year)
       ->with('select_term_year', $select_term_year)
       ->with('select_gedung', $select_gedung)
       ->with('building', $building)
       ->with('query',$data)
       ->with('search',$search)
       ->with('rowpage',$rowpage);

     }

     /**
      * Show the form for creating a new resource.
      *
      * @return \Illuminate\Http\Response
      */
     public function create(Request $request)
     {
       $building = Input::get('building');
       $search = Input::get('search');
       $page = Input::get('page');
       $rowpage = Input::get('rowpage');
       $gedung = DB::table('mstr_building')->where('Building_Id', $building)->get();
       $sched_session_group = DB::table('acd_sched_session_group')->get();
       return view('mstr_room/create')
       ->with('request', $request)
       ->with('building', $building)
       ->with('gedung', $gedung)
       ->with('sched_session_group', $sched_session_group)
       ->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);
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
         'Room_Code'=>'required|max:6',
         'Room_Name'=>'required',
         'Is_Active'=>'required',
         'Sched_Session_Group_Id'=>'required',
         'Capacity' => 'numeric',

       ]);
             $Building_Id  = Input::get('Building_Id');
             $Sched_Session_Group_Id = Input::get('Sched_Session_Group_Id');
             $Room_Code = Input::get('Room_Code');
             $Room_Name = Input::get('Room_Name');
             $Description = Input::get('Description');
             $Capacity = Input::get('Capacity');
             $Capacity_Exam = Input::get('Capacity_Exam');
             $Acronym = Input::get('Acronym');
             $Is_Active = Input::get('Is_Active');

 try {
       $u =  DB::table('mstr_room')
       ->insert(
       ['Room_Code' => $Room_Code, 
       'Room_Name' => $Room_Name, 
       'Building_Id' => $Building_Id, 
       'Sched_Session_Group_Id' => $Sched_Session_Group_Id, 
       'Description' => $Description, 
       'Capacity' => $Capacity, 
       'Capacity_Exam' => $Capacity_Exam, 
       'Term_Year_Id' => $request->term_year, 
       'Acronym' => $Acronym, 
       'Is_Active' => $Is_Active]);
       return Redirect::back()->withErrors('Berhasil Menambah Gedung');
     } catch (\Exception $e) {
       return Redirect::back()->withErrors('Gagal Menambah Gedung');
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
     public function edit(Request $request,$id)
     {
       $search = Input::get('search');
       $page = Input::get('page');
       $rowpage = Input::get('rowpage');
       $data = DB::table('mstr_room')

       ->where('Room_Id',$id)
       ->join('mstr_building','mstr_building.Building_Id','=','mstr_room.Building_Id')
       ->join('acd_sched_session_group','acd_sched_session_group.Sched_Session_Group_Id','=','mstr_room.Sched_Session_Group_Id')
       ->orderBy('mstr_room.Room_Code', 'asc')
       ->get();
       $sched_session_group = DB::table('acd_sched_session_group')->get();
       return view('mstr_room/edit')
       ->with('request',$request)
       ->with('query_edit',$data)
       ->with('sched_session_group', $sched_session_group)
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
         'Room_Code'=>'required|max:6',
         'Room_Name'=>'required',
         'Is_Active'=>'required',
         'Sched_Session_Group_Id'=>'required',
         'Capacity' => 'numeric',
         'Capacity_Exam' => 'numeric',

       ]);
             $Building_Id  = Input::get('Building_Id');
             $Sched_Session_Group_Id = Input::get('Sched_Session_Group_Id');
             $Room_Code = Input::get('Room_Code');
             $Room_Name = Input::get('Room_Name');
             $Description = Input::get('Description');
             $Capacity = Input::get('Capacity');
             $Capacity_Exam = Input::get('Capacity_Exam');
             $Acronym = Input::get('Acronym');
             $Is_Active = Input::get('Is_Active');

             try {
               $u =  DB::table('mstr_room')
               ->where('Room_Id',$id)
               ->update(
                ['Room_Code' => $Room_Code, 
                'Room_Name' => $Room_Name, 
                'Building_Id' => $Building_Id, 
                'Sched_Session_Group_Id' => $Sched_Session_Group_Id, 
                'Description' => $Description, 
                'Capacity' => $Capacity, 
                'Capacity_Exam' => $Capacity_Exam, 
                'Acronym' => $Acronym, 
                'Is_Active' => $Is_Active]);
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
         $q=DB::table('mstr_room')->where('Room_Id', $id)->delete();
         echo json_encode($q);
     }
}
