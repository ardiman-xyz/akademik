<?php

namespace App\Http\Controllers;

use App\Model\Auth\User;
use App\Model\Auth\Role;
use App\Model\Auth\Access;
use App\Model\Auth\Accesskeuangan;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Input;
use DB;
use Redirect;
use Alert;

class RoleController extends Controller
{

    use RegistersUsers;
    protected $redirectTo = '/home';

    public function __construct()
    {
         $this->middleware('auth');
         $this->middleware('access:CanView', ['only' => ['index','show']]);
         $this->middleware('access:CanAdd', ['only' => ['create','store']]);
         $this->middleware('access:CanEdit', ['only' => ['edit','update']]);
         $this->middleware('access:CanDelete', ['only' => ['destroy']]);
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
        $role = DB::table('_role')->where('app', 'Akademik')->where('is_admin', 1)
        ->paginate($rowpage);
        $role2 = DB::table('_role')->where('app', 'Akademik')->where('is_admin', 0)
        ->paginate($rowpage);
        }else {
        $role = DB::table('_role')->where('app', 'Akademik')->where('is_admin', 1)
        ->whereRaw("lower(name) like '%" . strtolower($search) . "%'")
        ->paginate($rowpage);
        $role2 = DB::table('_role')->where('app', 'Akademik')->where('is_admin', 0)
        ->whereRaw("lower(name) like '%" . strtolower($search) . "%'")
        ->paginate($rowpage);
        }
        $role->appends(['search'=> $search, 'rowpage'=> $rowpage]);
        return view('role/index')->with('role',$role)->with('role2',$role2)->with('search',$search)->with('rowpage',$rowpage);
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
        $access = Access::all();
        $accesskeu = Accesskeuangan::all();

        return view('role/create')->with('access', $access)->with('accesskeu', $accesskeu)->with('search', $search)->with('page', $page)->with('rowpage', $rowpage);
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
          'nama' => 'required',
        ]);
              $nama = Input::get('nama');
              $deskripsi = Input::get('deskripsi');
              $access = Input::get('access');
              $accesskeu = Input::get('accesskeu');


        // try {
        $data = new Role;

        $data->name = $nama;
        $data->description = $deskripsi;
        $data->app = 'Akademik';

        $data->save();
        $id = $data->id;
        if ($access != null) {
          foreach ($access as $data) {
            DB::table('_role_access')
            ->insert(
              ['role_id' => $id, 'access_id' => $data]);
            }
        }
        if ($accesskeu != null) {
          foreach ($accesskeu as $data) {
            DB::table('_role_accesskeuangan')
            ->insert(
              ['role_id' => $id, 'accesskeuangan_id' => $data]);
            }
        }
          return Redirect::back()->withErrors('Berhasil Menambah Role');
        // } catch (\Exception $e) {
        //   return Redirect::back()->withErrors('Gagal Menambah Role');
        // }



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

      $data = DB::table('_role')
      ->where('id',$id)
      ->get();
      $access = DB::table('_role_access')
      ->join('_access','_access.id','_role_access.access_id')
      ->where('role_id',$id)
      ->get();
      $accesskeu = DB::table('_role_accesskeuangan')
      ->join('_accesskeuangan','_accesskeuangan.id','_role_accesskeuangan.accesskeuangan_id')
      ->where('role_id',$id)
      ->get();
      $accesses = Access::all();
      $accesseskeu = Accesskeuangan::all();
      return view('role/show')->with('query',$data)->with('access', $access)->with('accesses', $accesses)->with('accesskeu', $accesskeu)->with('accesseskeu', $accesseskeu)->with('search', $search)->with('page', $page)->with('rowpage', $rowpage);
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

      $data = DB::table('_role')
      ->where('id',$id)
      ->get();
      $access = DB::table('_role_access')
      ->join('_access','_access.id','_role_access.access_id')
      ->where('role_id',$id)
      ->get();
      $accesskeu = DB::table('_role_accesskeuangan')
      ->join('_accesskeuangan','_accesskeuangan.id','_role_accesskeuangan.accesskeuangan_id')
      ->where('role_id',$id)
      ->get();
      $accesses = Access::all();
      $accesseskeu = Accesskeuangan::all();
      return view('role/edit')->with('query_edit',$data)->with('access', $access)->with('accesses', $accesses)->with('accesskeu', $accesskeu)->with('accesseskeu', $accesseskeu)->with('search', $search)->with('page', $page)->with('rowpage', $rowpage);
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
        'nama'=>'required',
        'deskripsi' => 'required',
      ]);
          $nama = Input::get('nama');
          $deskripsi = Input::get('deskripsi');
          $access = Input::get('access');
          $accesskeu = Input::get('accesskeu');


            try {
              $u =  DB::table('_role')
              ->where('id',$id)
              ->update(
              ['name' => $nama,'description' => $deskripsi,'app'=>'Akademik']);

              DB::table('_role_access')->where('role_id', $id)->delete();
              DB::table('_role_accesskeuangan')->where('role_id', $id)->delete();

              if ($access != null) {
                foreach ($access as $data) {
                  DB::table('_role_access')
                  ->insert(
                    ['role_id' => $id, 'access_id' => $data]);
                  }
              }
              if ($accesskeu) {
                foreach ($accesskeu as $data) {
                  DB::table('_role_accesskeuangan')
                  ->insert(
                    ['role_id' => $id, 'accesskeuangan_id' => $data]);
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
    public function destroy($id)
    {
      try {
        DB::table('_role')->where('id', $id)->delete();
        DB::table('_role_access')->where('role_id', $id)->delete();
        // return Redirect::back()->withErrors('Berhasil Menghapus Data');
        return Redirect::back();
      } catch (\Exception $e) {
        Alert::error('Gagal Menghapus Data, Kemungkinan data msih digunakan', 'Failed');
        // return Redirect::back()->withErrors('Gagal Menghapus Data, Kemungkinan data msih digunakan');
        return Redirect::back();

      }
    }
}
