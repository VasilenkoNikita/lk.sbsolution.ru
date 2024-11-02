<?php

namespace App\Imports;

use App\Models\SectionActivity;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;

class SectionActivityImport implements OnEachRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function onRow(Row $row)
    {
        if (isset($row[0])) {
            $rowIndex = $row->getIndex();
            $row = $row->toArray();

            SectionActivity::firstOrCreate([
                'section_code' => $row[0],
                'section_name' => $row[1],
                'section_description' => $row[2],
            ]);
        }
    }
}
