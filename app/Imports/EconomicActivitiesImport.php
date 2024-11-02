<?php

namespace App\Imports;

use App\Models\EconomicActivities;
use App\Models\SectionActivity;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;

class EconomicActivitiesImport implements OnEachRow
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


            $section = SectionActivity::where('section_code', $row[2])->get();
            EconomicActivities::firstOrCreate([
                'type_economic_activity' => $row[0],
                'code_economic_activity' => $row[1],
                'section_economic_activity_id' => $section[0]->id,
                'section_description' => $row[3],
            ]);
        }
    }
}
