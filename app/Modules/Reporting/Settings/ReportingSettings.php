<?php

declare(strict_types=1);

namespace App\Modules\Reporting\Settings;

use App\Models\Payment;
use App\Models\Report;
use App\Models\User;
use App\Modules\Reporting\Filters\DateIntervalFilter;
use App\Modules\Reporting\Filters\ReportingFilter;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportingSettings{

    /**
     * @var bool
     */
    public bool $filterEvents = false;

    public $scroll = false;

    public $applySettings = false;
    /**
     * @return Report | Payment
     */
    public function setDefaultSettings(Request $request): void
    {
        //Перенести часть настроек в отдельный модуль
        if ($request->session()->get('scroll')){
            $this->scroll = 'true';
        }else{
            $this->scroll = false;
        }

        if (is_null($request->request->get('group_'))) {
            //Подгружаем все группы текущего пользователя
            $userGroups = auth()->user()->load('groups');

            foreach ($userGroups->groups as $group) {
                $groups[] = $group->id;
            }
            $request->request->add(['group' => $groups]);
        }else{
            $request->request->add(['group' => array_map('intval', explode(",", $request->request->get('group_')))]);
        }

        //Перенести в модуль настройки
        if (empty($request->request->get('reporting'))) {
            $request->request->add(['reporting' => 'Отчеты']);
        }

        //Перенести с модуль настройки
        if (is_null($request->request->get('reportingEventDisplay'))) {
            $request->request->add(['reportingEventDisplay' => 1]);
            $this->filterEvents = true;
        }

        //Перенести с модуль настройки
        if((int) $request->request->get('reportingEventDisplay') === 0){
            $this->filterEvents = false;
        }

        //Перенести с модуль настройки
        if((int) $request->request->get('reportingEventDisplay') === 1){
            $this->filterEvents = true;
        }

        //Перенести в модуль настройки
        if (empty($request->request->get('rangeDate'))) {
            $dateIn['start'] = date('Y-m-01 H:i:s');
            $dateIn['end'] = date("Y-m-01 H:i:s", strtotime("+3 month"));

            $request->request->add(['rangeDate' => $dateIn]);
        }

        $this->applySettings = true;
    }


}
