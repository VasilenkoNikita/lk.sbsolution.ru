<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use App\Models\Rate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Switcher;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Screen\Actions\Link;

class RatesEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Добавить новый тариф';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Добавить новый тариф';

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
     * @param Rate $rate
     *
     * @return array
     */
    public function query(Rate $rate): array
    {
        $this->exists = $rate->exists;

        if(Auth::user()->name === 'natalia.s' || Auth::user()->name === 'anastasia.e' || Auth::user()->name === 'admin' ) {
            $this->cansee = true;
        }

        if($this->exists){
            $this->name = 'Редактировать тариф';
        }

        return [
            'rate' => $rate
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
		
            Button::make('Создать тариф')
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
                Input::make('rate.name')
                    ->title('Название тарифв')
                    ->help('Укажите наименование тарифа'),

				TextArea::make('rate.description')
					->title('Описание тарифа')
					->rows(6)
					->maxlength(2500),

                Switcher::make('rate.active')
                    ->sendTrueOrFalse()
                    ->title('Активность тарифа')
                    ->help('Переключите активность тарифа'),
            ]),
        ];
    }

    /**
     * @param Rate  $rate
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Rate $rate, Request $request)
    {
        $rate->fill($request->get('rate'))->save();

        Alert::info('Вы успешно создали тариф!');

        return redirect()->route('platform.rates.list');
    }

    /**
     * @param Rate $rate
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(Rate $rate)
    {
        $rate->delete()
            ? Alert::info('Вы успешно удалили тариф!')
            : Alert::warning('Упс. Ошибка')
        ;

        return redirect()->route('platform.rates.list');
    }

}
