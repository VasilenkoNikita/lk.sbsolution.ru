<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use App\Models\Manual;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\SimpleMDE;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class ManualEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Создать новое руководство';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Руководство по разделу';

    /**
     * @var bool
     */
    public $exists = false;

    /**
     * Query data.
     *
     * @param Manual $manual
     *
     * @return array
     */
    public function query(Manual $manual): array
    {
        $this->exists = $manual->exists;

        if($this->exists){
            $this->name = 'Руководство '.$manual->section;
            $this->description = 'Руководство по разделу '.$manual->section;
        }

        return [
            'manual' => $manual,
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
            Button::make('Создать')
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->exists),

            Button::make('Обновить')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->exists),

            Button::make('Удалить')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->exists),
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
                Input::make('manual.section')
                    ->title('Раздел сервиса')
                    ->placeholder('Клиенты')
                    ->help('Укажите для какого раздела сервиса используется руководство'),

                Input::make('manual.header')
                    ->title('Заголовок руководства')
                    ->help('Укажите заголовок для руководства'),

                Input::make('manual.code')
                    ->title('Код раздела')
                    ->placeholder('LKCL')
                    ->help('Укажите уникальный код для руководства'),

                SimpleMDE::make('manual.manual')
                    ->title('Тело руководства'),

                Input::make('manual.version')
                    ->title('Версия руководства')
                    ->placeholder('0.1')
                    ->help('Укажите версию руководства'),

            ]),
        ];
    }

    /**
     * @param Manual $manual
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Manual $manual, Request $request): \Illuminate\Http\RedirectResponse
    {
        $manual->fill($request->get('manual'))->save();

        Alert::info('Вы успешно обновили руководство!');

        return redirect()->route('platform.manuals.list');
    }

    /**
     * @param Manual $manual
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(Manual $manual): \Illuminate\Http\RedirectResponse
    {
        $manual->delete()
            ? Alert::info('Вы успешно удалили новость!.')
            : Alert::warning('Упс. Ошибка')
        ;

        return redirect()->route('platform.manuals.list');
    }
}
