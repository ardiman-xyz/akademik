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

class Komponen_PenilaianController extends Controller
{

  public function __construct()
  {
    // $this->middleware('access:CanView', ['except' => ['create','store','edit','update','destroy']]);
    // $this->middleware('access:CanAdd', ['except' => ['index','show','edit','update','destroy']]);
    // $this->middleware('access:CanEdit', ['except' => ['index','create','store','show','destroy']]);
    // $this->middleware('access:CanDelete', ['except' => ['index','create','store','show','edit','update']]);

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

       $term_year = Input::get('term_year');
       $select_term_year = DB::table('mstr_term_year')
       ->orderBy('mstr_term_year.Term_Year_Id', 'desc')
       ->get();

       if($FacultyId==""){
        if($DepartmentId == ""){
          $select_Department_Id = DB::table('mstr_department')
         ->wherenotnull('Faculty_Id')
         ->orderBy('mstr_department.Department_Name', 'asc')
         ->get();
        }else{
          $select_Department_Id = DB::table('mstr_department')
         ->wherenotnull('Faculty_Id')
         ->where('Department_Id',$DepartmentId)
         ->orderBy('mstr_department.Department_Name', 'asc')
         ->get();
        }
       }else{
         if($DepartmentId == ""){
          $select_Department_Id = DB::table('mstr_department as a')
          ->join('mstr_faculty as b','a.Faculty_Id','b.Faculty_Id')
          ->wherenotnull('a.Faculty_Id')
          ->where('a.Faculty_Id',$FacultyId)
          ->orderBy('a.Department_Name', 'asc')
          ->get();
         }else{
          $select_Department_Id = DB::table('mstr_department as a')
          ->join('mstr_faculty as b','a.Faculty_Id','b.Faculty_Id')
          ->wherenotnull('a.Faculty_Id')
          ->where('a.Faculty_Id',$FacultyId)
          ->where('a.Department_Id',$DepartmentId)
          ->orderBy('a.Department_Name', 'asc')
          ->get();
         }
       }


         if ($search == null) {
           $data = DB::table('acd_student_khs_item_bobot')
           ->where('Department_Id',$department)
            ->where('Term_Year_Id',$term_year)
           ->paginate($rowpage);
           $bobot = DB::table('acd_student_khs_item_bobot')
            ->where('Department_Id',$department)
            ->where('Term_Year_Id',$term_year)
            ->select(DB::raw('sum(Bobot) as Tbobot'))
            ->first();
         }else {
           $data = DB::table('acd_grade_department')
           ->join('mstr_department', 'mstr_department.Department_Id','=','acd_grade_department.Department_Id')
           ->join('acd_grade_letter', 'acd_grade_letter.Grade_Letter_Id','=','acd_grade_department.Grade_Letter_Id')
           ->where('acd_grade_department.Department_Id', $department)
           ->where('acd_grade_department.Term_Year_Id', $term_year)
           ->whereRaw("lower(acd_grade_letter.Grade_Letter) like '%" . strtolower($search) . "%'")
           ->orderBy('acd_grade_department.Grade_Department_Id', 'asc')
           ->paginate($rowpage);
         }
       

       $data->appends(['search'=> $search, 'rowpage'=> $rowpage, 'department'=> $department]);
       return view('mstr_komponenpenilaian/index')
       ->with('select_Department_Id', $select_Department_Id)
       ->with('department', $department)
       ->with('term_year', $term_year)
       ->with('term_year', $term_year)
       ->with('select_term_year', $select_term_year)
       ->with('query',$data)
       ->with('bobot',$bobot)
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
       $department = Input::get('department');
       $search = Input::get('search');
       $page = Input::get('page');
       $rowpage = Input::get('rowpage');
       $term_year = Input::get('term_year');
       $entry_year = Input::get('entry_year');
       $course_type = Input::get('course_type');
       $FacultyId = Auth::user()->Faculty_Id;

      $grade_department = DB::table('acd_grade_department')
      ->where('acd_grade_department.department_id', $department)->select('Grade_Letter_Id');

      $mstr_department = DB::table('acd_student_khs_item_bobot')->select('Department_Id');

      $select_mstr_department = DB::table('mstr_department')
          ->where('Faculty_Id','!=',null)
          ->orderBy('mstr_department.Department_Name', 'asc')
          ->get();

      $select_course_type = DB::table('acd_course_type')
          ->get();

      $mstr_entry_year = DB::table('mstr_entry_year')
      ->where('Entry_Year_Id', $entry_year)->first();

      $mstr_term_year = DB::table('mstr_term_year')
      ->where('Term_Year_Id', $term_year)->first();

      $year = DB::table('emp_lecturer_work_load')->select('Term_Year_Id');

      $select_entry_year = DB::table('mstr_entry_year')
       ->orderBy('mstr_entry_year.Entry_Year_Id', 'desc')
      //  ->wherenotin('Term_Year_Id',$year)
       ->get();

      $select_term_year = DB::table('mstr_term_year')
       ->orderBy('mstr_term_year.Term_Year_Id', 'desc')
      //  ->wherenotin('Term_Year_Id',$year)
       ->get();

      $acd_student_khs_item_bobot = DB::table('acd_student_khs_item_bobot')
            // ->where('Department_Id',$department)
            ->where('Entry_Year_Id',$entry_year)
            ->where('Course_Type_Id',$course_type)
            ->groupby('Item_Name')
            ->orderby('Order_Id','asc')
            ->get();
            // dd($acd_student_khs_item_bobot);
      // dd($acd_student_khs_item_bobot);
      $acd_student_khs_item_bobot_total = DB::table('acd_student_khs_item_bobot')
            // ->where('Department_Id',$department)
            ->where('Entry_Year_Id',$entry_year)
            ->where('Course_Type_Id',$course_type)
            ->groupby('Item_Name')
            ->select(DB::raw('sum(Bobot) as Tbobot'))
            ->first();

      $total = 0;
      foreach ($acd_student_khs_item_bobot as $key) {
        $total = $total + $key->Bobot;
      }

       $select_grade_letter = DB::table('acd_grade_letter')->WhereNotIn('Grade_Letter_Id', $grade_department)->get();
       return view('mstr_komponenpenilaian/create')
       ->with('department', $department)
       ->with('total', $total)
       ->with('mstr_department', $mstr_department)
       ->with('acd_student_khs_item_bobot', $acd_student_khs_item_bobot)
       ->with('acd_student_khs_item_bobot_total', $acd_student_khs_item_bobot_total)
       ->with('select_mstr_department', $select_mstr_department)
       ->with('select_grade_letter', $select_grade_letter)
       ->with('select_term_year', $select_term_year)
       ->with('select_course_type', $select_course_type)
       ->with('course_type', $course_type)
       ->with('search',$search)
       ->with('page', $page)
       ->with('term_year', $term_year)
       ->with('mstr_term_year', $mstr_term_year)
       ->with('entry_year', $entry_year)
       ->with('select_entry_year', $select_entry_year)
       ->with('request', $request)
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
             // $Functional_Position_Course_Code  = Input::get('Functional_Position_Course_Code');
        $term_year = Input::get('Term_Year_Id');
        $department = Input::get('department');
        $Item_Name = Input::get('Item_Name');
        $course_type = Input::get('course_type');
        $Bobot = Input::get('Bobot');
        $order_id = Input::get('order_id');
	//$count = DB::table('acd_student_khs_item_bobot')
	//	->where('Department_Id'=>$department)
	//	->where('Course_Type_Id'=>$course_type)
	//	->where('Term_Year_Id' => $term_year)
	//	->sum('Bobot');

      //if($count+$Bobot > 100){
       // Alert::warning('Total Bobot Lebih Dari 100%');
       // return redirect()->back();
      //}else{
        try {
          $all_prodi = DB::table('mstr_department')->get();
          foreach ($all_prodi as $key) {
            $get_prodi = DB::table('acd_student_khs_item_bobot')
            ->where('Department_Id',$key->Department_Id)
            ->where('Course_Type_Id',$request->course_type)
            ->where('Entry_Year_Id',$request->Entry_Year_Id)
            ->where('Item_Name',$request->Item_Name)
            ->first();
            if(!$get_prodi){
              $u =  DB::table('acd_student_khs_item_bobot')
              ->insert([
                'Entry_Year_Id' => $request->Entry_Year_Id, 
                'Bobot' => $request->Bobot, 
                'Department_Id'=>$key->Department_Id, 
                'Course_Type_Id'=>$request->course_type, 
                'Item_Name'=>$request->Item_Name, 
                'Order_Id'=>$order_id, 
                'Created_By' => auth()->user()->email,
                'Created_Date' => date('Y-m-d H:i:s')
              ]);
            }
          }
          return Redirect::back()->withErrors('Berhasil Menambah Komponen Penilaian');
        } catch (\Exception $e) {
          return Redirect::back()->withErrors('Gagal Menambah Komponen Penilaian');
        }
      //}
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

       return view('mstr_komponenpenilaian/copydata')
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
       $type_course = $request->type_course;
       $dept_dest = $request->dept_dest;
       if($term_dest == '0'){
         return Redirect::back()->withErrors('Semester Tujuan Kosong');
       }

       $komponen_awal = DB::table('acd_student_khs_item_bobot')
       ->where('Department_Id',$dept_asal)
       ->where('Term_Year_Id',$term_asal)
       ->where('Course_Type_Id',$type_course)
       ->get();

       try{
       foreach($dept_dest as $key){
        $count_komponen_awal = DB::table('acd_student_khs_item_bobot')
       ->where('Department_Id',$key)
       ->where('Term_Year_Id',$term_dest)
       ->where('Course_Type_Id',$type_course)
       ->count();
        if($count_komponen_awal == 0){
          foreach ($komponen_awal as $kmp) {
            $data = ['Term_Year_Id' => $term_dest, 
                    'Bobot' => $kmp->Bobot, 
                    'Department_Id'=>$key, 
                    'Course_Type_Id'=>$kmp->Course_Type_Id,
                    'Item_Name'=>$kmp->Item_Name, 
                    'Created_By' => auth()->user()->email,
                    'Created_Date' => date('Y-m-d H:i:s')];
            $insert =  DB::table('acd_student_khs_item_bobot')
                    ->insert($data);
          }
        }
       }
       return Redirect::back()->withErrors('Berhasil Kopi Komponen Penilaian');
       } catch (\Exception $e) {
          return Redirect::back()->withErrors('Gagal Kopi Komponen Penilaian');
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
       $data = DB::table('emp_lecturer_work_load')
       ->where('Lecturer_Work_Load_Id', $id)
       ->get();
       return view('mstr_komponenpenilaian/edit')
       ->with('query_edit',$data)
       ->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);

     }

