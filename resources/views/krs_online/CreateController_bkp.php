<?php

namespace App\Http\Controllers\KrsOnline;

use App\Http\Controllers\Controller;
// use App\Http\Helpers\SessionHelpers;
use App\Http\Models\KrsOnlineData;
use App\Http\Models\StoreProcedure;
// use App\Repositories\AcdCurriculumEntryYearRepository;
// use App\Repositories\AcdPrerequisiteDetailRepository;
// use App\Repositories\AcdStudentKrsRepository;
// use App\Repositories\AcdTranscriptRepository;
// use App\Repositories\MstrEventSchedRepository;
use Illuminate\Http\Request;
use Input;
use DB;
use Auth;

/**
 * Class CreateController
 * @package App\Http\Controllers\KrsOnline
 */
class CreateController_bkp extends Controller
{
  // public function __construct()
  // {
  //   $this->middleware('access:CanPostToken', ['only' => ['postToken']]);
  // }
    // /**
    //  * @var AcdPrerequisiteDetailRepository
    //  */
    // protected $acdPrerequisiteDetail;
    //
    // /**
    //  * @var AcdTranscriptRepository
    //  */
    // protected $acdTranscript;
    // /**
    //  * @var AcdStudentKrsRepository
    //  */
    // protected $acdStudentKrs;
    //
    // /**
    //  * @var MstrEventSchedRepository
    //  */
    // protected $mstrEventSched;
    //
    // /**
    //  * @var AcdCurriculumEntryYearRepository
    //  */
    // protected $acdCurriculumEntryYear;

