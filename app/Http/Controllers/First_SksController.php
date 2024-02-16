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

class First_SksController extends Controller
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
     public function index(Request $request, $fakultas = 0)
     {
       $this->validate($request,[
         'rowpage'=>'numeric|nullable'
       ]);
       $search = Input::get('search');
       $rowpage = Input::get('rowpage');
       $page = Input::get('page');
       if ($rowpage == null || $rowpage <= 0) {
         $rowpage = 10;
       }
      $FacultyId = Auth::user()->Faculty_Id;
      if ($FacultyId != null) {
        $select_fakultas = DB::table('mstr_faculty')
        ->where('Faculty_Id', $FacultyId)
        ->orderBy('mstr_faculty.faculty_code', 'asc')
        ->get();
      }else {
        $select_fakultas = DB::table('mstr_faculty')
        ->orderBy('mstr_faculty.faculty_code', 'asc')
        ->get();
      }

      if ($search == null) {
        $department = DB::table('mstr_department')
        ->join('mstr_education_program_type','mstr_education_program_type.Education_Prog_Type_Id','=','mstr_department.Education_Prog_Type_Id')
        ->leftjoin('acd_allowed_sks_start','acd_allowed_sks_start.Department_Id','mstr_department.Department_Id')
        // ->where('Faculty_Id', $fakultas)
        ->select(
          'mstr_department.Department_Id',
          'mstr_department.Department_Code',
          'mstr_department.Department_Name',
          'acd_allowed_sks_start.Sks_Max'
          )
        ->groupby('mstr_department.Department_Id')
        ->orderBy('department_code', 'asc')
        ->paginate($rowpage);
      }else {
        $department = DB::table('mstr_department')
        ->join('mstr_education_program_type','mstr_education_program_type.Education_Prog_Type_Id','=','mstr_department.Education_Prog_Type_Id')
        ->leftjoin('acd_allowed_sks_start','acd_allowed_sks_start.Department_Id','mstr_department.acd_allowed_sks_start')
        // ->where('Faculty_Id', $fakultas)
        ->whereRaw("lower(Department_Name) like '%" . strtolower($search) . "%'")
        ->orderBy('department_code', 'asc')
        ->paginate($rowpage);
      }
      $department->appends(['search'=> $search, 'rowpage'=> $rowpage]);
      return view('mstr_first_sks/index')
      ->with('page',$page)
      ->with('department',$department)
      ->with('search',$search)
      ->with('rowpage',$rowpage)
      ->with('select_fakultas', $select_fakultas)
      ->with('fakultas', $fakultas);
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
    public function create($Faculty_Id = "")
    {
        $search = Input::get('search');
        $page = Input::get('page');
        $rowpage = Input::get('rowpage');

        $cek = DB::table('mstr_faculty')->where('Faculty_Id', $Faculty_Id)->get();
        if(count($cek) == 0) { return view('404'); }
        $educationtype = DB::table('mstr_education_program_type')->get();
        return view('mstr_first_sks/create')->with('educationtype',$educationtype)->with('Faculty_Id', $Faculty_Id)->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);
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
        'Department_Code'=>'required',
        'Department_Name' => 'required',
        'Department_Acronym' => 'required',
        'Faculty_Id' => 'required',
      ]);
            $Faculty_Id = Input::get('Faculty_Id');
            $Department_Code = Input::get('Department_Code');
            $Department_Name = Input::get('Department_Name');
            $Department_Name_Eng = Input::get('Department_Name_Eng');
            $Department_Acronym = Input::get('Department_Acronym');
            $nodikti = Input::get('nodikti');
            $tgldikti = Input::get('tgldikti');
            $educationtype = Input::get('educationtype');
            $Order_Id = Input::get('Order_Id');

            try {
              $u =  DB::table('mstr_department')
              ->insert(
                ['Faculty_Id' => $Faculty_Id,'Department_Code' => $Department_Code,'Department_Name' => $Department_Name,'Department_Name_Eng' => $Department_Name_Eng,'Department_Acronym' => $Department_Acronym,'Department_Dikti_Sk_Number' => $nodikti,'Department_Dikti_Sk_Date' => $tgldikti,'Education_Prog_Type_Id' => $educationtype, 'Order_Id' => $Order_Id]);
                return Redirect::back()->withErrors('Berhasil Menambah Program Studi');
              } catch (\Exception $e) {
                return Redirect::back()->withErrors('Gagal Menyimpan Program Studi');
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
    public function edit($id,$fakultas = "")
    {
      $educationtype = DB::table('mstr_education_program_type')->get();
      $search = Input::get('search');
      $page = Input::get('page');
      $rowpage = Input::get('rowpage');

      $department = DB::table('mstr_department')
      ->where('Department_Id',$id)
      ->orderBy('mstr_department.department_code', 'asc')
      ->get();

      $class_prog = DB::table('mstr_department_class_program')
      ->join('mstr_class_program','mstr_department_class_program.Class_Prog_Id','=','mstr_class_program.Class_Prog_Id')
      ->where('Department_Id',$id)
      ->get();
      // dd($class_prog);

      return view('mstr_first_sks/edit')
      ->with('class_prog', $class_prog)
      ->with('fakultas', $fakultas)
      ->with('educationtype', $educationtype)
      ->with('query_edit',$department)
      ->with('search',$search)
      ->with('page', $page)
      ->with('rowpage', $rowpage);;
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
      $class_prog = DB::table('mstr_department_class_program')
      ->join('mstr_class_program','mstr_department_class_program.Class_Prog_Id','=','mstr_class_program.Class_Prog_Id')
      ->where('Department_Id',$id)
      ->get();
      try{
        foreach ($class_prog as $key) {
          if(isset($request[$key->Class_Prog_Id])){
            $cek_data = DB::table('acd_allowed_sks_start')->where([['Department_Id',$id],['Class_Prog_Id',$key->Class_Prog_Id]])->first();
            if($cek_data){
              $u =  DB::table('acd_allowed_sks_start')
              ->where([['Department_Id',$id],['Class_Prog_Id',$key->Class_Prog_Id]])
              ->update([
                'Sks_Max' => $request[$key->Class_Prog_Id]
              ]);
            }else{
              $u =  DB::table('acd_allowed_sks_start')
              ->insert([
                'Department_Id' => $id,
                'Class_Prog_Id' => $key->Class_Prog_Id,
                'Sks_Max' => $request[$key->Class_Prog_Id]
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
        $rs=DB::table('mstr_department')->where('Department_Id', $id)->delete();
        echo json_encode($rs);
    }
}
