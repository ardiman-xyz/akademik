<?php

namespace App\Exports;

use Maatwebsite3\Excel\Concerns\FromQuery;
use App\Mhs_krs;
use Illuminate\Contracts\View\View;
use Maatwebsite3\Excel\Concerns\FromView;
use Maatwebsite3\Excel\Concerns\Exportable;
use Maatwebsite3\Excel\Concerns\ShouldAutoSize;
use DB;

class Daftar_mhskrsnonaktif implements FromView, ShouldAutoSize
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
        return view('laporan_mhskrs.exportnonaktif', [
            'Daftar_mhskrsnonaktif' => Mhs_krs::
            join('mstr_class_program','acd_student.Class_Prog_Id','=','mstr_class_program.Class_Prog_Id')
            ->whereNotIn('acd_student.Student_Id',$std_krs)
            ->whereNotIn('acd_student.Student_Id',$std_out)
            ->whereNotIn('acd_student.Student_Id',$std_gradfinal)
            ->where('acd_student.Department_Id', $this->id_prod)->get()
            ]);
      }elseif ($this->prog_kelas==0 && $this->angkatan!=0) {
        return view('laporan_mhskrs.exportnonaktif', [
            'Daftar_mhskrsnonaktif' => Mhs_krs::
            join('mstr_class_program','acd_student.Class_Prog_Id','=','mstr_class_program.Class_Prog_Id')
            ->whereNotIn('acd_student.Student_Id',$std_krs)
            ->whereNotIn('acd_student.Student_Id',$std_out)
            ->whereNotIn('acd_student.Student_Id',$std_gradfinal)
            ->where('acd_student.Department_Id', $this->id_prod)
            ->where('acd_student.Entry_Year_Id', $this->angkatan)->get()
            ]);
      }elseif ($this->prog_kelas!=0 && $this->angkatan==0) {
        return view('laporan_mhskrs.exportnonaktif', [
            'Daftar_mhskrsnonaktif' => Mhs_krs::
            join('mstr_class_program','acd_student.Class_Prog_Id','=','mstr_class_program.Class_Prog_Id')
            ->whereNotIn('acd_student.Student_Id',$std_krs)
            ->whereNotIn('acd_student.Student_Id',$std_out)
            ->whereNotIn('acd_student.Student_Id',$std_gradfinal)
            ->where('acd_student.Department_Id', $this->id_prod)
            ->where('acd_student.Class_Prog_Id', $this->prog_kelas)->get()
            ]);
      }else{
        return view('laporan_mhskrs.exportnonaktif', [
            'Daftar_mhskrsnonaktif' => Mhs_krs::
            join('mstr_class_program','acd_student.Class_Prog_Id','=','mstr_class_program.Class_Prog_Id')
            ->whereNotIn('acd_student.Student_Id',$std_krs)
            ->whereNotIn('acd_student.Student_Id',$std_out)
            ->whereNotIn('acd_student.Student_Id',$std_gradfinal)
            ->where('acd_student.Department_Id', $this->id_prod)
            ->where('acd_student.Class_Prog_Id', $this->prog_kelas)
            ->where('acd_student.Entry_Year_Id', $this->angkatan)->get()
            ]);
      }
    }
}
?>
