<?php

namespace App\Imports;

use App\Models\EconomicActivities;
use App\Models\SubActivity;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;

class SubActivityImport implements OnEachRow
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


            $section = EconomicActivities::where('code_economic_activity',  substr($row[0], 0, 2))->get();
            SubActivity::firstOrCreate([
                'code' => $row[0],
                'name' => $row[1],
                'description' => $row[2],
                'economic_activity_id' => $section[0]->id,
            ]);
        }
    }
}
