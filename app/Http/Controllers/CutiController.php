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
use Excel;
use App\GetDepartment;
use Storage;

class CutiController extends Controller
{
    public function __construct()
    {
        // $this->middleware('access:CanView', ['only' => ['index','show']]);
        // $this->middleware('access:CanAdd', ['only' => ['create','store']]);
        // $this->middleware('access:CanEdit', ['only' => ['edit','update']]);
        // $this->middleware('access:CanDelete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'rowpage' => 'numeric|nullable',
        ]);
        $search = Input::get('search');
        $rowpage = Input::get('rowpage');
        if ($rowpage == null || $rowpage <= 0) {
            $rowpage = 10;
        }

        $term_year1 = Input::get('term_year');
        if ($term_year1 == null) {
            $term_year = $request->session()->get('term_year');
        } else {
            $term_year = Input::get('term_year');
        }
        $select_term_year = DB::table('mstr_term_year')
            ->orderBy('Term_Year_Id', 'desc')
            ->get();

        $FacultyId = Auth::user()->Faculty_Id;
        $DepartmentId = Auth::user()->Department_Id;
        $select_department = GetDepartment::getDepartment();
        $whereDepartment = GetDepartment::forWhereDepartment();

        $cuti = DB::table('acd_student_vacation')
            ->leftjoin('mstr_term_year', 'mstr_term_year.Term_Year_Id', '=', 'acd_student_vacation.Term_Year_Id')
            ->leftjoin('acd_student', 'acd_student.Student_Id', '=', 'acd_student_vacation.Student_Id')
            ->leftjoin('mstr_department', 'acd_student.Department_Id', '=', 'mstr_department.Department_Id')
            ->leftjoin('acd_vacation_reason', 'acd_vacation_reason.Vacation_Reason_Id', '=', 'acd_student_vacation.Vacation_Reason_Id')
            ->where(function ($query) use ($term_year,$whereDepartment) {
                $search = Input::get('search');
                $query->wherein('acd_student.Department_Id', $whereDepartment);
                $query->where('acd_student_vacation.Term_Year_Id', $term_year);
                $query->whereRaw("lower(acd_student.Full_Name) like '%" . strtolower($search) . "%'");
            })
            ->orwhere(function ($query) use ($term_year,$whereDepartment) {
                $search = Input::get('search');
                $query->wherein('acd_student.Department_Id', $whereDepartment);
                $query->where('acd_student_vacation.Term_Year_Id', $term_year);
                $query->whereRaw("lower(acd_student.Nim) like '%" . strtolower($search) . "%'");
            })
            ->orwhere(function ($query) use ($term_year,$whereDepartment) {
                $search = Input::get('search');
                $query->wherein('acd_student.Department_Id', $whereDepartment);
                $query->where('acd_student_vacation.Term_Year_Id', $term_year);
                $query->whereRaw("lower(mstr_department.Department_Name) like '%" . strtolower($search) . "%'");
            })
            ->select('acd_student.Nim', 'acd_student.Full_Name', 'acd_student_vacation.*', 'mstr_term_year.Term_Year_Name', 'acd_vacation_reason.Vacation_Reason', 'mstr_department.Department_Name')
            ->groupBy('Student_Vacation_Id')
            ->orderBy('Student_Vacation_Id', 'asc');
        // dd($cuti->get());
        $cuti = $cuti->paginate($rowpage);

        $cuti->appends(['search' => $search, 'rowpage' => $rowpage]);
        return view('acd_cuti/index')
            ->with('term_year', $term_year)
            ->with('select_term_year', $select_term_year)
            ->with('FacultyId', $FacultyId)
            ->with('cuti', $cuti)
            ->with('search', $search)
            ->with('rowpage', $rowpage);
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
    public function create(Request $request)
    {
        // dd($request->all());
        $search = Input::get('search');
        $page = Input::get('page');
        $rowpage = Input::get('rowpage');
        $mahasiswa = Input::get('mahasiswa');
        $term_year = Input::get('term_year');
        $reason = Input::get('reason');
        $FacultyId = Auth::user()->Faculty_Id;
        $DepartmentId = Auth::user()->Department_Id;

        $select_department = GetDepartment::getDepartment();

        $std = DB::table('acd_student_vacation as vac')
        ->join('acd_student as std','vac.Student_Id','=','std.Student_Id')
        ->where('vac.Term_Year_Id',$request->Term_Year_Id)->select('vac.Student_Id');
        // dd($std->get());

        $select_reason = DB::table('acd_vacation_reason')
            ->orderBy('Vacation_Reason_Id', 'asc')
            ->get();
        $select_term_year = DB::table('mstr_term_year')
            ->orderBy('mstr_term_year.Term_Year_Name', 'desc')
            ->get();

        $select_mahasiswa = DB::table('acd_student')
            // ->where('acd_student.Status_Id',1)
            ->where('acd_student.Entry_Year_Id', '>', 2016)
            ->where('acd_student.Department_Id', $request->Department_Id)
            ->whereNotIn('Student_Id', $std)
            ->get();

        return view('acd_cuti/create')
            ->with('reason', $reason)
            ->with('select_reason', $select_reason)
            ->with('select_term_year', $select_term_year)
            ->with('term_year', $term_year)
            ->with('mahasiswa', $mahasiswa)
            ->with('select_mahasiswa', $select_mahasiswa)
            ->with('select_department', $select_department)
            ->with('request', $request)
            ->with('search', $search)
            ->with('page', $page)
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
        $this->validate($request, [
            'file' => 'mimes:jpg,jpeg,png,pdf|max:2048',
        ]);
        $search = Input::get('search');
        $page = Input::get('page');
        $rowpage = Input::get('rowpage');
        $mahasiswa = Input::get('mahasiswa');
        $term_year = Input::get('term_year');
        $Reason = Input::get('reason');
        $Deskripsi = Input::get('Deskripsi');
        $SK_Date = Input::get('SK_Date');
        $Sk_Number = Input::get('Sk_Number');

        try {
            $student = DB::table('acd_student')
                ->where('Student_Id', $mahasiswa)
                ->first();
            $file = $request->file('file');
            $path = '';
            if ($file) {
                $fileName = date('dmy-') . $student->Nim . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/file_cuti/' . $student->Nim, $fileName);
                $path = 'file_cuti/' . $student->Nim . '/' . $fileName;
            }

            $all_vacation = DB::table('acd_student_vacation')
            ->where([['Student_Id', $student->Student_Id], ['Is_Approved', 1]])
            ->orderby('Term_Year_Id', 'desc')
            ->get();
            $extension = false;
            if (count($all_vacation) > 0) {
                $cuti_first = $all_vacation[0];
                //semester yang sudah ada
                $year_first = substr($cuti_first->Term_Year_Id, 0, -1);
                $term_first = substr($cuti_first->Term_Year_Id, 4, 1);
                //semester yang akan didaftarkan
                $year_insert = substr($request->term_year, 0, -1);
                $term_insert = substr($request->term_year, 4, 1);

                if ($term_insert == 2) {
                    $term_year_cuti = $request->term_year - 1;
                } else {
                    $term_year_cuti = ($year_insert - 1) . 2;
                }

                if ($cuti_first->Term_Year_Id == $term_year_cuti) {
                    $message = 'Mahasiswa Perpanjangan Cuti';
                    $extension = true;
                } else {
                    $message = 'sebelumnya tidak ada cuti';
                    $extension = false;
                }
            }

            if($extension == true){
                $u = DB::table('acd_student_vacation')->insert([
                    'Student_Id' => $mahasiswa,
                    'Previous_Student_Vacation_Id' => $all_vacation[0]->Student_Vacation_Id,
                    'Term_Year_Id' => $term_year,
                    'Vacation_Reason_Id' => 1,
                    'Description' => $Deskripsi,
                    'Sk_Date' => $SK_Date,
                    'Sk_Number' => $Sk_Number,
                    'Is_Approved' => $request->acc == 1 ? 1 : ($request->acc == 2 ? 0 : null),
                    'File' => $path,
                    'Created_By' => auth()->user()->email,
                    'Created_Date' => date('Y-m-d H:i:s')
                ]);
            }else{
                $u = DB::table('acd_student_vacation')->insert([
                    'Student_Id' => $mahasiswa,
                    'Term_Year_Id' => $term_year,
                    'Vacation_Reason_Id' => 1,
                    'Description' => $Deskripsi,
                    'Sk_Date' => $SK_Date,
                    'Sk_Number' => $Sk_Number,
                    'Is_Approved' => $request->acc == 1 ? 1 : ($request->acc == 2 ? 0 : null),
                    'File' => $path,
                    'Created_By' => auth()->user()->email, 
                    'Created_Date' => date('Y-m-d H:i:s')]);
            }
            $acd_student = DB::table('acd_student')
                ->where('Student_Id', $mahasiswa)
                ->update(['Status_Id' => 7]);
            return Redirect::back()->withErrors('Berhasil Menambah Cuti');
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
    public function edit(Request $request, $id)
    {
        $search = Input::get('search');
        $page = Input::get('page');
        $rowpage = Input::get('rowpage');
        $data_student = DB::table('acd_student_vacation')
            ->join('acd_student', 'acd_student_vacation.Student_Id', '=', 'acd_student.Student_Id')
            ->where('Student_Vacation_Id', $id)
            ->first();
        return view('acd_cuti/edit')
            ->with('data_student', $data_student)
            ->with('search', $search)
            ->with('page', $page)
            ->with('rowpage', $rowpage)
            ->with('request', $request);
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
        // "_token" => "2CO4oQ4hBax0nVLoyXYRx9rSr5XEjBSsj1MQyCio"
        // "Full_Name" => "AHMAD MA`RUF"
        // "Semester" => "20212"
        // "Deskripsi" => "werwer"
        // "Sk_Date" => "2022-07-06"
        // "Sk_Number" => "001/u/FTM/NA/II/2020"
        // "file" => UploadedFile {#1303 ▶}
        // try {
        $data_student = DB::table('acd_student_vacation')
            ->join('acd_student', 'acd_student_vacation.Student_Id', '=', 'acd_student.Student_Id')
            ->where('Student_Vacation_Id', $id)
            ->first();
        $file = $request->file('file');
        $path = $data_student->File;
        if ($file) {
            if (Storage::exists('public/' . $data_student->File)) {
                Storage::delete('public/' . $data_student->File);
            }
            $fileName = date('dmy-') . $data_student->Nim . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/file_cuti/' . $data_student->Nim, $fileName);
            $path = 'file_cuti/' . $data_student->Nim . '/' . $fileName;
        }

        // dd($request->all());
        $u = DB::table('acd_student_vacation')
            ->where('Student_Vacation_Id', $id)
            ->update([
                'Description' => $request->Deskripsi,
                'Sk_Date' => $request->Sk_Date,
                'Sk_Number' => $request->Sk_Number,
                'Is_Approved' => $request->acc == 1 ? 1 : ($request->acc == 2 ? 0 : null),
                'File' => $path,
                'Modified_By' => auth()->user()->email,
                'Modified_Date' => date('Y-m-d H:i:s'),
            ]);

        if ($request->acc == 1) {
            DB::table('acd_student')
                ->where('Student_Id', $data_student->Student_Id)
                ->update([
                    'Status_Id' => 7,
                ]);
        }

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
    public function destroy(Request $request, $id)
    {
        $std = DB::table('acd_student_vacation')
            ->where('Student_Vacation_Id', $id)
            ->first();
        $rs = DB::table('acd_student_vacation')
            ->where('Student_Vacation_Id', $id)
            ->delete();
        $std = DB::table('acd_student')
            ->where('Student_Id', $std->Student_Id)
            ->update(['Status_Id' => 1]);
        echo json_encode($rs);
    }

    public function berkascuti()
    {
        return view('acd_cuti/berkascuti');
    }
    public function masterberkascuti()
    {
        return view('acd_cuti/masterberkascuti');
    }

    public function kembali(Request $request)
    {
        $this->validate($request, [
            'rowpage' => 'numeric|nullable',
        ]);
        $search = Input::get('search');
        $rowpage = Input::get('rowpage');
        if ($rowpage == null || $rowpage <= 0) {
            $rowpage = 10;
        }

        $term_year1 = Input::get('term_year');
        if ($term_year1 == null) {
            $term_year = $request->session()->get('term_year');
        } else {
            $term_year = Input::get('term_year');
        }
        $select_term_year = DB::table('mstr_term_year')
            ->orderBy('Term_Year_Id', 'desc')
            ->get();

        $FacultyId = Auth::user()->Faculty_Id;
        $DepartmentId = Auth::user()->Department_Id;
        $select_department = GetDepartment::getDepartment();

        $cuti = DB::table('acd_student_vacation')
            ->leftjoin('mstr_term_year', 'mstr_term_year.Term_Year_Id', '=', 'acd_student_vacation.Term_Year_Id')
            ->leftjoin('acd_student', 'acd_student.Student_Id', '=', 'acd_student_vacation.Student_Id')
            ->leftjoin('acd_vacation_reason', 'acd_vacation_reason.Vacation_Reason_Id', '=', 'acd_student_vacation.Vacation_Reason_Id')
            ->where('acd_student_vacation.Term_Year_Id', $term_year)
            ->where('acd_student.Department_Id', 'like', '%' . $DepartmentId . '%')
            ->whereRaw("lower(acd_student.Full_Name) like '%" . strtolower($search) . "%'")
            ->select('acd_student.Nim', 'acd_student.Full_Name', 'acd_student_vacation.*', 'mstr_term_year.Term_Year_Name', 'acd_vacation_reason.Vacation_Reason')
            ->groupBy('Student_Vacation_Id')
            ->orderBy('Student_Vacation_Id', 'asc');
        // dd($cuti->get());
        $cuti = $cuti->paginate($rowpage);

        $cuti->appends(['search' => $search, 'rowpage' => $rowpage]);
        return view('acd_cuti/kembali')
            ->with('term_year', $term_year)
            ->with('select_term_year', $select_term_year)
            ->with('FacultyId', $FacultyId)
            ->with('cuti', $cuti)
            ->with('search', $search)
            ->with('rowpage', $rowpage);
    }

    public function editkembali(Request $request, $id)
    {
        $search = Input::get('search');
        $page = Input::get('page');
        $rowpage = Input::get('rowpage');
        $data_student = DB::table('acd_student_vacation')
            ->join('acd_student', 'acd_student_vacation.Student_Id', '=', 'acd_student.Student_Id')
            ->where('Student_Vacation_Id', $id)
            ->first();
        return view('acd_cuti/editkembali')
            ->with('data_student', $data_student)
            ->with('search', $search)
            ->with('page', $page)
            ->with('rowpage', $rowpage)
            ->with('request', $request);
    }

    public function updatekembali(Request $request, $id)
    {
        try {
            $data_student = DB::table('acd_student_vacation')
                ->join('acd_student', 'acd_student_vacation.Student_Id', '=', 'acd_student.Student_Id')
                ->where('Student_Vacation_Id', $id)
                ->first();
            $file = $request->file('file');
            $path = $data_student->File_Active;
            if ($file) {
                if (Storage::exists('public/' . $data_student->File_Active)) {
                    Storage::delete('public/' . $data_student->File_Active);
                }
                $fileName = date('dmy-') . $data_student->Nim . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/file_aktif_kembali/' . $data_student->Nim, $fileName);
                $path = 'file_aktif_kembali/' . $data_student->Nim . '/' . $fileName;
            }

            // dd($request->all());
            $u = DB::table('acd_student_vacation')
                ->where('Student_Vacation_Id', $id)
                ->update([
                    'Sk_Date_Active' => $request->Sk_Date_Active,
                    'Sk_Number_Active' => $request->Sk_Number_Active,
                    'File_Active' => $path,
                    'Modified_By' => auth()->user()->email,
                    'Modified_Date' => date('Y-m-d H:i:s'),
                ]);

            if ($request->Sk_Number_Active != null) {
                DB::table('acd_student')
                    ->where('Student_Id', $data_student->Student_Id)
                    ->update([
                        'Status_Id' => 1,
                    ]);
            } else {
                DB::table('acd_student')
                    ->where('Student_Id', $data_student->Student_Id)
                    ->update([
                        'Status_Id' => 7,
                    ]);
            }

            return Redirect::back()->withErrors('Berhasil Menyimpan Perubahan');
        } catch (\Exception $e) {
            return Redirect::back()->withErrors('Gagal Menyimpan Perubahan');
        }
    }

    public function exportCuti(Request $request)
    {
        $term_year1 = $request->term_year;
        if ($term_year1 == null) {
            $term_year = $request->session()->get('term_year');
        } else {
            $term_year = $request->term_year;
        }
        $select_term_year = DB::table('mstr_term_year')
            ->orderBy('Term_Year_Id', 'desc')
            ->get();

        $cuti = DB::table('acd_student_vacation')
            ->leftjoin('mstr_term_year', 'mstr_term_year.Term_Year_Id', '=', 'acd_student_vacation.Term_Year_Id')
            ->leftjoin('acd_student', 'acd_student.Student_Id', '=', 'acd_student_vacation.Student_Id')
            ->leftjoin('mstr_department', 'acd_student.Department_Id', '=', 'mstr_department.Department_Id')
            ->leftjoin('acd_vacation_reason', 'acd_vacation_reason.Vacation_Reason_Id', '=', 'acd_student_vacation.Vacation_Reason_Id')
            ->where(function ($query) use ($term_year,$request) {
                $search = $request->search;
                $query->where('acd_student_vacation.Term_Year_Id', $term_year);
                $query->whereRaw("lower(acd_student.Full_Name) like '%" . strtolower($search) . "%'");
            })
            ->orwhere(function ($query) use ($term_year,$request) {
                $search = $request->search;
                $query->where('acd_student_vacation.Term_Year_Id', $term_year);
                $query->whereRaw("lower(acd_student.Nim) like '%" . strtolower($search) . "%'");
            })
            ->orwhere(function ($query) use ($term_year,$request) {
                $search = $request->search;
                $query->where('acd_student_vacation.Term_Year_Id', $term_year);
                $query->whereRaw("lower(mstr_department.Department_Name) like '%" . strtolower($search) . "%'");
            })
            ->select('acd_student.Nim', 'acd_student.Full_Name', 'acd_student_vacation.*', 'mstr_term_year.Term_Year_Name', 'acd_vacation_reason.Vacation_Reason', 'mstr_department.Department_Name')
            ->groupBy('Student_Vacation_Id')
            ->orderBy('Student_Vacation_Id', 'asc')
            ->get();

        Excel::create('Data Mahasiswa Cuti', function ($excel) use ($cuti) {
            $data = [
                [
                    'Nim' => '',
                    'Nama' => '',
                    'Prodi' => '',
                    'Semester' => '',
                    'Acc' => '',
                    'Alasan' => '',
                    'SK Number' => '',
                    'File' => '',
                ],
            ];
            $i = 0;
            foreach ($cuti as $key) {
                $data[] = [
                    'Nim' => $key->Nim,
                    'Nama' => $key->Full_Name,
                    'Prodi' => $key->Department_Name,
                    'Semester' => $key->Term_Year_Name,
                    'Acc' => ($key->Is_Approved == 1 ? 'diterima':($key->Is_Approved == 0 ? 'ditolak':'belum diproses')),
                    'Alasan' => $key->Description,
                    'SK Number' => $key->Sk_Number,
                    'File' => ($key->File ? 'File SK':''),
                ];
                $i++;
            }

            $excel->sheet('Data Mahasiswa Cuti', function ($sheet) use ($data,$cuti) {
                $sheet->fromArray($data, null, 'A1');

                $q = 3;
                foreach ($cuti as $dts) {
                    $url_file = env('APP_URL').'/getfile?name='.$dts->File;
                    if ($dts->File != null || $dts->File != "") {
                        $sheet->getCell('H' . $q)->getHyperlink()->setUrl($url_file)->setTooltip('Click here to download file');
                    }
                    $q++;
                }
            });
        })->export('xlsx');
    }
}
