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

class Department_class_progController extends Controller
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
     public function index(Request $request, $fakultas = 0)
     {
       $this->validate($request,[
         'rowpage'=>'numeric|nullable'
       ]);
       $search = Input::get('search');
       $rowpage = Input::get('rowpage');
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
          $data = DB::table('mstr_department')
          ->where('mstr_department.Faculty_Id', $fakultas)
          ->orderBy('mstr_department.Department_Code', 'asc')
          ->paginate($rowpage);
        }else {
          $data = DB::table('mstr_department')
          ->where('mstr_department.Faculty_Id', $fakultas)
          ->whereRaw("lower(Department_Name) like '%" . strtolower($search) . "%'")
        //  ->where('mstr_department.Department_Name', 'LIKE', '%'.$search.'%')
          ->orderBy('mstr_department.Department_Code', 'asc')
          ->paginate($rowpage);
        }
        $data->appends(['search'=> $search, 'rowpage'=> $rowpage]);
        return view('mstr_department_class_program/index')->with('select_fakultas', $select_fakultas)->with('fakultas', $fakultas)->with('query',$data)->with('search',$search)->with('rowpage',$rowpage);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        'Class_Prog_Id' => 'required',
      ]);
            $Department_Id = Input::get('Department_Id');
            $Class_Prog_Id = Input::get('Class_Prog_Id');

try {
      $u =  DB::table('mstr_department_class_program')
      ->insert(
      ['Department_Id' => $Department_Id,'Class_Prog_Id' => $Class_Prog_Id]);
      return Redirect::back()->withErrors('Berhasil Menambah Program Kelas');
    } catch (\Exception $e) {
      return Redirect::back()->withErrors('Gagal Menyimpan Perubahan');
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
     public function edit($id,$fakultas)
         {
           $search = Input::get('search');
           $page = Input::get('page');
           $rowpage = Input::get('rowpage');
           $department = DB::table('mstr_department')
           ->where('Department_Id',$id)
           ->where('Faculty_Id',$fakultas)
           ->orderBy('mstr_department.department_code', 'asc')
           ->get();

           $classprog = DB::table('mstr_department_class_program')->where('Department_Id', $id)->select('Class_Prog_Id');
           $classprogram = DB::table('mstr_class_program')->whereNotIn('Class_Prog_Id', $classprog)->get();
           $query = DB::table('mstr_class_program')
                       ->join('mstr_department_class_program','mstr_class_program.Class_Prog_Id','=','mstr_department_class_program.Class_Prog_Id')
                       ->join('mstr_department','mstr_department_class_program.Department_Id','=','mstr_department.Department_Id')
                       ->where('mstr_department.Department_Id', $id)->get();
           return view('mstr_department_class_program/edit')->with('query', $query)->with('fakultas', $fakultas)->with('classprogram', $classprogram)->with('query_edit',$department)->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);;
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(request $request,$id)
    {
        $rs=DB::table('mstr_department_class_program')->where('Department_Class_Prog_Id', $id)->delete();
        echo json_encode($rs);
    }
}
