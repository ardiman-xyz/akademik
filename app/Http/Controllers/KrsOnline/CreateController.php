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
class CreateController extends Controller
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
        // dd($nim);

        if ($departmentid) {
            // $result = $this->mstrEventSched->with('mstrEvents')->orderBy('Event_Sched_Id', 'Desc')
            //     ->findWhere(['Department_Id' => $departmentid])->first();

            // $termyearid = $result->Term_Year_Id;
            $termyearid = $term_year;

            $result = StoreProcedure::getOfferedCourseForKRS($termyearid,$nim);
            // dd($result);
            $data2 = collect($result);
            // dd($data);
            $data = [];
            $i = 0;
                  foreach ($data2 as $hrg) {
                    $data[$i]['course_id'] = $hrg->course_id;
                    $data[$i]['course_code'] = $hrg->course_code;
                    $data[$i]['course_name'] = $hrg->course_name;
                    $data[$i]['course_name_eng'] = $hrg->course_name_eng;
                    $data[$i]['curriculum_id'] = $hrg->curriculum_id;
                    $data[$i]['curriculum_name'] = $hrg->curriculum_name;
                    $data[$i]['applied_sks'] = $hrg->applied_sks;
                    $data[$i]['is_sks'] = $hrg->is_sks;
                    $data[$i]['amount_per_sks'] =number_format($hrg->amount_per_sks, 0 ,",",".");
                    $i++;
                  }

                  if (collect($data)->count() != 0) {

                      $response = [
                          'success' => 'true',
                          'data' => $data,
                          'total' => collect($data)->count()
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
      // dd($nim);
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
            ->where('acd_offered_course.Term_Year_Id', $term_year)
            ->where('acd_offered_course.Course_Id', $courseid)
            ->where('acd_offered_course.Department_Id', $departmentid->Department_Id)
            ->where('acd_offered_course.Class_Prog_Id', $classprogid)
            ->orderBy('mstr_class.Class_Name','asc')
            ->get();
            // dd($data);


            $costforkrs = StoreProcedure::getCourseCostForKRS($term_year, $courseid,$nim);
              $data2 = collect($costforkrs);

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

    public function getCourseCost($courseid = null)
    {
      $nim = input::get('nim');
      // dd($nim);
      $term_year = input::get('term_year');
        $departmentid = DB::table('acd_student')->where('Nim', $nim)->select('Department_Id')->first();
        $classprogid1 = DB::table('acd_student')->where('Nim', $nim)->select('Class_Prog_Id')->first();
        $classprogid = $classprogid1->Class_Prog_Id;
        $studentid1 = DB::table('acd_student')->where('Nim', $nim)->select('Student_Id')->first();
        $studentid = $studentid1->Student_Id;

        $acd_course_type = DB::table('acd_course')->where('Course_Id', $courseid)->select('Course_Type_Id')->first();
        $discount=DB::table('fnc_student_cost_krs_personal')
        ->select('Description','Percent','fnc_student_cost_krs_personal.Course_Type_Id')
        ->where('Student_Id', $studentid)
        ->where('fnc_student_cost_krs_personal.Course_Type_Id', $acd_course_type->Course_Type_Id)->first();

        $discountcount=DB::table('fnc_student_cost_krs_personal')
        ->select('Description','Percent','fnc_student_cost_krs_personal.Course_Type_Id')
        ->where('Student_Id', $studentid)
        ->where('Term_Year_Id', $term_year)
        ->where('fnc_student_cost_krs_personal.Course_Type_Id', $acd_course_type->Course_Type_Id)->count();
        if($discountcount > 0){
          $disc = $discount->Percent;
          $ket = $discount->Description;
        }

        $costforkrs = StoreProcedure::getCourseCostForKRS($term_year, $courseid,$nim);
	//dd($costforkrs );
        $data2 = collect($costforkrs);
        $data = [];
        $i = 0;
              foreach ($data2 as $hrg) {
                $harga = $hrg->amount;
                if($discountcount>0){
                  $t_disc=$harga - ($harga * $disc / 100);
                }else{
                  $t_disc=$harga;
                }
                // dd($disc);
                $data[$i]['course_id'] = $hrg->course_id;
                $data[$i]['course_code'] = $hrg->course_code;
                $data[$i]['course_name'] = $hrg->course_name;
                $data[$i]['course_name_eng'] = $hrg->course_name_eng;
                $data[$i]['curriculum_id'] = $hrg->curriculum_id;
                $data[$i]['curriculum_name'] = $hrg->curriculum_name;
                $data[$i]['applied_sks'] = $hrg->applied_sks;
                $data[$i]['is_sks'] = $hrg->is_sks;
                $data[$i]['amount_per_sks'] =$hrg->amount_per_sks;
                $data[$i]['amount_per_mk'] = $hrg->amount_per_mk;
                $data[$i]['amount'] =  number_format($t_disc, 0 ,",",".");
                $data[$i]['amountfull'] = number_format($harga, 0 ,",",".");
                if($discountcount>0){
                  $data[$i]['discount'] = $disc;
                }else{
                  $data[$i]['discount'] = null;
                }
                if($discountcount>0){
                  $data[$i]['keterangan'] = $ket;
                }else{
                  $data[$i]['keterangan'] = null;
                }
              }

            if (collect($data)->count() != 0) {

                $response = [
                    'success' => 'true',
                    'data' => $data,
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

        $result = DB::table('mstr_event_sched')->join('mstr_event','mstr_event.Event_Id','=','mstr_event_sched.Event_Id')->where('mstr_event_sched.Department_Id', $departmentid)->first();
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
      $token = $request->input('session');
      $username = $request->input('username');
      // dd($request->all());
      $departmentid1 = DB::table('acd_student')->where('Nim', $nim)->select('Department_Id')->first();
      $departmentid = $departmentid1->Department_Id;
      $studentid1 = DB::table('acd_student')->where('Nim', $nim)->select('Student_Id')->first();
      $studentid = $studentid1->Student_Id;
      $classprogid1 = DB::table('acd_student')->where('Nim', $nim)->select('Class_Prog_Id')->first();
      $classprogid = $classprogid1->Class_Prog_Id;
        $courseid = $request->input('mata_kuliah');
        $classid = $request->input('daftar_kelas');



        // dd($cek);

        // $result = $this->mstrEventSched->with('mstrEvents')->orderBy('Event_Sched_Id', 'Desc')
        //     ->findWhere(['Department_Id' => $departmentid])->first();



        $termyearid = $term_year;

        $acd_course_type = DB::table('acd_course')->where('Course_Id', $courseid)->select('Course_Type_Id')->first();
        $discount=DB::table('fnc_student_cost_krs_personal')
        ->select('Description','Percent','fnc_student_cost_krs_personal.Course_Type_Id')
        ->where('Student_Id', $studentid)
        ->where('fnc_student_cost_krs_personal.Course_Type_Id', $acd_course_type->Course_Type_Id)->first();

        $discountcount=DB::table('fnc_student_cost_krs_personal')
        ->select('Description','Percent','fnc_student_cost_krs_personal.Course_Type_Id')
        ->where('Student_Id', $studentid)
        ->where('Term_Year_Id', $term_year)
        ->where('fnc_student_cost_krs_personal.Course_Type_Id', $acd_course_type->Course_Type_Id)->count();
        if($discountcount > 0){
          $disc = $discount->Percent;
        }

        $saldo = StoreProcedure::getSaldo($termyearid,$nim);
        $class = StoreProcedure::getClassInfoForKRS($termyearid, $courseid, $classid,$nim);
        $costforkrs = StoreProcedure::getCourseCostForKRS($termyearid, $courseid,$nim);        
        $allowedskss = DB::select('CALL usp_GetAllowedSKSForKRS(?,?)', [$termyearid, $studentid]);
        $allowedsks = 0;
        foreach ($allowedskss as $a) { $allowedsks = $a->AllowedSKS; }
        // dd($allowedsks,1);
        $sksambil = DB::table('acd_student_krs')->where('Student_Id', $studentid)->where('Term_Year_Id', $termyearid)
        ->select(DB::raw('(SUM(acd_student_krs.Sks)) as SKS'))->get(); 
        $Sksdiambil = 0;
        foreach ($sksambil as $b) { $Sksdiambil = $b->SKS; }
        if($Sksdiambil > $allowedsks){
          $response = [
              'success' => 'false',
              'data' => 'Melebihi batas SKS.',
              'total' => 0
          ];
          return response()->json($response);
        }
        

        if ($courseid) {
          foreach ($class as $item) {
              $free = $item->Free;
          }

          if ($free <= 0) {
              $response = [
                  'success' => 'false',
                  'data' => 'Kelas Sudah Penuh.',
                  'total' => 0
              ];
              DB::table('_token')->where('id', $nim)->delete();
              return response()->json($response);
          }

          // if (count($saldo) > 0) {
          //     foreach ($saldo as $sld) {
          //         $current_saldo = $sld->SisaSaldoSaatIni;
          //     }
          // }


          // $cek2=$request->input('total_biaya2');
          // $cek=str_replace(".", "", $cek2);

          if (count($costforkrs) > 0) {
              foreach (collect($costforkrs) as $cfk) {
                $harga = $cfk->amount;
                if($discountcount>0){
                  $t_disc=$harga - ($harga * $disc / 100);
                }else{
                  $t_disc=$harga;
                }
                  $biaya_sks = $cfk->applied_sks;
                  $biaya_matkul2 = number_format($t_disc, 0 ,",",".");
                  $biaya_matkul=str_replace(".", "", $biaya_matkul2);
                  $sks = $cfk->applied_sks;
                //  $biaya_matkul = $cfk->amount;
              }
          }

          // if ($current_saldo < $biaya_matkul) {
          //     $response = [
          //         'success' => 'false',
          //         'data' => 'Sisa Saldo tidak mencukupi.',
          //         'total' => 0
          //     ];
          //    DB::table('_token')->where('id', $nim)->delete();
          //
          //     return response()->json($response);
          // }

            // $this->acdStudentKrs->create($krsdata);
            $tokenn = DB::table('_token')->where('username', $username)->select('Token')->first();
            $username = DB::table('_token')->where('username', $username)->select('username')->first();
            // $datanim = DB::table('_token')->where('id', $nim)->select('id')->first();
            // $datamd5nim = DB::table('_token')->where('id', $nim)->select('Nim')->first();
            // dd($token);
            // $md5nim = md5($nim);
            // $nim_token =$md5nim.'ciyepaDanyarIapaa?';


            // $tokennnyaadalah = $request->token;
            if($token == $tokenn){
              $check_student = DB::table('acd_student')->where('Student_Id',$studentid)->first();
              $getofferedcoursekrs = DB::select('CALL usp_GetCourseCostForKRS(?,?,?,?,?)',array($check_student->Department_Id,$term_year,$classprogid,$check_student->Entry_Year_Id,$courseid));
              if($getofferedcoursekrs == null){
                $response = [
                    'success' => 'false',
                    'data' => 'Biaya SKS matakuliah belum diset oleh Keuangan',
                    'total' => 0
                ];
                return response()->json($response);
              }
              $data_now = DB::table('acd_offered_course')
                ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
                ->join('acd_course_curriculum','acd_course_curriculum.Course_Id','=','acd_offered_course.Course_Id')
                ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
                ->leftjoin('acd_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
                ->join('mstr_class','mstr_class.Class_Id','=','acd_offered_course.Class_Id')
                ->where('acd_offered_course.Department_Id', $check_student->Department_Id)
                ->where('acd_offered_course.Class_Prog_Id', $classprogid)
                ->where('acd_offered_course.Term_Year_Id', $term_year)
                ->where('acd_offered_course.Course_Id', $courseid)
                ->where('acd_offered_course.Class_Id', $classid)
                //  ->where('cd.Sched_Session_Group_Id', $schedsession)
                //  ->where('acd_offered_course.Curriculum_Id', $curriculum)
                ->select( 'acd_offered_course.*','acd_course.*','mstr_class.Class_Name','acd_course_curriculum.Study_Level_Id','acd_course_curriculum.Curriculum_Id',
                  DB::raw("(SELECT Group_Concat(acd_sched_session.Description SEPARATOR '|') 
                            FROM acd_offered_course_sched 
                            LEFT JOIN acd_sched_session ON acd_offered_course_sched.Sched_Session_Id = acd_sched_session.Sched_Session_Id 
                            LEFT JOIN acd_offered_course as dd ON dd.Offered_Course_id = acd_offered_course_sched.Offered_Course_id 
                            WHERE acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id) as jadwal")
                  )
                ->orderBy('acd_course_curriculum.Study_Level_Id', 'asc')
                ->orderBy('acd_course.Course_Name', 'asc')
                ->orderBy('acd_offered_course.Class_Id', 'asc')
                ->groupBy('acd_offered_course.Course_Id','acd_offered_course.Class_Id')
                ->get();

                $data_krs = DB::table('acd_student_krs')->where([
                  ['Student_Id',$studentid],
                  ['Term_Year_Id',$term_year],
                  ['Class_Prog_Id',$classprogid],
                ])->get();

                $num = 0;
                $all_jadwal = [];
                foreach ($data_krs as $key) {
                  $get_jdwl = DB::table('acd_offered_course')
                    ->join('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_offered_course.Term_Year_Id')
                    ->join('acd_course_curriculum','acd_course_curriculum.Course_Id','=','acd_offered_course.Course_Id')
                    ->join('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_offered_course.Class_Prog_Id')
                    ->leftjoin('acd_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
                    ->join('mstr_class','mstr_class.Class_Id','=','acd_offered_course.Class_Id')
                    ->where('acd_offered_course.Department_Id', $check_student->Department_Id)
                    ->where('acd_offered_course.Class_Prog_Id', $key->Class_Prog_Id)
                    ->where('acd_offered_course.Term_Year_Id', $key->Term_Year_Id)
                    ->where('acd_offered_course.Course_Id', $key->Course_Id)
                    ->where('acd_offered_course.Class_Id', $key->Class_Id)
                    //  ->where('cd.Sched_Session_Group_Id', $schedsession)
                    //  ->where('acd_offered_course.Curriculum_Id', $curriculum)
                    ->select( 'acd_offered_course.*','acd_course.*','mstr_class.Class_Name','acd_course_curriculum.Study_Level_Id','acd_course_curriculum.Curriculum_Id',
                      DB::raw("(SELECT Group_Concat(acd_sched_session.Description SEPARATOR '|') 
                                FROM acd_offered_course_sched 
                                LEFT JOIN acd_sched_session ON acd_offered_course_sched.Sched_Session_Id = acd_sched_session.Sched_Session_Id 
                                LEFT JOIN acd_offered_course as dd ON dd.Offered_Course_id = acd_offered_course_sched.Offered_Course_id 
                                WHERE acd_offered_course_sched.Offered_Course_id = acd_offered_course.Offered_Course_id) as jadwal")
                      )
                    ->orderBy('acd_course_curriculum.Study_Level_Id', 'asc')
                    ->orderBy('acd_course.Course_Name', 'asc')
                    ->orderBy('acd_offered_course.Class_Id', 'asc')
                    ->groupBy('acd_offered_course.Course_Id','acd_offered_course.Class_Id')
                    ->get();
                    foreach ($get_jdwl as $key2) {
                      if($key2->jadwal != "" || $key2->jadwal != NULL){
                        $explodes = explode('|',$key2->jadwal);
                        foreach ($explodes as $key3) {
                          $all_jadwal[$num] = $key3;
                          $num++; 
                        }
                      }else{

                      }
                    }
                }

                foreach ($data_now as $key) {
                  if($key->jadwal != "" || $key->jadwal != NULL){
                    $explodes = explode('|',$key->jadwal);
                    foreach ($explodes as $key2) {
                      if(in_array($key2,$all_jadwal)){
                        // dd([[$data_now],[$all_jadwal]]);
                        $response = [
                            'success' => 'false',
                            'data' => 'Jadwal Bentrok '.$key2,
                            'total' => 0
                        ];
                        return response()->json($response);
                      }
                    }
                  }
                }

              $acd_course_get = DB::table('acd_course')->where([['Course_Id',$courseid],['Department_Id',$departmentid]])->first();
              if($acd_course_get->Course_Type_Id == 12 || $acd_course_get->Course_Type_Id == 14 || $acd_course_get->Course_Type_Id == 15 || $acd_course_get->Course_Type_Id == 17 || $acd_course_get->Course_Type_Id == 18 || $acd_course_get->Course_Type_Id == 16){
                  DB::table('acd_student_krs')
                  ->insert(
                    [
                      'Student_Id' => $studentid,
                      'Term_Year_Id' => $term_year,
                      'Course_Id' => $courseid,
                      'Class_Prog_Id' => $classprogid,
                      'Class_Id' => $classid,
                      'Cost_Item_Id' => 2,
                      'Sks' => $sks,
                      'Amount' => $biaya_matkul,
                      'Is_Approved'=>1,
                      'Approved_By'=>'Admin',
                      'Modified_Date' => date('Y-m-d'),
                      'Created_By' => 'Admin',
                      'Created_Date' => date('Y-m-d')
                    ]);

                    DB::table('_token')->where('username', $username)->delete();

                    $response = [
                      'success' => 'true',
                      'data' => 'Mata kuliah berhasil ditambahkan.',
                      'total' => 0
                    ];

                    return response()->json($response);

                }elseif ($acd_course_get->Course_Type_Id == 13 || $acd_course_get->Course_Type_Id == 19 || $acd_course_get->Course_Type_Id == 22) {
                  $date = Date('Y-m-d');
                    DB::table('acd_student_krs')
                  ->insert(
                    [
                      'Student_Id' => $studentid,
                      'Term_Year_Id' => $term_year,
                      'Course_Id' => $courseid,
                      'Class_Prog_Id' => $classprogid,
                      'Class_Id' => $classid,
                      'Cost_Item_Id' => 105,
                      'Sks' => $sks,
                      'Amount' => $biaya_matkul,
                      'Is_Approved'=>1,
                      'Approved_By'=>'Admin',
                      'Modified_Date' => date('Y-m-d'),
                      'Created_By' => 'Admin',
                      'Created_Date' => date('Y-m-d')
                    ]);

                    DB::table('_token')->where('username', $username)->delete();

                    $response = [
                      'success' => 'true',
                      'data' => 'Mata kuliah berhasil ditambahkan.',
                      'total' => 0
                    ];

                    return response()->json($response);
                }

              
            }
        }
        $response = [
            'success' => 'false',
            'data' => 'tidak ada data',
            'total' => 0
        ];
        DB::table('_token')->where('id', $nim)->delete();
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

            //jika pakai saldo dulu
           $saldo = StoreProcedure::getSaldo($termyearid,$nim);
            $countusedsks = db::table('acd_student_krs')->where('Student_Id', $studentid)->where('Term_Year_Id', $termyearid)->count();
            $usedsks = db::table('acd_student_krs')->where('Student_Id', $studentid)->where('Term_Year_Id', $termyearid)->get();
            $sumsks = db::table('acd_student_krs')->where('Student_Id', $studentid)->where('Term_Year_Id', $termyearid)->sum('Sks');
            $allowedsks = DB::select('CALL usp_GetAllowedSKSForKRS(?,?)', [$termyearid, $studentid]);
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

           //check saldo saat ini
          //  if (count($saldo) > 0) {

          //      foreach ($saldo as $sld) {
          //          $current_saldo = $sld->SisaSaldoSaatIni;
          //      }
          //  }

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
            // dd($biaya_matkul);
            // if($biaya_matkul == null){
            //   $response = [
            //             'success' => 'false',
            //             'data' => 'Biaya Matakuliah Belum diset.',
            //             'total' => 0
            //         ];
            //         // DB::table('_token')->where('id', $nim)->delete();

            //         return response()->json($response);
            // }

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
                    DB::table('_token')->where('id', $nim)->delete();

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
                DB::table('_token')->where('id', $nim)->delete();

                return response()->json($response);
            }

            //check sks yang sudah digunakan
            if ($countusedsks > 0) {
              // dd($countusedsks);
              $total_sks =0;
              foreach($allowedsks as $sk){
                $total_sks = $sk->AllowedSKS ;
              }
              // dd($total_sks,$sumsks,$biaya_sks);
                if (($total_sks - $sumsks) < $biaya_sks) {
                    $response = [
                        'success' => 'false',
                        'data' => 'Sisa SKS tidak mencukupi.',
                        'total' => 0
                    ];
                    DB::table('_token')->where('id', $nim)->delete();

                    return response()->json($response);
                }
            }

//            //check saldo dan biaya matkul
           // if ($current_saldo < $biaya_matkul) {
           //
           //     $response = [
           //         'success' => 'false',
           //         'data' => 'Sisa Saldo tidak mencukupi.',
           //         'total' => 0
           //     ];
           //
           //     return response()->json($response);
           // }

            //check mata kuliah yang sudah diambil
            if (isset($exist_course) == $courseid) {

                $response = [
                    'success' => 'false',
                    'data' => 'Mata kuliah sudah diambil.',
                    'total' => 0
                ];
                DB::table('_token')->where('id', $nim)->delete();

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
                      // dd($cekdata);

                      $std = DB::table('acd_student')->where('Student_Id',$studentid)->first();
                      $querys=DB::table('acd_transcript')
                      ->select(DB::raw('SUM(acd_transcript.Sks) as jml_sks'),
                      DB::raw('round(sum(acd_transcript.Sks*acd_transcript.Weight_Value),2) as jml_mutu'),
                      DB::raw('round((sum(acd_transcript.Sks*acd_transcript.Weight_Value))/(SUM(acd_transcript.Sks)),2) as ipk'))
                      ->join('acd_student','acd_student.Student_Id','=','acd_transcript.Student_Id')->where('acd_student.Nim',$std->Nim)->first();

                      $cour = DB::table('acd_course')->where('Course_Id', $item->Course_Id)->first();
                      $gra = DB::table('acd_grade_letter')->where('Grade_Letter_Id', $item->Grade_Letter_Id)->first();

                        if ($item->Prerequisite_Type_Id == 1) {

                          if ($cekdata <= 0) {
                            $response = [
                                'success' => 'false',
                                'data' => 'Anda Belum Menggambil Matakuliah '.$cour->Course_Name.' Atau Nilai Masih Kosong',
                                'total' => 0
                            ];
                            DB::table('_token')->where('id', $nim)->delete();
                            $q=DB::table('_token')->get();
                            // dd($q);

                            return response()->json($response);
                          }

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
                            DB::table('_token')->where('id', $nim)->delete();

                            return response()->json($response);
                          }

                        }

                        if ($item->Prerequisite_Type_Id == 3) {
                            $cour = DB::table('acd_course')->where('Course_Id', $item->Course_Id)->first();
                            $count_course = DB::table('acd_student_krs')->where('Student_Id', $studentid)->where('Course_Id', $item->Course_Id)->where('Class_Prog_Id', $clasprogid)->where('Term_Year_Id', $term_year)->count();

                            if ($count_course > 0) {

                            }else {
                              $response = [
                                  'success' => 'false',
                                  'data' => "Matakuliah ".$cour->Course_Name." harus diambil terlebih dahulu.",
                                  'total' => 0
                              ];
                              DB::table('_token')->where('id', $nim)->delete();

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
                            DB::table('_token')->where('id', $nim)->delete();

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
                                DB::table('_token')->where('id', $nim)->delete();

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
                                    'data' => 'nilai anda kurang.',
                                    'total' => 0
                                ];
                                DB::table('_token')->where('id', $nim)->delete();

                                return response()->json($response);
                            }
                        }

                        if ($item->Prerequisite_Type_Id == 7) {
                            $bobot = 0;
                            $grade = '';

                            $ipktranskrip = $querys->ipk;
                            $ipkprerequisite = $item->Value;

                            if ($ipktranskrip <= $ipkprerequisite) {
                                $response = [
                                    'success' => 'false',
                                    'data' => 'IPK anda kurang dari '.$ipkprerequisite,
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


    public function semesterpendekCourse(Request $request, $courseid = null, $classid = null)
    {
      $nim = $request->input('nim');
      $term_year = $request->input('term_year');
      // dd($nim);
      $std_id1 = DB::table('acd_student')->where('Nim', $nim)->select('Student_Id')->first();
      $departmentid1 = DB::table('acd_student')->where('Nim', $nim)->select('Department_Id')->first();
      $clasprogid1 = DB::table('acd_student')->where('Nim', $nim)->select('Class_Prog_Id')->first();
      $entry_year1 = DB::table('acd_student')->where('Nim', $nim)->select('Entry_Year_Id')->first();
        $studentid = $std_id1->Student_Id;
        $departmentid = $departmentid1->Department_Id;
        $clasprogid = $clasprogid1->Class_Prog_Id;
        $entry_year = $entry_year1->Entry_Year_Id;

      $mstr_term_year = DB::table('mstr_term_year')->where('Term_Year_Id',$term_year)->first();
      $short_term = db::table('acd_short_term_krs')->where('Department_Id', $departmentid)->count();

      $KrsMatakuliahDibukaController = new KrsMatakuliahDibukaController();

      if($mstr_term_year->Term_Id == 3){
        if ($short_term == 0)
        {
          // $result = StoreProcedure::getOfferedCourseForKRSByStudent($termyearid,$nim);
          $select_course = DB::select('CALL usp_GetOfferredCourseForKRSByStudent(?,?,?,?,?)',array($term_year,$departmentid,$clasprogid,$entry_year,$studentid));
          $result = collect($select_course);

          if (collect($select_course)->count() == 0) {
              $response = [
                  'success' => 'false',
                  'data' => 'Opsi Untuk Semester Pendek Belum diatur.',
                  'total' => 0
              ];
              DB::table('_token')->where('id', $nim)->delete();

              return response()->json($response);
          }
          // $notif = " ";
        } else{
            $acd_short_term_krs = db::table('acd_short_term_krs')->where('Department_Id', $departmentid)->first();
            $datamkdibuka = $KrsMatakuliahDibukaController->getOpenedCourse($nim,$term_year);
            // dd($datamkdibuka);
            $select_course = DB::select('CALL usp_GetOfferredCourseForKRSByStudent(?,?,?,?,?)',array($term_year,$departmentid,$clasprogid,$entry_year,$studentid));
            if($courseid != null){
              $cost_sks = DB::table('fnc_course_cost_sks')->where('Department_Id', $departmentid)->where('Term_Year_Id', $term_year)->where('Class_Prog_Id', $clasprogid)->where('Entry_Year_Id', $entry_year)->count();
              // dd($cost_sks);
              if($cost_sks == 0){
                $notif = "Biaya Per SKS di Keuangan untuk Angkatan ".$entry_year." Belum di Set";
              }else{
                if($acd_short_term_krs->Taking_Rule_Id == 2){
                  if($acd_short_term_krs->Is_All_Year == 0){
                    $datamkdibukaulangalltahun = $KrsMatakuliahDibukaController->getOpenedCourseAllYearYbs($nim,$term_year);
                    // dd($datamkdibukaulangalltahun);
                    $id_mk = [];
                    $i = 0;
                    foreach ($datamkdibukaulangalltahun as $item) {
                      $id_mk[$i] = $item;
                      $i++;
                    }
                    // dd($datamkdibukaulangalltahun);
                    if(!in_array($courseid,$id_mk)){
                      $response = [
                          'success' => 'false',
                          'data' => 'Matakuliah Belum Pernah diambil ditahun ini.',
                          'total' => 0
                      ];
                      DB::table('_token')->where('id', $nim)->delete();

                      return response()->json($response);
                    }else{

                    }
                  }else{
                    $datamkdibukaulangalltahun = $KrsMatakuliahDibukaController->getOpenedCourseAllYear($nim,$term_year);
                    $id_mk = [];
                    $i = 0;
                    foreach ($datamkdibukaulangalltahun as $item) {
                      $id_mk[$i] = $item;
                      $i++;
                    }
                    // dd($datamkdibukaulangalltahun);
                    if(!in_array($courseid,$id_mk)){
                      $response = [
                          'success' => 'false',
                          'data' => 'Matakuliah Belum Pernah diambil sebelumnya.',
                          'total' => 0
                      ];
                      DB::table('_token')->where('id', $nim)->delete();

                      return response()->json($response);
                    }else{

                    }
                  }
                }
                else if($acd_short_term_krs->Taking_Rule_Id == 3){
                 if($acd_short_term_krs->Is_All_Year == 0){
                   // dd($acd_short_term_krs->Is_All_Year);
                   $datamkdibukaulangalltahun = $KrsMatakuliahDibukaController->getOpenedCourseNilaiAllYearYbs($nim,$term_year,$acd_short_term_krs->Grade_Letter_Minimum_Id);
                   $id_mk = [];
                   $i = 0;
                   foreach ($datamkdibukaulangalltahun as $item) {
                     $id_mk[$i] = $item->ID_MK;
                     $i++;
                   }
                   // dd($datamkdibukaulangalltahun);
                   if(!in_array($courseid,$id_mk)){
                     $response = [
                         'success' => 'false',
                         'data' => 'Matakuliah Belum Pernah diambil tahun ini atau nilai kurang dari nilai minimum.',
                         'total' => 0
                     ];
                     DB::table('_token')->where('id', $nim)->delete();

                     return response()->json($response);
                   }else{

                   }
                 }else{
                   $datamkdibukaulangalltahun = $KrsMatakuliahDibukaController->getOpenedCourseNilaiAllYear($nim,$term_year,$acd_short_term_krs->Grade_Letter_Minimum_Id);
                   // $result = collect($datamkdibukaulangalltahun);
                   // dd($datamkdibukaulangalltahun);
                   $id_mk = [];
                   $i = 0;
                   foreach ($datamkdibukaulangalltahun as $item) {
                     $id_mk[$i] = $item->ID_MK;
                     // dd($item);
                     $i++;
                   }
                   // dd($id_mk);
                   // dd(!in_array($courseid,$id_mk));
                   // dd($acd_short_term_krs->Grade_Letter_Minimum_Id);
                   // dd($datamkdibukaulangalltahun);
                   if(!in_array($courseid,$id_mk)){
                     $response = [
                         'success' => 'false',
                         'data' => 'Matakuliah Belum Pernah diambil sebelumnya atau nilai kurang dari nilai minimum.',
                         'total' => 0
                     ];
                     DB::table('_token')->where('id', $nim)->delete();

                     return response()->json($response);
                   }else{

                   }
                  }
                }
              }
            }
        }

  if ($courseid != null) {
        // if ($notif == null) {
          $courecostforkrs = DB::select('CALL usp_GetCourseCostForKRS(?,?,?,?,?)',array($departmentid,$term_year,$clasprogid,$entry_year,$courseid));
          foreach ($courecostforkrs as $cforkrs) {
            $SKS = $cforkrs->applied_sks;
            $amount = $cforkrs->amount;
          }
          $select_class = DB::table('acd_offered_course')
          ->join('mstr_class','mstr_class.Class_Id','=','acd_offered_course.Class_Id')
          ->where('acd_offered_course.Term_Year_Id', $term_year)->where('acd_offered_course.Course_Id', $courseid)->where('acd_offered_course.Class_Prog_Id', $clasprogid)->get();
        // }else {
        //
        // }
      }
          if ($classid != null) {
            $classinfo = DB::select('CALL usp_GetClassInfoForKRS(?,?,?,?,?)',array($term_year,$departmentid,$clasprogid,$courseid,$classid));
            foreach ($classinfo as $clsinfo) {
              $dosen = "";
              $kapasitas = $clsinfo->Capacity;
              $terdaftar = $clsinfo->Used;
              $sisakuota = $clsinfo->Free;
            }
            // dd($classinfo);
      }

      // dd($datamkdibukaulangalltahun);
    }
        $response = [
            'success' => 'success',
            'data' => 'Tidak ada persyaratan semester pendek pengambilan course',
            'total' => 0
        ];

        return response()->json($response);
    }

    public function postToken()
    {
      $xxx = input::get('xxx');
      $xxxx = $xxx.'ciyepaDanyarIapaa?';
      $nim = input::get('nim');
      $token = bcrypt($nim);

      $data = DB::table('_token')->where('id', $nim)->count();
      // dd($data);
      $timezone = +7;
      $Timestamp =  gmdate("Y-m-d H:i:s", time() + 3600*($timezone+date("I")));
      if($data == 0){
        DB::table('_token')
        ->insert(
          ['id'=>$nim,'Nim'=>$xxxx,'Token'=>$token,'Timestamp_time'=>$Timestamp]);

          $response = [
            'success' => 'success'
          ];
          return response()->json($response);
      }else{
        DB::table('_token')
        ->where('id', $nim)
        ->update(
          ['Nim'=>$xxx,'Token'=>$token,'Timestamp_time'=>$Timestamp]);

          $response = [
            'success' => 'success'
          ];
          return response()->json($response);
      }
    }

    public function getToken()
    {
        $nim2 = input::get('nim');
        // $data = DB::table('_token')->get();
        $data = DB::table('_token')->where('id', $nim2)->select('Token')->first();
        // dd($data);

        if ($data != null) {
            $response = [
                'success' => 'true',
                'data' => $data->Token
            ];

            return response()->json($response);
        }

    $response = [
        'success' => 'false'
    ];

    return response()->json($data);
    }

}
