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

class Approved_krsstoreController extends Controller
{
  public function __construct()
  {
    //  $this->middleware('access:CanView', ['only' => ['index','approved_store']]);
    //  $this->middleware('access:CanAdd', ['except' => ['index','show','edit','update','destroy']]);
    //  $this->middleware('access:CanEdit', ['except' => ['index','create','store','show','destroy']]);
    //  $this->middleware('access:CanDelete', ['except' => ['index','create','store','show','edit','update']]);

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
      ->where('mstr_event_sched.Event_Id', $Event_Id)
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
      ->where('mstr_event_sched.Event_Id', $Event_Id)
      ->where('mstr_department.Department_Id',$DepartmentId)
      
      ->orderBy('mstr_event_sched.Term_Year_Id', 'desc')
      ->select('mstr_event_sched.*','mstr_term_year.Term_Year_Name','mstr_department.Department_Name')
      ->paginate($rowpage);
    }else {
      $data = DB::table('mstr_event_sched')
      ->join('mstr_event','mstr_event.Event_Id','=','mstr_event_sched.Event_Id')
      ->join('mstr_department','mstr_department.Department_Id','=','mstr_event_sched.Department_Id')
      ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','mstr_event_sched.Term_Year_Id')
      ->where('mstr_event_sched.Event_Id', $Event_Id)
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
      ->where('mstr_event_sched.Event_Id', $Event_Id)
      
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
      ->where('mstr_event_sched.Event_Id', $Event_Id)
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
      ->where('mstr_event_sched.Event_Id', $Event_Id)
      
      ->orderBy('mstr_event_sched.Term_Year_Id', 'desc')
      ->select('mstr_event_sched.*','mstr_term_year.Term_Year_Name','mstr_department.Department_Name')
      ->paginate($rowpage);
    }else {
      $data = DB::table('mstr_event_sched')
      ->join('mstr_event','mstr_event.Event_Id','=','mstr_event_sched.Event_Id')
      ->join('mstr_department','mstr_department.Department_Id','=','mstr_event_sched.Department_Id')
      ->join('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
      ->where('mstr_faculty.Faculty_Id', $FacultyId)
      ->where('mstr_department.Department_Id',$DepartmentId)
      ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','mstr_event_sched.Term_Year_Id')
      ->where('mstr_event_sched.Event_Id', $Event_Id)
      ->whereRaw("lower(Department_Name) like '%" . strtolower($search) . "%'")
      
      ->orderBy('mstr_event_sched.Term_Year_Id', 'desc')
      ->select('mstr_event_sched.*','mstr_term_year.Term_Year_Name','mstr_department.Department_Name')
      ->paginate($rowpage);
    }
  }
}

       $data->appends(['event_id'=> $Event_Id, 'search'=> $search, 'rowpage'=> $rowpage]);
       return view('acd_approved_krs/index')
       ->with('query',$data)
       ->with('mstr_term_year',$mstr_term_year)
       ->with('term_year',$term_year)
       ->with('search',$search)
       ->with('rowpage',$rowpage)->with('select_event', $select_event)->with('event_id', $Event_Id);
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
          $krs = DB::table('acd_student_krs as a')
                    ->join('acd_student as b','a.Student_Id','=','b.Student_Id')
                    ->where('a.Term_Year_Id', $term_year )
                    ->where('a.Is_Approved',null)
                    ->where('b.Department_Id',$department)
                    ->get();

          $datas = DB::table('acd_offered_course')
            ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
            ->join('mstr_department','mstr_department.Department_Id','=','acd_offered_course.Department_Id')
            ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
            ->join('acd_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
            ->join('mstr_class','mstr_class.Class_Id','=','acd_offered_course.Class_Id')
            ->leftjoin('acd_student_krs' ,function ($join)
            {
              $join->on('acd_student_krs.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
              ->on('acd_student_krs.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
              ->on('acd_student_krs.Course_Id','=','acd_offered_course.Course_Id')
              ->on('acd_student_krs.Class_Id','=','acd_offered_course.Class_Id');
            })
            ->leftjoin('acd_student' , function ($join)
            {
              $join->on('acd_student.Student_Id', '=', 'acd_student_krs.Student_Id')
              ->on('acd_student.Department_Id', '=', 'acd_offered_course.Department_Id');
            })
            ->where('acd_offered_course.Department_Id', $department)
            ->where('acd_offered_course.Term_Year_Id', $term_year)
            ->select( 'acd_offered_course.*','acd_course.*','mstr_class.Class_Name', DB::raw('COUNT(acd_student.Student_Id) as jml_peserta'))
            ->groupBy('acd_course.Course_Id', 'mstr_class.Class_Id', 'acd_course.Course_Code', 'acd_course.Course_Name', 'mstr_class.Class_Name', 'acd_offered_course.Class_Capacity', 'acd_offered_course.Offered_Course_id')
            ->orderBy('acd_course.Course_Name', 'asc')
            ->orderBy('mstr_class.class_Name', 'asc')
            ->get();

          $log = [];
          $log['kurang'] = '';
          $i = 0;
          foreach ($datas as $key){
            $capacity = $key->Class_Capacity;
            $pesertaall = DB::table('acd_student_krs as a')
                    ->where('a.Is_Approved',1)
                    ->where('a.Term_Year_Id', $key->Term_Year_Id )
                    ->where('a.Course_Id',$key->Course_Id)
                    ->where('a.Class_Id',$key->Class_Id)
                    ->get();
            $pendaftarall = DB::table('acd_student_krs as a')
                    ->where('a.Is_Approved',null)
                    ->where('a.Term_Year_Id', $key->Term_Year_Id )
                    ->where('a.Course_Id',$key->Course_Id)
                    ->where('a.Class_Id',$key->Class_Id)
                    ->get();

            foreach ($pendaftarall as $siswa) {
              $peserta = DB::table('acd_student_krs as a')
                    ->where('a.Is_Approved',1)
                    ->where('a.Term_Year_Id', $siswa->Term_Year_Id )
                    ->where('a.Course_Id',$siswa->Course_Id)
                    ->where('a.Class_Id',$siswa->Class_Id)
                    ->get();
              $pendaftar = DB::table('acd_student_krs as a')
                    ->where('a.Is_Approved',null)
                    ->where('a.Term_Year_Id', $siswa->Term_Year_Id )
                    ->where('a.Course_Id',$siswa->Course_Id)
                    ->where('a.Class_Id',$siswa->Class_Id)
                    ->get();
              $sisa = $capacity-count($peserta);
              $kurang = $sisa - count($pendaftar);
              if($sisa > 0 ){
                $update = DB::table('acd_student_krs as a')
                         ->join('acd_student as b','a.Student_Id','=','b.Student_Id')
                         ->where('a.Term_Year_Id', $term_year )
                         ->where('a.Course_Id', $siswa->Course_Id )
                         ->where('a.Class_Prog_Id', $siswa->Class_Prog_Id )
                         ->where('a.Student_Id', $siswa->Student_Id )
                         ->where('a.Is_Approved',null)
                         ->where('b.Department_Id',$department)
                         ->update(['Is_Approved'=>1, 'Approved_By'=>'Admin']);
              }else{
                if(count($pendaftar) > 0){
                  $log['kurang'] = $log['kurang'].($log['kurang'] == '' ? '<tr><td>':' <td> ').$key->Course_Name.'('.$key->Course_Code.')'.'</td><td>'.$kurang.'</td>'.'</tr>';
                }else{
                  continue;
                }
              }
            }
            $i++;
          }
          // dd($log);

          if($log['kurang'] == ''){
            return response()->json([
                      'status' => 200,
                      'message' => 'Sukses.',
                      'log' => $log
                  ]);
          }else{
            return response()->json([
                      'success' => false,
                      'err_info' => 'kls_penuh',
                      'message' => 'Kapasitas  Penuh. Beriku daftar kelas yang tidak mencukupi',
                      'log' => $log
                  ]);
          }
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
