<?php
namespace App\Exports;

use Maatwebsite3\Excel\Concerns\FromCollection;
use Maatwebsite3\Excel\Concerns\WithHeadings;
use Maatwebsite3\Excel\Concerns\Exportable;
use Illuminate\Support\Facades\Input;

use DB;

class NilaiTransferExport implements FromCollection, WithHeadings
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
        $student_courses = DB::table('acd_transcript')
                            ->select(
                                'mstr_department.Department_Id as id_prodi',
                                'acd_student.Nim',
                                'acd_student.Full_Name',
                                'acd_course.Course_Code',
                                'acd_course.Course_Name',
                                'acd_transcript.Sks',
                                'acd_grade_letter.Grade_Letter',
                                'acd_transcript.Weight_Value',
                                'acd_transcript.Course_Code_Transfer',
                                'acd_transcript.Course_Name_Transfer',
                                'acd_transcript.Sks_Transfer',
                                'acd_transcript.Grade_Letter_Transfer',
                                'acd_student.id_registrasi_mahasiswa',
                                'acd_student.Student_Id as id_mahasiswa_local',
                                'acd_course.Course_Id as id_matkul_local',
                                'acd_course.Feeder_Id as id_matkul'
                                )
                            ->leftJoin('acd_course','acd_transcript.Course_Id','=','acd_course.Course_Id')
                            ->leftJoin('acd_student','acd_transcript.Student_Id','=','acd_student.Student_Id')
                            ->leftJoin('mstr_department','acd_student.Department_Id','=','mstr_department.Department_Id')
                            ->leftJoin('acd_grade_letter','acd_transcript.Grade_Letter_Id','=','acd_grade_letter.Grade_Letter_Id')
                            ->where([
                                ['mstr_department.Department_Id',$prodi],
                                ['acd_transcript.Term_Year_Id',$m_term_year->Term_Year_Id]
                            ])
                            ->get();
        //
        $i = 0;
        $items = [];
        foreach($student_courses as $d){
            $items[$i]['id_prodi'] = $d->id_prodi;
            $items[$i]['term_year_id'] = $m_term_year->Term_Year_Id;
            $items[$i]['id_registrasi_mahasiswa'] = $d->id_registrasi_mahasiswa;
            $items[$i]['id_mahasiswa_local'] = $d->id_mahasiswa_local;
            $items[$i]['nipd'] = $d->Nim;
            $items[$i]['nm_pd'] = $d->Full_Name;
            $items[$i]['id_matkul_local'] = $d->id_matkul_local;
            $items[$i]['id_matkul'] = $d->id_matkul;
            $items[$i]['kode_mk'] = $d->Course_Code;
            $items[$i]['nm_mk'] = $d->Course_Name;
            $items[$i]['sks_diakui'] = $d->Sks;
            $items[$i]['nilai_huruf_diakui'] = $d->Grade_Letter;
            $items[$i]['nilai_angka_diakui'] = $d->Weight_Value;
            $items[$i]['kode_mk_asal'] = $d->Course_Code_Transfer;
            $items[$i]['nm_mk_asal'] = $d->Course_Name_Transfer;
            $items[$i]['sks_asal'] = $d->Sks_Transfer;
            $items[$i]['nilai_huruf_asal'] = $d->Grade_Letter_Transfer;

            $i++;
        }

        return collect($items);

    }

    public function headings(): array
    {
        return [
            'id_prodi',
            'term_year_id',
            'id_registrasi_mahasiswa',
            'id_mahasiswa_local',
            'nipd',
            'nm_pd',
            'id_matkul_local',
            'id_matkul',
            'kode_mk',
            'nm_mk',
            'sks_diakui',
            'nilai_huruf_diakui',
            'nilai_angka_diakui',
            'kode_mk_asal',
            'nm_mk_asal',
            'sks_asal',
            'nilai_huruf_asal'
        ];
    }

}
