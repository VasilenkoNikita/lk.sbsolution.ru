<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use App\Imports\ReportsImport;
use App\Models\Report;
use App\Models\ReportSubtype;
use App\Models\ReportType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Orchid\Attachment\Models\Attachment;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Switcher;
use Orchid\Screen\Fields\Upload;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Screen\Actions\Link;

class ReportsEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Добавить новый отчет';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Добавить новый отчет';

    /**
     * @var bool
     */
    public $exists = false;


    /**
     * @var bool
     */
    public $cansee = false;

    /**
     * Query data.
     *
     * @param Report $report
     *
     * @return array
     */
    public function query(Report $report): array
    {
        $this->exists = $report->exists;

        if(Auth::user()->name === 'natalia.s' || Auth::user()->name === 'anastasia.e' || Auth::user()->name === 'admin' ) {
            $this->cansee = true;
        }

        if($this->exists){
            $this->name = 'Редактировать отчет';
        }

        return [
            'report' => $report
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
           /* ModalToggle::make(__('Импорт отчетов'))
                ->icon('lock-open')
                ->method('asyncImport')
                ->modal('uploadReports')
                ->title('Импорт отчетов'), */

            Button::make('Создать отчет')
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee($this->cansee),

            Button::make('Обновить')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->cansee),

            Button::make('Удалить')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->cansee),
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
            Layout::rows([

                Input::make('report.report_name')
                    ->title('Название отчета')
                    ->help('Укажите название для отчета'),

                DateTimer::make('report.report_date')
                    ->title('Дата сдачи отчета')
                    ->placeholder('Укажите дату отчета')
                    ->required()
                    ->allowInput(),

                /*
                Input::make('report.type')
                    ->title('Тип отчета')
                    ->help('Укажите тип для отчета'),
                */

                Select::make('report.reportsTypes')
                    ->fromModel(ReportType::class, 'name')
                    ->title('Тип отчета'),

                /*
                Input::make('report.subtype')
                    ->title('Вид отчета')
                    ->help('Укажите вид для отчета'),
                */

                Select::make('report.reportsSubtypes')
                    ->fromModel(ReportSubtype::class, 'name')
                    ->title('Вид отчета'),

                Select::make('report.type_of_ownership')
                    ->options([
                        'Любая' => 'Любая',
                        'ИП'  => 'ИП',
                        'ООО' => 'ООО',
                    ])
                    ->title('Форма собственности')
                    ->help('Укажите форму собственности к которым подходит отчет'),

                Switcher::make('report.visibility')
                    ->sendTrueOrFalse()
                    ->title('Видимость отчета')
                    ->help('Видимость активность отчета'),

                Switcher::make('report.active')
                    ->sendTrueOrFalse()
                    ->title('Активность отчета')
                    ->help('Переключите активность отчета'),

            ]),

            /*Layout::modal('uploadReports', [
                Layout::rows([
                    Upload::make('upload')
                        ->title('Загрузите excel файл с отчетами')
                ]),
            ]),*/
        ];
    }

    /**
     * @param Report  $report
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Report $report, Request $request)
    {
        $report->fill($request->get('report'))->save();
        $report->reportsTypes()->sync($request->input('report.reportsTypes'));
        $report->reportsSubtypes()->sync($request->input('report.reportsSubtypes'));

        Alert::info('Вы успешно создали отчет!');

        return redirect()->route('platform.reports.list');
    }

    /**
     * @param Report $report
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(Report $report)
    {
        $report->delete()
            ? Alert::info('Вы успешно удалили отчет!')
            : Alert::warning('Упс. Ошибка')
        ;

        return redirect()->route('platform.reports.list');
    }

    public function asyncImport(Report $report, Request $request)
    {
        $attach = Attachment::where('id', $request->get('upload'))->get();
        $attachfile = "";

        foreach ($attach as $attachs) {
            $attachfile = public_path() .'/storage/' . $attachs->path . $attachs->name . '.' . $attachs->extension;
        }

        Excel::import(new ReportsImport, $attachfile);

        return back();
    }
}
