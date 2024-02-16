<?php

namespace App\Exports;

use Maatwebsite3\Excel\Concerns\FromQuery;
use App\Mhs_krs;
use Illuminate\Contracts\View\View;
use Maatwebsite3\Excel\Concerns\FromView;
use Maatwebsite3\Excel\Concerns\Exportable;
use Maatwebsite3\Excel\Concerns\ShouldAutoSize;
use Maatwebsite3\Excel\Concerns\WithColumnFormatting;
use Maatwebsite3\Excel\Concerns\WithEvents;
use Maatwebsite3\Excel\Events\AfterSheet;

class Daftar_mhskrs implements FromView, ShouldAutoSize, WithEvents
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

      if($this->prog_kelas==0 && $this->angkatan==0){
        return view('laporan_mhskrs.export', [
            'Daftar_mhskrs' => Mhs_krs::
            join('acd_student_krs','acd_student.Student_Id','=','acd_student_krs.Student_Id')
            ->join('mstr_class_program','acd_student.Class_Prog_Id','=','mstr_class_program.Class_Prog_Id')
            ->where('Term_Year_Id', $this->term_year)
            ->where('Department_Id', $this->id_prod)->groupBy('acd_student.Student_Id')->get()
            ]);
      }elseif ($this->prog_kelas==0 && $this->angkatan!=0) {
        return view('laporan_mhskrs.export', [
            'Daftar_mhskrs' => Mhs_krs::
            join('acd_student_krs','acd_student.Student_Id','=','acd_student_krs.Student_Id')
            ->join('mstr_class_program','acd_student.Class_Prog_Id','=','mstr_class_program.Class_Prog_Id')
            ->where('Term_Year_Id', $this->term_year)
            ->where('Entry_Year_Id', $this->angkatan)
            ->where('Department_Id', $this->id_prod)->groupBy('acd_student.Student_Id')->get()
            ]);
      }elseif ($this->prog_kelas!=0 && $this->angkatan==0) {
        return view('laporan_mhskrs.export', [
            'Daftar_mhskrs' => Mhs_krs::
            join('acd_student_krs','acd_student.Student_Id','=','acd_student_krs.Student_Id')
            ->join('mstr_class_program','acd_student.Class_Prog_Id','=','mstr_class_program.Class_Prog_Id')
            ->where('Term_Year_Id', $this->term_year)
            ->where('acd_student.Class_Prog_Id', $this->prog_kelas)
            ->where('Department_Id', $this->id_prod)->groupBy('acd_student.Student_Id')->get()
            ]);
      }else{
        return view('laporan_mhskrs.export', [
            'Daftar_mhskrs' => Mhs_krs::
            join('acd_student_krs','acd_student.Student_Id','=','acd_student_krs.Student_Id')
            ->join('mstr_class_program','acd_student.Class_Prog_Id','=','mstr_class_program.Class_Prog_Id')
            ->where('Term_Year_Id', $this->term_year)
            ->where('acd_student.Class_Prog_Id', $this->prog_kelas)
            ->where('Entry_Year_Id', $this->angkatan)
            ->where('Department_Id', $this->id_prod)->groupBy('acd_student.Student_Id')->get()
            ]);
      }
    }

    public function registerEvents(): array
    {
      return [
          AfterSheet::class=> function(AfterSheet $event) {
              $cellRange = 'A1:D1'; // All headers
              $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
              $event->sheet->getDelegate()->getStyle($cellRange)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
              $event->sheet->getStyle('A3:D3')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFe6f7ff');
          },
      ];
    }

}
?>
