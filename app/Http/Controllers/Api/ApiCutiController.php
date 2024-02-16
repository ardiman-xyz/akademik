<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\User;
use Session;
use Auth;
use File;
use Storage;

class ApiCutiController extends Controller
{
    //all
    public function getMasterBerkasCutiController(Request $request)
    {
        // dd(Auth::user());
        try {
            $data = DB::table('mstr_vacation_document')
                ->orderBy('mstr_vacation_document.Vacation_Document_Id', 'asc')
                ->get();

            $datas = [];
            $i = 0;
            foreach ($data as $key) {
                $datas[$i]['No'] = $i + 1;
                $datas[$i]['Vacation_Document_Id'] = $key->Vacation_Document_Id;
                $datas[$i]['Vacation_Document_Name'] = $key->Vacation_Document_Name;
                $i++;
            }
            return response()->json([
                "success" => true,
                "data" => $datas,
                "total" => count($datas),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "data" => $e
            ], 200);
        }
    }

    //post
    public function postMasterBerkasCutiController(Request $request)
    {
        // dd($request->all());
        try {
            if ($request->data['Vacation_Document_Id'] == null) {
                $insert = DB::table('mstr_vacation_document')
                    ->insert([
                        'Vacation_Document_Name' => $request->data['Vacation_Document_Name'],
                        'Created_By' => Auth::user()->email,
                        'Created_Date' => date('Y-m-d H:i:s')
                    ]);
                return response()->json([
                    "success" => true,
                    "data" => 'Berhasil menambah data',
                    "total" => 1,
                ], 200);
            } else {
                $insert = DB::table('mstr_vacation_document')
                    ->where('Vacation_Document_Id', $request->data['Vacation_Document_Id'])
                    ->update([
                        'Vacation_Document_Name' => $request->data['Vacation_Document_Name'],
                        'Modified_By' => Auth::user()->email,
                        'Modified_Date' => date('Y-m-d H:i:s')
                    ]);
                return response()->json([
                    "success" => true,
                    "data" => 'Berhasil mengubah data',
                    "total" => 1,
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "data" => 'Gagal menambah data',
                "message" => $e
            ], 200);
        }
    }

    //delete
    public function deleteMasterBerkasCutiController(Request $request)
    {
        try {
            $insert = DB::table('mstr_vacation_document')
                ->where('Vacation_Document_Id', $request->data)
                ->delete();
            return response()->json([
                "success" => true,
                "data" => 'Berhasil menghapus data',
                "total" => 1,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "data" => 'Gagal menghapus data/ Data masih digunakan',
                "message" => $e
            ], 200);
        }
    }

    //CUTI
    //berkas per prodi
    public function getMasterBerkasProdiCutiController(Request $request)
    {
        // dd($request->all());
        try {
            $datas = DB::table('acd_student_vacation_prerequisite')
                ->join('mstr_vacation_document', 'mstr_vacation_document.Vacation_Document_Id', '=', 'acd_student_vacation_prerequisite.Vacation_Document_Id')
                ->where([['Department_Id', $request->Department_Id], ['Is_Vacation', 1]])
                ->orderBy('acd_student_vacation_prerequisite.Order_Id', 'asc')
                ->get();

            $data = [];
            $i = 0;
            foreach ($datas as $key) {
                $data[$i]['No'] = $i + 1;
                $data[$i]['Student_Vacation_Prerequisite_Id'] = $key->Student_Vacation_Prerequisite_Id;
                $data[$i]['Vacation_Document_Id'] = $key->Vacation_Document_Id;
                $data[$i]['Vacation_Document_Name'] = $key->Vacation_Document_Name;
                $data[$i]['Document_File'] = $key->Document_File;
                $data[$i]['File_Name'] = $key->Document_File;
                $data[$i]['File_Url'] = 'berkas_cuti/' . $key->Student_Vacation_Prerequisite_Id . '/' . $key->Document_File;
                $data[$i]['Copies'] = $key->Copies;
                $i++;
            }
            return response()->json([
                "success" => true,
                "data" => $data,
                "total" => count($data),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "data" => $e
            ], 200);
        }
    }

    public function getBerkasNotinCutiController(Request $request)
    {
        // dd($request->all());
        try {
            $in = DB::table('acd_student_vacation_prerequisite')
                ->where([['Department_Id', $request->Department_Id], ['Is_Vacation', 1]])
                ->select('Vacation_Document_Id');

            $data = DB::table('mstr_vacation_document')
                ->wherenotin('Vacation_Document_Id', $in)
                ->orderBy('mstr_vacation_document.Vacation_Document_Id', 'asc')
                ->get();

            $datas = [];
            $i = 0;
            foreach ($data as $key) {
                $datas[$i]['No'] = $i + 1;
                $datas[$i]['Vacation_Document_Id'] = $key->Vacation_Document_Id;
                $datas[$i]['Vacation_Document_Name'] = $key->Vacation_Document_Name;
                $i++;
            }
            if ($request->Vacation_Document_Id != null) {
                $i_use = DB::table('mstr_vacation_document')
                    ->where('Vacation_Document_Id', $request->Vacation_Document_Id)
                    ->first();
                $datas[$i]['No'] = $i + 1;
                $datas[$i]['Vacation_Document_Id'] = $i_use->Vacation_Document_Id;
                $datas[$i]['Vacation_Document_Name'] = $i_use->Vacation_Document_Name;
            } else {
            }
            return response()->json([
                "success" => true,
                "data" => $datas,
                "total" => count($datas),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "data" => $e
            ], 200);
        }
    }

    //post
    public function postMasterBerkasProdiCutiController(Request $request)
    {
        // dd($request->all());
        try {
            if ($request->data['Student_Vacation_Prerequisite_Id'] == null) {
                $i = 0;
                foreach ($request->data['Vacation_Document_Id'] as $key => $value) {
                    $insert = DB::table('acd_student_vacation_prerequisite')
                        ->insert([
                            'Vacation_Document_Id' => $value,
                            'Department_Id' => $request->data['Department_Id'],
                            'Copies' => ($request->data['Copies'][$i] == 0 ? 1 : $request->data['Copies'][$i]),
                            'Order_Id' => $value,
                            'Is_Vacation' => 1,
                            'Created_By' => Auth::user()->email,
                            'Created_Date' => date('Y-m-d H:i:s')
                        ]);
                    $i++;
                }
                return response()->json([
                    "success" => true,
                    "data" => 'Berhasil menambah data',
                    "total" => 1,
                ], 200);
            } else {
                $insert = DB::table('acd_student_vacation_prerequisite')
                    ->where('Student_Vacation_Prerequisite_Id', $request->data['Student_Vacation_Prerequisite_Id'])
                    ->update([
                        'Vacation_Document_Id' => $request->data['Vacation_Document_Id'],
                        'Copies' => $request->data['Copies'],
                        'Document_File' => $request->data['File_Url'],
                        'Is_Vacation' => 1,
                        'Modified_By' => Auth::user()->email,
                        'Modified_Date' => date('Y-m-d H:i:s')
                    ]);
                return response()->json([
                    "success" => true,
                    "data" => 'Berhasil mengubah data',
                    "total" => 1,
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "data" => 'Gagal menambah data',
                "message" => $e
            ], 200);
        }
    }

    //delete
    public function deleteMasterBerkasProdiCutiController(Request $request)
    {
        try {
            $insert = DB::table('acd_student_vacation_prerequisite')
                ->where('Student_Vacation_Prerequisite_Id', $request->data)
                ->delete();
            return response()->json([
                "success" => true,
                "data" => 'Berhasil menghapus data',
                "total" => 1,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "data" => 'Gagal menghapus data/ Data masih digunakan',
                "message" => $e
            ], 200);
        }
    }
    //END CUTI

    //AKTIF KEMBALI
    public function getMasterBerkasProdiKembaliController(Request $request)
    {
        // dd($request->all());
        try {
            $datas = DB::table('acd_student_vacation_prerequisite')
                ->join('mstr_vacation_document', 'mstr_vacation_document.Vacation_Document_Id', '=', 'acd_student_vacation_prerequisite.Vacation_Document_Id')
                ->where([['Department_Id', $request->Department_Id], ['Is_Vacation', 2]])
                ->orderBy('acd_student_vacation_prerequisite.Order_Id', 'asc')
                ->get();

            $data = [];
            $i = 0;
            foreach ($datas as $key) {
                $data[$i]['No'] = $i + 1;
                $data[$i]['Student_Vacation_Prerequisite_Id'] = $key->Student_Vacation_Prerequisite_Id;
                $data[$i]['Vacation_Document_Id'] = $key->Vacation_Document_Id;
                $data[$i]['Vacation_Document_Name'] = $key->Vacation_Document_Name;
                $data[$i]['Copies'] = $key->Copies;
                $i++;
            }
            return response()->json(
                [
                    "success" => true,
                    "data" => $data,
                    "total" => count($data),
                ],
                200
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    "success" => false,
                    "data" => $e
                ],
                200
            );
        }
    }

    public function getBerkasNotinKembaliController(Request $request)
    {
        // dd($request->all());
        try {
            $in = DB::table('acd_student_vacation_prerequisite')
                ->where([['Department_Id', $request->Department_Id], ['Is_Vacation', 2]])
                ->select('Vacation_Document_Id');

            $data = DB::table('mstr_vacation_document')
                ->wherenotin('Vacation_Document_Id', $in)
                ->orderBy('mstr_vacation_document.Vacation_Document_Id', 'asc')
                ->get();

            $datas = [];
            $i = 0;
            foreach ($data as $key) {
                $datas[$i]['No'] = $i + 1;
                $datas[$i]['Vacation_Document_Id'] = $key->Vacation_Document_Id;
                $datas[$i]['Vacation_Document_Name'] = $key->Vacation_Document_Name;
                $i++;
            }
            if ($request->Vacation_Document_Id != null) {
                $i_use = DB::table('mstr_vacation_document')
                    ->where('Vacation_Document_Id', $request->Vacation_Document_Id)
                    ->first();
                $datas[$i]['No'] = $i + 1;
                $datas[$i]['Vacation_Document_Id'] = $i_use->Vacation_Document_Id;
                $datas[$i]['Vacation_Document_Name'] = $i_use->Vacation_Document_Name;
            } else {
            }
            return response()->json(
                [
                    "success" => true,
                    "data" => $datas,
                    "total" => count($datas),
                ],
                200
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    "success" => false,
                    "data" => $e
                ],
                200
            );
        }
    }

