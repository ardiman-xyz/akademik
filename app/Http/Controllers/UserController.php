<?php

namespace App\Http\Controllers;


use App\Model\Auth\User;
use App\Model\Auth\Role;
use App\Model\Auth\Access;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Input;
use DB;
use Redirect;
use Alert;

class UserController extends Controller
{
      use RegistersUsers;
      protected $redirectTo = '/home';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
      $this->middleware('auth');

      $this->middleware('access:CanView', ['except' => ['create','store','edit','update','destroy']]);
      $this->middleware('access:CanAdd', ['except' => ['index','show','edit','update','destroy']]);
      $this->middleware('access:CanEdit', ['except' => ['index','create','store','show','destroy']]);
      $this->middleware('access:CanDelete', ['except' => ['index','create','store','show','edit','update']]);

    }

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
        $user = DB::table('_user')
        ->Join('_role_user', '_user.id', '=', '_role_user.user_id')
        ->Join('_role', '_role.id', '=', '_role_user.role_id')
        ->leftjoin('mstr_faculty', 'mstr_faculty.Faculty_Id', '=', '_user.Faculty_Id')
        ->leftjoin('mstr_department', 'mstr_department.Department_ID', '=', '_user.Department_Id')
        ->select('_role.name as role_name','email','_user.name as name','_user.id','mstr_faculty.*','mstr_department.Department_Name')
        ->where('_role.app', 'Akademik')
        ->orderBy('_user.id', 'asc')
        ->paginate($rowpage);
      } else {
        $user = DB::table('_user')
        ->Join('_role_user', '_user.id', '=', '_role_user.user_id')
        ->Join('_role', '_role.id', '=', '_role_user.role_id')
        ->leftjoin('mstr_faculty', 'mstr_faculty.Faculty_Id', '=', '_user.Faculty_Id')
        ->leftjoin('mstr_department', 'mstr_department.Department_ID', '=', '_user.Department_Id')
        ->where([['_user.name', 'LIKE', '%'.$search.'%'],['_role.app', 'Akademik']])
        ->orwhere([['_user.email', 'LIKE', '%'.$search.'%'],['_role.app', 'Akademik']])
        ->select('_role.name as role_name','email','_user.name as name','_user.id','mstr_faculty.*','mstr_department.Department_Name')
        ->orderBy('_user.id', 'asc')
        ->paginate($rowpage);
      }
      $user->appends(['search'=> $search, 'rowpage'=> $rowpage]);
      return view('user/index')->with('user',$user)->with('search',$search)->with('rowpage',$rowpage);
    }


    protected function create()
    {
        $search = Input::get('search');
        $page = Input::get('page');
        $rowpage = Input::get('rowpage');

        $role = DB::table('_role')
        ->where('app', 'Akademik')
        ->where('is_admin','!=',1)
        ->get();

        $fakultas = DB::table('mstr_faculty')->get();
        $department = DB::table('mstr_department')->where('Faculty_Id','!=',null)->get();

        $role_in = DB::table('_role')
        ->where('app', 'Akademik')
        // ->where('is_admin','!=',1)
        ->select('id');

        $user_in = DB::table('_user as a')
        ->join('_role_user as b','a.id','=','b.user_id')
        ->whereIn('b.role_id', $role_in)
        ->select('a.id');
        $user = DB::table('_user as a')
        ->leftjoin('_role_user as b','a.id','=','b.user_id')
        ->whereNotIn('a.id', $user_in)
        ->select('a.id','a.name','a.email','b.role_id','b.user_id')
        ->groupby('a.id')
        ->get();
        // dd($user);   

        // $fakultas = DB::table('fakultas')->get();,,

        return view('user/create')
        ->with('department',$department)
        ->with('role', $role)
        ->with('user', $user)
        ->with('fakultas', $fakultas)->with('search', $search)->with('page', $page)->with('rowpage', $rowpage);

        // return view('user/create');

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function store(Request $request)
    {
      // $this->validate($request,[
      //   'password'=>'required',
      //   'email' => 'required|string|email|max:255|unique:_user',
      //   'confirm'=>'required|same:password'
      // ]);
      $nama = Input::get('nama');
      $email = Input::get('email');
      $password = Input::get('password');
      $rol = Input::get('role');
      $fakultas = Input::get('fakultas');
      $department = Input::get('department');
      $Email_Id = Input::get('Email_Id');
      if($Email_Id == null ||$Email_Id == 0 ){
        $user =  User::create([
            'name' => $nama,
            'email' => $email,
            'password' => bcrypt($password),
            'Faculty_Id' => $fakultas,
            'Department_Id' => $department,
        ]);
  
        $us = DB::table('_user')
              ->where('email',$email)
              ->where('name',$nama)
              ->get();
        if ($user) {
          foreach ($us as $data) {
                  $u =  DB::table('_role_user')
                  ->insert(
                  ['user_id' => $data->id,'role_id' => $rol]);
          }
        }
      }else{
        $update = DB::table('_user')
                ->where('id',$Email_Id)
                ->update(['Faculty_Id' => $fakultas,'Department_Id' => $department]);
        $u =  DB::table('_role_user')
            ->insert(
            ['user_id' => $Email_Id,'role_id' => $rol]);
      }


      return Redirect::back()->withErrors('Berhasil Menambah User');
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
    public function edit($id = "null")
    {
        $search = Input::get('search');
        $page = Input::get('page');
        $rowpage = Input::get('rowpage');

        $query_edit = DB::table('_user')
                    ->leftjoin('_role_user','_user.id','=','_role_user.user_id')
                    ->leftjoin('_role','_role_user.role_id','=','_role.id')
                    ->where('_role.app', 'Akademik')
                    ->where('_user.id', $id)->select('_user.*','_role_user.role_id')->get();
        

        $_role = DB::table('_role_user as a')
                ->join('_user as b','b.id','=','a.user_id')
                ->leftjoin('_role as c','a.role_id','=','c.id')
                ->where('b.id',$id)
                ->select('a.id as _role_id_id','a.role_id','a.user_id' ,'b.*','c.*')
                ->get();
        // dd($_role);
        // $role = DB::table('_role')->where('app', 'Akademik')->get();
        $role = DB::table('_role')->where('app', 'Akademik')->get();
        $fakultas = DB::table('mstr_faculty')->get();
        $department = DB::table('mstr_department')->where('Faculty_Id','!=',null)->get();
        // $fakultas = DB::table('fakultas')->get();
        // $prodi_user = DB::table('prodi')->join('prodi_user','prodi_user.id_prodi','=','prodi.id_prodi')->leftjoin('_user','_user.id','=','prodi_user.id_user')->where('_user.id',$id)->get();
        return view('user/edit')
        ->with('department',$department)
        ->with('query_edit',$query_edit)
        ->with('role', $role)
        ->with('_role', $_role)
        ->with('fakultas', $fakultas)->with('search', $search)->with('page', $page)->with('rowpage', $rowpage);
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
      $nama = Input::get('nama');
      $email = Input::get('email');
      $role = Input::get('role');
      $fakultas = Input::get('fakultas');
      $department = Input::get('department');
      try {
        $insert = DB::table('_user')
          ->where('_user.id', $id)
          ->update(['_user.name' => $nama , '_user.email' => $email, '_user.Faculty_Id' => $fakultas, '_user.Department_Id' => $department]);

        return Redirect::back()->withErrors('Berhasil Mengubah Data');
      } catch (\Exception $e) {
        return Redirect::back()->withErrors('Gagal Mengubah Data');
      }
    }

    public function update_role(Request $request)
    {
      $id_userrole = $request->id_userrole;
      $id_role = $request->id_role;
      try {
        $insert = DB::table('_role_user')
          ->where('id', $id_userrole)
          ->update(['role_id' => $id_role]);

        return json_encode($insert);
      } catch (\Exception $e) {
        return json_encode("Gagal");
      }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy_role_user($id)
    {
        try {
          DB::table('_role_user')->where('id', $id)->delete();
          return redirect()->to('/administrator/user')->withErrors('Berhasil Menghapus Data');
        } catch (\Exception $e) {
          return Redirect::back()->withErrors('Gagal Menghapus Data');
        }
    }

    public function destroy($id)
    {
      $user = DB::table('_user')
              ->leftjoin('_role_user','_user.id','=','_role_user.user_id')
              ->leftjoin('_role','_role_user.role_id','=','_role.id')
              ->where('_role.app', 'Akademik')->where('_user.id', $id)->get();
        try {
          // DB::table('_role_user')->where('user_id', $id)->delete();
          DB::table('_user')
          ->leftjoin('_role_user','_user.id','=','_role_user.user_id')
              ->leftjoin('_role','_role_user.role_id','=','_role.id')
              ->where('_role.app', 'Akademik')
              ->where('id', $id)->delete();

          return Redirect::back()->withErrors('Berhasil Menghapus Data');
        } catch (\Exception $e) {
          // DB::table('_user')
          // ->leftjoin('_role_user','_user.id','=','_role_user.user_id')
          //     ->leftjoin('_role','_role_user.role_id','=','_role.id')
          //     ->where('_role.app', 'Akademik')->where('id', $id)->delete();

          // return Redirect::back()->withErrors('Gagal Menghapus Data');
        }
    }
}
