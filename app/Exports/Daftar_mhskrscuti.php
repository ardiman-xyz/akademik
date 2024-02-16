<?php

namespace App\Exports;

use Maatwebsite3\Excel\Concerns\FromQuery;
use App\Mhs_krs;
use Illuminate\Contracts\View\View;
use Maatwebsite3\Excel\Concerns\FromView;
use Maatwebsite3\Excel\Concerns\Exportable;
use Maatwebsite3\Excel\Concerns\ShouldAutoSize;
use DB;

class Daftar_mhskrscuti implements FromView, ShouldAutoSize
{
  use Exportable;

  // public function view(): View
  //   {
  //       return view('acd_course.daftar_mk', [
  //           'Daftar_mk' => Daftar_mk::all()
  //       ]);
  //   }

    private $term_year;
    private $prog_kelas;
    private $angkatan;
    private $id_prod;




    public function __construct(string $term_year, $prog_kelas, $angkatan,$id_prod)
    {
         $this->term_year = $term_year;
         $this->prog_kelas = $prog_kelas;
         $this->angkatan = $angkatan;
         $this->id_prod = $id_prod;
    }

    public function view(): View
    {
      $std_krs = DB::table('acd_student_krs')->select('Student_Id');
      $std_out = DB::table('acd_student_out')->select('Student_Id');
      $std_gradfinal = DB::table('acd_graduation_final')->select('Student_Id');

      if($this->prog_kelas==0 && $this->angkatan==0){
        return view('laporan_mhskrs.exportcuti', [
            'Daftar_mhskrscuti' => Mhs_krs::
            leftjoin('acd_student_vacation','acd_student.Student_Id','=','acd_student_vacation.Student_Id')
            ->leftjoin('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_student_vacation.Term_Year_Id')
            ->leftjoin('acd_vacation_reason','acd_vacation_reason.Vacation_Reason_Id','=','acd_student_vacation.Vacation_Reason_Id')
            ->join('mstr_class_program','acd_student.Class_Prog_Id','=','mstr_class_program.Class_Prog_Id')
            ->where('acd_student_vacation.Term_Year_Id', $this->term_year)
            ->where('acd_student.Department_Id', $this->id_prod)
            ->where('acd_student.Class_Prog_Id', $this->prog_kelas)
            ->where('acd_student.Entry_Year_Id', $this->angkatan)
            ->groupBy('Student_Vacation_Id')
            ->orderBy('Student_Vacation_Id', 'asc')->get()
            ]);
      }elseif ($this->prog_kelas==0 && $this->angkatan!=0) {
        return view('laporan_mhskrs.exportcuti', [
            'Daftar_mhskrscuti' => Mhs_krs::
            leftjoin('acd_student_vacation','acd_student.Student_Id','=','acd_student_vacation.Student_Id')
            ->leftjoin('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_student_vacation.Term_Year_Id')
            ->leftjoin('acd_vacation_reason','acd_vacation_reason.Vacation_Reason_Id','=','acd_student_vacation.Vacation_Reason_Id')
            ->join('mstr_class_program','acd_student.Class_Prog_Id','=','mstr_class_program.Class_Prog_Id')
            ->where('acd_student_vacation.Term_Year_Id', $this->term_year)
            ->where('acd_student.Department_Id', $this->id_prod)
            ->where('acd_student.Entry_Year_Id', $this->angkatan)
            ->groupBy('Student_Vacation_Id')
            ->orderBy('Student_Vacation_Id', 'asc')->get()
            ]);
      }elseif ($this->prog_kelas!=0 && $this->angkatan==0) {
        return view('laporan_mhskrs.exportcuti', [
            'Daftar_mhskrscuti' => Mhs_krs::
            leftjoin('acd_student_vacation','acd_student.Student_Id','=','acd_student_vacation.Student_Id')
            ->leftjoin('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_student_vacation.Term_Year_Id')
            ->leftjoin('acd_vacation_reason','acd_vacation_reason.Vacation_Reason_Id','=','acd_student_vacation.Vacation_Reason_Id')
            ->join('mstr_class_program','acd_student.Class_Prog_Id','=','mstr_class_program.Class_Prog_Id')
            ->where('acd_student_vacation.Term_Year_Id', $this->term_year)
            ->where('acd_student.Department_Id', $this->id_prod)
            ->where('acd_student.Class_Prog_Id', $this->prog_kelas)
            ->groupBy('Student_Vacation_Id')
            ->orderBy('Student_Vacation_Id', 'asc')->get()
            ]);
      }else{
        return view('laporan_mhskrs.exportcuti', [
            'Daftar_mhskrscuti' => Mhs_krs::
            leftjoin('acd_student_vacation','acd_student.Student_Id','=','acd_student_vacation.Student_Id')
            ->leftjoin('mstr_term_year','mstr_term_year.Term_Year_Id','=','acd_student_vacation.Term_Year_Id')
            ->leftjoin('acd_vacation_reason','acd_vacation_reason.Vacation_Reason_Id','=','acd_student_vacation.Vacation_Reason_Id')
            ->join('mstr_class_program','acd_student.Class_Prog_Id','=','mstr_class_program.Class_Prog_Id')
            ->where('acd_student_vacation.Term_Year_Id', $this->term_year)
            ->where('acd_student.Department_Id', $this->id_prod)
            ->where('acd_student.Class_Prog_Id', $this->prog_kelas)
            ->where('acd_student.Entry_Year_Id', $this->angkatan)
            ->groupBy('Student_Vacation_Id')
            ->orderBy('Student_Vacation_Id', 'asc')->get()
            ]);
      }
    }
}
?>
