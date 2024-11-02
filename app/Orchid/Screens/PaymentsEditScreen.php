<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use App\Imports\PaymentsImport;
use App\Models\Payment;
use App\Models\PaymentSubtype;
use App\Models\PaymentType;
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

class PaymentsEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Добавить новую оплату';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Бухгалтерские оплаты';

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
     * @param Payment $payment
     *
     * @return array
     */
    public function query(Payment $payment): array
    {
        $this->exists = $payment->exists;

        if(Auth::user()->name === 'natalia.s' || Auth::user()->name === 'anastasia.e' || Auth::user()->name === 'admin' ) {
            $this->cansee = true;
        }

        if($this->exists){
            $this->name = 'Редактировать оплату';
        }

        return [
            'payment' => $payment
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
           /*  ModalToggle::make('Импорт оплат')
                ->icon('lock-open')
                ->method('asyncImport')
                ->modal('uploadPayments')
                ->title('Импорт оплат'), */

            Button::make('Создать оплату')
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
                ->canSee($this->cansee)
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
                Input::make('payment.payment_name')
                    ->title('Название отчета')
                    ->help('Укажите название для отчета'),

                DateTimer::make('payment.payment_date')
                    ->title('Дата сдачи оплаты')
                    ->placeholder('Укажите дату оплаты')
                    ->required()
                    ->allowInput(),
/*
                Input::make('payment.type')
                    ->title('Тип оплаты')
                    ->help('Укажите тип для оплаты'),
 */
                Select::make('payment.paymentsTypes')
                    ->fromModel(PaymentType::class, 'name')
                    ->title('Тип оплаты'),
/*
                Input::make('payment.subtype')
                    ->title('Вид оплаты')
                    ->help('Укажите вид для оплаты'),
 */
                Select::make('payment.paymentsSubtypes')
                    ->fromModel(PaymentSubtype::class, 'name')
                    ->title('Вид оплаты'),

                Select::make('payment.type_of_ownership')
                    ->options([
                        'Любая' => 'Любая',
                        'ИП'  => 'ИП',
                        'ООО' => 'ООО',
                    ])
                    ->title('Форма собственности')
                    ->help('Укажите форму собственности к которым подходит оплата'),

                Switcher::make('payment.visibility')
                    ->sendTrueOrFalse()
                    ->title('Видимость оплаты')
                    ->help('Видимость активность оплаты'),

                Switcher::make('payment.active')
                    ->sendTrueOrFalse()
                    ->title('Активность оплаты')
                    ->help('Переключите активность оплаты'),
            ]),

            Layout::modal('uploadPayments', [
                Layout::rows([
                    Upload::make('upload')
                        ->title('Загрузите excel файл с оплатами')
                ]),
            ]),
        ];
    }

    /**
     * @param Payment $payment
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Payment $payment, Request $request): \Illuminate\Http\RedirectResponse
    {
        $payment->fill($request->get('payment'))->save();
        $payment->paymentsTypes()->sync($request->input('payment.paymentsTypes'));
        $payment->paymentsSubtypes()->sync($request->input('payment.paymentsSubtypes'));
        Alert::info('Вы успешно создали оплату!');

        return redirect()->route('platform.payments.list');
    }

    /**
     * @param Payment $payment
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(Payment $payment): \Illuminate\Http\RedirectResponse
    {
        $payment->delete()
            ? Alert::info('Вы успешно удалили оплату!')
            : Alert::warning('Упс. Ошибка')
        ;

        return redirect()->route('platform.payments.list');
    }

    public function asyncImport(Payment $payment, Request $request)
    {
        $attach = Attachment::where('id', $request->get('upload'))->get();
        $attachfile = "";

        foreach ($attach as $attachs) {
            $attachfile = public_path() .'/storage/' . $attachs->path . $attachs->name . '.' . $attachs->extension;
        }

        Excel::import(new PaymentsImport, $attachfile);

        return back();
    }
}
