<?php

namespace App\Exports;
use App\AcdStudent;

use Maatwebsite3\Excel\Concerns\FromCollection;
use Maatwebsite3\Excel\Concerns\WithHeadings;
use Maatwebsite3\Excel\Concerns\Exportable;
use Illuminate\Support\Facades\Input;

use DB;

class BimbingMahasiswaExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
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



        $term_year = DB::table('mstr_term_year')->where([['Term_Year_Id', $default_term_year]])->first();

        $dospem = DB::table('acd_student_supervision')->join('acd_student','acd_student_supervision.Student_Id','=','acd_student.Student_Id');
        foreach($prodi as $value){
            $dospem = $dospem->where([['acd_student.Department_Id',$value]])->where([['Entry_Year_Id', $term_year->Year_Id],['Entry_Term_Id',$term_year->Term_Id]]);

        }

        $dospem = $dospem->leftjoin('emp_employee','acd_student_supervision.Employee_Id','=','emp_employee.Employee_Id')
        ->leftjoin('mstr_term_year','acd_student.Entry_Year_Id','=','mstr_term_year.Year_Id')
        ->leftjoin('mstr_department','acd_student.Department_Id','=','mstr_department.Department_Id')
        ->leftjoin('mstr_education_program_type','acd_student.Department_Id','=','mstr_department.Department_Id')
        ->select('acd_student.Student_Id',
        'acd_student.Full_Name as Nama_Mahasiswa',
        'acd_student.id_registrasi_mahasiswa as Register_Id',
        'mstr_education_program_type.Acronym',
        'mstr_department.Department_Name',
        'emp_employee.Full_Name as Nama_Dosen',
        'emp_employee.Nidn as Nidn'
        )
        ->groupby('Student_Id')
        ->get();

        $datas = [];
        $i = 0;

        foreach($dospem as $dos){
            $datas[$i]['Prodi']= $dos->Acronym.'-'.$dos->Department_Name;
            $datas[$i]['Nama_Dosen']= $dos->Nama_Dosen;
            $datas[$i]['Nidn']= $dos->Nidn;
            $datas[$i]['Student_Id']= $dos->Student_Id;
            $datas[$i]['Nama_Mahasiswa']= $dos->Nama_Mahasiswa;
            if($dos->Register_Id != null){

                $datas[$i]['Id_Registrasi_Mahasiswa']= $dos->Register_Id;
            }else{
                $datas[$i]['Id_Registrasi_Mahasiswa']= '';
            }

            $i++;
        }

        return collect($datas);
    }

    public function headings(): array
    {
        return [
            'Jenjang_Prodi',
            'Nama_Dosen',
            'Nidn',
            'Student_Id',
            'Nama_Mahasiswa',
            'Id_Registrasi_Mahasiswa',
        ];
    }
}
