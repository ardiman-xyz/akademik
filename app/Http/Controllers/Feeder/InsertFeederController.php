<?php

namespace App\Http\Controllers\Feeder;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class InsertFeederController extends Controller
{
    protected $token = "";
    public function __construct()
    {
        // getToken
        $param = json_encode(
            array(
                'act' => 'GetToken',
                'username' => '052006',
                'password' => 'bgyhn15',
            )
        );
        $body = array('http' =>
            array(
                'method' => 'POST',
                'header' => 'Content-type:  application/json',
                'content' => $param,
            )
        );
        $context = stream_context_create($body);
        $link = file_get_contents('http://localhost:8082/ws/sandbox2.php',false,$context);
        $getToken = json_decode($link);

        $this->token = "";
        if($getToken->error_code == 0){
            $this->token = $getToken->data->token;
        }
        ////////
    }
    public function InsertMahasiswa(Request $request)
    {
        $wsdl = DB::table('feeder_conf')->first();

        // dd($request->data);
        $error = [];
        foreach ($request->data as $row)
        {
            // dd($row);
            $err = "";
            // dd($row['ada_kps']);
            //BIODATA MAHASISWA
            $param = json_encode(
                array(
                    'act' => 'InsertBiodataMahasiswa',
                    'token' => $this->token,
                    'record' => array(
                        "nama_mahasiswa" => $row['nama'],
                        "jenis_kelamin" => $row['jenis_kelamin'],
                        "tempat_lahir"=> $row['tempat_lahir'],
                        "tanggal_lahir"=> date('Y-m-d',strtotime($row['tanggal_lahir'])),
                        "id_agama"=> $row['agama'],
                        "nik"=> str_replace("'",'',$row['nik']),
                        "nisn"=> str_replace("'",'',$row['nisn']),
                        "kewarganegaraan"=> 'ID',
                        "jalan"=> substr($row['alamat'],0.60),
                        "id_wilayah"=> $row['wilayah'],
                        "rt"=> $row['rt'],
                        "rw"=> $row['rw'],
                        "dusun"=> substr($row['alamat'],0,60),
                        "kelurahan"=> $row['kelurahan'],
                        "id_wilayah" => "056000",
                        "kode_pos"=> $row['kode_pos'],
                        "penerima_kps"=> $row['ada_kps'],
                        "email"=> $row['email'],
                        "telepon"=> $row['email'],
                        "id_kebutuhan_khusus_mahasiswa"=> '0',
                        // orang tua

                        "nik_ayah"=> str_replace("'",'',$row['nik_ayah']),
                        "nama_ayah"=> $row['nama_ayah'],
                        "tanggal_lahir_ayah"=> date('Y-m-d',strtotime($row['tanggal_lahir_ayah'])),
                        "id_pendidikan_ayah"=> $row['jenjang_pendidikan_ayah'],
                        "id_pekerjaan_ayah"=> $row['id_pekerjaan_ayah'],
                        "id_penghasilan_ayah"=> '14',
                        "id_kebutuhan_khusus_ayah"=> '0',


                        "nik_ibu"=> str_replace("'",'',$row['nik_ibu']),
                        "nama_ibu_kandung"=> $row['nama_ibu'],
                        "tanggal_lahir_ibu"=> date('Y-m-d',strtotime($row['tanggal_lahir_ibu'])),
                        "id_pendidikan_ibu"=> $row['jenjang_pendidikan_ibu'],
                        "id_pekerjaan_ibu"=> $row['id_pekerjaan_ibu'],
                        "id_penghasilan_ibu"=> '14',
                        "id_kebutuhan_khusus_ibu"=>'0',

                        // "nik_wali"=> str_replace("'",'',$row['nik_wali']),
                        // "nama_wali"=> $row['nama_wali'],
                        // "tanggal_lahir_wali"=> date('Y-m-d',strtotime($row['tanggal_lahir'])),
                        // "id_pendidikan_wali"=> $row['jenjang_pendidikan_wali'],
                        // "id_pekerjaan_wali"=> $row['id_pekerjaan_wali'],
                        // "id_penghasilan_wali"=> '14',
                    ),
                )
            );


            $body = array('http' =>
                array(
                    'method' => 'POST',
                    'header' => 'Content-type:  application/json',
                    'content' => $param,
                )
            );
            // dump($wsdl->url_wsdl);
            $context = stream_context_create($body);
            $link = file_get_contents($wsdl->url_wsdl,false,$context);

            $data = json_decode($link);
            // dd($data);
            $id_mahasiswa = "";
            if($data->error_code != 0){
                $err = $err." ".$data->error_desc."; ";
            }else{
                $err = $err." Berhasil; ";
                $id_mahasiswa = $data->data->id_mahasiswa;
                DB::table('acd_student')->where('Student_Id',$row['student_id'])->update(['Feeder_Id'=>$id_mahasiswa]);

                //////
                // dd($row['jenis_daftar']);
                // RIWAYAT PENDIDIKAN MAHASISWA
                // dump($row['nik']);
                $param = json_encode(
                    array(
                        'act' => 'InsertRiwayatPendidikanMahasiswa',
                        'token' => $this->token,
                        'record' => array(
                            "id_mahasiswa" => $id_mahasiswa,
                            "nim" => str_replace("'",'',$row['nim']),
                            "id_jenis_daftar"=> $row['jenis_daftar'],
                            "id_periode_masuk"=> $row['mulai_semester'],
                            "tanggal_daftar"=> date('Y-m-d',strtotime($row['tanggal_masuk'])),
                            "id_perguruan_tinggi"=> $row['kode_pti'],
                            "id_prodi"=> $row['id_prodi'],
                            "id_pembiayaan"=> "1",
                            "biaya_masuk"=> 0,
                        ),
                    )
                );


                $body = array('http' =>
                    array(
                        'method' => 'POST',
                        'header' => 'Content-type:  application/json',
                        'content' => $param,
                    )
                );
                $context = stream_context_create($body);
                $link = file_get_contents($wsdl->url_wsdl,false,$context);

                $data = json_decode($link);
                // dd($data);

                $id_reg_mahasiswa = "";
                if($data->error_code != 0){
                    $err = $err." ".$data->error_desc."; ";
                }else{
                    $err = $err." Berhasil; ";
                    $id_reg_mahasiswa = $data->data->id_registrasi_mahasiswa;
                    DB::table('acd_student')->where('Student_Id',$row['student_id'])->update(['id_registrasi_mahasiswa'=>$id_reg_mahasiswa]);
                }
            }
            //////
            array_push($error, $err);
        }
        // dump($error);
        return response()->json(['data' =>$error]);
    }
    public function InsertMataKuliah(Request $request)
    {
        $wsdl = DB::table('feeder_conf')->first();
        // dd($collection);
        $error = [];
        foreach ($request->data as $row)
        {
            // dd($row);
            $err = "";

            //Data Mata Kuliah
            $param = json_encode(
                array(
                    'act' => 'InsertMataKuliah',
                    'token' => $this->token,
                    'record' => array(
                        "id_prodi" => $row['id_prodi'],
                        "kode_mata_kuliah" => $row['kode_mk'],
                        "nama_mata_kuliah"=> $row['nm_mk'],
                        "id_jenis_mata_kuliah"=> $row['jns_mk'],
                        "id_kelompok_mata_kuliah"=> $row['kel_mk'],
                        "sks_mata_kuliah"=> $row['sks_mk'],
                        "sks_tatap_muka"=> $row['sks_tm'],
                        "sks_praktek"=> $row['sks_prak'],
                        "sks_praktek_lapangan"=> $row['sks_prak_lap'],
                        "sks_simulasi"=> $row['sks_sim'],
                        "metode_kuliah"=> $row['metode_pelaksanaan_kuliah'],
                        "ada_sap"=> $row['a_sap'],
                        "ada_silabus"=> $row['a_silabus'],
                        "ada_bahan_ajar"=> $row['b_ajar'],
                        "ada_acara_praktek" => $row['acara_praktek'],
                        "ada_diktat" => $row['a_diktat'],
                        "tanggal_mulai_efektif"=> Date("Y-m-d",strtotime($row['tgl_mulai_efektif'])),
                        "tanggal_akhir_efektif"=> Date("Y-m-d",strtotime($row['tgl_akhir_efektif']))
                    ),
                )
            );


            $body = array('http' =>
                array(
                    'method' => 'POST',
                    'header' => 'Content-type:  application/json',
                    'content' => $param,
                )
            );
            // dump($wsdl->url_wsdl);
            $context = stream_context_create($body);
            $link = file_get_contents($wsdl->url_wsdl,false,$context);


            $data = json_decode($link);
            // dd($data);
            $id_matkul = "";
            if($data->error_code != 0){
                $err = $err." ".$data->error_desc."; ";

                $param = json_encode(
                    array(
                        'act' => 'GetListMataKuliah',
                        'token' => $this->token,
                        "filter" => "kode_mata_kuliah = '".$row['kode_mk']."'",
                        "order" => 0,
                        "offset" => 0,
                        "limit" => 1
                    )
                );

                $body = array('http' =>
                    array(
                        'method' => 'POST',
                        'header' => 'Content-type:  application/json',
                        'content' => $param,
                    )
                );
                // dump($wsdl->url_wsdl);
                $context = stream_context_create($body);
                $link = file_get_contents($wsdl->url_wsdl,false,$context);

                $data = json_decode($link);
                if($data->error_code != 0){
                }else{
                    $id_matkul = $data->data[0]->id_matkul;
                    // dd($id_matkul);
                    DB::table('acd_course')->where('Course_Id',$row['id_mk'])->update(['Feeder_Id'=>$id_matkul]);
                }
            }else{
                $err = $err." Berhasil; ";
                $id_matkul = $data->data->id_matkul;
                DB::table('acd_course')->where('Course_Id',$row['id_mk'])->update(['Feeder_Id'=>$id_matkul]);
            }
            //////
            array_push($error, $err);
        }
        // dump($error);
        return response()->json(['data' =>$error]);
    }
    public function InsertKelasKuliah(Request $request)
    {
    }
    public function InsertAjarDosen(Request $request)
    {
    }
    public function InsertBobotNilai(Request $request)
    {
    }
    public function InsertNilai(Request $request)
    {
    }
    public function InsertKuliahMahasiswa(Request $request)
    {
    }
    public function InsertNilaiTransfer(Request $request)
    {
        // id_transfer
        // id_registrasi_mahasiswa
        // kode_mata_kuliah_asal
        // nama_mata_kuliah_asal
        // sks_mata_kuliah_asal
        // nilai_huruf_asal
        // Id_matkul
        // kode_matkul_diakui
        // nama_mata_kuliah_diakui
        // sks_mata_kuliah_diakui
        // nilai_angka_diakui
        // nilai_huruf_diakui
        $wsdl = DB::table('feeder_conf')->first();
        // dd($collection);
        $error = [];
        foreach ($request->data as $row)
        {
            // dd($row);
            $err = "";

            //Data Mata Kuliah
            $param = json_encode(
                array(
                    'act' => 'InsertMataKuliah',
                    'token' => $this->token,
                    'record' => array(
                        "id_prodi" => $row['id_prodi'],
                        "id_transfer" => null,
                        "id_registrasi_mahasiswa" => $row['id_registrasi_mahasiswa'],
                        "kode_mata_kuliah_asal" => $row['kode_mk_asal'],
                        "nama_mata_kuliah_asal" => $row['nm_mk_asal'],
                        "sks_mata_kuliah_asal" => $row['sks_asal'],
                        "nilai_huruf_asal" => $row['nilai_huruf_asal'],
                        "Id_matkul" => $row['id_matkul'],
                        "kode_matkul_diakui" => $row['kode_mk'],
                        "nama_mata_kuliah_diakui" => $row['nm_mk'],
                        "sks_mata_kuliah_diakui" => $row['sks_diakui'],
                        "nilai_angka_diakui" => $row['nilai_angka_diakui'],
                        "nilai_huruf_diakui" => $row['nilai_huruf_diakui']
                    ),
                )
            );


            $body = array('http' =>
                array(
                    'method' => 'POST',
                    'header' => 'Content-type:  application/json',
                    'content' => $param,
                )
            );
            // dump($wsdl->url_wsdl);
            $context = stream_context_create($body);
            $link = file_get_contents($wsdl->url_wsdl,false,$context);


            $data = json_decode($link);
            // dd($data);
            $id_transfer = "";
            if($data->error_code != 0){
                $err = $err." ".$data->error_desc."; ";
                //
                // $param = json_encode(
                //     array(
                //         'act' => 'GetListMataKuliah',
                //         'token' => $this->token,
                //         "filter" => "kode_mata_kuliah = '".$row['kode_mk']."'",
                //         "order" => 0,
                //         "offset" => 0,
                //         "limit" => 1
                //     )
                // );
                //
                // $body = array('http' =>
                //     array(
                //         'method' => 'POST',
                //         'header' => 'Content-type:  application/json',
                //         'content' => $param,
                //     )
                // );
                // // dump($wsdl->url_wsdl);
                // $context = stream_context_create($body);
                // $link = file_get_contents($wsdl->url_wsdl,false,$context);
                //
                // $data = json_decode($link);
                // if($data->error_code != 0){
                // }else{
                //     $id_matkul = $data->data[0]->id_matkul;
                //     // dd($id_matkul);
                //     DB::table('acd_course')->where('Course_Id',$row['id_mk'])->update(['Feeder_Id'=>$id_matkul]);
                // }
            }else{
                $err = $err." Berhasil; ";
                $id_transfer = $data->data->id_transfer;
                DB::table('acd_student_krs')->where([['Course_Id',$row['id_matkul_local'],['Student_Id',$row['id_mahasiswa_local'],['Term_Year_Id',$row['term_year_id']]]]])->update(['Feeder_Id'=>$id_transfer]);
            }
            //////
            array_push($error, $err);
        }
        // dump($error);
        return response()->json(['data' =>$error]);
    }
    public function InsertMahasiswaLulusDo(Request $request)
    {
    }
    public function InsertDosenPembimbing(Request $request)
    {
    }
    public function InsertBimbingMahasiswa(Request $request)
    {
    }
    public function InsertAktivitasMahasiswa(Request $request)
    {
    }
    public function InsertAnggotaAktivitasMahasiswa(Request $request)
    {
    }
    public function InsertPrestasi(Request $request)
    {
    }

}