    //post
    public function postMasterBerkasProdiKembaliController(Request $request)
    {
        // dd($request->all());
        try {
            if ($request->data['Student_Vacation_Prerequisite_Id'] == null) {
                $i = 0;
                foreach ($request->data['Vacation_Document_Id'] as $key => $value) {
                    $insert = DB::table('acd_student_vacation_prerequisite')
                        ->insert([
                            'Vacation_Document_Id' => $value,
                            'Department_Id' => $request->data['Department_Id'],
                            'Copies' => ($request->data['Copies'][$i] == 0 ? 1 : $request->data['Copies'][$i]),
                            'Order_Id' => $value,
                            'Is_Vacation' => 2,
                            'Created_By' => Auth::user()->email,
                            'Created_Date' => date('Y-m-d H:i:s')
                        ]);
                    $i++;
                }
                return response()->json([
                    "success" => true,
                    "data" => 'Berhasil menambah data',
                    "total" => 1,
                ], 200);
            } else {
                $insert = DB::table('acd_student_vacation_prerequisite')
                    ->where('Student_Vacation_Prerequisite_Id', $request->data['Student_Vacation_Prerequisite_Id'])
                    ->update([
                        'Vacation_Document_Id' => $request->data['Vacation_Document_Id'],
                        'Copies' => $request->data['Copies'],
                        'Is_Vacation' => 2,
                        'Modified_By' => Auth::user()->email,
                        'Modified_Date' => date('Y-m-d H:i:s')
                    ]);
                return response()->json([
                    "success" => true,
                    "data" => 'Berhasil mengubah data',
                    "total" => 1,
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json(
                [
                    "success" => false,
                    "data" => 'Gagal menambah data',
                    "message" => $e
                ],
                200
            );
        }
    }

    //delete
    public function deleteMasterBerkasProdiKembaliController(Request $request)
    {
        try {
            $insert = DB::table('acd_student_vacation_prerequisite')
                ->where('Student_Vacation_Prerequisite_Id', $request->data)
                ->delete();
            return response()->json(
                [
                    "success" => true,
                    "data" => 'Berhasil menghapus data',
                    "total" => 1,
                ],
                200
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    "success" => false,
                    "data" => 'Gagal menghapus data/ Data masih digunakan',
                    "message" => $e
                ],
                200
            );
        }
    }
    //END AKTIF KEMBALI

    //AKTIF Perpanjangan
    public function getMasterBerkasProdiPerpanjanganController(Request $request)
    {
        // dd($request->all());
        try {
            $datas = DB::table('acd_student_vacation_prerequisite')
                ->join('mstr_vacation_document', 'mstr_vacation_document.Vacation_Document_Id', '=', 'acd_student_vacation_prerequisite.Vacation_Document_Id')
                ->where([['Department_Id', $request->Department_Id], ['Is_Vacation', 3]])
                ->orderBy('acd_student_vacation_prerequisite.Order_Id', 'asc')
                ->get();

            $data = [];
            $i = 0;
            foreach ($datas as $key) {
                $data[$i]['No'] = $i + 1;
                $data[$i]['Student_Vacation_Prerequisite_Id'] = $key->Student_Vacation_Prerequisite_Id;
                $data[$i]['Vacation_Document_Id'] = $key->Vacation_Document_Id;
                $data[$i]['Vacation_Document_Name'] = $key->Vacation_Document_Name;
                $data[$i]['Copies'] = $key->Copies;
                $i++;
            }
            return response()->json(
                [
                    "success" => true,
                    "data" => $data,
                    "total" => count($data),
                ],
                200
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    "success" => false,
                    "data" => $e
                ],
                200
            );
        }
    }

    public function getBerkasNotinPerpanjanganController(Request $request)
    {
        // dd($request->all());
        try {
            $in = DB::table('acd_student_vacation_prerequisite')
                ->where([['Department_Id', $request->Department_Id], ['Is_Vacation', 3]])
                ->select('Vacation_Document_Id');

            $data = DB::table('mstr_vacation_document')
                ->wherenotin('Vacation_Document_Id', $in)
                ->orderBy('mstr_vacation_document.Vacation_Document_Id', 'asc')
                ->get();

            $datas = [];
            $i = 0;
            foreach ($data as $key) {
                $datas[$i]['No'] = $i + 1;
                $datas[$i]['Vacation_Document_Id'] = $key->Vacation_Document_Id;
                $datas[$i]['Vacation_Document_Name'] = $key->Vacation_Document_Name;
                $i++;
            }
            if ($request->Vacation_Document_Id != null) {
                $i_use = DB::table('mstr_vacation_document')
                    ->where('Vacation_Document_Id', $request->Vacation_Document_Id)
                    ->first();
                $datas[$i]['No'] = $i + 1;
                $datas[$i]['Vacation_Document_Id'] = $i_use->Vacation_Document_Id;
                $datas[$i]['Vacation_Document_Name'] = $i_use->Vacation_Document_Name;
            } else {
            }
            return response()->json(
                [
                    "success" => true,
                    "data" => $datas,
                    "total" => count($datas),
                ],
                200
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    "success" => false,
                    "data" => $e
                ],
                200
            );
        }
    }

