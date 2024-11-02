<?php

namespace App\Imports;

use App\Models\Payment;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;

class PaymentsImport implements OnEachRow
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
                $date_payment = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[0]);
            }else{
                $date_payment = null;
            }

            Payment::firstOrCreate([
                'payment_date' => $date_payment,
                'payment_name' => $row[1]
            ]);
        }
    }
}
