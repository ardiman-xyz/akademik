<?php

namespace App\Exports;
use App\AcdStudent;

use Maatwebsite3\Excel\Concerns\FromCollection;
use Maatwebsite3\Excel\Concerns\WithHeadings;
use Maatwebsite3\Excel\Concerns\Exportable;
use Illuminate\Support\Facades\Input;

use DB;

class MahasiswaExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    use Exportable;
    protected $param;
    protected $default_term_year;
    
    public function __construct($param,$default_term_year)
    {
        $this->prodi = $param; 
        $this->default_term_year = $default_term_year; 
    }


    public function collection()
    {

        $prodi  = $this->prodi;
        $default_term_year  = $this->default_term_year;

        $term_year = DB::table('mstr_term_year')->where('Term_Year_Id',$default_term_year)->first();
        $students = DB::table('acd_student');

        foreach ($prodi as $value) {
            $students  = $students->orwhere([['acd_student.Department_Id', $value]])->where([['acd_student.Entry_Year_Id', $term_year->Year_Id],['.acd_student.Entry_Term_Id', $term_year->Term_Id]]);
        }
        $students = $students->leftjoin('mstr_department','acd_student.Department_Id','=','mstr_department.Department_Id')
        ->leftjoin('mstr_education_program_type','mstr_department.Education_Prog_Type_Id','=','mstr_education_program_type.Education_Prog_Type_Id')
        ->leftjoin('reg_camaru','acd_student.Register_Number','=','reg_camaru.Reg_Num')
        ->leftjoin('reg_register_type','reg_camaru.Register_Type_Id','=','reg_register_type.Register_Type_Id')
        ->leftjoin('mstr_country','acd_student.Birth_Country_Id','=','mstr_country.Country_Id')
        ->leftjoin('mstr_register_status','reg_camaru.Register_Status_Id','=','mstr_register_status.Register_Status_Id')
        ->select(
            'acd_student.Student_Id',
            'acd_student.Nim',
            'acd_student.Full_Name',
            'acd_student.Entry_Year_Id',
            'acd_student.Entry_Term_Id',
            'acd_student.Created_Date as Tanggal_Masuk',
            'acd_student.Gender_Id as Jen_Kel',
            'acd_student.Recieve_Kps',
            'acd_student.Kps_Number',
            'acd_student.Nik',
            'mstr_department.Department_Name',
            'mstr_department.Feeder_Id as Id_Department',
            'mstr_education_program_type.Acronym',
            'reg_register_type.Register_Type_Id',
            'reg_camaru.Nisn_Number',
            'reg_camaru.Nik_Number',
            'reg_camaru.Birth_Place',
            'reg_camaru.Birth_Date',
            'reg_camaru.Religion_Id',
            'reg_camaru.Address',
            'reg_camaru.RT',
            'reg_camaru.RW',
            'reg_camaru.Sub_District',
            'reg_camaru.District_Id',
            'reg_camaru.Zip_Code',
            'reg_camaru.Email_General',
            'mstr_country.Country_Acronym',
            'mstr_register_status.Feeder_Id as Jenis_Daftar'
            )
        ->orderby('acd_student.Department_Id','asc')
        ->get();
        
        $i = 0;
        $std = [];
        foreach($students as $d){
            $std[$i]['Student_Id'] = $d->Student_Id;
            $std[$i]['Nim'] = "'".$d->Nim;
            $std[$i]['Full_Name'] = ucwords(strtolower($d->Full_Name));
            $std[$i]['Status_Mahasiswa'] = 'A';
            $std[$i]['Jenjang_Prodi'] = $d->Acronym.'-'. $d->Department_Name;
            $std[$i]['Id_Prodi'] = $d->Id_Department;
            $std[$i]['Kode_PTI'] = '6fe2114d-4542-4cec-8168-ddf767ce284f';
            $std[$i]['Status_Masuk'] = '1';
            $std[$i]['Jalur_Daftar'] = $d->Register_Type_Id;
            $std[$i]['Jenis_Daftar'] = $d->Jenis_Daftar;
            $std[$i]['Tanggal_Masuk'] = date('Ymd', strtotime($d->Tanggal_Masuk));
            $std[$i]['Status_Keluar'] = '';
            $std[$i]['Tanggal_Lulus'] = '';
            $std[$i]['Ket_Keluar'] = '';
            $std[$i]['No_SK_UN'] = '';
            // Mulai Semester 
            $Mulai = DB::table('mstr_term_year')->where('Term_Year_Id', $default_term_year)->first();
            $std[$i]['Mulai_Semester'] = $Mulai->Term_Year_Id;
            $std[$i]['SKS_Diakui'] = '0';
            $std[$i]['Jalur_Skripsi'] = '';
            $std[$i]['Judul_Skripsi'] = '';
            $std[$i]['Bulan_Awal_Bimbingan'] = '';
            $std[$i]['Bimbingan'] = '';
            $std[$i]['No_SK_Yudisium'] = '';
            $std[$i]['Tgl_SK_Yudisium'] = '';
            // IPK

            $ipks = DB::table('acd_student_krs')->where([['acd_student_krs.Term_Year_Id',$Mulai->Term_Year_Id],['acd_student_krs.Student_Id',$d->Student_Id]])
            ->join('acd_student_khs','acd_student_krs.Krs_Id','acd_student_khs.Krs_Id')
            ->select(DB::RAW(" SUM(acd_student_khs.Sks * Weight_Value) / SUM(acd_student_khs.Sks) as Total_IPK"))
            ->first();

            $std[$i]['IPK'] = number_format($ipks->Total_IPK,2,'.',',');
            $std[$i]['Nomor_Ijasah'] = '';
            $std[$i]['Nomor_sertifikat_sertifikasi'] = '';
            $std[$i]['Pindah_Mhs_Asing'] = '';
            $std[$i]['Pindah_Mhs_Asing'] = '';
            $std[$i]['Nama_PT_Asal'] = '';
            $std[$i]['Nama_Prodi_Asal'] = '';
            $std[$i]['UUID'] = '';
            // Jenis Kelamin
            if($d->Jen_Kel == 1){
                $jk = 'L';
            }elseif($d->Jen_Kel == 2){
                $jk = 'P';
            }
            $std[$i]['Jenis_Kelamin'] = $jk;
            $std[$i]['NISN'] = "'".$d->Nisn_Number;
            $std[$i]['Nik'] = "'".$d->Nik_Number;
            $std[$i]['Tempat_Lahir'] = ucfirst($d->Birth_Place);
            $std[$i]['Tanggal_Lahir'] = date('Ymd', strtotime($d->Birth_Date));
            
            $std[$i]['Agama'] = $d->Religion_Id;
            $std[$i]['Kebutuhan_Khusus'] = '0';
            $std[$i]['Alamat'] = ucfirst($d->Address);
            $std[$i]['RT'] = $d->RT;
            $std[$i]['RW'] = $d->RW;
            $std[$i]['Dusun'] = $d->Sub_District;
            $std[$i]['Wilayah'] = $d->District_Id;
            $std[$i]['Kode_Pos'] = $d->Zip_Code;
            $std[$i]['Kelurahan'] = substr($d->Address, 0,60);
            $std[$i]['Kecamatan'] = substr($d->Address, 0,60);
            $std[$i]['Jenis_Tinggal'] = '';
            $std[$i]['No_Telp'] = '';
            $std[$i]['Phone_Home'] = '';
            $std[$i]['Email'] = $d->Email_General;
            if($d->Recieve_Kps != null){
                $std[$i]['Ada_Kps'] = $d->Recieve_Kps;

            }else{
                $std[$i]['Ada_Kps'] ='0';
            }
            $std[$i]['KPS_Number'] = $d->Kps_Number;
            // Ayah
            $ortu = DB::table('acd_student_parent')->where([['Student_Id',$d->Student_Id],['Parent_Type_Id', 1]])
            ->leftjoin('mstr_education_type','acd_student_parent.Education_Type_Id','=','mstr_education_type.Education_Type_Id')
            ->leftjoin('mstr_job_category','acd_student_parent.Job_Category_Id','=','mstr_job_category.Job_Category_Id')
            ->select('acd_student_parent.*','mstr_education_type.*','mstr_job_category.Feeder_Id as Id_Pekerjaan')
            ->first();
            if($ortu != null){
            $std[$i]['Nik_Ayah'] = "'".$ortu->Nik;
            $std[$i]['Nama_Ayah'] = ucwords(strtolower($ortu->Full_Name));
            $std[$i]['Tanggal_Lahir_Ayah'] = date('Ymd', strtotime($ortu->Birth_Date));
            $std[$i]['Jenjang_Pendidikan_Ayah'] = $ortu->Feeder_Id;
            $std[$i]['Penghasilan_Ayah'] = $ortu->Income;
            $std[$i]['Id_Penghasilan_Ayah'] = $ortu->Job_Category_Id;
            $std[$i]['Kebutuhan_Khusus_Ayah'] = '0';
            }else{
                $std[$i]['Nik_Ayah'] = '';
                $std[$i]['Nama_Ayah'] = '';
                $std[$i]['Tanggal_Lahir_Ayah'] = '';
                $std[$i]['Jenjang_Pendidikan_Ayah'] = '';
                $std[$i]['Penghasilan_Ayah'] = '';
                $std[$i]['Id_Penghasilan_Ayah'] = '';
                $std[$i]['Kebutuhan_Khusus_Ayah'] = '0';
            }
           
            // Ibu
            $ibu = DB::table('acd_student_parent')->where([['Student_Id',$d->Student_Id],['Parent_Type_Id', 2]])
            ->join('mstr_education_type','acd_student_parent.Education_Type_Id','=','mstr_education_type.Education_Type_Id')
            ->join('mstr_job_category','acd_student_parent.Job_Category_Id','=','mstr_job_category.Job_Category_Id')
            ->select('acd_student_parent.*','mstr_education_type.*','mstr_job_category.Feeder_Id as Id_Pekerjaan')
            ->first();
            if($ibu != null){
                $std[$i]['Nik_Ibu'] = "'".$ibu->Nik;
                $std[$i]['Nama_Ibu'] =  ucwords(strtolower($ibu->Full_Name));
                $std[$i]['Tanggal_Lahir_Ibu'] = date('Ymd', strtotime($ibu->Birth_Date));
                $std[$i]['Jenjang_Pendidikan_Ibu'] = $ibu->Feeder_Id;
                $std[$i]['Penghasilan_Ibu'] = $ibu->Income;
                $std[$i]['Id_Penghasilan_Ibu'] = $ibu->Id_Pekerjaan;
                $std[$i]['Kebutuhan_Khusus_Ibu'] = '0';
            }else{
                $std[$i]['Nik_Ibu'] = '';
                $std[$i]['Nama_Ibu'] = '';
                $std[$i]['Tanggal_Lahir_Ibu'] = '';
                $std[$i]['Jenjang_Pendidikan_Ibu'] = '';
                $std[$i]['Penghasilan_Ibu'] = '';
                $std[$i]['Id_Penghasilan_Ibu'] = '';
                $std[$i]['Kebutuhan_Khusus_Ibu'] = '0';
            }
           
            // Wali
            $wali = DB::table('acd_student_parent')->where([['Student_Id',$d->Student_Id],['Parent_Type_Id', 3]])
            ->join('mstr_education_type','acd_student_parent.Education_Type_Id','=','mstr_education_type.Education_Type_Id')
            ->join('mstr_job_category','acd_student_parent.Job_Category_Id','=','mstr_job_category.Job_Category_Id')
            ->select('acd_student_parent.*','mstr_education_type.*','mstr_job_category.Feeder_Id as Id_Pekerjaan')
            ->first();
           if($wali != null){
               $std[$i]['Nik_Wali'] ="'". $wali->Nik;
               $std[$i]['Nama_Wali'] =  ucwords(strtolower($wali->Full_Name));
               $std[$i]['Tanggal_Lahir_Wali'] = date('Ymd', strtotime($wali->Birth_Date));
               $std[$i]['Jenjang_Pendidikan_Wali'] = $wali->Feeder_Id;
               $std[$i]['Penghasilan_Wali'] = $wali->Income;
               $std[$i]['Id_Penghasilan_Wali'] = $ibu->Id_Pekerjaan;
            }else{
                $std[$i]['Nik_Wali'] = '';
                $std[$i]['Nama_Wali'] = '';
                $std[$i]['Tanggal_Lahir_Wali'] = '';
                $std[$i]['Jenjang_Pendidikan_Wali'] = '';
                $std[$i]['Penghasilan_Wali'] = '';
           }
            $std[$i]['Kewarganegaraan'] = $d->Country_Acronym;


            $i++;
        }
        


        
        return collect($std);
        // return AcdStudent::take(50)->get();
    }

    public function headings(): array
    {
        return [
            'Student_Id',
            'Nim',
            'Nama',
            'Status_Mahasiswa',
            'Jenjang_Prodi',
            'Id_Prodi',
            'Kode_PTI',
            'Status_Masuk',
            'Jalur_Daftar',
            'Jenis_Daftar',
            'Tanggal_Masuk',
            'Status_Keluar',
            'Tanggal_Lulus',
            'Keterangan_Keluar',
            'No_SK_UN',
            'Mulai_Semester',
            'SKS_Diakui',
            'Jalur_Skripsi',
            'Judul_Skripsi',
            'Bulan_Awal_Bimbingan',
            'Bimbingan',
            'No_SK_Yudisium',
            'Tgl_SK_Yudisium',
            'Total_IPK',
            'Nomor_Ijasah',
            'Nomor_sertifikat_sertifikasi',
            'Pindah_Mhs_Asing',
            'Nama_PT_Asal',
            'Nama_Prodi_Asal',
            'UUID',
            'Jenis_Kelamin',
            'NISN',
            'NIK',
            'Tempat_Lahir',
            'Tanggal_Lahir',
            'Agama',
            'Kebutuhan_Khusus',
            'Alamat',
            'RT',
            'RW',
            'Dusun',
            'Wilayah',
            'Kode_Pos',
            'Kelurahan',
            'Kecamatan',
            'Jenis_Tinggal',
            'No_Hp',
            'No_Telp_Rumah',
            'Email',
            'Ada_Kps',
            'No_Kps',
            'Nik_Ayah',
            'Nama_Ayah',
            'Tanggal_Lahir_Ayah',
            'Jenjang_Pendidikan_Ayah',
            'Penghasilan_Ayah',
            'Id_Pekerjaan_Ayah',
            'Kebutuhan_Khusus_Ayah',
            'Nik_Ibu',
            'Nama_Ibu',
            'Tanggal_Lahir_Ibu',
            'Jenjang_Pendidikan_Ibu',
            'Penghasilan_Ibu',
            'Id_Pekerjaan_Ibu',
            'Kebutuhan_Khusus_Ibu',
            'Nik_Wali',
            'Nama_Wali',
            'Tanggal_Lahir_Wali',
            'Jenjang_Pendidikan_Wali',
            'Penghasilan_Wali',
            'Id_Pekerjaan_Wali',
            'Kewarganegaraan',
        ];
    }
}
