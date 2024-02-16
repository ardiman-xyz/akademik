<?php

namespace App\Http\Controllers\KrsOnline;

use App\Http\Controllers\Controller;
use App\Http\Helpers\SessionHelpers;
use App\Http\Models\KrsOnlineData;
use App\Http\Models\StoreProcedure;
use App\Repositories\AcdCurriculumEntryYearRepository;
use App\Repositories\AcdStudentKhsRepository;
use App\Repositories\AcdStudentKrsRepository;
use App\Repositories\AcdStudentRepository;
use App\Repositories\MstrEventSchedRepository;
use Illuminate\Http\Request;

/**
 * Class UpdateController
 * @package App\Http\Controllers\KrsOnline
 */
class UpdateController extends Controller
{

    /**
     * @var AcdStudentKrsRepository
     */
    protected $acdStudentKrs;
    /**
     * @var AcdStudentRepository
     */
    protected $acdStudent;

    protected $acdCurriculumEntryYear;

    protected $mstrEventSched;

    protected $acdStudentKhs;

    /**
     * UpdateController constructor.
     * @param AcdStudentRepository $acdStudent
     * @param AcdStudentKrsRepository $acdStudentKrs
     */
    public function __construct(AcdStudentRepository $acdStudent, AcdStudentKrsRepository $acdStudentKrs, AcdCurriculumEntryYearRepository $acdCurriculumEntryYear,
                                MstrEventSchedRepository $mstrEventSched, AcdStudentKhsRepository $acdStudentKhs)
    {
        $this->acdStudentKrs = $acdStudentKrs;
        $this->acdStudent = $acdStudent;
        $this->acdCurriculumEntryYear = $acdCurriculumEntryYear;
        $this->mstrEventSched = $mstrEventSched;
        $this->acdStudentKhs = $acdStudentKhs;
    }

    /**
     * return halaman edit KRS
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return view('krs_online.edit');
    }

    public function getKrsData(Request $request)
    {

        $studentid = SessionHelpers::getStudentId();

        $krsid = $request->input('Krs_Id');
        $termyearid = $request->input('Term_Year_Id');
        $courseid = $request->input('Course_Id');
        $classid = $request->input('Class_Id');

        $krs = $this->acdStudentKrs->with('mstrClass')->with('acdCourse')
            ->findWhere(['Krs_Id' => $krsid, 'Student_Id' => $studentid, 'Course_Id' => $courseid, 'Class_Id' => $classid, 'Term_Year_Id' => $termyearid]);

        if ($krs->count() > 0) {

            $response = [
                'success' => 'true',
                'data' => $krs,
                'total' => $krs->count()
            ];

            return response()->json($response);
        }

        $response = [
            'success' => 'false',
            'data' => 'Oppsss... Terjadi kesalahan',
            'total' => []
        ];

        return response()->json($response);
    }

    /**
     * return list data kelas yang tersedia
     * @param int $courseid
     * @return \Illuminate\Http\JsonResponse
     */
    public function getClassList(Request $request)
    {
        $courseid = $request->input('Course_Id');

        if ($courseid) {

            $termyear = $this->getCurriculumEntryYear()->getOriginalContent();
            $departmentid = SessionHelpers::getDepartmentId();

            $result = $this->mstrEventSched->with('mstrEvents')->orderBy('Event_Sched_Id', 'Desc')
                ->findWhere(['Department_Id' => $departmentid])->first();

            $termyearid = $result->Term_Year_Id;

            if ($termyear['success'] == true) {

                foreach ($termyear['data'] as $item) {
                    $classprogid = $item->Class_Prog_Id;
                }
            }

            $data = KrsOnlineData::getClassName($courseid, $termyearid, $classprogid);

            if (collect($data)->count() != 0) {

                $response = [
                    'success' => 'true',
                    'data' => collect($data),
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
     * return data tahun ajaran kurikulum
     * @param int $termyearid
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCurriculumEntryYear()
    {
        $departmentid = SessionHelpers::getDepartmentId();
        $entryyearid = SessionHelpers::getEntryYearId();

        $result = $this->mstrEventSched->with('mstrEvents')->orderBy('Event_Sched_Id', 'Desc')
            ->findWhere(['Department_Id' => $departmentid])->first();

        if ($result) {

            $termyearid = $result->Term_Year_Id;
            $data = $this->acdCurriculumEntryYear->findWhere(['Department_Id' => $departmentid,
                'Term_Year_Id' => $termyearid, 'Entry_Year_Id' => $entryyearid]);

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
     * return list class info kapasitas kelas
     * @param int $classid
     * @return \Illuminate\Http\JsonResponse
     */
    public function getClassInfo(Request $request)
    {
        $courseid = $request->input('Course_Id');
        $classid = $request->input('Class_Id');

        $departmentid = SessionHelpers::getDepartmentId();

        if ($courseid) {
            $result = $this->mstrEventSched->with('mstrEvents')->orderBy('Event_Sched_Id', 'Desc')
                ->findWhere(['Department_Id' => $departmentid])->first();

            $termyearid = $result->Term_Year_Id;

            $data = StoreProcedure::getClassInfoForKRS($termyearid, $courseid, $classid);

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
     * return simpan data update KRS
     * @param null $courseid
     * @param null $classid
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        $studentid = SessionHelpers::getStudentId();

        $krsid = $request->input('Krs_Id');
        $courseid = $request->input('Course_Id');
        $classid = $request->input('Class_Id');
        $termyearid = $request->input('Term_Year_Id');

        $khs = $this->acdStudentKhs->findWhere(['Krs_Id' => $krsid, 'Student_Id' => $studentid]);

        if ($khs->count() > 0) {

            $response = [
                'success' => 'false',
                'data' => 'Mata kuliah sudah terpakai di KHS',
                'total' => $khs->count()
            ];

            return response()->json($response);
        }

        $class = StoreProcedure::getClassInfoForKRS($termyearid, $courseid, $classid);

        foreach ($class as $item) {
            $free = $item->Free;
        }

        if ($free <= 0) {

            $response = [
                'success' => 'false',
                'data' => 'Kelas sudah penuh',
                'total' => $khs->count()
            ];

            return response()->json($response);
        }

        if ($khs->count() == 0) {

            $krs = $this->acdStudentKrs->update(['Class_Id' => $classid], $krsid);

            $response = [
                'success' => 'true',
                'data' => 'Berhasil memperbaharui kelas.',
                'total' => $khs->count()
            ];

            return response()->json($response);
        }

        $response = [
            'success' => 'false',
            'data' => 'Opps... terjadi kesalahan',
            'total' => 0
        ];

        return response()->json($response);
    }

}