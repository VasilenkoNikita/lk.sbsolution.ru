<?php

namespace App\Imports;

use App\Models\TypesOfTaxes;
use App\Models\Client;
use App\Models\Group;
use App\Models\ClientEmail;
use App\Models\ClientPhone;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;

class ClientsImport implements OnEachRow
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
            $type_of_ownership = "";

            if (strpos($row[0], ' ')) {
                $pieces = explode(" ", $row[0]);
                $type_of_ownership = $pieces[0];
            }

            if (isset($row[6])) {
                $start_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[6]);
            }else{
                $start_date = null;
            }

            $client = Client::firstOrCreate([
                'type_of_ownership' => $type_of_ownership,
                'organization' => $row[0],
                'name' => $row[1],
                'inn' => $row[2],
                'services_provided' => $row[3],
                'access' => $row[4],
                'reporting_system' => $row[5],
                'client_active' => 1,
                'start_date' => $start_date,
                'history_cno' => $row[10],
                'region' => $row[11],
                'type_of_company' => $row[12],
                'accountant' => $row[13],
                'assistant' => $row[14]
            ]);

            if (strpos($row[7], ',')) {
                $pieces = explode(", ", $row[7]);
                foreach ($pieces as $piece) {
                    $client->phones()->create([
                        'phone' => $piece,
                    ]);
                }
            } else {
                $client->phones()->create([
                    'phone' => $row[7],
                ]);
            }

            if (strpos($row[8], ',')) {
                $pieces = explode(", ", $row[8]);
                foreach ($pieces as $piece) {
                    $client->emails()->create([
                        'email' => $piece,
                    ]);
                }
            } else {
                $client->emails()->create([
                    'email' => $row[8],
                ]);
            }

            $typeOfTax = TypesOfTaxes::where('alias', mb_strtolower($row[9]))->get();

            $client->typeOfTaxes()->sync([$typeOfTax[0]->id]);



            if($row[13] === ""){
                $client->groups()->sync([2]);
            }else{
                Group::firstOrCreate([
                    'name' => $row[13],
                ]);
            }
            $group = Group::where('name', $row[13])->get();

            $client->groups()->sync([$group[0]->id, 2]);


        }
    }
}
