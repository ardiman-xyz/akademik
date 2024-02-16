<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Redirect;
use Alert;
use PDF;
use App\Thesis;
use Auth;
use Excel;
use App\GetDepartment;

class KuesionerListController extends Controller
{
    public function index(Request $request){
        $rowpage = 10;
        if ($request->rowpage != null || $request->rowpage > 0) {
            $rowpage = $request->rowpage;
        }
        $term_year1 = $request->term_year;
        if ($term_year1 == null) {
            $term_year = $request->session()->get('term_year');
        } else {
            $term_year = $request->term_year;
        }
        $data = DB::table('acd_student_krs_list as list')
        ->join('acd_student as std','list.Student_Id','=','std.Student_Id')
        ->where([['Department_Id',$request->department],['Term_Year_Id',$request->term_year]])
        ->get();
        // ->paginate($rowpage);
        // $data->appends(['request'=> $request]);

        $select_term_year = DB::table('mstr_term_year')
            ->orderBy('mstr_term_year.Term_Year_Name', 'desc')
            ->get();

        $select_department = GetDepartment::getDepartment();
        return view('kuesionerlist.index')->with('request',$request)->with('query',$data)->with('select_term_year',$select_term_year)->with('select_department',$select_department);
    }

    public function import(Request $request){
        $department =$request->department;
        $mstr_department = DB::table('mstr_department')->where('Department_Id', $department)->get();
        return view('kuesionerlist.import')->with('request',$request)->with('mstr_department', $mstr_department)->with('department', $department);
    }

    public function storemhskuesioner(Request $request){
        $data_mhs = DB::table('acd_student_krs_list as list')
        ->join('acd_student as std','list.Student_Id','=','std.Student_Id')
        ->where([['std.Department_Id', $request->department],['list.Term_Year_Id',$request->term_year]])
        ->select('std.Nim')
        ->get()
        ->toarray();
        $std_nim = [];
        $i = 0;
        foreach ($data_mhs as $item) {
            $std_nim[$i] = $item->Nim;
            $i++;
        }
        if ($request->hasFile('import_file')) {
            Excel::load($request->file('import_file')->getRealPath(), function ($reader) use ($request, $std_nim) {
                foreach ($reader->toArray() as $row) {
                    $nim = (int)$row['nim'];
                    $data = [];
                    if (in_array(($row['nim']), $std_nim) == false) {
                        $check_dept = DB::table('mstr_department')->where('Department_Id', $request->department)->first();
                        $check_std = DB::table('acd_student')->where('Nim', $row['nim'])->first();
                        if ($check_std->Department_Id == $check_dept->Department_Id) {
                            $data['Student_Id'] = $check_std->Student_Id;
                            $data['Term_Year_Id'] = $request->term_year;
                            $data['Created_By'] = auth()->user()->email;
                            $data['Created_Date'] = date('Y-m-d H:i:s');

                            DB::table('acd_student_krs_list')->insert($data);
                        }
                    } else {
                        continue;
                    }
                }  
            });
        }

        return redirect()->to('/proses/krslist/index?department=' . $request->department.'&term_year='.$request->term_year)->withErrors('Berhasil Memasukkan Data Ke Database');
    }

    public function delete(Request $request){
        if(isset($request->Student_Id)){
            $data_mhs = DB::table('acd_student_krs_list')->where([['Student_Id',$request->Student_Id],['Term_Year_Id',$request->term_year]])->delete();
        }else{
            $data_mhs = DB::table('acd_student_krs_list')->where([['Term_Year_Id',$request->term_year]])->get();
            foreach ($data_mhs as $key) {
                $data_mhs = DB::table('acd_student_krs_list')->where('Student_Krs_List_Id',$key->Student_Krs_List_Id)->delete();
            }
        }

        return Redirect::back()->withErrors('Data telah dihapus')->with('success', false);
    }
}