    //post
    public function postMasterBerkasProdiPerpanjanganController(Request $request)
    {
        // dd($request->all());
        try {
            if ($request->data['Student_Vacation_Prerequisite_Id'] == null) {
                $i = 0;
                foreach ($request->data['Vacation_Document_Id'] as $key => $value) {
                    $insert = DB::table('acd_student_vacation_prerequisite')
                        ->insert([
                            'Vacation_Document_Id' => $value,
                            'Department_Id' => $request->data['Department_Id'],
                            'Copies' => ($request->data['Copies'][$i] == 0 ? 1 : $request->data['Copies'][$i]),
                            'Order_Id' => $value,
                            'Is_Vacation' => 3,
                            'Created_By' => Auth::user()->email,
                            'Created_Date' => date('Y-m-d H:i:s')
                        ]);
                    $i++;
                }
                return response()->json([
                    "success" => true,
                    "data" => 'Berhasil menambah data',
                    "total" => 1,
                ], 200);
            } else {
                $insert = DB::table('acd_student_vacation_prerequisite')
                    ->where('Student_Vacation_Prerequisite_Id', $request->data['Student_Vacation_Prerequisite_Id'])
                    ->update([
                        'Vacation_Document_Id' => $request->data['Vacation_Document_Id'],
                        'Copies' => $request->data['Copies'],
                        'Is_Vacation' => 3,
                        'Modified_By' => Auth::user()->email,
                        'Modified_Date' => date('Y-m-d H:i:s')
                    ]);
                return response()->json([
                    "success" => true,
                    "data" => 'Berhasil mengubah data',
                    "total" => 1,
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json(
                [
                    "success" => false,
                    "data" => 'Gagal menambah data',
                    "message" => $e
                ],
                200
            );
        }
    }

    //delete
    public function deleteMasterBerkasProdiPerpanjanganController(Request $request)
    {
        try {
            $insert = DB::table('acd_student_vacation_prerequisite')
                ->where('Student_Vacation_Prerequisite_Id', $request->data)
                ->delete();
            return response()->json(
                [
                    "success" => true,
                    "data" => 'Berhasil menghapus data',
                    "total" => 1,
                ],
                200
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    "success" => false,
                    "data" => 'Gagal menghapus data/ Data masih digunakan',
                    "message" => $e
                ],
                200
            );
        }
    }
    //END AKTIF Perpanjangan

    // get stundet yudisium
    public function GetStudentYudisium(Request $request)
    {
        try {
            $datas = DB::table('acd_student_vacation')
                ->join('acd_transcript', 'acd_transcript.Student_Id', '=', 'acd_student_vacation.Student_Id')
                ->join('acd_student', 'acd_student.Student_Id', '=', 'acd_student_vacation.Student_Id')
                ->leftjoin('mstr_graduate_predicate', 'mstr_graduate_predicate.Graduate_Predicate_Id', '=', 'acd_student_vacation.Graduate_Predicate_Id')
                ->where('acd_student.Department_Id', $request->Department_Id)
                ->where('acd_student_vacation.Term_Year_Id', 'like', '%' . $request->Term_Year_Id . '%')
                ->select('acd_student_vacation.*', 'acd_student.*', 'mstr_graduate_predicate.*')
                // DB::raw('round((sum(acd_transcript.Sks*acd_transcript.Weight_Value))/(SUM(acd_transcript.Sks)),2) as ipk'))
                // DB::raw('round((sum(acd_transcript.Sks*acd_transcript.Weight_Value))/(SUM(acd_transcript.Sks)),2) as ipk'))
                ->groupBy('acd_student_vacation.Student_Id')
                ->orderby('acd_student.Nim', 'asc')
                ->get();

            return response()->json([
                "success" => true,
                "data" => $datas,
                "total" => count($datas),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "data" => $e
            ], 200);
        }
    }

    public function GetPredicate(Request $request)
    {
        try {
            $datas = DB::table('mstr_graduate_predicate')->get();

            return response()->json($datas, 200);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "data" => $e
            ], 200);
        }
    }

