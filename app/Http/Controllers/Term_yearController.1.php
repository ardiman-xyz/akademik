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

class Term_yearController extends Controller
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
      $entry_year = Input::get('entry_year');


      $select_entry_year = DB::table('mstr_entry_year')
      ->orderBy('mstr_entry_year.entry_year_code', 'desc')
      ->get();

      if ($search == null) {
        $data = DB::table('mstr_term_year')
        ->join('mstr_entry_year','mstr_entry_year.Entry_Year_Id','=','mstr_term_year.Year_Id')
        ->join('mstr_term','mstr_term.Term_Id','=','mstr_term_year.Term_Id')
        ->where('mstr_term_year.Year_Id', $entry_year)
        ->orderBy('Term_Year_Id', 'asc')
        ->paginate($rowpage);
      }else {
        $data = DB::table('mstr_term_year')
        ->join('mstr_entry_year','mstr_entry_year.Entry_Year_Id','=','mstr_term_year.Year_Id')
        ->join('mstr_term','mstr_term.Term_Id','=','mstr_term_year.Term_Id')
        ->where('mstr_term_year.Year_Id', $entry_year)
        ->whereRaw("lower(mstr_term_year.Term_Year_Name) like '%" . strtolower($search) . "%'")
        ->orderBy('Term_Year_Id', 'asc')
        ->paginate($rowpage);
      }
      $data->appends(['search'=> $search, 'rowpage'=> $rowpage]);
      return view('mstr_term_year/index')->with('query',$data)->with('search',$search)->with('rowpage',$rowpage)->with('select_entry_year', $select_entry_year)->with('entry_year', $entry_year);
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
      $entry_year = Input::get('entry_year');

      $u =  DB::table('mstr_term_year')->select('Term_Id')->get();
      foreach($u as $a){
        $data[] = $a->Term_Id;
      }
      $term = DB::table('mstr_term')->get();
      // dd($term);
      return view('mstr_term_year/create')->with('term',$term)->with('entry_year', $entry_year)->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);
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
        'term'=>'required',
        'entry_year' => 'required',
      ]);
            $term = Input::get('term');
            $entry_year = Input::get('entry_year');
            $start_date = Input::get('Start_Date');
            $end_date = Input::get('End_Date');


            $mstr_term = DB::table('mstr_term')->where('Term_Id',$term)->get();
            $mstr_year = DB::table('mstr_entry_year')->where('Entry_Year_Id',$entry_year)->get();
            foreach ($mstr_term as $k) {
              $term_name = $k->Term_Name;
            }
            foreach ($mstr_year as $y) {
              $year_code = $y->Entry_Year_Code;
            }
            $term_year_id = $entry_year.$term;
            $term_year_name = $year_code.$term."/".$term_name;

      $u =  DB::table('mstr_term_year')
      ->insert(
      ['Term_Year_Id' => $term_year_id,'Term_Id' => $term,'Year_Id' => $entry_year,'Term_Year_Name' => $term_year_name,'Start_Date' => $start_date, 'End_Date' => $end_date]);
      return Redirect::back()->withErrors('Berhasil Menambah Program Studi');
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
      $search = Input::get('search');
      $page = Input::get('page');
      $rowpage = Input::get('rowpage');

      $term_year = DB::table('mstr_term_year')->join('mstr_term','mstr_term.Term_Id','=','mstr_term_year.Term_Id')->where('Term_Year_Id', $id)->first();
      return view('mstr_term_year/edit')->with('dat', $term_year)->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);
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
      $start_date = Input::get('Start_Date');
      $end_date = Input::get('End_Date');

            try {
              $u =  DB::table('mstr_term_year')
              ->where('Term_Year_Id',$id)
              ->update(
              ['Start_Date' => $start_date, 'End_Date' => $end_date]);
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
      $rs=DB::table('mstr_term_year')->where('Term_Year_Id', $id)->delete();
      echo json_encode($rs);
    }
}
