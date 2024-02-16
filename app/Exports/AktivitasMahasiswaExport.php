<?php

namespace App\Exports;

use Maatwebsite3\Excel\Concerns\FromCollection;
use Maatwebsite3\Excel\Concerns\WithHeadings;
use Maatwebsite3\Excel\Concerns\Exportable;
use Illuminate\Support\Facades\Input;

use DB;

class AktivitasMahasiswaExport implements FromCollection, WithHeadings
{
    use Exportable;
    protected $param;
    protected $default_term_year;
    
    public function __construct($param,$default_term_year)
    {
        $this->prodi = $param; 
        $this->default_term_year = $default_term_year; 
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
    }
}