    //berkas cuti per mahasiswa
    public function getMasterBerkasSiswaCutiController(Request $request)
    {
        $student = DB::table('acd_student_vacation')
            ->join('acd_student', 'acd_student_vacation.Student_Id', '=', 'acd_student.Student_Id')
            ->where([['Student_Vacation_Id', $request->Student_Vacation_Id]])
            ->first();
        if (isset($request->Student_Id)) {
            $student = DB::table('acd_student')
                ->where('Student_Id', $request->Student_Id)
                ->select('Student_Id', 'Department_Id')
                ->first();
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
            $year_insert = substr($request->Term_Year_Id, 0, -1);
            $term_insert = substr($request->Term_Year_Id, 4, 1);

            if ($term_insert == 2) {
                $term_year_cuti = $request->Term_Year_Id - 1;
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
        
        // try {
            $data_berkass = DB::table('acd_student_vacation_prerequisite')
                ->join('mstr_vacation_document', 'mstr_vacation_document.Vacation_Document_Id', '=', 'acd_student_vacation_prerequisite.Vacation_Document_Id')
                ->where([['Department_Id', $student->Department_Id], ['Is_Vacation', 1]])
                ->orderBy('acd_student_vacation_prerequisite.Order_Id', 'asc')
                ->get();
            if($extension == true ){
                $data_berkass = DB::table('acd_student_vacation_prerequisite')
                    ->join('mstr_vacation_document', 'mstr_vacation_document.Vacation_Document_Id', '=', 'acd_student_vacation_prerequisite.Vacation_Document_Id')
                    ->where([['Department_Id', $student->Department_Id], ['Is_Vacation',3]])
                    ->orderBy('acd_student_vacation_prerequisite.Order_Id', 'asc')
                    ->get();
            }
            if(isset($request->Student_Vacation_Id)){
                if($all_vacation[0]->Previous_Student_Vacation_Id != null){
                    $data_berkass = DB::table('acd_student_vacation_prerequisite')
                        ->join('mstr_vacation_document', 'mstr_vacation_document.Vacation_Document_Id', '=', 'acd_student_vacation_prerequisite.Vacation_Document_Id')
                        ->where([['Department_Id', $student->Department_Id], ['Is_Vacation',3]])
                        ->orderBy('acd_student_vacation_prerequisite.Order_Id', 'asc')
                        ->get();
                }
            }
            $data = [];
            $i = 0;
            foreach ($data_berkass as $data_berkas) {
                $berkass = DB::table('acd_student_vacation_document')
                    ->leftjoin('acd_student_vacation', 'acd_student_vacation_document.Student_Vacation_Id', '=', 'acd_student_vacation.Student_Vacation_Id')
                    ->join('acd_student_vacation_prerequisite', 'acd_student_vacation_document.Student_Vacation_Prerequisite_Id', '=', 'acd_student_vacation_prerequisite.Student_Vacation_Prerequisite_Id')
                    ->join('mstr_vacation_document', 'mstr_vacation_document.Vacation_Document_Id', '=', 'acd_student_vacation_prerequisite.Vacation_Document_Id')
                    ->where([
                        ['acd_student_vacation_prerequisite.Department_Id', $student->Department_Id],
                        ['acd_student_vacation.Student_Id', $student->Student_Id],
                        ['acd_student_vacation_document.Student_Vacation_Prerequisite_Id', $data_berkas->Student_Vacation_Prerequisite_Id]
                    ])
                    ->orderBy('acd_student_vacation_prerequisite.Order_Id', 'asc')
                    ->select(
                        'acd_student_vacation_prerequisite.Vacation_Document_Id',
                        'acd_student_vacation_prerequisite.Copies',
                        'mstr_vacation_document.Vacation_Document_Name',
                        'acd_student_vacation.Student_Id',
                        'acd_student_vacation_document.Student_Vacation_Document_Id',
                        'acd_student_vacation_document.Student_Vacation_Prerequisite_Id',
                        'acd_student_vacation_document.File_Upload',
                        'acd_student_vacation_document.Is_Accepted',
                        'acd_student_vacation_document.Notes',
                        'acd_student_vacation_document.Created_By',
                        'acd_student_vacation_document.Modified_By'
                    )
                    ->first();

                if ($berkass) {
                    $data[$i]['No'] = $i + 1;
                    $data[$i]['Student_Vacation_Document_Id'] = $berkass->Student_Vacation_Document_Id;
                    $data[$i]['Student_Vacation_Prerequisite_Id'] = $berkass->Student_Vacation_Prerequisite_Id;
                    $data[$i]['Student_Id'] = $berkass->Student_Id;
                    $data[$i]['File_Upload'] = $berkass->File_Upload;
                    $data[$i]['Is_Accepted'] = $berkass->Is_Accepted;
                    $data[$i]['Notes'] = $berkass->Notes;
                    $data[$i]['Created_By'] = $berkass->Created_By;
                    $data[$i]['Modified_By'] = $berkass->Modified_By;

                    $data[$i]['Vacation_Document_Id'] = $data_berkas->Vacation_Document_Id;
                    $data[$i]['Vacation_Document_Name'] = $data_berkas->Vacation_Document_Name;
                    $data[$i]['Copies'] = $data_berkas->Copies;
                } else {
                    $data[$i]['No'] = $i + 1;
                    $data[$i]['Student_Vacation_Document_Id'] = null;
                    $data[$i]['Student_Vacation_Prerequisite_Id'] = $data_berkas->Student_Vacation_Prerequisite_Id;
                    $data[$i]['Student_Id'] = $request->Student_Id;
                    $data[$i]['File_Upload'] = null;
                    $data[$i]['Is_Accepted'] = null;
                    $data[$i]['Notes'] = null;
                    $data[$i]['Created_By'] = null;
                    $data[$i]['Modified_By'] = null;

                    $data[$i]['Vacation_Document_Id'] = $data_berkas->Vacation_Document_Id;
                    $data[$i]['Vacation_Document_Name'] = $data_berkas->Vacation_Document_Name;
                    $data[$i]['Copies'] = $data_berkas->Copies;
                }
                $i++;
            }

            return response()->json([
                "success" => true,
                "data" => $data,
                "total" => count($data),
            ], 200);
        // } catch (\Exception $e) {
        //     return response()->json([
        //         "success" => false,
        //         "data" => $e
        //     ], 200);
        // }
    }
    public function postBerkasSiswaCutiController(Request $request)
    {
        if (isset($request->data['Student_Id'])) {
            $student = DB::table('acd_student')
                ->where('Student_Id', $request->data['Student_Id'])
                ->select('Student_Id', 'Department_Id')
                ->first();
        } else {
            $student = DB::table('acd_student_vacation')
                ->join('acd_student', 'acd_student_vacation.Student_Id', '=', 'acd_student.Student_Id')
                ->where('Student_Vacation_Id', $request->data['Student_Vacation_Id'])
                ->first();
        }
        try {
            foreach ($request->data['models'] as $key) {
                if ($key['Student_Vacation_Document_Id'] == null) {
                    $insert = DB::table('acd_student_vacation_document')
                        ->insert([
                            'Student_Id' => $student->Student_Id,
                            'Student_Vacation_Prerequisite_Id' => $key['Student_Vacation_Prerequisite_Id'],
                            'Is_Accepted' => ($key['Is_Accepted'] == 'true' ? true : false),
                            'Notes' => $key['Notes'],
                            'Created_By' => Auth::user()->email,
                            'Created_Date' => date('Y-m-d H:i:s')
                        ]);
                } else {
                    $have_data = DB::table('acd_student_vacation_document')
                        ->where('Student_Vacation_Document_Id', $key['Student_Vacation_Document_Id'])
                        ->first();
                    if ($have_data) {
                        if ($have_data->File_Upload == '' && $key['Is_Accepted'] == 'false' && $key['Notes'] == '') {
                            $update = DB::table('acd_student_vacation_document')
                                ->where('Student_Vacation_Document_Id', $key['Student_Vacation_Document_Id'])
                                ->delete();
                        } else {
                            $update = DB::table('acd_student_vacation_document')
                                ->where('Student_Vacation_Document_Id', $key['Student_Vacation_Document_Id'])
                                ->update([
                                    'Student_Id' => $key['Student_Id'],
                                    'Student_Vacation_Prerequisite_Id' => $key['Student_Vacation_Prerequisite_Id'],
                                    'Is_Accepted' => ($key['Is_Accepted'] == 'true' ? true : false),
                                    'Notes' => $key['Notes'],
                                    'Modified_By' => Auth::user()->email,
                                    'Modified_Date' => date('Y-m-d H:i:s')
                                ]);
                        }
                    } else {
                        $update = DB::table('acd_student_vacation_document')
                            ->where('Student_Vacation_Document_Id', $key['Student_Vacation_Document_Id'])
                            ->update([
                                'Student_Id' => $key['Student_Id'],
                                'Student_Vacation_Prerequisite_Id' => $key['Student_Vacation_Prerequisite_Id'],
                                'Is_Accepted' => ($key['Is_Accepted'] == 'true' ? true : false),
                                'Notes' => $key['Notes'],
                                'Modified_By' => Auth::user()->email,
                                'Modified_Date' => date('Y-m-d H:i:s')
                            ]);
                    }
                }
            }
            return response()->json([
                "success" => true,
                "data" => 'Berhasil mengubah data',
                "total" => 1,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "data" => $e
            ], 200);
        }
    }

    //berkas cuti per mahasiswa
    public function getMasterBerkasSiswaKembaliController(Request $request)
    {
        $student = DB::table('acd_student_vacation')
            ->join('acd_student', 'acd_student_vacation.Student_Id', '=', 'acd_student.Student_Id')
            ->where([['Student_Vacation_Id', $request->Student_Vacation_Id]])
            ->first();
        if (isset($request->Student_Id)) {
            $student = DB::table('acd_student')
                ->where('Student_Id', $request->Student_Id)
                ->select('Student_Id', 'Department_Id')
                ->first();
        }

        try {
            $data_berkass = DB::table('acd_student_vacation_prerequisite')
                ->join('mstr_vacation_document', 'mstr_vacation_document.Vacation_Document_Id', '=', 'acd_student_vacation_prerequisite.Vacation_Document_Id')
                ->where([['Department_Id', $student->Department_Id], ['Is_Vacation', 2]])
                ->orderBy('acd_student_vacation_prerequisite.Order_Id', 'asc')
                ->get();

            $data = [];
            $i = 0;
            foreach ($data_berkass as $data_berkas) {
                $berkass = DB::table('acd_student_vacation_document')
                    ->join('acd_student_vacation_prerequisite', 'acd_student_vacation_document.Student_Vacation_Prerequisite_Id', '=', 'acd_student_vacation_prerequisite.Student_Vacation_Prerequisite_Id')
                    ->join('mstr_vacation_document', 'mstr_vacation_document.Vacation_Document_Id', '=', 'acd_student_vacation_prerequisite.Vacation_Document_Id')
                    ->where([
                        ['acd_student_vacation_prerequisite.Department_Id', $student->Department_Id],
                        ['acd_student_vacation_document.Student_Id', $student->Student_Id],
                        ['acd_student_vacation_document.Student_Vacation_Prerequisite_Id', $data_berkas->Student_Vacation_Prerequisite_Id]
                    ])
                    ->orderBy('acd_student_vacation_prerequisite.Order_Id', 'asc')
                    ->select(
                        'acd_student_vacation_prerequisite.Vacation_Document_Id',
                        'acd_student_vacation_prerequisite.Copies',
                        'mstr_vacation_document.Vacation_Document_Name',
                        'acd_student_vacation_document.Student_Id',
                        'acd_student_vacation_document.Student_Vacation_Document_Id',
                        'acd_student_vacation_document.Student_Vacation_Prerequisite_Id',
                        'acd_student_vacation_document.File_Upload',
                        'acd_student_vacation_document.Is_Accepted',
                        'acd_student_vacation_document.Notes',
                        'acd_student_vacation_document.Created_By'
                    )
                    ->first();

                if ($berkass) {
                    $data[$i]['No'] = $i + 1;
                    $data[$i]['Student_Vacation_Document_Id'] = $berkass->Student_Vacation_Document_Id;
                    $data[$i]['Student_Vacation_Prerequisite_Id'] = $berkass->Student_Vacation_Prerequisite_Id;
                    $data[$i]['Student_Id'] = $berkass->Student_Id;
                    $data[$i]['File_Upload'] = $berkass->File_Upload;
                    $data[$i]['Is_Accepted'] = $berkass->Is_Accepted;
                    $data[$i]['Notes'] = $berkass->Notes;
                    $data[$i]['Created_By'] = $berkass->Created_By;

                    $data[$i]['Vacation_Document_Id'] = $data_berkas->Vacation_Document_Id;
                    $data[$i]['Vacation_Document_Name'] = $data_berkas->Vacation_Document_Name;
                    $data[$i]['Copies'] = $data_berkas->Copies;
                } else {
                    $data[$i]['No'] = $i + 1;
                    $data[$i]['Student_Vacation_Document_Id'] = null;
                    $data[$i]['Student_Vacation_Prerequisite_Id'] = $data_berkas->Student_Vacation_Prerequisite_Id;
                    $data[$i]['Student_Id'] = $request->Student_Id;
                    $data[$i]['File_Upload'] = null;
                    $data[$i]['Is_Accepted'] = null;
                    $data[$i]['Notes'] = null;
                    $data[$i]['Created_By'] = null;

                    $data[$i]['Vacation_Document_Id'] = $data_berkas->Vacation_Document_Id;
                    $data[$i]['Vacation_Document_Name'] = $data_berkas->Vacation_Document_Name;
                    $data[$i]['Copies'] = $data_berkas->Copies;
                }
                $i++;
            }

            return response()->json(
                [
                    "success" => true,
                    "data" => $data,
                    "total" => count($data),
                ],
                200
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    "success" => false,
                    "data" => $e
                ],
                200
            );
        }
    }
    public function postBerkasSiswaKembaliController(Request $request)
    {
        if (isset($request->data['Student_Id'])) {
            $student = DB::table('acd_student')
                ->where('Student_Id', $request->data['Student_Id'])
                ->select('Student_Id', 'Department_Id')
                ->first();
        } else {
            $student = DB::table('acd_student_vacation')
                ->join('acd_student', 'acd_student_vacation.Student_Id', '=', 'acd_student.Student_Id')
                ->where('Student_Vacation_Id', $request->data['Student_Vacation_Id'])
                ->first();
        }
        try {
            foreach ($request->data['models'] as $key) {
                if ($key['Student_Vacation_Document_Id'] == null) {
                    $insert = DB::table('acd_student_vacation_document')
                        ->insert([
                            'Student_Id' => $student->Student_Id,
                            'Student_Vacation_Prerequisite_Id' => $key['Student_Vacation_Prerequisite_Id'],
                            'Is_Accepted' => ($key['Is_Accepted'] == 'true' ? true : false),
                            'Notes' => $key['Notes'],
                            'Created_By' => Auth::user()->email,
                            'Created_Date' => date('Y-m-d H:i:s')
                        ]);
                } else {
                    $have_data = DB::table('acd_student_vacation_document')
                        ->where('Student_Vacation_Document_Id', $key['Student_Vacation_Document_Id'])
                        ->first();
                    if ($have_data) {
                        if ($have_data->File_Upload == '' && $key['Is_Accepted'] == 'false' && $key['Notes'] == '') {
                            $update = DB::table('acd_student_vacation_document')
                                ->where('Student_Vacation_Document_Id', $key['Student_Vacation_Document_Id'])
                                ->delete();
                        } else {
                            $update = DB::table('acd_student_vacation_document')
                                ->where('Student_Vacation_Document_Id', $key['Student_Vacation_Document_Id'])
                                ->update([
                                    'Student_Id' => $key['Student_Id'],
                                    'Student_Vacation_Prerequisite_Id' => $key['Student_Vacation_Prerequisite_Id'],
                                    'Is_Accepted' => ($key['Is_Accepted'] == 'true' ? true : false),
                                    'Notes' => $key['Notes'],
                                    'Modified_By' => Auth::user()->email,
                                    'Modified_Date' => date('Y-m-d H:i:s')
                                ]);
                        }
                    } else {
                        $update = DB::table('acd_student_vacation_document')
                            ->where('Student_Vacation_Document_Id', $key['Student_Vacation_Document_Id'])
                            ->update([
                                'Student_Id' => $key['Student_Id'],
                                'Student_Vacation_Prerequisite_Id' => $key['Student_Vacation_Prerequisite_Id'],
                                'Is_Accepted' => ($key['Is_Accepted'] == 'true' ? true : false),
                                'Notes' => $key['Notes'],
                                'Modified_By' => Auth::user()->email,
                                'Modified_Date' => date('Y-m-d H:i:s')
                            ]);
                    }
                }
            }
            return response()->json(
                [
                    "success" => true,
                    "data" => 'Berhasil mengubah data',
                    "total" => 1,
                ],
                200
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    "success" => false,
                    "data" => $e
                ],
                200
            );
        }
    }

