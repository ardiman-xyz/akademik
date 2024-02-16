<?php

namespace App\Exports;

use Maatwebsite3\Excel\Concerns\FromCollection;
use Maatwebsite3\Excel\Concerns\WithHeadings;
use Maatwebsite3\Excel\Concerns\Exportable;
use Illuminate\Support\Facades\Input;

use DB;

class AjarDosenExport implements FromCollection, WithHeadings
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
        $curriculum_entry_year = DB::table('acd_curriculum_entry_year')->where([['Term_Year_Id', $m_term_year->Term_Year_Id],['Department_Id', $prodi],['Class_Prog_Id', 1],['Entry_Year_Id',$m_term_year->Year_Id]])->first();
        $offered_courses = DB::table('acd_offered_course')->where([['Department_Id', $prodi],['Class_Prog_Id', 1],['Term_Year_Id', $m_term_year->Term_Year_Id]])->select('acd_offered_course.Course_Id')->get();
        $off_courses=[];
        foreach ($offered_courses as $offered_course) {
            $off_courses[] = $offered_course->Course_Id;
        }
        $courses = DB::table('acd_course_curriculum')
                    ->select(
                        'acd_course.Course_Id',
                        'mstr_education_program_type.Acronym as jenjang_prodi',
                        'acd_course.Course_Code',
                        'acd_course.Course_Name',
                        'acd_course_type.Feeder_Id as jns_mk',
                        'acd_course_group.Feeder_Id as kel_mk',
                        'mstr_department.Department_Name',
                        'acd_course_curriculum.Applied_Sks',
                        'mstr_class.Class_Name',
                        'emp_employee.Nidn',
                        'emp_employee.Full_Name'
                    )
                    ->leftJoin('acd_course','acd_course_curriculum.Course_Id','=','acd_course.Course_Id')
                    ->leftJoin('acd_course_type','acd_course.Course_Type_Id','=','acd_course_type.Course_Type_Id')
                    ->leftJoin('acd_course_group','acd_course_curriculum.Course_Group_Id','=','acd_course_group.Course_Group_Id')
                    ->leftJoin('mstr_department','acd_course.Department_Id','=','mstr_department.Department_Id')
                    ->leftJoin('mstr_education_program_type','mstr_department.Education_Prog_Type_Id','=','mstr_education_program_type.Education_Prog_Type_Id')
                    ->leftJoin('acd_offered_course','acd_course.Course_Id','=','acd_offered_course.Course_Id')
                    ->leftJoin('acd_offered_course_lecturer','acd_offered_course.Offered_Course_id','=','acd_offered_course_lecturer.Offered_Course_id')
                    ->leftJoin('emp_employee','acd_offered_course_lecturer.Employee_Id','=','emp_employee.Employee_Id')
                    ->leftJoin('acd_student_krs','acd_course.Course_Id','=','acd_student_krs.Course_Id')
                    ->leftJoin('mstr_class','acd_student_krs.Class_Id','=','mstr_class.Class_Id')
                    ->where([
                        ['acd_course_curriculum.Curriculum_Id',$curriculum_entry_year->Curriculum_Id],
                        ['acd_course_curriculum.Department_Id',$prodi],
                        ['acd_course_curriculum.Class_Prog_Id',1],
                        ['acd_offered_course.Department_Id', $prodi],
                        ['acd_offered_course.Class_Prog_Id', 1],
                        ['acd_offered_course.Term_Year_Id', $m_term_year->Term_Year_Id],
                        ['acd_student_krs.Class_Prog_Id', 1],
                        ['acd_student_krs.Term_Year_Id', $m_term_year->Term_Year_Id],
                        ['acd_student_krs.Is_Remediasi', 0]
                    ])
                    ->whereIn('acd_course.Course_Id', $off_courses)
                    ->groupBy('acd_course.Course_Id')
                    ->groupBy('mstr_education_program_type.Acronym')
                    ->groupBy('acd_course.Course_Code')
                    ->groupBy('acd_course.Course_Name')
                    ->groupBy('acd_course_type.Feeder_Id')
                    ->groupBy('acd_course_group.Feeder_Id')
                    ->groupBy('mstr_department.Department_Name')
                    ->groupBy('acd_course_curriculum.Applied_Sks')
                    ->groupBy('mstr_class.Class_Name')
                    ->groupBy('emp_employee.Nidn')
                    ->groupBy('emp_employee.Full_Name')
                    ->get();

        $i = 0;
        $items = [];
        foreach($courses as $d){
            $items[$i]['id_ajar'] = null;
            $items[$i]['id_smt'] = $m_term_year->Term_Year_Id;
            $items[$i]['id_sms'] = $d->jenjang_prodi."-".$d->Department_Name;
            $items[$i]['kode_mk'] = $d->Course_Code;
            $items[$i]['nm_mk'] = $d->Course_Name;
            $items[$i]['nm_kls'] = $d->Class_Name;
            $items[$i]['nidn'] = $d->Nidn;
            $items[$i]['nm_sdm'] = $d->Full_Name;
            $items[$i]['sks_subst_tot'] = $d->Applied_Sks;
            $items[$i]['sks_tm_subst'] = null;
            $items[$i]['sks_prak_subst'] = null;
            $items[$i]['sks_prak_lap_subst'] = null;
            $items[$i]['sks_sim_subst'] = null;
            $items[$i]['jml_tm_renc'] = null;
            $items[$i]['jml_tm_real'] = null;
            $items[$i]['id_jns_eval'] = 1;

            // ad.id_ajar
            // kls.id_smt
            // mk.id_sms
            // mk.kode_mk
            // mk.nm_mk
            // kls.nm_kls
            // ptk.nidn
            // ptk.nm_sdm
            // ad.sks_subst_tot
            // ad.sks_tm_subst
            // ad.sks_prak_subst
            // ad.sks_prak_lap_subst
            // ad.sks_sim_subst
            // ad.jml_tm_renc
            // ad.jml_tm_real
            // ad.id_jns_eval

            $i++;
        }
        // dd($items);


        return collect($items);

    }

    public function headings(): array
    {
        return [
            'id_ajar',
            'id_smt',
            'id_sms',
            'kode_mk',
            'nm_mk',
            'nm_kls',
            'nidn',
            'nm_sdm',
            'sks_subst_tot',
            'sks_tm_subst',
            'sks_prak_subst',
            'sks_prak_lap_subst',
            'sks_sim_subst',
            'jml_tm_renc',
            'jml_tm_real',
            'id_jns_eval'
        ];
    }
}
