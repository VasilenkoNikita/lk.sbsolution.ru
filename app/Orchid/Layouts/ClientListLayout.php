<?php

namespace App\Orchid\Layouts;

use App\Models\Client;
use App\Models\ClientUserSettings;
use App\Models\UserColor;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;

class ClientListLayout extends Table
{
    /**
     * Data source.
     *
     * @var string
     */
    public $target = 'clients';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        $dataarr = [
            'organization' =>  TD::make('organization', 'Организация')
                ->filter(TD::FILTER_TEXT)
                ->width('260px')
                ->sort()
                ->render(function (Client $client) {
                    $colors = UserColor::where('user_id', Auth::user()->id)->orderby('position')->get();
                    //dd(Auth::user()->id);
                    $color = !$client->userColors->isEmpty() ? $client->userColors[0]->color : '';
                    $colorid = !$client->userColors->isEmpty() ? $client->userColors[0]->id : '';
                    $infotext = "";
                    $badge = "";
                    $notification = false;
                    $thisdate = date('Y-m-01');
                    if(!$client->bankStatements->isEmpty()) {
                        foreach ($client->bankStatements as $bankStatement) {
                            if($bankStatement->active == 1) {
                                if (strtotime($bankStatement->bank_statement_processing_date) < strtotime(date('Y-m-d', strtotime($thisdate . " - 1 day")))) {
                                    $infotext .= "Не обновлена информация по банковской выписке - $bankStatement->checking_account" .
                                        "<br /><b>Дата обработки выписки " . date('d-m-Y', strtotime($bankStatement->bank_statement_processing_date)) . "</b><br />---------------------------<br />";
                                    $badge = "!";
                                    $notification = true;
                                }
                            }
                        }
                        if(!$client->marketplaces->isEmpty()) {
                            foreach ($client->marketplaces as $marketplace) {
                                if ($marketplace->activity == 1) {
                                    if (strtotime($marketplace->marketplace_processing_date) < strtotime(date('Y-m-d', strtotime($thisdate . " - 1 day")))) {
                                        $infotext .= "Не обновлена информация по маркетплейсу - $marketplace->marketplace_name" .
                                            "<br /><b>Дата обработки " . date('d-m-Y', strtotime($marketplace->marketplace_processing_date)) . "</b><br />---------------------------<br />";
                                        $badge = "!";
                                        $notification = true;
                                    }
                                }
                            }
                        }
                    }

                    $target = '_blank';
                    if(Auth::user()->name === 'elena.g') {
                        $target = '_self';
                    }

                   $modal = ModalToggle::make('🖌')
                        ->modal('asyncModalColorsChoose')
                        ->id((string)  $client->id)
                        ->modalTitle('Укажите цвет')
                        ->method('methodForChooseColorModal')
                        ->asyncParameters([
                            'client' => $client->id,
                            'colors' => $colors,
                            'color' => $colorid
                        ]);

                    return view('colorsSettings/td', [
                        'client' => $client,
                        'modal' => $modal,
                        'color' => $color,
                        'infotext' => $infotext,
                        'badge' => $badge,
                        'notification' => $notification,
                        'target' => $target
                    ]);

                }),
            'name' => TD::make('name', 'Имя клиента')
                ->width('150px')
                ->sort()
                ->filter(TD::FILTER_TEXT)
                ->render(function (Client $client) {
                    return Link::make($client->name)
                        ->route('platform.clients.edit', $client->id);
                }),
            'emails.email.' => TD::make('emails.email.', 'Email')
                ->width('190px')
                ->render(function (Client $client) {
                    $emaillist = "";
                    if(!$client->emails->isEmpty()) {
                        foreach ($client->emails as $email) {
                            $emaillist .= "<a href=\"mailto:".$email->email."\" target=\"_blank\">".$email->email."</a><br>";
                        }
                        return $emaillist;
                    }else{
                        return "Не указан";
                    }
                }),
            'emails.phone.' => TD::make('emails.phone.', 'Телефон')
                ->width('130px')
                ->render(function (Client $client) {
                    $phonelist = "";
                    if(!$client->phones->isEmpty()) {
                        foreach ($client->phones as $phone) {
                            $phonelist .= $phone->phone."<br>";
                        }
                        return $phonelist;
                    }

                    return "Не указан";
                }),
            'keeping_accounting' => TD::make('keeping_accounting', 'Ведение учета')
                ->width('150px')
                ->sort()
                ->filter(TD::FILTER_TEXT),
            'reporting_system' => TD::make('reporting_system', 'Система сдачи отчетности')
                ->width('150px')
                ->sort(),
            'typeOfTaxes.name.' => TD::make('typeOfTaxes.name.', 'СНО')
                ->width('120px')
                ->render(function (Client $client) {
                    return $client->typeOfTaxes[0]->name;
                }),
            'inn' => TD::make('inn', 'ИНН')
                ->width('115px')
                ->sort()
                ->filter(TD::FILTER_TEXT),
            'region' => TD::make('region', 'Регион')
                ->width('100px')
                ->sort(),
            'certificate_end_date' => TD::make('certificate_end_date', 'Срок действия ЭЦП')
                ->width('105px')
                ->sort()
                ->render(function (Client $client) {
                    if($client->certificate_end_date){
                        return date('d-m-Y', strtotime($client->certificate_end_date));
                    }
                    return $client->certificate_end_date;
                }),
            'bankStatements' => TD::make('bankStatements', 'Банковские выписки')
                ->width('250px')
                ->sort()
                ->render(function (Client $client) {
                    $bankStatementsList = "";
                    if(!$client->bankStatements->isEmpty()) {
                        foreach ($client->bankStatements as $bankStatement) {
                            $bankStatementsList .= $bankStatement->checking_account." - ".date('d-m-Y', strtotime($bankStatement->bank_statement_processing_date))."<br>";
                        }
                        return $bankStatementsList;
                    }
                    return "Данные отсутствуют";
                }),

            'cashDesks.date_of_cash_processing' => TD::make('cashDesks.date_of_cash_processing', 'Касса (Дата обработки наличных)')
                ->width('250px')
                ->sort()
                ->render(function (Client $client) {
                    $cashDesksList = "";
                    if(!$client->cashDesks->isEmpty()) {
                        foreach ($client->cashDesks as $cashDesk) {
                            $cashDesksList .= $cashDesk->date_of_cash_processing."<br>";
                        }
                        return $cashDesksList;
                    }
                    return "Данные отсутствуют";
                }),

            'comment' => TD::make('comment', 'Комментарий')
                ->width('250px')
                ->sort()
                ->render(function (Client $client) {
                    return TextArea::make('client.comment')
                        ->value($client->comment ?? "")
                        ->rows(3)
                        ->id("textarea$client->id");
                }),
        ];

        $settings = ClientUserSettings::where('user_id', Auth::user()->id)->orderby('position')->get();

        foreach ($settings as $setting) {
           $dataarr2[$setting->row_name] = $setting->position;
        }
        //dd($dataarr2);


            asort($dataarr2);

            uksort ($dataarr, function($l, $r) use ($dataarr2){
                 return $dataarr2[$l] > $dataarr2[$r];
          });


        return $dataarr;
    }
}
