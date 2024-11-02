<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use App\Models\ReportSubtype;
use App\Models\ReportType;
use App\Orchid\Layouts\Modal\ReportsSubtypesModalLayout;
use App\Orchid\Layouts\Modal\ReportsTypesModalLayout;
use App\Orchid\Layouts\ReportsListLayout;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ReportsListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Список отчетов';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Все отчеты';

    public $cansee = false;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        if(Auth::user()->name === 'natalia.s' || Auth::user()->name === 'anastasia.e' || Auth::user()->name === 'admin' ) {
            $this->cansee = true;
        }

        return [
            'reports' => Report::filters()->defaultSort('report_date')->paginate(30),
            'reportsTypes' => ReportType::get(),
            'reportsSubtypes' => ReportSubtype::get(),
        ];
    }

    /**
     * Button commands.
     *
     * @return Link[]
     */
    public function commandBar(): array
    {
        return [
            ModalToggle::make( "Типы отчетов")
                ->icon('pencil')
                ->modal('reportsTypesModal')
                ->modalTitle('Типы отчетов')
                ->canSee($this->cansee)
                ->method('reportsTypesModal'),

            ModalToggle::make( "Виды отчетов")
                ->icon('pencil')
                ->modal('reportsSubtypesModal')
                ->modalTitle('Виды отчетов')
                ->canSee($this->cansee)
                ->method('reportsSubtypesModal'),

            Link::make('Создать новый отчет')
                ->icon('pencil')
                ->canSee($this->cansee)
                ->route('platform.reports.create'),
        ];
    }

    /**
     * Views.
     *
     * @return Layout[]
     */
    public function layout(): array
    {
        return [
            ReportsListLayout::class,
            Layout::modal('reportsTypesModal', [
                ReportsTypesModalLayout::class,
            ]),
            Layout::modal('reportsSubtypesModal', [
                ReportsSubtypesModalLayout::class,
            ]),
        ];
    }

    public function addIds($data, $source){
        if(!is_null($source)) {
            foreach ($source as $key => $s) {
                isset($data[$key]) ? $data[$key] = ['id' => $s->id] + $data[$key] : false;
            }
        }
        return $data;
    }

    public function reportsTypesModal(Request $request)
    {

        $requestReportsTypesList = [];

        $reportsTypesList = $this->addIds($request->input('reportsTypes'), ReportType::get());
        if ($request->input('reportsTypes')){
            foreach($reportsTypesList as $reportsTypes => $reportType){
                if(isset($reportType["id"])){
                    ReportType::updateOrCreate(['id' => $reportType["id"]], ['name' => $reportType["name"]]);
                    $requestReportsTypesList[] = $reportType["id"];
                }else{
                    $reportNewType = ReportType::updateOrCreate(['name' => $reportType["name"]]);
                    $requestReportsTypesList[] = $reportNewType->id;
                }
            }
        }

        if(!ReportType::get()->isEmpty()) {
            foreach (ReportType::get() as $key => $val) {
                if(!in_array($val->id, $requestReportsTypesList, true)){
                    ReportType::where('id', $val->id)->delete();
                }
            }
        }else{
            ReportType::whereNotNull('name')->delete();
        }

        Toast::info('Вы успешно обновили типы отчетов!');
        return back();
    }

    public function reportsSubtypesModal(Request $request)
    {
        $requestReportsSubtypesList = [];

        $reportsSubtypesList = $this->addIds($request->input('reportsSubtypes'), ReportSubtype::get());
        if ($request->input('reportsSubtypes')){
            foreach($reportsSubtypesList as $reportsSubtypes => $reportSubtype){
                if(isset($reportSubtype["id"])){
                    ReportSubtype::updateOrCreate(['id' => $reportSubtype["id"]], ['name' => $reportSubtype["name"]]);
                    $requestReportsSubtypesList[] = $reportSubtype["id"];
                }else{
                    $reportNewSubtype = ReportSubtype::updateOrCreate(['name' => $reportSubtype["name"]]);
                    $requestReportsSubtypesList[] = $reportNewSubtype->id;
                }
            }
        }
        if(!ReportSubtype::get()->isEmpty()) {
            foreach (ReportSubtype::get() as $key => $val) {
                if(!in_array($val->id, $requestReportsSubtypesList, true)){
                    ReportSubtype::where('id', $val->id)->delete();
                }
            }
        }else{
            ReportSubtype::whereNotNull('name')->delete();
        }

        Toast::info('Вы успешно обновили виды отчетов!');
        return back();
    }
}
