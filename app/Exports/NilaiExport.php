<?php

namespace App\Exports;

use Maatwebsite3\Excel\Concerns\FromCollection;
use Maatwebsite3\Excel\Concerns\WithHeadings;
use Maatwebsite3\Excel\Concerns\Exportable;
use Illuminate\Support\Facades\Input;

use DB;

class NilaiExport implements FromCollection, WithHeadings
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

        $m_term_year = DB::table('mstr_term_year')->where('Term_Year_Id',$default_term_year)->first();
        $student_courses = DB::table('acd_student_krs')
                            ->select(
                                'acd_student.Nim',
                                'acd_student.Full_Name',
                                'acd_course.Course_Code',
                                'acd_course.Course_Name',
                                'mstr_class.Class_Name',
                                'acd_student_khs_nilai_component.Total_score'
                                )
                            ->leftJoin('acd_student','acd_student_krs.Student_Id','=','acd_student.Student_Id')
                            ->leftJoin('acd_course','acd_student_krs.Course_Id','=','acd_course.Course_Id')
                            ->leftJoin('mstr_class','acd_student_krs.Class_Id','=','mstr_class.Class_Id')
                            ->leftJoin('acd_student_khs_nilai_component','acd_student_krs.Krs_Id','=','acd_student_khs_nilai_component.Krs_Id')
                            ->where([
                                ['Term_Year_Id', $m_term_year->Term_Year_Id],
                                ['acd_student_krs.Class_Prog_Id', 1],
                                ['acd_student.Department_Id',$prodi],
                                ['acd_course.Department_Id',$prodi]
                            ])
                            ->get();
        //
        $i = 0;
        $items = [];
        foreach($student_courses as $d){
            $items[$i]['id_smt'] = $m_term_year->Term_Year_Id ;
            $items[$i]['nipd'] = $d->Nim ;
            $items[$i]['nm_pd'] = $d->Full_Name ;
            $items[$i]['kode_mk'] = $d->Course_Code ;
            $items[$i]['nm_mk'] = $d->Course_Name ;
            $items[$i]['nm_kls'] = $d->Class_Name ;
            $items[$i]['nilai_angka'] = $d->Total_score ;
            if ($d->Total_score != null) {
                // code...
                $grd = DB::table('acd_grade_department')
                ->leftJoin('acd_grade_letter','acd_grade_department.Grade_Letter_Id','=','acd_grade_letter.Grade_Letter_Id')
                ->where([
                    ['Department_Id',$prodi],
                    ['Term_Year_Id',$m_term_year->Term_Year_Id],
                    ['Scale_Numeric_Min','<=',$d->Total_score],
                    ['Scale_Numeric_Max','>=',$d->Total_score]
                    ])->first();

                if ($grd != null) {
                    $items[$i]['nilai_huruf'] = $grd->Grade_Letter ;
                }else{
                    $items[$i]['nilai_huruf'] = null ;
                }
            }else{
                $items[$i]['nilai_huruf'] = null ;
            }
            $items[$i]['nilai_indeks'] = null ;

            // id_smt
            // nipd
            // nm_pd
            // kode_mk
            // nm_mk
            // nm_kls
            // nilai_angka
            // nilai_huruf
            // nilai_indeks

            $i++;
        }

        return collect($items);

    }

    public function headings(): array
    {
        return [
            'id_smt',
            'nipd',
            'nm_pd',
            'kode_mk',
            'nm_mk',
            'nm_kls',
            'nilai_angka',
            'nilai_huruf',
            'nilai_indeks'
        ];
    }
}
