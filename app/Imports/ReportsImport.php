<?php

namespace App\Imports;

use App\Models\Report;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;

class ReportsImport implements OnEachRow
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

            if (isset($row[0])) {
                $date_report = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[0]);
            }else{
                $date_report = null;
            }

            Report::firstOrCreate([
                'report_date' => $date_report,
                'report_name' => $row[1]
            ]);
        }
    }
}
