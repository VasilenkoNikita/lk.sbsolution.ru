<?php

namespace App\Orchid\Screens;

use App\Models\History;
use App\Models\Client;
use App\Models\TypesOfTaxes;
use App\Models\User;
use App\Orchid\Layouts\DateStatsListener;
use App\Orchid\Layouts\UserStatistics\CnoPieLayout;
use App\Orchid\Layouts\UserStatistics\UserLineLayout;
use App\Orchid\Layouts\UserStatistics\UserPieLayout;
use App\Orchid\Layouts\UserStatistics\WorkersBarLayout;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Orchid\Screen\Fields\DateRange;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Action;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class UserStatistics extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Статистика пользователей';

    /**
     * Display header description.
     *
     * @var string|null
     */
    public $description = 'Статистика пользователей';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Request $request): array
    {

        $number_of_days = $request->session()->get('number_of_days') ?? 7;

        $historyUserChanges = History::whereBetween('updated_at', [date("Y-m-d H:i:s", strtotime("-4 days")), date('Y-m-d H:i:s', strtotime("-3 days"))])->get();
        $count =  History::groupBy('user_id')->whereBetween('updated_at', [date("Y-m-d H:i:s", strtotime("-$number_of_days day")), date('Y-m-d H:i:s')])->get();
        $clientsTypeOfOwnership = Client::groupBy('type_of_ownership')->get();
        $cno = TypesOfTaxes::get();

        //Функции для графика истории изменений
        function getUserName($userId){
            $user = User::where('id', $userId)->get();
            return $user[0]->name;
        }

        function getChangesCountValues($userId, $days){
            for ($i = 0, $n = 1; $i <= $days-1; $i++, $n++) {
                $changes = History::whereBetween('updated_at', [date("Y-m-d H:i:s", strtotime("-$n days")), date('Y-m-d H:i:s', strtotime("-$i days"))])->where('user_id', $userId)->get();
                $countChangesOfDay[] = $changes->count();

            }
            return array_reverse($countChangesOfDay);
        }

        function getDaysHistory($days){
            for ($i = 0; $i <= $days-1; $i++) {
                $daysHistory[] = date("d.m.Y", strtotime("-$i days"));
            }
            return array_reverse($daysHistory);
        }

        //Функции для графика Формы собственности
        function getTypeOfOwnershipValues($typeOfOwnership){
            $typeOfOwnershipValues = Client::where('type_of_ownership', $typeOfOwnership)->get();
            return $typeOfOwnershipValues->count();
        }

        //Функции для графика Систем налогооблажения клиентов
        function getCnoValues($cnoId){
            $cnoValues = Client::with('typeOfTaxes')->whereHas('typeOfTaxes', function (Builder $query) use ($cnoId) {
                $query->where('type_of_tax_id', $cnoId);
            });
            return $cnoValues->count();
        }

        //Функции для графика по количесву работников у клиентов
        function getWorkersCountOfTypeOfOwnership($workersOption){
            $clientsTypeOfOwnership = Client::groupBy('type_of_ownership')->get();
            foreach($clientsTypeOfOwnership as $tow) {
            if ($workersOption === 'С работниками'){
                $clients = Client::where('number_of_employees', '>', 0)->where('type_of_ownership', $tow->type_of_ownership)->get();
            }elseif ($workersOption === 'Без работников'){
                $clients = Client::where('number_of_employees', 0)->orWhere('number_of_employees', null)->where('type_of_ownership', $tow->type_of_ownership)->get();
            }elseif ($workersOption === 'Работников > 1'){
                $clients = Client::where('number_of_employees', '>', 1)->where('type_of_ownership', $tow->type_of_ownership)->get();
            }

                $clientsWithWorkersCount[] = $clients->count();
            }

            return $clientsWithWorkersCount;
        }

        function getTypeOfOwnership(){
            $clientsTypeOfOwnership = Client::groupBy('type_of_ownership')->get();
            foreach($clientsTypeOfOwnership as $tow) {
                $typeOfOwnershipListLabels[] = $tow->type_of_ownership;
            }
            return $typeOfOwnershipListLabels;
        }

        $typeOfOwnershipList['name'] = 'Форма собственности';
        $clientsCno['name'] = 'Система налогооблажения клиентов';

        foreach($cno as $c){
            $cnoListValues[] = getCnoValues($c->id);
            $cnoListLabels[] = $c->name;
        }


        foreach($count as $user){
            $users[] = ['name' =>  getUserName($user->user_id), 'values' => getChangesCountValues($user->user_id, $number_of_days), 'labels' => getDaysHistory($number_of_days)];
        }


        foreach($clientsTypeOfOwnership as $typeOfOwnership){
            $typeOfOwnershipListValues[] = getTypeOfOwnershipValues($typeOfOwnership->type_of_ownership);
            $typeOfOwnershipListLabels[] = $typeOfOwnership->type_of_ownership;
        }



        $workersStatsInitData[] = ['name' => 'С работниками'];
        $workersStatsInitData[] = ['name' => 'Без работников'];
        $workersStatsInitData[] = ['name' => 'Работников > 1'];


        foreach($workersStatsInitData as $key => $val){
            $workersStatsList[] = ['name' => $val['name'], 'values' => getWorkersCountOfTypeOfOwnership($val['name']), 'labels' => getTypeOfOwnership()];
        }


        $typeOfOwnershipList['values'] = $typeOfOwnershipListValues;
        $typeOfOwnershipList['labels'] = $typeOfOwnershipListLabels;

        $clientsCno['values'] = $cnoListValues;
        $clientsCno['labels'] = $cnoListLabels;

        return [
            'UserChangesActivity' => $users,
            'TypeOfOwnershipStats' => [$typeOfOwnershipList],
            'cnoStats' => [$clientsCno],
            'workersStats' => $workersStatsList,
            'number_of_days' => $number_of_days,
        ];


    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return [];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): array
    {
        return [
            DateStatsListener::class,
            Layout::wrapper('wrappers/statistics', [
                'typeOfOwnershipStats' => UserPieLayout::class,
                'cno' => CnoPieLayout::class,
                'workers' => WorkersBarLayout::class,
            ]),
        ];
    }

    public function asyncDateStats($number_of_days, Request $request)
    {
        session(['number_of_days' => $number_of_days]);
        $number_of_days = $request->session()->get('number_of_days') ?? 7;

        $historyUserChanges = History::whereBetween('updated_at', [date("Y-m-d H:i:s", strtotime("-4 days")), date('Y-m-d H:i:s', strtotime("-3 days"))])->get();
        $count =  History::groupBy('user_id')->whereBetween('updated_at', [date("Y-m-d H:i:s", strtotime("-$number_of_days day")), date('Y-m-d H:i:s')])->get();
        $clientsTypeOfOwnership = Client::groupBy('type_of_ownership')->get();
        $cno = TypesOfTaxes::get();

        //Функции для графика истории изменений
        function getUserName($userId){
            $user = User::where('id', $userId)->get();
            return $user[0]->name;
        }

        function getChangesCountValues($userId, $days){
            for ($i = 0, $n = 1; $i <= $days-1; $i++, $n++) {
                $changes = History::whereBetween('updated_at', [date("Y-m-d H:i:s", strtotime("-$n days")), date('Y-m-d H:i:s', strtotime("-$i days"))])->where('user_id', $userId)->get();
                $countChangesOfDay[] = $changes->count();

            }
            return array_reverse($countChangesOfDay);
        }

        function getDaysHistory($days){
            for ($i = 0; $i <= $days-1; $i++) {
                $daysHistory[] = date("d.m.Y", strtotime("-$i days"));
            }
            return array_reverse($daysHistory);
        }

        foreach($count as $user){
            $users[] = ['name' =>  getUserName($user->user_id), 'values' => getChangesCountValues($user->user_id, $number_of_days), 'labels' => getDaysHistory($number_of_days)];
        }

        return [
            'UserChangesActivity' => $users,
            'number_of_days' => $number_of_days,
        ];
    }
}
