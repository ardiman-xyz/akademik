<?php

namespace App\Exports;
use App\AcdStudent;

use Maatwebsite3\Excel\Concerns\FromCollection;
use Maatwebsite3\Excel\Concerns\WithHeadings;
use Maatwebsite3\Excel\Concerns\Exportable;
use Illuminate\Support\Facades\Input;

use DB;

class BobotNilaiExport implements  FromCollection, WithHeadings{
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

        $bobot_nilai = DB::table('acd_grade_department');


        foreach ($prodi as $value) {
            $bobot_nilai  = $bobot_nilai->orwhere([['acd_grade_department.Department_Id', $value]])->where([['acd_grade_department.Term_Year_Id', $default_term_year]]);
        }

        $bobot_nilai = $bobot_nilai->leftjoin('acd_grade_letter','acd_grade_department.Grade_Letter_Id','=','acd_grade_letter.Grade_Letter_Id')
        ->leftjoin('mstr_department','acd_grade_department.Department_Id','=','mstr_department.Department_Id')
        ->leftjoin('mstr_education_program_type','mstr_department.Education_Prog_Type_Id','=','mstr_education_program_type.Education_Prog_Type_Id')
        // ->leftjoin('mstr_education_program_type','mstr_department.Education_Prog_Type_Id','=','mstr_education_program_type.Education_Prog_Type_Id')
        ->leftjoin('mstr_term_year','acd_grade_department.Term_Year_Id','=','mstr_term_year.Term_Year_Id')
        ->orderby('acd_grade_department.Grade_Letter_Id','asc')->get();

        $datas = [];
        $i = 0;

        foreach($bobot_nilai as $d){
            $datas[$i]['UUID_Bobot_Nilai'] = '';
            // $acr = DB::table('mstr_department')
            // ->join('mstr_education_program_type','mstr_department.Education_Prog_Type_Id','=','mstr_education_program_type.Education_Prog_Type_Id')
            // ->where('Education_Prog_Type_Id',$d->Education_Prog_Type_Id)->first();
            $datas[$i]['Jenjang_Prodi'] = $d->Acronym.'-'.$d->Department_Name;
            $datas[$i]['Nilai_Huruf'] = $d->Grade_Letter;
            $datas[$i]['Rentang_Nilai_Min'] = $d->Scale_Numeric_Min;
            $datas[$i]['Rentang_Nilai_Max'] = $d->Scale_Numeric_Max;
            $datas[$i]['Bobot_Index'] = $d->Weight_Value;
            $datas[$i]['Tgl_Mulai'] = date('Ymd',strtotime($d->Start_Date));
            $datas[$i]['Tgl_Selesai'] = date('Ymd',strtotime($d->End_Date));


            $i++;

        }

        return collect($datas);
    }

    public function headings(): array
    {
        return [
            'UUID_Bobot_Nilai',
            'Jenjang_Prodi',
            'Nilai_Huruf',
            'Rentang_Nilai_Min',
            'Rentang_Nilai_Max',
            'Bobot_Index',
            'Tgl_Mulai',
            'Tgl_Selesai',
        ];
    }
}
