<?php

namespace App\Exports;

use Maatwebsite3\Excel\Concerns\FromCollection;
use Maatwebsite3\Excel\Concerns\WithHeadings;

class LogErrorExport implements FromCollection, WithHeadings
{
    protected $data;
    
    public function __construct($param)
    {
        $this->data = $param; 
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $i = 0;
        $std = [];
        foreach ($this->data as $value) {
            foreach ($value['data'] as $val) {
                $std[$i]['Error'] = $val;
                $i++;
            }
        }
        return collect($std);
    }
    public function headings(): array
    {
        return [
            'Error',
        ];
    }
}
