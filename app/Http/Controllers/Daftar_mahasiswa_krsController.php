<?php

  namespace App\Http\Controllers;

  use App\Http\Controllers\Controller;
  use Illuminate\Support\Facades\Validator;
  use Illuminate\Foundation\Auth\Registerst5s;
  use Illuminate\Http\Request;
  use Illuminate\Support\Str;
  use Input;
  use DB;
  use Redirect;
  use Alert;
  use PDF;
  use Auth;
  use Excel;
use App\GetDepartment;

  class Daftar_mahasiswa_krsController extends Controller
  {
    public function __construct()
    {
      $this->middleware('access:CanView', ['only' => ['index','show']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index(Request $request)
     {
         $search = Input::get('search');
         $rowpage = Input::get('rowpage');
         $department = Input::get('department');
         $class_program = Input::get('class_program');
         
         $FacultyId = Auth::user()->Faculty_Id;
         $DepartmentId = Auth::user()->Department_Id;

         $term_year1 = Input::get('term_year');
       if($term_year1 == null){
        $term_year =  $request->session()->get('term_year');
       }else{
        $term_year = Input::get('term_year');
       }

       if ($rowpage == null) {
         $rowpage = 10;
       }
        $select_department = GetDepartment::getDepartment();

       $select_class_program = DB::table('mstr_department_class_program')
       ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','mstr_department_class_program.Class_Prog_Id')
       ->where('mstr_department_class_program.Department_Id', $department)
       ->orderBy('mstr_class_program.Class_Program_Name', 'desc')
       ->get();
       
       $data=DB::table('acd_student_krs')
         ->select('acd_student_krs.*','acd_student.*','mstr_class_program.Class_Program_Name',
           DB::raw('SUM(acd_student_krs.Sks) as jml_sks'),
           DB::raw('SUM(acd_student_krs.Amount) as biaya'),
           DB::raw('COUNT(acd_student_krs.Student_Id) as jml_mk'))
         ->join('acd_student','acd_student_krs.Student_Id','=','acd_student.Student_Id')
         ->leftjoin('mstr_department','acd_student.Department_id','=','mstr_department.department_id')
         ->join('mstr_class_program','acd_student_krs.Class_Prog_Id','=','mstr_class_program.Class_Prog_Id')
         ->join('mstr_term_year','acd_student_krs.Term_Year_Id','=','mstr_term_year.Term_Year_Id')
         ->groupBy('acd_student_krs.Student_Id')
         ->where('acd_student_krs.Term_Year_Id', $term_year)
         ->where('mstr_department.department_id', $department)
         ->where('acd_student_krs.Class_Prog_Id', $class_program)
         ->where('acd_student_krs.Is_Approved', 1)
         ->where(function($query){
            $search = Input::get('search');
            $query->whereRaw("lower(acd_student.Full_Name) like '%" . strtolower($search) . "%'");
            $query->orwhere('acd_student.Nim', 'LIKE', '%'.$search.'%');
          })
         ->paginate($rowpage);

         $select_term_year = DB::table('mstr_term_year')
         ->orderBy('mstr_term_year.Term_Year_Name', 'desc')
         ->get();

         $event_sched = DB::table('mstr_event_sched')
         ->where('Department_Id',$department)
         ->where('Term_Year_Id',$term_year)
         ->where('Event_Id',1)
        //  ->where('Is_Open',1)
         ->first();
         $tutupan = '';
         $date_now = Date('Y-m-d');
         if($event_sched){
           if($event_sched->End_Date_Cost < $date_now){
            $tutupan = 1;
           }
         }

         $data->appends(['search'=> $search, 'rowpage'=> $rowpage, 'class_program'=> $class_program,'term_year'=> $term_year, 'department'=> $department]);
         return view('laporan_daftar_mahasiswa_krs/index')->with('query',$data)->with('search',$search)->with('rowpage',$rowpage)->with('select_class_program', $select_class_program)->with('class_program', $class_program)->with('select_department', $select_department)->with('department', $department)->with('select_term_year', $select_term_year)->with('term_year', $term_year)->with('tutupan', $tutupan);
       }

       public function show($id)
       {
         $search = Input::get('search');
         $rowpage = Input::get('rowpage');
         $department = Input::get('department');
         $class_program = Input::get('class_program');
         $term_year = Input::get('term_year');

           $page = Input::get('page');
           if ($rowpage == null) {
             $rowpage = 10;
           }

           $data=DB::table('acd_student_krs')
           ->select('acd_student_krs.*','acd_student.*','mstr_class_program.Class_Program_Name','mstr_department.Department_Name')
           ->join('acd_student','acd_student_krs.Student_Id','=','acd_student.Student_Id')
           ->leftjoin('mstr_department','acd_student.Department_Id','=','mstr_department.Department_Id')
           ->join('mstr_class_program','acd_student_krs.Class_Prog_Id','=','mstr_class_program.Class_Prog_Id')
           ->where('acd_student.Nim', $id)
           ->first();

           $query=DB::table('acd_student_krs')
           ->select('acd_student_krs.*','acd_student.Full_Name','mstr_class.class_Name','acd_course.*')
           ->join('acd_student','acd_student_krs.Student_Id','=','acd_student.Student_Id')
           ->join('mstr_class','acd_student_krs.Class_Id','=','mstr_class.Class_Id')
           ->join('acd_course','acd_student_krs.Course_Id','=','acd_course.Course_Id')
           ->where('acd_student.Nim', $id)
           ->where('acd_student_krs.Term_Year_Id', $term_year)

           //$data=DB::table('acd_student')
           //->select('acd_student.Full_Name','acd_student.Nim','mstr_department.Department_Name')
           //->join('mstr_department','acd_student.Department_Id','=','mstr_department.Department_Id')


           ->paginate($rowpage);

           //$data->appends(['search'=> $search, 'rowpage'=> $rowpage, 'class_program'=> $class_program,'term_year'=> $term_year, 'department'=> $department]);
           //$query->appends(['search'=> $search,'rowpage'=> $rowpage,'class_program'=> $class_program,'term_year'=> $term_year,'department'=> $department])
           $query->appends(['search'=> $search, 'rowpage'=> $rowpage, 'class_program'=> $class_program,'term_year'=> $term_year, 'department'=> $department ]);
           return view('laporan_daftar_mahasiswa_krs/show')->with('Nim',$id)->with('query',$query)->with('data',$data)->with('Student_Id',$id)->with('search',$search)->with('rowpage',$rowpage)->with('class_program', $class_program)->with('department', $department)->with('term_year', $term_year);

       }


       // public function modal()
       // {
       //   return view('mstr_class_program/modal');
       // }
       /**
        * Show the form for creating a new resource.
        *
        * @return \Illuminate\Http\Response
        */
       public function create()
       {

       }

       /**
        * Store a newly created resource in storage.
        *
        * @param  \Illuminate\Http\Request  $request
        * @return \Illuminate\Http\Response
        */
       public function store(Request $request)
       {
         }

       /**
        * Show the form for editing the specified resource.
        *
        * @param  int  $id
        * @return \Illuminate\Http\Response
        */
       public function edit($id)
       {
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
       }

       /**
        * Remove the specified resource from storage.
        *
        * @param  int  $id
        * @return \Illuminate\Http\Response
        */
       public function destroy(Request $request, $id)
       {
         $select_data = DB::table('acd_student_krs as a')
                      ->rightjoin('acd_student as b','a.Student_Id','=','b.Student_Id')
                      ->where('a.Student_Id',$id)
                      ->where('a.Term_Year_Id',$request->term_year)
                      ->where('b.Department_Id',$request->department)
                      ->where('a.Class_Prog_Id',$request->class_program)
                      ->select( 'a.Krs_Id',
                                'a.Student_Id',
                                'a.Term_Year_Id',
                                'a.Course_Id',
                                'a.Class_Id',
                                'a.Class_Prog_Id',
                                'a.Sks',
                                'a.Amount',
                                'a.Krs_Date',
                                'a.Cost_Item_Id',
                                'a.Is_Approved',
                                'a.Is_Locked')
                      ->get();
          foreach ($select_data as $key) {
            $insert = DB::table('acd_student_krs_deleted')
                    ->insert(['Krs_Id'=>$key->Krs_Id,
                              'Student_Id'=>$key->Student_Id,
                              'Term_Year_Id'=>$key->Term_Year_Id,
                              'Course_Id'=>$key->Course_Id,
                              'Class_Id'=>$key->Class_Id,
                              'Class_Prog_Id'=>$key->Class_Prog_Id,
                              'Sks'=>$key->Sks,
                              'Amount'=>$key->Amount,
                              'Krs_Date'=>$key->Krs_Date,
                              'Cost_Item_Id'=>$key->Cost_Item_Id,
                              'Is_Approved'=>$key->Is_Approved,
                              'Is_Locked'=>$key->Is_Locked,
                              'Created_Date'=>Date('Y-m-d'),
                              'Created_By'=>Auth::user()->Email]);
            
            $update = DB::table('acd_student')->where('Student_Id',$key->Student_Id)->update(['Status_Id' => 2]);
          }

          $deleted = DB::table('acd_student_krs as a')
                      ->rightjoin('acd_student as b','a.Student_Id','=','b.Student_Id')
                      ->where('a.Student_Id',$id)
                      ->where('a.Term_Year_Id',$request->term_year)
                      ->where('b.Department_Id',$request->department)
                      ->where('a.Class_Prog_Id',$request->class_program)
                      ->delete();
        if ($deleted) {
            return response()->json([
                'status' => 200,
                'message' => 'deleted.',
                'deleted' => $deleted,
            ]);
        }
        //  echo json_encode($id);
       }

       public function exportdata($department, $entry_year, $class_program){
          Excel::create('Mahasiswa KRS', function ($excel) use($department, $entry_year, $class_program){       
            $items  = DB::table('acd_student_krs')
             ->select('acd_student_krs.*','acd_student.*','mstr_class_program.Class_Program_Name',
               DB::raw('SUM(acd_student_krs.Sks) as jml_sks'),
               DB::raw('SUM(acd_student_krs.Amount) as biaya'),
               DB::raw('COUNT(acd_student_krs.Student_Id) as jml_mk'))
             ->join('acd_student','acd_student_krs.Student_Id','=','acd_student.Student_Id')
             ->leftjoin('mstr_department','acd_student.Department_id','=','mstr_department.department_id')
             ->join('mstr_class_program','acd_student_krs.Class_Prog_Id','=','mstr_class_program.Class_Prog_Id')
             ->join('mstr_term_year','acd_student_krs.Term_Year_Id','=','mstr_term_year.Term_Year_Id')
             ->groupBy('acd_student_krs.Student_Id')
             ->where('acd_student_krs.Term_Year_Id', $entry_year)
             ->where('mstr_department.department_id', $department)
             ->where('acd_student_krs.Class_Prog_Id', $class_program)
             ->where('acd_student_krs.Is_Approved', 1)
             ->get();

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

              // if ($cetak_hari) {
              //     $num = date('N', strtotime($tanggal));
              //     return $hari[$num] . ', ' . $tgl_indo;
              // }
              return $tgl_indo;
          }

            if ($items->count() == 0) {
              $data = [
                  [
                    'NO' => '',
                    'NIM' => '',
                    'Nama Mahasiswa' => '',
                    'Kelas Program' => '',
                    'Jumlah Matakuliah' => '',
                    'Jumlah SKS' => '',
                    'Jumlah Biaya KRS' => '',
                    'Tagihan KRS' => '',
                  ]
              ];
          }

          $i = 1;
          foreach ($items as $item) {
            if ($item->Birth_Date == null) {
                $birth = "";
              }else {
                $date = strtotime($item->Birth_Date);
                $da = Date('Y-m-d',$date);
                $birth = tanggal_indo($da,true);
              }
              $totalbiaya = number_format($item->biaya,'0',',','.');
              $tagihan = DB::table('fnc_student_payment')
                    ->where('Term_Year_Id',$entry_year)
                    ->where('Register_number',$item->Register_Number)
                    ->where('Cost_Item_Id',85)
                    ->select('Payment_Amount')
                    ->get();
                  $sumAmount =0;
                    foreach ($tagihan as $tagihan) {
                      $sumAmount += $tagihan->Payment_Amount;
                    }
              $totals = $item->biaya - $sumAmount ;
              $total = number_format($totals,'0',',','.');

              $data[] = [
                          'NO' => $i,
                          'NIM' => $item->Nim,
                          'Nama Mahasiswa' => $item->Full_Name,
                          'Kelas Program' => $item->Class_Program_Name,
                          'Jumlah Matakuliah Matakuliahas' => $item->jml_mk,
                          'Jumlah SKS' => $item->jml_sks,
                          'Jumlah Biaya KRS' => $totalbiaya,
                          'Tagihan KRS' => $total,
                        ];
              $i++;
          }

          $excel->sheet('Mahasiswa KRS', function ($sheet) use ($data,$items) {
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
                  'B' => 20,
                  'C' => 40,
                  'D' => 15,
                  'E' => 10,
                  'F' => 20,
              ]);
              
              $sheet->setHorizontalCentered(true);

              for ($i = 1; $i <= $num_rows; $i++) { 
                  $sheet->row($i, function ($row) {
                      $row->setValignment('center');
                  });
              }

              for ($i = 1; $i > $num_rows; $i++) { 
                  $sheet->row($i, function ($row) {
                      $cells->setAlignment('center');
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
              // foreach ($data as $dt) {
              //       $no = ($dt['NO'] + 1);
              //       if ($dt['SKS'] == null || $dt['Semester'] == null || $dt['SKS Transkrip'] == null) {
              //           $sheet->cells('A' . $no . ':I' . $no, function ($cells) {
              //               $cells->setBackground('#ff0000');
              //               $cells->setFontColor('#ffffff');
              //               $cells->setAlignment('center');
              //           });
              //       }else{
              //         $sheet->cells('A' . $no . ':I' . $no, function ($cells) {
              //               $cells->setAlignment('center');
              //       });
              //     }
              //   }

              foreach ($data as $dt) {
                  $sheet->cells('D' . $i . ':E' . sizeof($data), function ($cells) {
                      $cells->setAlignment('center');
                  });
                }

              // $last = $i+1;
              // $sheet->cells('E2:E9999', function ($cells) {
              //             $cells->setAlignment('center');
              //     });
              // $sheet->setCellValue('B'.$last, 'STIKES MUHAMMADIYAH PALEMBANG');
          });
      })->export('xls');
    }
   }
