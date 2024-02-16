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
use Auth;
use App\GetDepartment;

class Approved_krsController extends Controller
{
  public function __construct()
  {
     $this->middleware('access:CanView', ['only' => ['index','approved_store']]);
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
       $term_year = Input::get('term_year');
       if ($rowpage == null || $rowpage <= 0) {
         $rowpage = 10;
       }
       $Event_Id = Input::get('event_id');
       $FacultyId = Auth::user()->Faculty_Id;
       $DepartmentId = Auth::user()->Department_Id;

       $select_event = DB::table('mstr_event')
       ->orderBy('mstr_event.Event_Id', 'asc')
       ->get();

       $mstr_term_year = DB::table('mstr_term_year')
       ->orderBy('Year_Id', 'desc','Term_Id','asc')
       ->get();       


      $select_department = GetDepartment::getDepartment();
      // dd($select_department);

if($FacultyId==""){
  if($DepartmentId == ""){
      if ($search == null) {
      $data = DB::table('mstr_department')
      ->where('Faculty_Id','!=',null)
      ->paginate($rowpage);

    }else {
      $data = DB::table('mstr_event_sched')
      ->join('mstr_event','mstr_event.Event_Id','=','mstr_event_sched.Event_Id')
      ->join('mstr_department','mstr_department.Department_Id','=','mstr_event_sched.Department_Id')
      ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','mstr_event_sched.Term_Year_Id')
      // ->where('mstr_event_sched.Event_Id', $Event_Id)
      ->whereRaw("lower(Department_Name) like '%" . strtolower($search) . "%'")
      
      ->orderBy('mstr_event_sched.Term_Year_Id', 'desc')
      ->select('mstr_event_sched.*','mstr_term_year.Term_Year_Name','mstr_department.Department_Name')
      ->paginate($rowpage);
    }
  }else{
    if ($search == null) {
      $data = DB::table('mstr_event_sched')
      ->join('mstr_event','mstr_event.Event_Id','=','mstr_event_sched.Event_Id')
      ->join('mstr_department','mstr_department.Department_Id','=','mstr_event_sched.Department_Id')
      ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','mstr_event_sched.Term_Year_Id')
      // ->where('mstr_event_sched.Event_Id', $Event_Id)
      ->where('mstr_department.Department_Id',$DepartmentId)
      
      ->orderBy('mstr_event_sched.Term_Year_Id', 'desc')
      ->select('mstr_event_sched.*','mstr_term_year.Term_Year_Name','mstr_department.Department_Name')
      ->paginate($rowpage);
    }else {
      $data = DB::table('mstr_event_sched')
      ->join('mstr_event','mstr_event.Event_Id','=','mstr_event_sched.Event_Id')
      ->join('mstr_department','mstr_department.Department_Id','=','mstr_event_sched.Department_Id')
      ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','mstr_event_sched.Term_Year_Id')
      // ->where('mstr_event_sched.Event_Id', $Event_Id)
      ->where('mstr_department.Department_Id',$DepartmentId)
      ->whereRaw("lower(Department_Name) like '%" . strtolower($search) . "%'")
      
      ->orderBy('mstr_event_sched.Term_Year_Id', 'desc')
      ->select('mstr_event_sched.*','mstr_term_year.Term_Year_Name','mstr_department.Department_Name')
      ->paginate($rowpage);
    }
  }
}else{
  if($DepartmentId == ""){
    if ($search == null) {
    $data = DB::table('mstr_event_sched')
      ->join('mstr_event','mstr_event.Event_Id','=','mstr_event_sched.Event_Id')
      ->join('mstr_department','mstr_department.Department_Id','=','mstr_event_sched.Department_Id')
      ->join('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
      ->where('mstr_faculty.Faculty_Id', $FacultyId)
      ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','mstr_event_sched.Term_Year_Id')
      // ->where('mstr_event_sched.Event_Id', $Event_Id)
      
      ->orderBy('mstr_event_sched.Term_Year_Id', 'desc')
      ->select('mstr_event_sched.*','mstr_term_year.Term_Year_Name','mstr_department.Department_Name')
      ->paginate($rowpage);
    }else {
      $data = DB::table('mstr_event_sched')
      ->join('mstr_event','mstr_event.Event_Id','=','mstr_event_sched.Event_Id')
      ->join('mstr_department','mstr_department.Department_Id','=','mstr_event_sched.Department_Id')
      ->join('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
      ->where('mstr_faculty.Faculty_Id', $FacultyId)
      ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','mstr_event_sched.Term_Year_Id')
      // ->where('mstr_event_sched.Event_Id', $Event_Id)
      ->whereRaw("lower(Department_Name) like '%" . strtolower($search) . "%'")
      
      ->orderBy('mstr_event_sched.Term_Year_Id', 'desc')
      ->select('mstr_event_sched.*','mstr_term_year.Term_Year_Name','mstr_department.Department_Name')
      ->paginate($rowpage);
    }
  }else{
    if ($search == null) {
    $data = DB::table('mstr_event_sched')
      ->join('mstr_event','mstr_event.Event_Id','=','mstr_event_sched.Event_Id')
      ->join('mstr_department','mstr_department.Department_Id','=','mstr_event_sched.Department_Id')
      ->join('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
      ->where('mstr_faculty.Faculty_Id', $FacultyId)
      ->where('mstr_department.Department_Id',$DepartmentId)
      ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','mstr_event_sched.Term_Year_Id')
      // ->where('mstr_event_sched.Event_Id', $Event_Id)
      
      ->orderBy('mstr_event_sched.Term_Year_Id', 'desc')
      ->select('mstr_event_sched.*','mstr_term_year.Term_Year_Name','mstr_department.Department_Name')
      ->paginate($rowpage);

      // $data = DB::table('mstr_department')
      // ->leftjoin('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
      // ->where('mstr_faculty.Faculty_Id', $FacultyId)
      // ->where('mstr_department.Department_Id',$DepartmentId)
      // ->paginate($rowpage);
      // dd($data->get(),$Event_Id);
    }else {
      $data = DB::table('mstr_event_sched')
      ->join('mstr_event','mstr_event.Event_Id','=','mstr_event_sched.Event_Id')
      ->join('mstr_department','mstr_department.Department_Id','=','mstr_event_sched.Department_Id')
      ->join('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
      ->where('mstr_faculty.Faculty_Id', $FacultyId)
      ->where('mstr_department.Department_Id',$DepartmentId)
      ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','mstr_event_sched.Term_Year_Id')
      // ->where('mstr_event_sched.Event_Id', $Event_Id)
      ->whereRaw("lower(Department_Name) like '%" . strtolower($search) . "%'")
      
      ->orderBy('mstr_event_sched.Term_Year_Id', 'desc')
      ->select('mstr_event_sched.*','mstr_term_year.Term_Year_Name','mstr_department.Department_Name')
      ->paginate($rowpage);
    }
  }
}

       // $data->appends(['event_id'=> $Event_Id, 'search'=> $search, 'rowpage'=> $rowpage, 'term_year'=>$term_year]);
       return view('acd_approved_krs/index')
       ->with('query',$select_department)
       ->with('mstr_term_year',$mstr_term_year)
       ->with('term_year',$term_year)
       ->with('search',$search)
       ->with('rowpage',$rowpage)->with('select_event', $select_event)->with('event_id', $Event_Id);
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
     public function create()
     {
       dd('here');
         $Event_Id = Input::get('event_id');
         $event = DB::table('mstr_event')->where('Event_Id', $Event_Id)->get();

         $search = Input::get('search');
         $page = Input::get('page');
         $rowpage = Input::get('rowpage');
         $FacultyId = Auth::user()->Faculty_Id;
         $DepartmentId = Auth::user()->Department_Id;

if($FacultyId==""){
  if($DepartmentId == ""){
    $select_department = DB::table('mstr_department')->wherenotnull('Faculty_Id')->get();
  }else{
    $select_department = DB::table('mstr_department')->wherenotnull('Faculty_Id')->where('mstr_department.Department_Id',$DepartmentId)->get();
  }
}else{
  if($DepartmentId == ""){
    $select_department = DB::table('mstr_department')
  ->join('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
  ->where('mstr_faculty.Faculty_Id', $FacultyId)->get();
  }else{
    $select_department = DB::table('mstr_department')
  ->join('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
  ->where('mstr_faculty.Faculty_Id', $FacultyId)
  ->where('mstr_department.Department_Id',$DepartmentId)->get();
  }
}

         $select_term_year = DB::table('mstr_term_year')->orderBy('Term_Year_Name', 'desc')->get();

         return view('acd_approved_krs/create')->with('event', $event)->with('event_id', $Event_Id)->with('select_department',$select_department)->with('select_term_year', $select_term_year)->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);
     }

     /**
      * Store a newly created resource in storage.
      *
      * @param  \Illuminate\Http\Request  $request
      * @return \Illuminate\Http\Response
      */
     public function store(Request $request)
     {
       $Event_Id = Input::get('Event_Id');
        $Department_Id = Input::get('Department_Id');
        $Term_Year_Id = Input::get('Term_Year_Id');
        $Is_Open = Input::get('Is_Open');
        $Start_Date = Input::get('Start_Date');
        $End_Date = Input::get('End_Date');
        $End_Date_Cost = Input::get('End_Date_Cost');
       if($Event_Id == 1){
          $this->validate($request,[
          'Event_Id'=>'required',
          'Department_Id' => 'required',
          'Term_Year_Id' => 'required',
          'Is_Open' => 'required',
          'Start_Date' => 'required',
          'End_Date' => 'required',
          'End_Date_Cost' => 'required',
        ]);

        $u =  DB::table('mstr_event_sched')
          ->insert(
                  ['Event_Id' => $Event_Id,
                   'Department_Id' => $Department_Id,
                   'Term_Year_Id' => $Term_Year_Id,
                   'Is_Open' => $Is_Open,
                   'Start_Date' => $Start_Date,
                   'End_Date' => $End_Date,
                   'End_Date_Cost' => $End_Date_Cost]);
          return Redirect::to('/setting/event_sched?event_id='.$Event_Id)->withErrors('Berhasil Menambah Jadwal Pengisian');
       }else{
          $this->validate($request,[
          'Event_Id'=>'required',
          'Department_Id' => 'required',
          'Term_Year_Id' => 'required',
          'Is_Open' => 'required',
          'Start_Date' => 'required',
          'End_Date' => 'required',
        ]);

        $u =  DB::table('mstr_event_sched')
          ->insert(
                  ['Event_Id' => $Event_Id,
                   'Department_Id' => $Department_Id,
                   'Term_Year_Id' => $Term_Year_Id,
                   'Is_Open' => $Is_Open,
                   'Start_Date' => $Start_Date,
                   'End_Date' => $End_Date]);
          return Redirect::to('/setting/event_sched?event_id='.$Event_Id)->withErrors('Berhasil Menambah Jadwal Pengisian');
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
       dd('edit');
       $FacultyId = Auth::user()->Faculty_Id;
       $DepartmentId = Auth::user()->Department_Id;
       $Event_Id = Input::get('eventnya');

      if($FacultyId==""){
        if($DepartmentId == ""){
          $data = DB::table('mstr_event_sched')
          ->join('mstr_event','mstr_event.Event_Id','=','mstr_event_sched.Event_Id')
          ->join('mstr_department','mstr_department.Department_Id','=','mstr_event_sched.Department_Id')
          ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','mstr_event_sched.Term_Year_Id')
          ->where('mstr_event_sched.Event_Sched_Id', $id)
          ->select('mstr_event_sched.*','mstr_event.Event_Name','mstr_term_year.Term_Year_Name','mstr_department.Department_Name')
          ->get();

          $select_department = DB::table('mstr_department')->wherenotnull('Faculty_Id')->get();
        }else{
          $data = DB::table('mstr_event_sched')
          ->join('mstr_event','mstr_event.Event_Id','=','mstr_event_sched.Event_Id')
          ->join('mstr_department','mstr_department.Department_Id','=','mstr_event_sched.Department_Id')
          ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','mstr_event_sched.Term_Year_Id')
          ->where('mstr_event_sched.Event_Sched_Id', $id)
          ->where('mstr_department.Department_Id',$DepartmentId)
          ->select('mstr_event_sched.*','mstr_event.Event_Name','mstr_term_year.Term_Year_Name','mstr_department.Department_Name')
          ->get();

          $select_department = DB::table('mstr_department')->where('mstr_department.Department_Id',$DepartmentId)->wherenotnull('Faculty_Id')->get();
        }
      }else{
        if($DepartmentId == ""){
          $data = DB::table('mstr_event_sched')
        ->join('mstr_event','mstr_event.Event_Id','=','mstr_event_sched.Event_Id')
        ->join('mstr_department','mstr_department.Department_Id','=','mstr_event_sched.Department_Id')
        ->join('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
        ->where('mstr_faculty.Faculty_Id', $FacultyId)
        ->where('mstr_department.Department_Id',$DepartmentId)
        ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','mstr_event_sched.Term_Year_Id')
        ->where('mstr_event_sched.Event_Sched_Id', $id)
        ->select('mstr_event_sched.*','mstr_event.Event_Name','mstr_term_year.Term_Year_Name','mstr_department.Department_Name')
        ->get();

        $select_department = DB::table('mstr_department')->join('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
        ->where('mstr_department.Department_Id',$DepartmentId)
        ->where('mstr_faculty.Faculty_Id', $FacultyId)->get();
        }else{

        }
      }


       $search = Input::get('search');
       $page = Input::get('page');
       $rowpage = Input::get('rowpage');


       $select_term_year = DB::table('mstr_term_year')->orderBy('Term_Year_Name', 'desc')->get();

       return view('acd_approved_krs/edit')->with('query_edit', $data)->with('Event_Id',$Event_Id)->with('select_department', $select_department)->with('select_term_year',$select_term_year)->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);;
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
       $Event_Id = Input::get('eventnya');
       $Department_Id = Input::get('Department_Id');
        $Term_Year_Id = Input::get('Term_Year_Id');
        $Is_Open = Input::get('Is_Open');
        $Start_Date = Input::get('Start_Date');
        $End_Date = Input::get('End_Date');
        $End_Date_Cost = Input::get('End_Date_Cost');

        if($Event_Id == 1){
          $this->validate($request,[
            'Department_Id' => 'required',
            'Term_Year_Id' => 'required',
            'Is_Open' => 'required',
            'Start_Date' => 'required',
            'End_Date' => 'required',
            'End_Date_Cost' => 'required',
          ]);

          try {
            $u =  DB::table('mstr_event_sched')
            ->where('Event_Sched_Id',$id)
            ->update(
            ['Department_Id' => $Department_Id,'Term_Year_Id' => $Term_Year_Id,'Is_Open' => $Is_Open,'Start_Date' => $Start_Date,'End_Date' => $End_Date,'End_Date_Cost'=>$End_Date_Cost]);
            return Redirect::to('/setting/event_sched?event_id='.$Event_Id)->withErrors('Berhasil Menyimpan Perubahan');
          } catch (\Exception $e) {
            return Redirect::back()->withErrors('Gagal Menyimpan Perubahan');
          }
        }else{
          $this->validate($request,[
            'Department_Id' => 'required',
            'Term_Year_Id' => 'required',
            'Is_Open' => 'required',
            'Start_Date' => 'required',
            'End_Date' => 'required',
          ]);

          try {
            $u =  DB::table('mstr_event_sched')
            ->where('Event_Sched_Id',$id)
            ->update(
            ['Department_Id' => $Department_Id,'Term_Year_Id' => $Term_Year_Id,'Is_Open' => $Is_Open,'Start_Date' => $Start_Date,'End_Date' => $End_Date]);
            return Redirect::to('/setting/event_sched?event_id='.$Event_Id)->withErrors('Berhasil Menyimpan Perubahan');
          } catch (\Exception $e) {
            return Redirect::back()->withErrors('Gagal Menyimpan Perubahan');
          }
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
      $q = DB::table('mstr_event_sched')->where('Event_Sched_Id', $id)->delete();
      echo json_encode($q);
     }

     public function approved_store(Request $request)
     {
       try{
         $term_year = $request->Term_Year_Id;
         $department = $request->Department_Id;
         $update = DB::table('acd_student_krs as a')
                  ->join('acd_student as b','a.Student_Id','=','b.Student_Id')
                  ->where('a.Term_Year_Id', $term_year )
                  ->where('a.Is_Approved',null)
                  ->where('b.Department_Id',$department)
                  ->update(['Is_Approved'=>1, 'Approved_By'=>'Admin']);
        return response()->json([
                  'status' => 200,
                  'message' => 'Sukses.',
              ]);
       }catch (\Throwable $th) {
            return response()->json([
                "success" => false,
                "message" => $th,
            ], 200);
        }
     }

     public function approved_rollback_store(Request $request)
     {
       $term_year = $request->Term_Year_Id;
       $department = $request->Department_Id;
       $update = DB::table('acd_student_krs as a')
                ->join('acd_student as b','a.Student_Id','=','b.Student_Id')
                ->where('a.Term_Year_Id', $term_year )
                ->where('a.Approved_By','Admin')
                ->where('b.Department_Id',$department)
                ->update(['Is_Approved'=>null, 'Approved_By'=>null]);
      return response()->json([
                'status' => 200,
                'message' => 'Sukses.',
            ]);
     }
 }
