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
use Storage;

class AnnouncementController extends Controller
{
  public function __construct()
  {
    $this->middleware('access:CanView', ['only' => ['index','create','store','edit','update','destroy']]);
    // $this->middleware('access:CanAdd', ['except' => ['index','show','edit','update','destroy']]);
    // $this->middleware('access:CanEdit', ['except' => ['index','create','store','show','destroy']]);
    // $this->middleware('access:CanDelete', ['except' => ['index','create','store','show','edit','update']]);
  }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index(Request $request, $department = 0)
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
        $datas = DB::table('acd_annoucement')
        ->leftjoin('mstr_department','acd_annoucement.Department_Id','=','mstr_department.Department_Id')
        ->where('Department_Id', $department)
        ->paginate($rowpage);
      }else {
        $datas = DB::table('acd_annoucement')
        ->leftjoin('mstr_department','acd_annoucement.Department_Id','=','mstr_department.Department_Id')
        ->paginate($rowpage);
      }
      $datas->appends(['search'=> $search, 'rowpage'=> $rowpage]);
      return view('mstr_announcement/index')->with('datas',$datas)->with('search',$search)->with('rowpage',$rowpage)->with('department', $department);
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
    public function create(Request $request,$department = "")
    {
        $search = Input::get('search');
        $page = Input::get('page');
        $rowpage = Input::get('rowpage');

        $prodi = GetDepartment::getDepartment();

        $cek = DB::table('acd_annoucement')->where('Department_Id', $department)->get();
        // if(count($cek) == 0) { return view('404'); }
        $educationtype = DB::table('mstr_education_program_type')->get();
        return view('mstr_announcement/create')
        ->with('educationtype',$educationtype)
        ->with('prodi', $prodi)
        ->with('department', $department)
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
        'announcement_name'=>'required',
        'message' => 'required',
        'start_date' => 'required',
        'prodi' => 'required',
        'file'=>'mimes:jpg,jpeg,png,pdf|max:2048',
      ]);
            $department = Input::get('prodi');
            $announcement_name = Input::get('announcement_name');
            $message = Input::get('message');
            $start_date = Input::get('start_date');
            $end_date = Input::get('end_date');
            // dd($request->all());

            try {
              $file = $request->file('file');
              $fileName = '';
              $path = '';
              if($file){
                $fileName = date('dmy-') . uniqid(rand()) . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/file_announcement/', $fileName);
                $path = 'file_announcement/' . $fileName;
              }
              $pp = '';
              foreach ($request->Penerima as $key) {
                $pp = $pp.($pp == '' ? '':';').$key;
              }

              $u =  DB::table('acd_annoucement')
              ->insert(
                [
                  'Department_Id' => ($department == 0 ? null:$department),
                  'Announcement_Name' => $announcement_name,
                  'Message' => $message,
                  'Penerima' => $pp,
                  'Post_Start_Date' => $start_date,
                  'Post_End_Date' => $end_date,
                  'File_Upload' => $path,
                  'Created_By' => auth()->user()->email,
                  'Created_Date' => date('Y-m-d H:i:s')]);
                return Redirect::back()->withErrors('Berhasil Menambah Pengumuman');
              } catch (\Exception $e) {
                return Redirect::back()->withErrors('Gagal Menyimpan Pengumuman');
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
    public function edit($id,$department = 0)
    {
      $search = Input::get('search');
      $page = Input::get('page');
      $rowpage = Input::get('rowpage');
      if($department == 0){
        $data = DB::table('acd_annoucement')
        ->where('Announcement_Id',$id)
        ->get();
      }else{
        $data = DB::table('acd_annoucement')
        ->where('Announcement_Id',$id)
        ->where('Department_Id',$department)
        ->get();
      }
      $prodi = GetDepartment::getDepartment();
      return view('mstr_announcement/edit')->with('query_edit',$data)->with('department',$department)->with('search',$search)->with('page', $page)->with('rowpage', $rowpage)->with('prodi',$prodi);
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
        'Announcement_Name'=>'required',
        'Message' => 'required',
        'Post_Start_Date' => 'required',
        'Post_End_Date' => 'required',
        'Aplikasi' => 'required',
        'file'=>'mimes:jpg,jpeg,png,pdf|max:2048',
      ]);
            $Announcement_Name = Input::get('Announcement_Name');
            $Message = Input::get('Message');
            $Post_Start_Date = Input::get('Post_Start_Date');
            $Post_End_Date = Input::get('Post_End_Date');

            // try {
              $data = DB::table('acd_annoucement')
              ->where('Announcement_Id',$id)->first();

              $file = $request->file('file');
              if($file){
                if (Storage::exists('public/' . $data->File_Upload)) {
                  Storage::delete('public/' . $data->File_Upload);
                }
                $fileName = date('dmy-') . uniqid(rand()) . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/file_announcement/', $fileName);
                $path = 'file_announcement/' . $fileName;
              }              
              $pp = '';
              foreach ($request->Aplikasi as $key) {
                $pp = $pp.($pp == '' ? '':';').$key;
              }

              $u =  DB::table('acd_annoucement')
              ->where('Announcement_Id',$id)
              ->update([
                'Announcement_Name' => $Announcement_Name,
                'Penerima' => $pp,
                'Message' => $Message,
                'Department_Id' => ($request->prodi == 0 ? null:$request->prodi),
                'Post_Start_Date' => $Post_Start_Date,
                'Post_End_Date' => $Post_End_Date,
                'File_Upload' => ($file ? $path:$data->File_Upload),
                'Modified_By' => auth()->user()->email,
                'Modified_Date' => date('Y-m-d H:i:s')
              ]);
              return Redirect::back()->withErrors('Berhasil Menyimpan Perubahan');
            // } catch (\Exception $e) {
            //   return Redirect::back()->withErrors('Gagal Menyimpan Perubahan');
            // }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $rs=DB::table('acd_annoucement')->where('Announcement_Id', $id)->delete();
        echo json_encode($rs);
    }
}