     /**
      * Update the specified resource in storage.
      *
      * @param  \Illuminate\Http\Request  $request
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function update(Request $request)
     {
       $item_namee = Input::get('item_namee');
       $item_namee_old = Input::get('item_namee_old');
       $bobote = Input::get('bobote');
       $order_id_edit = Input::get('order_id_edit');
       $id_nya = Input::get('id_nya');
       $term_yeare = Input::get('term_yeare');
       $departmente = Input::get('departmente');
       
       try {
        $all_prodi = DB::table('mstr_department')->get();
        foreach ($all_prodi as $key){
          if($item_namee == $item_namee_old){
            $get_prodi = DB::table('acd_student_khs_item_bobot')
              ->where('Department_Id',$key->Department_Id)
              ->where('Course_Type_Id',$request->jm)
              ->where('Entry_Year_Id',$request->entry)
              ->where('Item_Name',$item_namee_old)
              ->first();
              // dd($get_prodi);
            if(!$get_prodi){
              $u =  DB::table('acd_student_khs_item_bobot')
              ->insert([
                'Entry_Year_Id' => $request->entry, 
                'Bobot' => $bobote, 
                'Order_Id' => $order_id_edit, 
                'Department_Id'=>$key->Department_Id, 
                'Course_Type_Id'=>$request->jm, 
                'Item_Name'=>$item_namee_old, 
                'Created_By' => auth()->user()->email,
                'Created_Date' => date('Y-m-d H:i:s')
              ]);
            }else{
             $u =  DB::table('acd_student_khs_item_bobot')
              ->where('Department_Id',$key->Department_Id)
              ->where('Course_Type_Id',$request->jm)
              ->where('Entry_Year_Id',$request->entry)
              ->where('Item_Name',$item_namee_old)
               ->update([
                'Item_Name' => $item_namee_old,
                'Bobot'=>$bobote ,
                'Order_Id' => $order_id_edit, 
                'Modified_By' => auth()->user()->email,
                'Modified_Date' => date('Y-m-d H:i:s')
              ]);            
            }
          }else{
            $get_prodi = DB::table('acd_student_khs_item_bobot')
              ->where('Department_Id',$key->Department_Id)
              ->where('Course_Type_Id',$request->jm)
              ->where('Entry_Year_Id',$request->entry)
              ->where('Item_Name',$item_namee_old)
              ->first();
              // dd($get_prodi);
            if(!$get_prodi){
              $u =  DB::table('acd_student_khs_item_bobot')
              ->insert([
                'Entry_Year_Id' => $request->entry, 
                'Bobot' => $bobote, 
                'Order_Id' => $order_id_edit, 
                'Department_Id'=>$key->Department_Id, 
                'Course_Type_Id'=>$request->jm, 
                'Item_Name'=>$item_namee, 
                'Created_By' => auth()->user()->email,
                'Created_Date' => date('Y-m-d H:i:s')
              ]);
            }else{
             $u =  DB::table('acd_student_khs_item_bobot')
              ->where('Department_Id',$key->Department_Id)
              ->where('Course_Type_Id',$request->jm)
              ->where('Entry_Year_Id',$request->entry)
              ->where('Item_Name',$item_namee_old)
               ->update([
                'Item_Name' => $item_namee,
                'Bobot'=>$bobote ,
                'Order_Id' => $order_id_edit, 
                'Modified_By' => auth()->user()->email,
                'Modified_Date' => date('Y-m-d H:i:s')
              ]);            
            }
          }
        }

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
       
     }
     public function destroy_bobot(Request $request,$id)
     {
       
        $u =  DB::table('acd_student_khs_item_bobot')
                ->where('Student_Khs_Item_Bobot_Id',$id)
                ->first();

        $q=DB::table('acd_student_khs_item_bobot')->where([
          ['Entry_Year_Id', $u->Entry_Year_Id],
          ['Course_Type_Id',$u->Course_Type_Id],
          ['Item_Name',$u->Item_Name],
          ])->delete();
        //  $q=DB::table('acd_student_khs_item_bobot')->where('Student_Khs_Item_Bobot_Id', $id)->delete();
         return Redirect::back();
        echo json_encode($q);
     }

     public function refresh_data(Request $request){
      $acd_student_khs_item_bobot = DB::table('acd_student_khs_item_bobot')
      // ->where('Department_Id',$department)
      ->where('Entry_Year_Id',$request->entry_year)
      ->where('Course_Type_Id',$request->course_type)
      ->groupby('Item_Name')
      ->orderby('Order_Id','asc')
      ->get();

      $department = DB::table('mstr_department')->get();
      foreach($department as $key){
        foreach($acd_student_khs_item_bobot as $bobot){
          $check_bobot = DB::table('acd_student_khs_item_bobot')
          ->where('Department_Id',$key->Department_Id)
          ->where('Entry_Year_Id',$request->entry_year)
          ->where('Course_Type_Id',$request->course_type)
          ->where('Item_Name',$bobot->Item_Name)
          ->first();
          if(!$check_bobot){
            $data = [
              'Entry_Year_Id' => $bobot->Entry_Year_Id, 
              'Term_Year_Id' => $bobot->Term_Year_Id, 
              'Bobot' => $bobot->Bobot, 
              'Department_Id'=>$key->Department_Id, 
              'Course_Type_Id'=>$bobot->Course_Type_Id,
              'Item_Name'=>$bobot->Item_Name, 
              'Order_Id'=>$bobot->Order_Id, 
              'Created_By' => auth()->user()->email,
              'Created_Date' => date('Y-m-d H:i:s')
            ];
            // dd($data);
            $insert =  DB::table('acd_student_khs_item_bobot')->insert($data);
          }
        }
      }
      // dd($request->all());
      return Redirect::to('/parameter/komponen_penilaian/create?entry_year='.$request->entry_year.'&course_type='.$request->course_type.'&department='.$request->department)->withErrors('Berhasil Refresh Penilaian');
     }

 }