    // /**
    //  * @param AcdPrerequisiteDetailRepository $acdPrerequisiteDetail
    //  * @param AcdStudentKrsRepository $acdStudentKrs
    //  * @param AcdTranscriptRepository $acdTranscript
    //  * @param AcdCurriculumEntryYearRepository $acdCurriculumEntryYear
    //  * @param MstrEventSchedRepository $mstrEventSched
    //  * CreateController constructor.
    //  */
    // public function __construct(AcdPrerequisiteDetailRepository $acdPrerequisiteDetail, AcdTranscriptRepository $acdTranscript,
    //                             AcdStudentKrsRepository $acdStudentKrs, MstrEventSchedRepository $mstrEventSched, AcdCurriculumEntryYearRepository $acdCurriculumEntryYear)
    // {
    //     $this->acdPrerequisiteDetail = $acdPrerequisiteDetail;
    //     $this->acdTranscript = $acdTranscript;
    //     $this->acdStudentKrs = $acdStudentKrs;
    //     $this->mstrEventSched = $mstrEventSched;
    //     $this->acdCurriculumEntryYear = $acdCurriculumEntryYear;
    // }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
      $nim = input::get('nim');
      $term_year = input::get('term_year');
      $token = DB::table('_token')->where('id', $nim)->select('Token')->first();
      $user = Auth::user()->session;
      $username = Auth::user()->email;
      if($user !=null ){
        $user = $user;
      } else{
        $user = "";
      }
      // dd($user);
      // dd($token);
      return view('krs_online.create')->with('nim', $nim)->with('term_year', $term_year)->with('token', $token)->with('session', $user)->with('username',$username);
    }

    // public function postToken(Request $request,$nim)
    //     {
    //       // $xxx = input::get('xxx');
    //       $nimmd5 = md5($nim);
    //       $xxxx = $nimmd5.'ciyepaDanyarIapaa?';
    //       $token = bcrypt($nim);
    //
    //       $data = DB::table('_token')->where('id', $nim)->count();
    //       // dd($data);
    //       $timezone = +7;
    //       $Timestamp =  gmdate("Y-m-d H:i:s", time() + 3600*($timezone+date("I")));
    //       if($data == 0){
    //         DB::table('_token')
    //         ->insert(
    //           ['id'=>$nim,'Nim'=>$xxxx,'Token'=>$token,'Timestamp_time'=>$Timestamp]);
    //
    //           $response = [
    //             'success' => 'success'
    //           ];
    //           return response()->json($response);
    //       }else{
    //         DB::table('_token')
    //         ->where('id', $nim)
    //         ->update(
    //           ['Nim'=>$xxxx,'Token'=>$token,'Timestamp_time'=>$Timestamp]);
    //
    //           $response = [
    //             'success' => 'success'
    //           ];
    //           return response()->json($response);
    //       }
    //     }
    //
    //     public function getToken(Request $request)
    //     {
    //       // $Course_Id=Input::get('Course_Id');
    //       // $p=DB::table('acd_student_krs')
    //       // ->where('Student_Id',$request->Student_Id)
    //       // ->where('Course_Id', $Course_Id)->first();
    //       //
    //       //     return response()->json($p);
    //
    //         $nim2 = $request->nim;
    //         // $data = DB::table('_token')->get();
    //         $data = DB::table('_token')->where('id', $nim2)->select('Token')->first();
    //         // dd($data);
    //
    //         if ($data != null) {
    //             $response = [
    //                 'success' => 'true',
    //                 'data' => $data->Token
    //             ];
    //
    //             return response()->json($response);
    //         }
    //
    //     $response = [
    //         'success' => 'false'
    //     ];
    //
    //     return response()->json($data);
    //     }

    /**
     * return list data mata kuliah
     * @param int $termyearid
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCourseList()
    {
      $nim = input::get('nim');
      $term_year = input::get('term_year');
        $departmentid = DB::table('acd_student')->where('Nim', $nim)->select('Department_Id')->first();
        // dd($departmentid);

        if ($departmentid) {
            // $result = $this->mstrEventSched->with('mstrEvents')->orderBy('Event_Sched_Id', 'Desc')
            //     ->findWhere(['Department_Id' => $departmentid])->first();

            // $termyearid = $result->Term_Year_Id;
            $termyearid = $term_year;

            $result = StoreProcedure::getOfferedCourseForKRSByStudent($termyearid,$nim);
            // dd($result);
            $data = collect($result);

            if ($data->count() != 0) {

                $response = [
                    'success' => 'true',
                    'data' => $data,
                    'total' => $data->count()
                ];

                return response()->json($response);
            }
        }

        $response = [
            'success' => 'false',
            'data' => [],
            'total' => 0
        ];

        return response()->json($response);
    }

    /**
     * return list data nama kelas
     * @param null $courseid
     * @return \Illuminate\Http\JsonResponse
     */
    public function getClassList($courseid = null)
    {
      $nim = input::get('nim');
      $term_year = input::get('term_year');
        $departmentid = DB::table('acd_student')->where('Nim', $nim)->select('Department_Id')->first();
        $classprogid1 = DB::table('acd_student')->where('Nim', $nim)->select('Class_Prog_Id')->first();
        $classprogid = $classprogid1->Class_Prog_Id;
        // dd($courseid);
        // if ($courseid) {

            // $termyear = $this->getCurriculumEntryYear()->getOriginalContent();
            // $departmentid = $departmentid;
            // // dd($termyear);
            //
            // // $result = $this->mstrEventSched->with('mstrEvents')->orderBy('Event_Sched_Id', 'Desc')
            // //     ->findWhere(['Department_Id' => $departmentid])->first();
            //
            // $termyearid = $term_year;
            //
            // // $classprogid="";
            // if ($termyear['success'] == true) {
            //
            //     foreach ($termyear['data'] as $item) {
            //         $classprogid = $item->Class_Prog_Id;
            //     }
            // }

            // $data = DB::table('acd_offered_course')
            // ->join('mstr_class','mstr_class.Class_Id','=','acd_offered_course.Class_Id')
            // ->where('acd_offered_course.Term_Year_Id', $term_year)->where('acd_offered_course.Course_Id', $courseid)->where('acd_offered_course.Class_Prog_Id', $classprogid);
            // // $data = KrsOnlineData::getClassName($courseid, $termyearid, $classprogid);

            // dd($classprogid);
            $data = DB::table('acd_offered_course')
            ->join('mstr_class','mstr_class.Class_Id','=','acd_offered_course.Class_Id')
            ->where('acd_offered_course.Term_Year_Id', $term_year)->where('acd_offered_course.Course_Id', $courseid)->where('acd_offered_course.Class_Prog_Id', $classprogid)
            ->orderBy('mstr_class.Class_Name','asc')
            ->get();

            if ($data->count() != 0) {

                $response = [
                    'success' => 'true',
                    'data' => collect($data),
                    'total' => collect($data)->count()
                ];

                return response()->json($response);
            }
        // }

        $response = [
            'success' => 'false',
            'data' => [],
            'total' => 0
        ];

        return response()->json($response);
    }

    /**
     * return data tahun ajaran kurikulum
     * @param int $termyearid
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCurriculumEntryYear()
    {
      $nim = input::get('nim');
      $departmentid = DB::table('acd_student')->where('Nim', $nim)->select('Department_Id')->first();
      $entryyearid = DB::table('acd_student')->where('Nim', $nim)->select('Entry_Year_Id')->first();

        $result = DB::table('mstr_event_sched')->join('mstr_event','mstr_event.Event_Id','=','mstr_event_sched.Event_Id')->where('mstr_event.Department_Id', $departmentid)->first();
         // $this->mstrEventSched->with('mstrEvents')->orderBy('Event_Sched_Id', 'Desc')
         //    ->findWhere(['Department_Id' => $departmentid])->first();
         // dd($result);

        if ($result) {

            $termyearid = input::get('term_year');;
            $data = DB::table('acd_curriculum_entry_year')->where('Department_Id', $departmentid)->where('Term_Year_Id', $termyearid)->where('Entry_Year_Id', $entryyearid)->get();
             // $this->acdCurriculumEntryYear->findWhere(['Department_Id' => $departmentid,
             //    'Term_Year_Id' => $termyearid, 'Entry_Year_Id' => $entryyearid]);

            if ($data->count() != 0) {

                $response = [
                    'success' => 'true',
                    'data' => $data,
                    'total' => $data->count()
                ];

                return response()->json($response);
            }
        }

        $response = [
            'success' => 'false',
            'data' => [],
            'total' => 0
        ];

        return response()->json($response);
    }

    /**
     * return kapasitas ruang kelas
     * @param null $classid
     * @param null $courseid
     * @return \Illuminate\Http\JsonResponse
     */
    public function getClassInfo($courseid = null, $classid = null)
    {
        // $departmentid = SessionHelpers::getDepartmentId();
        $nim = input::get('nim');
        $term_year = input::get('term_year');
        // dd($nim);
        $departmentid1 = DB::table('acd_student')->where('Nim', $nim)->select('Department_Id')->first();
        $departmentid = $departmentid1->Department_Id;

        if ($courseid) {
            // $result = $this->mstrEventSched->with('mstrEvents')->orderBy('Event_Sched_Id', 'Desc')
            //     ->findWhere(['Department_Id' => $departmentid])->first();

            $termyearid = $term_year;

            $data = StoreProcedure::getClassInfoForKRS($termyearid, $courseid, $classid,$nim);

            if (collect($data)->count() != 0) {
                $response = [
                    'success' => 'true',
                    'data' => $data,
                    'total' => count($data)
                ];

                return response()->json($response);
            }
        }

        $response = [
            'success' => 'false',
            'data' => 'tidak ada data'
        ];

        return response()->json($response);
    }

    /**
     * return push data ke database
     * @param null $courseid
     * @param null $termyearid
     * @param null $classid
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeData(Request $request)
    {
      $nim = $request->input('nim');
      $term_year = $request->input('term_year');
      // dd($request->all());
      $departmentid1 = DB::table('acd_student')->where('Nim', $nim)->select('Department_Id')->first();
      $departmentid = $departmentid1->Department_Id;
      $studentid1 = DB::table('acd_student')->where('Nim', $nim)->select('Student_Id')->first();
      $studentid = $studentid1->Student_Id;
      $classprogid1 = DB::table('acd_student')->where('Nim', $nim)->select('Class_Prog_Id')->first();
      $classprogid = $classprogid1->Class_Prog_Id;
        $courseid = $request->input('mata_kuliah');
        $classid = $request->input('daftar_kelas');
        // dd($courseid);

        // $result = $this->mstrEventSched->with('mstrEvents')->orderBy('Event_Sched_Id', 'Desc')
        //     ->findWhere(['Department_Id' => $departmentid])->first();

        $termyearid = $term_year;

        if ($courseid) {

            // $this->acdStudentKrs->create($krsdata);
            DB::table('acd_student_krs')
            ->insert(
              [
                  'Student_Id' => $studentid,
                  'Term_Year_Id' => $term_year,
                  'Course_Id' => $courseid,
                  'Class_Prog_Id' => $classprogid,
                  'Class_Id' => $classid,
                  'Cost_Item_Id' => 10,
                  'Sks' => $request->input('total_sks'),
                  'Amount' => $request->input('total_biaya'),
                  'Modified_By' => $studentid,
                  'Modified_Date' => date('Y-m-d'),
                  'Created_By' => $studentid,
                  'Created_Date' => date('Y-m-d')
              ]);


            $response = [
                'success' => 'true',
                'data' => 'Mata kuliah berhasil ditambahkan.',
                'total' => 0
            ];

            return response()->json($response);
        }

        $response = [
            'success' => 'false',
            'data' => 'tidak ada data',
            'total' => 0
        ];

        return response()->json($response);
    }

    /**
     * return syarat pengambilan kelas
     * @param null $termyearid
     * @param null $classid
     * @param null $courseid
     * @return mixed
     */
    public function prerequisiteClass(Request $request, $courseid = null, $classid = null)
    {
      $nim = $request->input('nim');
      $term_year = $request->input('term_year');
      // dd($nim);
      $std_id1 = DB::table('acd_student')->where('Nim', $nim)->select('Student_Id')->first();
      $departmentid1 = DB::table('acd_student')->where('Nim', $nim)->select('Department_Id')->first();
        $studentid = $std_id1->Student_Id;
        $departmentid = $departmentid1->Department_Id;

        $result = $term_year;

        if ($result) {

            $termyearid = $result;
//            $saldo = StoreProcedure::getSaldo($termyearid);
            $countusedsks = db::table('acd_student_krs')->where('Student_Id', $studentid)->where('Term_Year_Id', $termyearid)->count();
            $usedsks = db::table('acd_student_krs')->where('Student_Id', $studentid)->where('Term_Year_Id', $termyearid)->get();
            $sumsks = db::table('acd_student_krs')->where('Student_Id', $studentid)->where('Term_Year_Id', $termyearid)->sum('Sks');
            $allowedsks = DB::select('CALL usp_GetAllowedSKSForKRS(?,?)', [$termyearid, $nim]);
            // $sks = DB::select('CALL usp_GetAllowedSKSForKRS(?,?)',array($termyearid,$studentid));
            // dd($allowedsks);
            // dd($sks);
            $costforkrs = StoreProcedure::getCourseCostForKRS($termyearid, $courseid,$nim);
            $checkcourseid = db::table('acd_student_krs')->where('Student_Id', $studentid)->where('Course_Id', $courseid)->where('Term_Year_Id', $termyearid)->get();
            // $this->acdStudentKrs->findWhere(['Student_Id' => $studentid, 'Course_Id' => $courseid, 'Term_Year_Id' => $termyearid]);
            $classinfo = StoreProcedure::getClassInfoForKRS($termyearid, $courseid, $classid,$nim);
            // dd($checkcourseid);
            //check mata kuliah yang sama
            if (count($checkcourseid) > 0) {

                foreach ($checkcourseid as $checkcourse) {
                    $exist_course = (string)$checkcourse->Course_Id;
                }
            }

//            //check saldo saat ini
//            if (count($saldo) > 0) {
//
//                foreach ($saldo as $sld) {
//                    $current_saldo = $sld->SisaSaldoSaatIni;
//                }
//            }

            //check mata kuliah yang diizinkan
            if (isset($allowedsks)) {

                // if (count($allowedsks) > 0) {

                    foreach ($allowedsks as $als) {
                        $total_sks = $als->AllowedSKS;
                    }
                // }
            }

            //check biaya per mata kuliah
            if (count($costforkrs) > 0) {

                foreach (collect($costforkrs) as $cfk) {
                    $biaya_sks = $cfk->applied_sks;
                    $biaya_matkul = $cfk->amount;
                }
            }

            //check kapasitas ruang kelas
            if (count($classinfo) > 0) {

                foreach ($classinfo as $key) {
                    $class_capacity = $key->Capacity;
                    $class_used = $key->Used;
                    $class_kuota = $key->Free;
                }
            }

            if ($checkcourseid->count() > 0 || $classinfo <= 0 || isset($total_sks) || isset($usedsks) || isset($matkulkrs) == $courseid) {

                //cek mata kuliah yang sudah diambil
                if ($checkcourseid->count() > 0) {

                    $response = [
                        'success' => 'false',
                        'data' => 'Mata kuliah sudah diambil.',
                        'total' => 0
                    ];

                    return response()->json($response);
                }
            }

            //cek kapasitas ruang kelas
            if ($classinfo <= 0) {

                $response = [
                    'success' => 'false',
                    'data' => 'Kelas Sudah Penuh.',
                    'total' => 0
                ];

                return response()->json($response);
            }

            //check sks yang sudah digunakan
            if ($countusedsks > 0) {
              // dd($countusedsks);
              $total_sks =0;
              foreach($allowedsks as $sk){
                $total_sks = $sk->AllowedSKS ;
              }
              // dd($total_sks);
                if (($total_sks - $sumsks) < $biaya_sks) {
                    $response = [
                        'success' => 'false',
                        'data' => 'Sisa SKS tidak mencukupi.',
                        'total' => 0
                    ];

                    return response()->json($response);
                }
            }

//            //check saldo dan biaya matkul
//            if ($current_saldo < $biaya_matkul) {
//
//                $response = [
//                    'success' => 'false',
//                    'data' => 'Sisa Saldo tidak mencukupi.',
//                    'total' => 0
//                ];
//
//                return response()->json($response);
//            }

            //check mata kuliah yang sudah diambil
            if (isset($exist_course) == $courseid) {

                $response = [
                    'success' => 'false',
                    'data' => 'Mata kuliah sudah diambil.',
                    'total' => 0
                ];

                return response()->json($response);
            }
        }

        $response = [
            'success' => 'true',
            'data' => 'Berhasil',
            'total' => 0
        ];

        return response()->json($response);
    }

    /**
     * return syarat pengambilan mata kuliah
     * @param null $courseid
     * @return \Illuminate\Http\JsonResponse
     */
    public function prerequisiteCourse(Request $request, $courseid = null, $classid = null)
    {
      $nim = $request->input('nim');
      $term_year = $request->input('term_year');
      // dd($nim);
      $std_id1 = DB::table('acd_student')->where('Nim', $nim)->select('Student_Id')->first();
      $departmentid1 = DB::table('acd_student')->where('Nim', $nim)->select('Department_Id')->first();
      $clasprogid1 = DB::table('acd_student')->where('Nim', $nim)->select('Class_Prog_Id')->first();
        $studentid = $std_id1->Student_Id;
        $departmentid = $departmentid1->Department_Id;
        $clasprogid = $clasprogid1->Class_Prog_Id;

        if ($courseid) {
            $result = KrsOnlineData::getPrerequisite($courseid, $departmentid);
            if (collect($result)->count() != 0) {

                $prerequisite = $result->Prerequisite_Id;
                $prerequisiteid =DB::table('acd_prerequisite_detail')->where('Prerequisite_Id', $prerequisite)->get();



                if ($prerequisiteid->count() > 0) {
                    foreach ($prerequisiteid as $item) {

                      $departmentgrade = DB::table('acd_prerequisite_detail')
                      ->join('acd_grade_letter','acd_grade_letter.Grade_Letter_Id','=','acd_prerequisite_detail.Grade_Letter_Id')
                      ->join('acd_grade_department','acd_grade_department.Grade_Letter_Id','=','acd_grade_letter.Grade_Letter_Id')
                      ->where('Prerequisite_Id', $item->Prerequisite_Id)
                      ->where('acd_grade_department.Department_Id', $departmentid)
                      ->select('acd_grade_department.Weight_Value')->first();

                      $gradedetail = DB::table('acd_transcript')
                      ->join('acd_grade_letter','acd_grade_letter.Grade_Letter_Id','=','acd_transcript.Grade_Letter_Id')
                      ->join('acd_grade_department','acd_grade_department.Grade_Letter_Id','=','acd_grade_letter.Grade_Letter_Id')
                      ->where('acd_transcript.Course_Id', $item->Course_Id)->where('acd_transcript.Student_Id', $studentid)
                      ->where('acd_grade_department.Department_Id', $departmentid)
                      ->select('acd_grade_department.Weight_Value')->first();

                      $studentgrade = DB::table('acd_transcript')
                      ->join('acd_grade_letter','acd_grade_letter.Grade_Letter_Id','=','acd_transcript.Grade_Letter_Id')
                      ->join('acd_grade_department','acd_grade_department.Grade_Letter_Id','=','acd_grade_letter.Grade_Letter_Id')
                      ->where('acd_transcript.Course_Id', $item->Course_Id)->where('acd_transcript.Student_Id', $studentid)
                      ->where('acd_grade_department.Department_Id', $departmentid)
                      ->select('acd_grade_department.Weight_Value')->first();

                      $cekdata = DB::table('acd_transcript')->where('Course_Id', $item->Course_Id)->where('Student_Id', $studentid)->count();

                      $cour = DB::table('acd_course')->where('Course_Id', $item->Course_Id)->first();
                      $gra = DB::table('acd_grade_letter')->where('Grade_Letter_Id', $item->Grade_Letter_Id)->first();

                        if ($item->Prerequisite_Type_Id == 1) {

                          if ($cekdata > 0) {
                            // dd($departmentgrade->Weight_Value);
                            if ($gradedetail->Weight_Value < $departmentgrade->Weight_Value) {
                                $response = [
                                    'success' => 'false',
                                    'data' =>  "Nilai Matakuliah ".$cour->Course_Name." kurang dari ".$gra->Grade_Letter.".",
                                    'total' => 0
                                ];

                                return response()->json($response);
                            }
                          }else{
                              $response = [
                                  'success' => 'false',
                                  'data' => 'Anda Belum Menggambil Matakuliah '.$cour->Course_Name.' Atau Nilai Masih Kosong',
                                  'total' => 0
                              ];

                              return response()->json($response);
                            }
                        }

                        if ($item->Prerequisite_Type_Id == 2) {
                          $cour = DB::table('acd_course')->where('Course_Id', $item->Course_Id)->first();
                          $count_course = DB::table('acd_student_krs')->where('Student_Id', $studentid)->where('Course_Id', $item->Course_Id)->where('Class_Prog_Id', $clasprogid)->where('Term_Year_Id', $term_year)->count();

                          if ($count_course > 0) {

                          }else {
                            $response = [
                                'success' => 'false',
                                'data' => "Matakuliah ".$cour->Course_Name." harus diambil terlebih dahulu.",
                                'total' => 0
                            ];

                            return response()->json($response);
                          }

                        }

                        if ($item->Prerequisite_Type_Id == 3) {
                            if ($studentgrade < $departmentgrade) {

                                $response = [
                                    'success' => 'false',
                                    'data' => 'nilai anda kurang dari...',
                                    'total' => 0
                                ];

                                return response()->json($response);
                            }
                        }

                        if ($item->Prerequisite_Type_Id == 4) {
                          $entryyear1 = DB::table('acd_student')->where('Nim', $nim)->select('Entry_Year_Id')->first();
                          $entryterm1 = DB::table('acd_student')->where('Nim', $nim)->select('Entry_Term_Id')->first();

                          $entry_year = $entryyear1->Entry_Year_Id."".$entryterm1->Entry_Term_Id;
                          $term_years = DB::table('acd_student_krs')->where('Student_Id', $studentid)->select('Term_Year_Id')->orderby('Term_Year_Id', 'DESC')->first();

                          // result = hasil semester
                          $result = 0;
                          $result = $term_years->Term_Year_Id - $entry_year;
                          // dd($result);
                          if ($result % 2 == 1) {
                            $result = $result - 1;
                            $result = $result / 5;
                            $result = $result + 2;

                          }elseif ($result % 2 == 0) {
                            $result = $result / 5;
                            $result = $result + 1;
                          }
                          $value = $item->Value;
                          if ($result < $value) { // CEK apakah sudah masuk pada semester "sesuai prasyarat"
                            $response = [
                                'success' => 'false',
                                'data' => "Anda Belum masuk Semester ".$value.".",
                                'total' => 0
                            ];

                            return response()->json($response);
                          }else {

                          }


                        }

                        if ($item->Prerequisite_Type_Id == 5) {
                          $total_sks = DB::table('acd_transcript')->where('Student_Id', $studentid)->where('Grade_Letter_Id','!=', null)->sum('Sks');
                          $value = $item->Value;

                            if ($total_sks < $value) {

                                $response = [
                                    'success' => 'false',
                                    'data' => " Total SKS yang ditempuh belum mencukupi ".$value." SKS.",
                                    'total' => 0
                                ];

                                return response()->json($response);
                            }
                        }

                        if ($item->Prerequisite_Type_Id == 6) {
                            $value = $item->Value;
                            $gradeletter = 'D';

                            $totalnilai = KrsOnlineData::sumGrade($studentid, $gradeletter);
                            if ($totalnilai > $gradeletter) {

                                $response = [
                                    'success' => 'false',
                                    'data' => 'nilai anda kurang dari...',
                                    'total' => 0
                                ];

                                return response()->json($response);
                            }
                        }

                        if ($item->Prerequisite_Type_Id == 7) {
                            $bobot = 0;
                            $grade = '';

                            foreach ($gradedetail as $total) {
                                $finalgrade = $bobot + $total->Sks + $total->Weight_Value;
                            }

                            $totalsks = collect($studentgrade)->count();
                            $ipktranskrip = $bobot / $totalsks;
                            $ipkprerequisite = $item->Value;

                            if ($ipktranskrip < $ipkprerequisite) {
                                $response = [
                                    'success' => 'false',
                                    'data' => 'Anda belum masuk semester.',
                                    'total' => 0
                                ];

                                return response()->json($response);
                            }
                        }
                    }
                }
            }

            $response = [
                'success' => 'true',
                'data' => 'Anda belum masuk semester.',
                'total' => 0
            ];

            return response()->json($response);
        }

        $response = [
            'success' => 'success',
            'data' => 'Tidak ada persyaratan pengambilan course',
            'total' => 0
        ];

        return response()->json($response);
    }

}