    //perpanjangan

    //berkas cuti per mahasiswa
    public function getMasterBerkasSiswaPerpanjanganController(Request $request)
    {
        $student = DB::table('acd_student_vacation')
        ->join('acd_student', 'acd_student_vacation.Student_Id', '=', 'acd_student.Student_Id')
        ->where([['Student_Vacation_Id', $request->Student_Vacation_Id]])
        ->first();
        if (isset($request->Student_Id)) {
            $student = DB::table('acd_student')
                ->where('Student_Id', $request->Student_Id)
                ->select('Student_Id', 'Department_Id')
                ->first();
        }

        try {
            $data_berkass = DB::table('acd_student_vacation_prerequisite')
            ->join('mstr_vacation_document', 'mstr_vacation_document.Vacation_Document_Id', '=', 'acd_student_vacation_prerequisite.Vacation_Document_Id')
            ->where([['Department_Id', $student->Department_Id], ['Is_Vacation', 3]])
            ->orderBy('acd_student_vacation_prerequisite.Order_Id', 'asc')
            ->get();

            $data = [];
            $i = 0;
            foreach ($data_berkass as $data_berkas) {
                $berkass = DB::table('acd_student_vacation_document')
                ->join('acd_student_vacation_prerequisite', 'acd_student_vacation_document.Student_Vacation_Prerequisite_Id', '=', 'acd_student_vacation_prerequisite.Student_Vacation_Prerequisite_Id')
                ->join('mstr_vacation_document', 'mstr_vacation_document.Vacation_Document_Id', '=', 'acd_student_vacation_prerequisite.Vacation_Document_Id')
                ->where([
                    ['acd_student_vacation_prerequisite.Department_Id', $student->Department_Id],
                    ['acd_student_vacation_document.Student_Id', $student->Student_Id],
                    ['acd_student_vacation_document.Student_Vacation_Prerequisite_Id', $data_berkas->Student_Vacation_Prerequisite_Id]
                ])
                    ->orderBy('acd_student_vacation_prerequisite.Order_Id', 'asc')
                    ->select(
                        'acd_student_vacation_prerequisite.Vacation_Document_Id',
                        'acd_student_vacation_prerequisite.Copies',
                        'mstr_vacation_document.Vacation_Document_Name',
                        'acd_student_vacation_document.Student_Id',
                        'acd_student_vacation_document.Student_Vacation_Document_Id',
                        'acd_student_vacation_document.Student_Vacation_Prerequisite_Id',
                        'acd_student_vacation_document.File_Upload',
                        'acd_student_vacation_document.Is_Accepted',
                        'acd_student_vacation_document.Notes',
                        'acd_student_vacation_document.Created_By'
                    )
                    ->first();

                if ($berkass) {
                    $data[$i]['No'] = $i + 1;
                    $data[$i]['Student_Vacation_Document_Id'] = $berkass->Student_Vacation_Document_Id;
                    $data[$i]['Student_Vacation_Prerequisite_Id'] = $berkass->Student_Vacation_Prerequisite_Id;
                    $data[$i]['Student_Id'] = $berkass->Student_Id;
                    $data[$i]['File_Upload'] = $berkass->File_Upload;
                    $data[$i]['Is_Accepted'] = $berkass->Is_Accepted;
                    $data[$i]['Notes'] = $berkass->Notes;
                    $data[$i]['Created_By'] = $berkass->Created_By;

                    $data[$i]['Vacation_Document_Id'] = $data_berkas->Vacation_Document_Id;
                    $data[$i]['Vacation_Document_Name'] = $data_berkas->Vacation_Document_Name;
                    $data[$i]['Copies'] = $data_berkas->Copies;
                } else {
                    $data[$i]['No'] = $i + 1;
                    $data[$i]['Student_Vacation_Document_Id'] = null;
                    $data[$i]['Student_Vacation_Prerequisite_Id'] = $data_berkas->Student_Vacation_Prerequisite_Id;
                    $data[$i]['Student_Id'] = $request->Student_Id;
                    $data[$i]['File_Upload'] = null;
                    $data[$i]['Is_Accepted'] = null;
                    $data[$i]['Notes'] = null;
                    $data[$i]['Created_By'] = null;

                    $data[$i]['Vacation_Document_Id'] = $data_berkas->Vacation_Document_Id;
                    $data[$i]['Vacation_Document_Name'] = $data_berkas->Vacation_Document_Name;
                    $data[$i]['Copies'] = $data_berkas->Copies;
                }
                $i++;
            }

            return response()->json(
                [
                    "success" => true,
                    "data" => $data,
                    "total" => count($data),
                ],
                200
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    "success" => false,
                    "data" => $e
                ],
                200
            );
        }
    }
    public function postBerkasSiswaPerpanjanganController(Request $request)
    {
        if (isset($request->data['Student_Id'])) {
            $student = DB::table('acd_student')
                ->where('Student_Id', $request->data['Student_Id'])
                ->select('Student_Id', 'Department_Id')
                ->first();
        } else {
            $student = DB::table('acd_student_vacation')
            ->join('acd_student', 'acd_student_vacation.Student_Id', '=', 'acd_student.Student_Id')
            ->where('Student_Vacation_Id', $request->data['Student_Vacation_Id'])
            ->first();
        }
        try {
            foreach ($request->data['models'] as $key) {
                if ($key['Student_Vacation_Document_Id'] == null) {
                    $insert = DB::table('acd_student_vacation_document')
                    ->insert([
                        'Student_Id' => $student->Student_Id,
                        'Student_Vacation_Prerequisite_Id' => $key['Student_Vacation_Prerequisite_Id'],
                        'Is_Accepted' => ($key['Is_Accepted'] == 'true' ? true : false),
                        'Notes' => $key['Notes'],
                        'Created_By' => Auth::user()->email,
                        'Created_Date' => date('Y-m-d H:i:s')
                    ]);
                } else {
                    $have_data = DB::table('acd_student_vacation_document')
                    ->where('Student_Vacation_Document_Id', $key['Student_Vacation_Document_Id'])
                    ->first();
                    if ($have_data) {
                        if ($have_data->File_Upload == '' && $key['Is_Accepted'] == 'false' && $key['Notes'] == '') {
                            $update = DB::table('acd_student_vacation_document')
                            ->where('Student_Vacation_Document_Id', $key['Student_Vacation_Document_Id'])
                            ->delete();
                        } else {
                            $update = DB::table('acd_student_vacation_document')
                            ->where('Student_Vacation_Document_Id', $key['Student_Vacation_Document_Id'])
                            ->update([
                                'Student_Id' => $key['Student_Id'],
                                'Student_Vacation_Prerequisite_Id' => $key['Student_Vacation_Prerequisite_Id'],
                                'Is_Accepted' => ($key['Is_Accepted'] == 'true' ? true : false),
                                'Notes' => $key['Notes'],
                                'Modified_By' => Auth::user()->email,
                                'Modified_Date' => date('Y-m-d H:i:s')
                            ]);
                        }
                    } else {
                        $update = DB::table('acd_student_vacation_document')
                        ->where('Student_Vacation_Document_Id', $key['Student_Vacation_Document_Id'])
                        ->update([
                            'Student_Id' => $key['Student_Id'],
                            'Student_Vacation_Prerequisite_Id' => $key['Student_Vacation_Prerequisite_Id'],
                            'Is_Accepted' => ($key['Is_Accepted'] == 'true' ? true : false),
                            'Notes' => $key['Notes'],
                            'Modified_By' => Auth::user()->email,
                            'Modified_Date' => date('Y-m-d H:i:s')
                        ]);
                    }
                }
            }
            return response()->json(
                [
                    "success" => true,
                    "data" => 'Berhasil mengubah data',
                    "total" => 1,
                ],
                200
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    "success" => false,
                    "data" => $e
                ],
                200
            );
        }
    }

    //delete yudisium siswa
    public function deleteSiswaCutiController(Request $request)
    {
        try {
            // dd($request->all());
            $insert = DB::table('acd_student_vacation')
                ->where('Yudisium_Id', $request->data)
                ->delete();
            return response()->json([
                "success" => true,
                "data" => 'Berhasil menghapus data',
                "total" => 1,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "data" => 'Gagal menghapus data/ Data masih digunakan',
                "message" => $e
            ], 200);
        }
    }

    public function postDataSiswaCutiController(Request $request)
    {
        // dd($request->all());
        try {
            $err['nim'] = '';
            $s = 0;
            foreach ($request->data['models'] as $key) {
                $duplicate = DB::table('acd_student_vacation')
                    ->where([['National_Certificate_Number', $key['National_Certificate_Number']], ['National_Certificate_Number', '!=', null], ['Student_Id', '!=', $key['Student_Id']]])
                    ->orwhere([['Transcript_Num', $key['Transcript_Num']], ['Transcript_Num', '!=', null], ['Student_Id', '!=', $key['Student_Id']]])
                    ->orwhere([['Skpi_Number', $key['Skpi_Number']], ['Skpi_Number', '!=', null], ['Student_Id', '!=', $key['Student_Id']]])
                    ->first();
                if ($duplicate) {
                    $student = DB::table('acd_student')->where('Student_Id', $key['Student_Id'])->first();
                    $err['nim'] = $err['nim'] . '; ' . $student->Nim;
                    continue;
                } else {
                    $insert = DB::table('acd_student_vacation')
                        ->where('Yudisium_Id', $key['Yudisium_Id'])
                        ->update([
                            'Term_Year_Id' => $request->data['Term_Year_Id'],
                            'Graduate_Predicate_Id' => $key['Graduate_Predicate_Id'],
                            'National_Certificate_Number' => $key['National_Certificate_Number'],
                            'Transcript_Num' => $key['Transcript_Num'],
                            'Skpi_Number' => $key['Skpi_Number'],
                            'Yudisium_Date' => $key['Yudisium_Date'],
                            'Graduate_Date' => $key['Graduate_Date'],
                            'Graduate_Predicate_Id' => $key['Graduate_Predicate_Id'],
                            'Transcript_Date' => $key['Transcript_Date'],
                            'Modified_By' => Auth::user()->email,
                            'Modified_Date' => date('Y-m-d H:i:s')
                        ]);
                }
                $s++;
            }
            if ($err['nim'] != '') {
                return response()->json([
                    "success" => true,
                    "warning" => true,
                    "data" => 'Sebagian data berhasil dirubah, Data Duplikat NIM ' . $err['nim'] . ' berhasil di kembalikan',
                    "total" => 1,
                ], 200);
            } else {
                return response()->json([
                    "success" => true,
                    "warning" => false,
                    "data" => 'Berhasil mengubah data',
                    "total" => 1,
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "data" => $e
            ], 200);
        }
    }

    public function uploadMasterBerkasProdiCutiController(Request $request, $id)
    {

        $data = DB::table('acd_student_vacation_prerequisite')
            ->where('Student_Vacation_Prerequisite_Id', $id)
            ->first();

        if ($data) {
            if ($data->Document_File != null) {
                if (Storage::exists('public/berkas_cuti/' . $id . '/' . $data->Document_File)) {
                    Storage::delete('public/berkas_cuti/' . $id . '/' . $data->Document_File);
                }
            }
        }
        // Document_File
        $file = $request->file('File_Url');

        $fileUrl = $id . '-' . date('dmy-') . uniqid(rand()) . '.' . $file->getClientOriginalExtension();
        $fileName = $file->getClientOriginalName();

        $file->storeAs('public/berkas_cuti/' . $id . '/', $fileUrl);

        echo json_encode(['File_Url' => $fileUrl, 'File_Name' => $fileName]);
    }

    public function getAllowedCuti(Request $request){
        try {
            $all_vacation = DB::table('acd_student_vacation')
                ->where([['Student_Id', $request->Student_Id],['Is_Approved',1]])
                ->orderby('Term_Year_Id','desc')
                ->get();
            
            if(count($all_vacation) >= 2){
                return response()->json([
                    "success" => false,
                    "data" => 'Maaf, Anda sudah cuti sebanyak 2x.'
                ], 400);
            }

            if(count($all_vacation) > 0){
                $cuti_first = $all_vacation[0];
                //semester yang sudah ada
                $year_first = substr($cuti_first->Term_Year_Id,0,-1);
                $term_first = substr($cuti_first->Term_Year_Id,4,1);
                //semester yang akan didaftarkan
                $year_insert = substr($request->Term_Year_Id,0,-1);
                $term_insert = substr($request->Term_Year_Id,4,1);

                if($term_insert == 2){
                    $term_year_cuti = $request->Term_Year_Id - 1;
                }else{
                    $term_year_cuti = ($year_insert-1). 2;
                }

                if($cuti_first->Term_Year_Id == $term_year_cuti){
                    $message = 'Mahasiswa Perpanjangan Cuti';
                    $extension = true;
                }else{
                    $message = 'sebelumnya tidak ada cuti';
                    $extension = false;
                }

                return response()->json([
                    "success" => true,
                    "data" => $message,
                    "extra" => $extension,
                    "total" => 1,
                ], 200);
            }else{
                return response()->json([
                    "success" => true,
                    "data" => 'ok',
                    "extra" => false,
                    "total" => 1,
                ], 200);
            }

        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "data" => 'Gagal menghapus data/ Data masih digunakan',
                "message" => $e
            ], 200);
        }
    }
}
