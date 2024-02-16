<?php

namespace App\Exports;

use Maatwebsite3\Excel\Concerns\FromQuery;
use App\Daftar_mk;
use Illuminate\Contracts\View\View;
use Maatwebsite3\Excel\Concerns\FromView;
use Maatwebsite3\Excel\Concerns\Exportable;
use Maatwebsite3\Excel\Concerns\ShouldAutoSize;

class Daftar_matakuliah implements FromView, ShouldAutoSize
{
  use Exportable;

  // public function view(): View
  //   {
  //       return view('acd_course.daftar_mk', [
  //           'Daftar_mk' => Daftar_mk::all()
  //       ]);
  //   }

    private $query;
    private $search;

    public function __construct(string $query, $search)
    {
         $this->query = $query;
         $this->search = $search;
    }

    public function view(): View
    {
        return view('acd_course.daftar_mk', [
            'Daftar_mk' => Daftar_mk::join('mstr_department', 'mstr_department.Department_Id','=','acd_course.Department_Id')
            ->join('acd_course_type', 'acd_course_type.Course_Type_Id','=','acd_course.Course_Type_Id')
            ->where('acd_course.Department_Id', $this->query)
            ->where(function($query){
              $query->whereRaw("lower(acd_course.Course_Code) like '%" . strtolower($this->search) . "%'");
              $query->orwhereRaw("lower(acd_course.Course_Name) like '%" . strtolower($this->search) . "%'");
            })
            ->get()
        ]);
    }

  // public function __construct(string $name)
  // {
  //     $this->name = $name;
  // }
  // public function query()
  // {
  //     return Daftar_mk::query()
  //     ->join('mstr_department', 'mstr_department.Department_Id','=','acd_course.Department_Id')
  //     ->join('acd_course_type', 'acd_course_type.Course_Type_Id','=','acd_course.Course_Type_Id')
  //     ->where('acd_course.Department_Id', $this->name)
  //     ->select('acd_course.Course_Id','acd_course.Course_Name','acd_course.Course_Name_Eng')
  //     ->orderBy('acd_course.Course_Code', 'asc');
  //
  // }
}
?>
