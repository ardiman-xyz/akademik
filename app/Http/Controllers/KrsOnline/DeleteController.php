<?php

namespace App\Http\Controllers\KrsOnline;

use App\Http\Controllers\Controller;
use App\Http\Helpers\SessionHelpers;
use App\Repositories\AcdStudentKhsRepository;
use App\Repositories\AcdStudentKrsRepository;
use Illuminate\Http\Request;

class DeleteController extends Controller
{
    /**
     * @var AcdStudentKrsRepository
     */
    protected $acdStudentKrs;
    protected $acdStudentKhs;

    /**
     * DeleteController constructor.
     * @param AcdStudentKrsRepository $acdStudentKrs
     * @param AcdStudentKhsRepository $acdStudentKhs
     */
    public function __construct(AcdStudentKrsRepository $acdStudentKrs, AcdStudentKhsRepository $acdStudentKhs)
    {
        $this->acdStudentKrs = $acdStudentKrs;
        $this->acdStudentKhs = $acdStudentKhs;
    }

    /**
     * return hapus data KRS
     * @param null $krsid
     * @param null $termyearid
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $studentid = SessionHelpers::getStudentId();
        $krsid = $request->input('Krs_Id');
        $termyearid = $request->input('Term_Year_Id');

        if ($krsid) {
            $khs = $this->acdStudentKhs->findWhere(['Krs_Id' => $krsid['Krs_Id'], 'Student_Id' => $studentid]);

            if ($khs->count() > 0) {

                $response = [
                    'success' => 'false',
                    'data' => 'Mata kuliah sudah terpakai di KHS',
                    'total' => $khs->count()
                ];

                return response()->json($response);
            }

            if ($khs->count() == 0) {

                $krs = $this->acdStudentKrs->findWhere(['Krs_Id' => $krsid['Krs_Id'], 'Student_Id' => $studentid, 'Term_Year_Id' => $termyearid['Term_Year_Id']]);

                if ($krs->count() > 0) {

                    $this->acdStudentKrs->delete($krsid['Krs_Id']);

                    $response = [
                        'success' => 'true',
                        'data' => 'Berhasil menghapus data !',
                        'total' => $khs->count()
                    ];

                    return response()->json($response);
                }

                $response = [
                    'success' => 'false',
                    'data' => 'Oppsss... terjadi kesalahan',
                    'total' => $krs->count()
                ];

                return response()->json($response);
            }
        }

        $response = [
            'success' => 'false',
            'data' => [],
            'total' => []
        ];

        return response()->json($response);
    }

}