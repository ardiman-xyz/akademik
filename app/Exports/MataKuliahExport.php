<?php

namespace App\Exports;
// use App\MatKuliah;

use Maatwebsite3\Excel\Concerns\FromCollection;
use Maatwebsite3\Excel\Concerns\WithHeadings;
use Maatwebsite3\Excel\Concerns\Exportable;
use Illuminate\Support\Facades\Input;

use DB;

class MataKuliahExport implements FromCollection, WithHeadings
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

        // SELECT * FROM acd_course_curriculum
        // WHERE Curriculum_Id = (SELECT Curriculum_Id FROM acd_curriculum_entry_year WHERE Term_Year_Id = 20191 AND Department_Id = 8 AND Class_Prog_Id = 1 AND Entry_Year_Id = 2019)
        // AND Department_Id = 8 AND Class_Prog_Id = 1
        // AND Course_Id IN (SELECT Course_Id FROM acd_offered_course WHERE Department_Id = 8 AND Class_Prog_Id = 1 AND Term_Year_Id = 20191)

        $curriculum_entry_year = DB::table('acd_curriculum_entry_year')->where([['Term_Year_Id', $term_year->Term_Year_Id],['Department_Id', $prodi],['Class_Prog_Id', 1],['Entry_Year_Id',$term_year->Year_Id]])->first();
        $offered_courses = DB::table('acd_offered_course')->where([['Department_Id', $prodi],['Class_Prog_Id', 1],['Term_Year_Id', $term_year->Term_Year_Id]])->select('acd_offered_course.Course_Id')->get();
        $off_courses=[];
        foreach ($offered_courses as $offered_course) {
            $off_courses[] = $offered_course->Course_Id;
        }
        $courses = DB::table('acd_course_curriculum')
                    ->select(
                        'acd_course.Course_Id',
                        'mstr_education_program_type.Acronym as sms',
                        'acd_course.Course_Code',
                        'acd_course.Course_Name',
                        'acd_course_type.Feeder_Id as jns_mk',
                        'acd_course_group.Feeder_Id as kel_mk',
                        'mstr_department.Department_Name',
                        'mstr_department.Feeder_Id as Department_Id',
                        'mstr_department.Department_Id as Local_Department_Id',
                        'acd_course_curriculum.Applied_Sks',
                        'mstr_curriculum.Feeder_Id as Curriculum_Id',
                        'mstr_curriculum.Curriculum_Id as Local_Curriculum_Id'
                    )
                    ->leftJoin('acd_course','acd_course_curriculum.Course_Id','=','acd_course.Course_Id')
                    ->leftJoin('acd_course_type','acd_course.Course_Type_Id','=','acd_course_type.Course_Type_Id')
                    ->leftJoin('acd_course_group','acd_course_curriculum.Course_Group_Id','=','acd_course_group.Course_Group_Id')
                    ->leftJoin('mstr_department','acd_course.Department_Id','=','mstr_department.Department_Id')
                    ->leftJoin('mstr_education_program_type','mstr_department.Education_Prog_Type_Id','=','mstr_education_program_type.Education_Prog_Type_Id')
                    ->leftJoin('mstr_curriculum','acd_course_curriculum.Curriculum_Id','=','mstr_curriculum.Curriculum_Id')
                    ->where([['acd_course_curriculum.Curriculum_Id',$curriculum_entry_year->Curriculum_Id],['acd_course_curriculum.Department_Id',$prodi],['acd_course_curriculum.Class_Prog_Id',1]])
                    ->whereIn('acd_course.Course_Id', $off_courses)
                    ->get();

        $i = 0;
        $items = [];
        foreach($courses as $d){
            $items[$i]['id_mk'] = $d->Course_Id;
            $items[$i]['id_kurikulum_sp'] = $d->Curriculum_Id;
            $items[$i]['id_kurikulum_sp_local'] = $d->Local_Curriculum_Id;
            $items[$i]['id_smt'] = $term_year->Term_Year_Id;
            $items[$i]['id_sms'] = $d->sms."-".$d->Department_Name;
            $items[$i]['id_prodi'] = $d->Department_Id;
            $items[$i]['id_prodi_local'] = $d->Local_Department_Id;
            $items[$i]['smt'] = $term_year->Term_Id;
            $items[$i]['kode_mk'] = $d->Course_Code;
            $items[$i]['nm_mk'] = $d->Course_Name;
            $items[$i]['jns_mk'] = $d->jns_mk;
            $items[$i]['kel_mk'] = $d->kel_mk;
            $items[$i]['sks_mk'] = $d->Applied_Sks;
            $items[$i]['sks_tm'] = null;
            $items[$i]['sks_prak'] = null;
            $items[$i]['sks_prak_lap'] = null;
            $items[$i]['sks_sim'] = null;
            $items[$i]['metode_pelaksanaan_kuliah'] = null;
            $items[$i]['a_sap'] = 1;
            $items[$i]['a_silabus'] = 1;
            $items[$i]['b_ajar'] = 1;
            $items[$i]['acara_praktek'] = 0;
            $items[$i]['a_diktat'] = 1;
            $items[$i]['tgl_mulai_efektif'] = Date("Y-m-d",strtotime($term_year->Start_Date));
            $items[$i]['tgl_akhir_efektif'] = Date("Y-m-d",strtotime($term_year->End_Date));

            $i++;
        }


        return collect($items);
        // return AcdStudent::take(50)->get();
    }

    public function headings(): array
    {
        return [
            'id_mk',
            'id_kurikulum_sp',
            'id_kurikulum_sp_local',
            'id_smt',
            'id_sms',
            'id_prodi',
            'id_prodi_local',
            'smt',
            'kode_mk',
            'nm_mk',
            'jns_mk',
            'kel_mk',
            'sks_mk',
            'sks_tm',
            'sks_prak',
            'sks_prak_lap',
            'sks_sim',
            'metode_pelaksanaan_kuliah',
            'a_sap',
            'a_silabus',
            'b_ajar',
            'acara_praktek',
            'a_diktat',
            'tgl_mulai_efektif',
            'tgl_akhir_efektif'
        ];
    }
}
