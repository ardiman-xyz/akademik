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
class Entry_yearController extends Controller
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


      if ($search == null) {
        $data = DB::table('mstr_entry_year')
        ->orderBy('mstr_entry_year.Entry_Year_Code', 'desc')
        ->paginate($rowpage);
      }else {
        $data = DB::table('mstr_entry_year')
        ->where('Entry_Year_Name', 'LIKE', '%'.$search.'%')
        ->orderBy('mstr_entry_year.Entry_Year_Code', 'desc')
        ->paginate($rowpage);
      }
      $data->appends(['search'=> $search, 'rowpage'=> $rowpage]);
      return view('mstr_entry_year/index')->with('query',$data)->with('search',$search)->with('rowpage',$rowpage);
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
        $ldate = date('Y');
        $lname = $ldate."/".( $ldate + 1 );
        $total = DB::table('mstr_entry_year')->count();
        if($total == 0){
          $u =  DB::table('mstr_entry_year')
          ->insert(
            ['Entry_Year_Id' => $ldate,'Entry_Year_Code' => $ldate,'Entry_Year_Name' => $lname]);
            return Redirect::back()->withErrors('Berhasil Menambah Angkatan');
          }
         else{
          $data = DB::table('mstr_entry_year')->orderBy('Entry_Year_Id','desc')->first();
          foreach ($data as $a) {
            $Entry_Year_Id   = ( $data->Entry_Year_Id + 1 );
            $Entry_Year_Code = $Entry_Year_Id;
            $Entry_Year_Name = $Entry_Year_Id."/".( $Entry_Year_Id + 1 );
            $u =  DB::table('mstr_entry_year')
            ->insert(
              ['Entry_Year_Id' => $Entry_Year_Id,'Entry_Year_Code' => $Entry_Year_Code,'Entry_Year_Name' => $Entry_Year_Name]);
              return Redirect::back()->withErrors('Berhasil Menambah Angkatan');
            }
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
        //
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
    public function destroy($id)
    {
        $rs=DB::table('mstr_entry_year')->where('Entry_Year_Id', $id)->delete();
        echo json_encode($rs);
    }
}
