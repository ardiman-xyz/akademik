<?php

namespace App\Http\Controllers\Feeder;

use App\Exports\AjarDosenExport;
use App\Exports\AktivitasMahasiswaExport;
use App\Exports\AnggotaAktivitasMahasiswaExport;
use App\Exports\BimbingMahasiswaExport;
use App\Exports\BobotNilaiExport;
use App\Exports\DosenPembimbingExport;
use App\Exports\KelasKuliahExport;
use App\Exports\KuliahMahasiswaExport;
use App\Exports\LogErrorExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\FeederConf;
use App\MstrFaculty;
use Maatwebsite3\Excel\Facades\Excel;
use App\Exports\MahasiswaExport;
use App\Exports\MahasiswaLulusDoExport;
use App\Exports\MataKuliahExport;
use App\Exports\NilaiExport;
use App\Exports\NilaiTransferExport;
use App\Exports\PrestasiExport;
use App\FeederLogError;
use App\FeederUpload;
use App\FeederUploadUpdate;
use App\Imports\ToArrayImport;
use App\Model\MstrDepartment;
use DateTime;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use DB;

class SyncController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // if($request->all() != null){
        //     dd($request->all());
        // }
        $term_year = $request->default_term_year;
        $prodi = $request->prodi;
        $type = $request->type;
        $tipeData = $request->tipeData;
        $facultyDepartment = MstrFaculty::with('mstrDepartments')->get();
        $config = FeederConf::first();
        $program_studi = "";

        $data_detail_new = [];
        $data_detail_update = [];
        $data_detail_log = [];

        if($prodi!=null){
            foreach ($prodi as $value) {
                $pd = MstrDepartment::where('Department_Id',$value)->first();
                $program_studi = $program_studi.$pd->Department_Name.";   ";
            }

            $format = ['mahasiswa','mata_kuliah','kelas_kuliah','ajar_dosen','bobot_nilai','nilai','kuliah_mahasiswa','nilai_transfer','mahasiswa_lulus_do','dosen_pembimbing','bimbing_mahasiswa','aktivitas_mahasiswa','anggota_aktivitas_mahasiswa','prestasi'];

            foreach ($format as $v) {
                $dataDetailNeww = null;
                $dataDetailUpdatee = null;
                $dataDetailLogg = null;
                //
                $dataDetailNew = FeederUpload::where('Format',$v);
                foreach ($prodi as  $value) {
                    $dataDetailNew =  $dataDetailNew->whereHas('mstrDepartments', function($q) use($value){
                        $q->where('feeder_upload_mstr_department.Department_Id', $value);
                    });
                }
                $dataDetailNew = $dataDetailNew->OrderBy('Id_File_Feeder','ASC')->get();



                foreach($dataDetailNew as $it){
                    $j = true;
                    foreach ($it->mstrDepartments as $dp) {
                        if(!in_array($dp->Department_Id, $prodi)){
                            $j = false;
                        }
                    }

                    if($j == true){
                        $dataDetailNeww = $it;
                    }
                }


                $dataDetailUpdate = FeederUploadUpdate::where('Format',$v);

                foreach ($prodi as  $value) {
                    $dataDetailUpdate =  $dataDetailUpdate->whereHas('mstrDepartments', function($q) use($value){
                        $q->where('feeder_upload_update_mstr_department.Department_Id', $value);
                    });
                }
                $dataDetailUpdate = $dataDetailUpdate->OrderBy('Id_File_Feeder_Update','ASC')->get();



                foreach($dataDetailUpdate as $it){
                    $j = true;
                    foreach ($it->mstrDepartments as $dp) {
                        if(!in_array($dp->Department_Id, $prodi)){
                            $j = false;
                        }
                    }

                    if($j == true){
                        $dataDetailUpdatee = $it;
                    }
                }
                ////

                //LOG ERROR
                $dataDetailLog = FeederLogError::where('Format',$v);

                foreach ($prodi as  $value) {
                    $dataDetailLog =  $dataDetailLog->whereHas('mstrDepartments', function($q) use($value){
                        $q->where('feeder_log_error_mstr_department.Department_Id', $value);
                    });
                }
                $dataDetailLog = $dataDetailLog->OrderBy('Id_Log_Error_Feeder','ASC')->get();



                foreach($dataDetailLog as $it){
                    $j = true;
                    foreach ($it->mstrDepartments as $dp) {
                        if(!in_array($dp->Department_Id, $prodi)){
                            $j = false;
                        }
                    }

                    if($j == true){
                        $dataDetailLogg = $it;
                    }
                }
                //
                array_push($data_detail_new,$dataDetailNeww);
                array_push($data_detail_update,$dataDetailUpdatee);
                array_push($data_detail_log,$dataDetailLogg);

            }
        }



        return view('feeder.sync.index')->with(['program_studi' => $program_studi, 'conf' => $config,'facultyDepartment' => $facultyDepartment, 'dataDetailNew' => $data_detail_new,'dataDetailUpdate' => $data_detail_update ,'dataDetailLog' => $data_detail_log , 'prodi' => $prodi ,'term_year' => $term_year, 'type' => $type, 'tipeData' => $tipeData]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $prodi = json_decode($request->prodi);
        $format = $request->format;
        $default_term_year = $request->default_term_year;
        $dp = [];
        foreach ($prodi as $val) {
            $pdd = MstrDepartment::where('Department_Id',$val)->first();
            array_push($dp, $pdd->Department_Name);
        }

        $pd = implode("-",$dp);
        $now = date("Y-m-d-h-i-s");
        $filename = $format.'-export-'.$pd.'-'.$now.'.csv';

        switch ($format) {
            case 'mahasiswa':
                $store = Excel::store(new MahasiswaExport($prodi, $default_term_year), $filename, 'public');
                break;
            case 'mata_kuliah':
                $store = Excel::store(new MataKuliahExport($prodi, $default_term_year), $filename, 'public');
                break;
            case 'kelas_kuliah':
                $store = Excel::store(new KelasKuliahExport($prodi, $default_term_year), $filename, 'public');
                break;
            case 'ajar_dosen':
                $store = Excel::store(new AjarDosenExport($prodi, $default_term_year), $filename, 'public');
                break;
            case 'bobot_nilai':
                $store = Excel::store(new BobotNilaiExport($prodi, $default_term_year), $filename, 'public');
                break;
            case 'nilai':
                $store = Excel::store(new NilaiExport($prodi, $default_term_year), $filename, 'public');
                break;
            case 'kuliah_mahasiswa':
                $store = Excel::store(new KuliahMahasiswaExport($prodi, $default_term_year), $filename, 'public');
                break;
            case 'nilai_transfer':
                $store = Excel::store(new NilaiTransferExport($prodi, $default_term_year), $filename, 'public');
                break;
            case 'mahasiswa_lulus_do':
                $store = Excel::store(new MahasiswaLulusDoExport($prodi, $default_term_year), $filename, 'public');
                break;
            case 'dosen_pembimbing':
                $store = Excel::store(new DosenPembimbingExport($prodi, $default_term_year), $filename, 'public');
                break;
            case 'bimbing_mahasiswa':
                $store = Excel::store(new BimbingMahasiswaExport($prodi, $default_term_year), $filename, 'public');
                break;
            case 'aktivitas_mahasiswa':
                $store = Excel::store(new AktivitasMahasiswaExport($prodi, $default_term_year), $filename, 'public');
                break;
            case 'anggota_aktivitas_mahasiswa':
                $store = Excel::store(new AnggotaAktivitasMahasiswaExport($prodi, $default_term_year), $filename, 'public');
                break;
            case 'prestasi':
                $store = Excel::store(new PrestasiExport($prodi, $default_term_year), $filename, 'public');
                break;

            default:
                $store = false;
                break;
        }

        if($store){
            $f = FeederUpload::where('File_Name',$filename)->first();
            if($f == null){
                $feeder_upload = new FeederUpload();
                $feeder_upload->File_Name = $filename;
                $feeder_upload->Format = $format;
                $feeder_upload->save();

                $fu = FeederUpload::where('File_Name',$filename)->first();
                foreach ($prodi as $val) {
                    $fu->mstrDepartments()->attach($val);
                }
            }
        }



        return response()->json(['no' => $request->no, 'prodi' => $prodi, 'default_term_year' => $default_term_year,'format' => $format ,'file_name' => $filename]);
    }

    public function newUpdate(Request $request)
    {
        // dd($request->all());

        $prodi = explode(",",$request->prodi);
        $format = $request->format;
        $file = $request->file('csv');
        $dp = [];
        dump($format);

        foreach ($prodi as $val) {
            $pdd = MstrDepartment::where('Department_Id',$val)->first();
            array_push($dp, $pdd->Department_Name);
        }

        $pd = implode("-",$dp);
        $now = date("Y-m-d-h-i-s");
        $filename = $format.'-export-update-'.$pd.'-'.$now.'.csv';
        $store = Storage::putFileAs(
            'public', $file, $filename
        );

        if($store){
            $f = FeederUploadUpdate::where('File_Name',$filename)->first();
            if($f == null){
                $feeder_upload = new FeederUploadUpdate();
                $feeder_upload->File_Name = $filename;
                $feeder_upload->Format = $format;
                $feeder_upload->save();

                $fu = FeederUploadUpdate::where('File_Name',$filename)->first();
                foreach ($prodi as $val) {
                    $fu->mstrDepartments()->attach($val);
                }
            }
        }

        return response()->json(['no' => $request->no, 'prodi' => $prodi,'format' => $format ,'file_name' => $filename]);
    }

    public function syncronize(Request $request)
    {
        $prodi = json_decode($request->prodi);
        $format = $request->format;
        $data = [];

        foreach ($format as $fmt) {
            $dataDetailNeww = [];
            $dataDetailUpdatee = [];

            $dataDetailNew = FeederUpload::where('Format',$fmt);
            foreach ($prodi as  $pd) {
                $dataDetailNew =  $dataDetailNew->whereHas('mstrDepartments', function($q) use($pd){
                    $q->where('feeder_upload_mstr_department.Department_Id', $pd);
                });
            }
            $dataDetailNew = $dataDetailNew->OrderBy('Id_File_Feeder','ASC')->get();



            foreach($dataDetailNew as $it){
                $j = true;
                foreach ($it->mstrDepartments as $dp) {
                    if(!in_array($dp->Department_Id, $prodi)){
                        $j = false;
                    }
                }

                if($j == true){
                    $dataDetailNeww = $it;
                }
            }

            ////////
            $dataDetailUpdate = FeederUploadUpdate::where('Format',$fmt);

            foreach ($prodi as  $value) {
                $dataDetailUpdate =  $dataDetailUpdate->whereHas('mstrDepartments', function($q) use($value){
                    $q->where('feeder_upload_update_mstr_department.Department_Id', $value);
                });
            }
            $dataDetailUpdate = $dataDetailUpdate->OrderBy('Id_File_Feeder_Update','ASC')->get();



            foreach($dataDetailUpdate as $it){
                $j = true;
                foreach ($it->mstrDepartments as $dp) {
                    if(!in_array($dp->Department_Id, $prodi)){
                        $j = false;
                    }
                }

                if($j == true){
                    $dataDetailUpdatee = $it;
                }
            }
            ////////
            // dd($dataDetailUpdate);
            $dataDetail = $dataDetailNeww;
            if($dataDetailUpdatee != null){$dataDetail = $dataDetailUpdatee;}

            $filename = $dataDetail->File_Name;
            // dd($dataDetail->File_Name);
            $csv = [];
            $csv = Excel::toArray(new ToArrayImport(), public_path().'/storage/'.$filename);

            $data[] = (object) ['csv' => $csv, 'format' => $fmt];
        }
        return response()->json(['data' => $data]);
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
    public function destroyUpload(Request $request)
    {
        $prodi = json_decode($request->prodi);
        $format = $request->format;

        $dataDetailUpdatee = [];
        $dataDetailUpdate = FeederUpload::where('Format',$format);

        foreach ($prodi as  $pd2) {
            $dataDetailUpdate =  $dataDetailUpdate->whereHas('mstrDepartments', function($q) use($pd2){
                $q->where('feeder_upload_mstr_department.Department_Id', $pd2);
            });
        }
        $dataDetailUpdate = $dataDetailUpdate->OrderBy('Id_File_Feeder','ASC')->get();



        foreach($dataDetailUpdate as $it){
            $j = true;
            foreach ($it->mstrDepartments as $dp) {
                if(!in_array($dp->Department_Id, $prodi)){
                    $j = false;
                }
            }

            if($j == true){
                // dump($it);
                array_push($dataDetailUpdatee,$it->Id_File_Feeder);
            }
        }
        // dd($dataDetailUpdatee);
        $data = FeederUpload::whereIn('Id_File_Feeder', $dataDetailUpdatee)->delete();
        if($data){
            $status = "Berhasil";
        }else{
            $status = "Gagal";
        }
        return response()->json(['status' => $status,'prodi' => $prodi, 'format' => $format]);
    }
    public function destroyUploadUpdate(Request $request)
    {
        $prodi = json_decode($request->prodi);
        $format = $request->format;

        $dataDetailUpdatee = [];
        $dataDetailUpdate = FeederUploadUpdate::where('Format',$format);

        foreach ($prodi as  $pd2) {
            $dataDetailUpdate =  $dataDetailUpdate->whereHas('mstrDepartments', function($q) use($pd2){
                $q->where('feeder_upload_update_mstr_department.Department_Id', $pd2);
            });
        }
        $dataDetailUpdate = $dataDetailUpdate->OrderBy('Id_File_Feeder_Update','ASC')->get();



        foreach($dataDetailUpdate as $it){
            $j = true;
            foreach ($it->mstrDepartments as $dp) {
                if(!in_array($dp->Department_Id, $prodi)){
                    $j = false;
                }
            }

            if($j == true){
                // dump($it);
                array_push($dataDetailUpdatee,$it->Id_File_Feeder_Update);
            }
        }
        // dd($dataDetailUpdatee);
        $data = FeederUploadUpdate::whereIn('Id_File_Feeder_Update', $dataDetailUpdatee)->delete();
        if($data){
            $status = "Berhasil";
        }else{
            $status = "Gagal";
        }
        return response()->json(['status' => $status,'prodi' => $prodi, 'format' => $format]);
    }
    public function logError(Request $request)
    {
        $data = $request->data;
        // dd($data);
        $prodi = json_decode($request->prodi);
        // try {
            foreach ($data as $value) {
                $pd = implode("-",$prodi);
                $now = date("Y-m-d-h-i-s");
                $filename = $value['format'].'-log_error-'.$pd.'-'.$now.'.csv';
                $store = Excel::store(new LogErrorExport($value['data']), $filename, 'public');
                if($store){
                    $f = FeederLogError::where('File_Name',$filename)->first();
                    if($f == null){
                        $feeder_log = new FeederLogError();
                        $feeder_log->File_Name = $filename;
                        $feeder_log->Format = $value['format'];
                        $feeder_log->save();
        
                        $fu = FeederLogError::where('File_Name',$filename)->first();
                        foreach ($prodi as $val) {
                            $fu->mstrDepartments()->attach($val);
                        }
                    }
                }
            }
            return response()->json(['status' => "Berhasil Membuat Log"]);
        // } catch (\Throwable $th) {
        //     return response()->json(['status' => "Gagal_Membuat Log"]);
        // }
    }
}
