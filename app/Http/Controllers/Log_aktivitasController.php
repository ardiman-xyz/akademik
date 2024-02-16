<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Input;
use Excel;
use Auth;
use Session;

class Log_AktivitasController extends Controller
{
      public function __construct()
  {
    $this->middleware('access:CanView', ['only' => ['index','exportdata']]);

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jenis = Input::get('jenis');
        $department = Input::get('prodi');
        $curriculum = Input::get('curriculum');
        $search = Input::get('search');
        $FacultyId = Auth::user()->Faculty_Id;
       $DepartmentId = Auth::user()->Department_Id;
     if($FacultyId==""){
        if($DepartmentId == ""){
          $select_department = DB::table('mstr_department')
         ->wherenotnull('Faculty_Id')
         ->orderBy('mstr_department.Department_Id', 'desc')
         ->get();
        }else{
          $select_department = DB::table('mstr_department')
         ->wherenotnull('Faculty_Id')
         ->where('Department_Id',$DepartmentId)
         ->orderBy('mstr_department.Department_Id', 'desc')
         ->get();
        }
       }else{
         if($DepartmentId == ""){
          $select_department = DB::table('mstr_department as a')
          ->join('mstr_faculty as b','a.Faculty_Id','b.Faculty_Id')
          ->wherenotnull('a.Faculty_Id')
          ->where('a.Faculty_Id',$FacultyId)
          ->orderBy('a.Department_Id', 'desc')
          ->get();
         }else{
          $select_department = DB::table('mstr_department as a')
          ->join('mstr_faculty as b','a.Faculty_Id','b.Faculty_Id')
          ->wherenotnull('a.Faculty_Id')
          ->where('a.Faculty_Id',$FacultyId)
          ->where('a.Department_Id',$DepartmentId)
          ->orderBy('a.Department_Id', 'desc')
          ->get();
         }
       }

        $select_curriculum = DB::table('mstr_curriculum')
       ->orderBy('mstr_curriculum.Curriculum_Name', 'desc')
       ->get();

       $data = DB::table('log_user as a')
       ->leftjoin('acd_student as b','a.Student_Id','=','b.Student_Id')
       ->leftjoin('emp_employee as c','a.Employee_Id','=','c.Employee_Id')
       ->where(function($query){
          $search = Input::get('search');
          $query->whereRaw("lower(c.Name) like '%" . strtolower($search) . "%'");
          $query->orwhere('a.Log_User_Id', 'LIKE', '%'.$search.'%');
          $query->orwhere('c.Email_Corporate', 'LIKE', '%'.$search.'%');
          $query->orwhere('b.Nim', 'LIKE', '%'.$search.'%');
        })
       ->select('a.*','b.Nim','c.Name')
       ->orderby('Created_Date','desc')->get();

        return view('laporan_logaktivitas/index')
        ->with('cek_data',$data)
        ->with('curriculum',$curriculum)
        ->with('select_curriculum',$select_curriculum)
        ->with('select_department',$select_department)
        ->with('department',$department)
        ->with('search',$search)
        ->with('jenis',$jenis);
    }

    public function exportdata($id1){
        function tanggal_indo($tanggal, $cetak_hari = false)
            {
                $hari = array ( 1 =>    'Senin',
                            'Selasa',
                            'Rabu',
                            'Kamis',
                            'Jumat',
                            'Sabtu',
                            'Minggu'
                        );

                $bulan = array (1 =>   'Januari',
                            'Februari',
                            'Maret',
                            'April',
                            'Mei',
                            'Juni',
                            'Juli',
                            'Agustus',
                            'September',
                            'Oktober',
                            'November',
                            'Desember'
                        );
                $split 	  = explode('-', $tanggal);
                $tgl_indo = $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];

                if ($cetak_hari) {
                    $num = date('N', strtotime($tanggal));
                    return $hari[$num] . ', ' . $tgl_indo;
                }
                return $tgl_indo;
            }

        $search = ($id1 == 0 ? '':$id1);
        
            Excel::create('Log Aktivitas', function ($excel) use($search){       
             $items = DB::table('log_user as a')
                    ->leftjoin('acd_student as b','a.Student_Id','=','b.Student_Id')
                    ->leftjoin('emp_employee as c','a.Employee_Id','=','c.Employee_Id')
                    ->where(function($query) use($search){
                        $query->whereRaw("lower(c.Name) like '%" . strtolower($search) . "%'");
                        $query->orwhere('a.Log_User_Id', 'LIKE', '%'.$search.'%');
                        $query->orwhere('c.Email_Corporate', 'LIKE', '%'.$search.'%');
                        $query->orwhere('b.Nim', 'LIKE', '%'.$search.'%');
                        })
                    ->select('a.*','b.Nim','c.Name')
                    ->orderby('Created_Date','desc')->get();

             if ($items->count() == 0) {
                $data = [
                    [
                        'NO' => '',
                        'Log ID' => '',
                        'NIM' => '',
                        'Pegawai' => '',
                        'User Aplikasi' => '',
                        'Aplikasi' => '',
                        'Aktifitas' => '',
                        'Tanggal' => '',
                    ]
                ];
            }

            $i = 1;
            foreach ($items as $item) {
                $date = strtotime($item->Created_Date);
                $da = Date('Y-m-d',$date);
                $show_date = tanggal_indo($da,true);

                $data[] = [
                    'NO' => $i,
                    'Log ID' => $item->Log_User_Id,
                    'NIM' => $item->Nim,
                    'Pegawai' => $item->Name,
                    'User Aplikasi' => $item->Userapp,
                    'Aplikasi' => $item->Application,
                    'Aktifitas' => $item->Activity,
                    'Tanggal' => $show_date,
                ];

                $i++;
            }

            $excel->sheet('Data Mahasiswa', function ($sheet) use ($data) {
                $sheet->fromArray($data, null, 'A1');

                $num_rows = sizeof($data) + 1;

                for ($i = 1; $i <= $num_rows; $i++) { 
                    $rows[$i] = 18;
                }

                $rows[1] = 30;

                $sheet->setAutoSize(true);

                $sheet->setStyle([
                    'font' => [
                        'name' => 'Arial',
                        'size' => 10
                    ]
                ]);

                $sheet->setAllBorders('none');

                $sheet->setHeight($rows);

                $sheet->setWidth([
                    'A' => 6,
                    'B' => 70,
                    'C' => 12,
                    'D' => 20,
                    'E' => 25,
                    'F' => 18,
                ]);
                
                $sheet->setHorizontalCentered(true);

                for ($i = 1; $i <= $num_rows; $i++) { 
                    $sheet->row($i, function ($row) {
                        $row->setValignment('center');
                    });
                }
                
                $sheet->setBorder('A1:H' . (sizeof($data) + 1), 'thin');

                $sheet->setHorizontalCentered(true);

                $sheet->cells('A1:H1', function ($cells) {
                    $cells->setBackground('#97D86E');
                    $cells->setFontWeight('bold');
                    $cells->setAlignment('center');
                });
                // $sheet->cells('Q1', function ($cells) {
                //     $cells->setBackground('#F0FF00');
                //     $cells->setFontWeight('bold');
                //     $cells->setAlignment('center');
                // });
                // $sheet->cells('R1:T1', function ($cells) {
                //     $cells->setBackground('#FF3939');
                //     $cells->setFontWeight('bold');
                //     $cells->setAlignment('center');
                // });
            });
        })->export('xls');
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
        //
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
        //
    }
}
