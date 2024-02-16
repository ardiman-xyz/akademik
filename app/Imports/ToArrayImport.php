<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite3\Excel\Concerns\ToCollection;
use Maatwebsite3\Excel\Concerns\WithHeadingRow;

class ToArrayImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        return $collection;
    }
}

