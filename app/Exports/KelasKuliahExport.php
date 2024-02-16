<?php

namespace App\Exports;

use Maatwebsite3\Excel\Concerns\FromCollection;
use Maatwebsite3\Excel\Concerns\WithHeadings;
use Maatwebsite3\Excel\Concerns\Exportable;
use Illuminate\Support\Facades\Input;

use DB;

class KelasKuliahExport implements FromCollection, WithHeadings
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

        // $term_year = DB::table('mstr_term_year')->where('Term_Year_Id',$default_term_year)->first();

        $data = DB::table('acd_offered_course');

        foreach ($prodi as $value) {
            // dump($value);
            $data  = $data->orwhere(['acd_offered_course.Department_Id'=> $value])->where('acd_offered_course.Term_Year_Id',$default_term_year);
        }
        $data = $data->leftjoin('mstr_department','acd_offered_course.Department_Id','=','mstr_department.Department_Id')
        ->leftjoin('mstr_term_year','acd_offered_course.Term_Year_Id','=','mstr_term_year.Term_Year_Id')
        ->leftjoin('acd_course','acd_offered_course.Course_Id','=','acd_course.Course_Id')
        ->leftjoin('mstr_class','acd_offered_course.Class_Id','=','mstr_class.Class_Id')
        ->select(
            'mstr_department.Feeder_Id as Department_Feeder_Id',
            'mstr_term_year.Feeder_Id as TermYear_Feeder_Id',
            'acd_course.Feeder_Id as Course_Feeder_Id',
            'acd_course.Course_Name',
            'mstr_class.Class_Name'
            )
        ->orderby('acd_offered_course.Department_Id','asc')
        ->get();

        $i = 0;
        foreach($data as $d){
            $std[$i]['TermYear_Feeder_Id'] = $d->TermYear_Feeder_Id;
            $std[$i]['Department_Feeder_Id'] = $d->Department_Feeder_Id;
            $std[$i]['Course_Feeder_Id'] = $d->Course_Feeder_Id;
            $std[$i]['Course_Name'] = $d->Course_Name;
            $std[$i]['Class_Name'] = "'".$d->Class_Name."'";
            $std[$i]['Bahasan'] = "";
            $i++;
        }

        return collect($std);
    }

    public function headings(): array
    {
        return [
            'Tahun Ajaran',
            'Jenjang Prodi',
            'Kode MK',
            'Nama MK',
            'Nama Kelas',
            'Pokok Bahasan',
        ];
    }
}
