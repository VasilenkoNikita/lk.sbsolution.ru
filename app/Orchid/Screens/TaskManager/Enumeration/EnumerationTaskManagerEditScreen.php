<?php

namespace App\Orchid\Screens\TaskManager\Enumeration;

use App\Models\Enumeration;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Screen\Fields\Switcher;


class EnumerationTaskManagerEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Добавить свойство проекта';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = '';


    /**
     * @var bool
     */
    public $exists = false;

    /**
     * Query data.
     *
     * @param Enumeration $enumeration
     *
     * @return array
     */

    public function query(Enumeration $enumeration): array
    {

        $this->exists = $enumeration->exists;

        if($this->exists){
            $this->name = 'Редактировать общее свойство проектов';
        }

        return [
            'enumeration' => $enumeration,
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
            Button::make('Добавить свойство')
                ->icon('note')
                ->method('createOrUpdate'),

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

				Layout::tabs([
					'Информация о свойстве проекта' => [
						Layout::rows([
                            input::make('enumeration.name')
                                ->type('text')
                                ->title('Наименование свойства'),

                            input::make('enumeration.type')
                                ->type('text')
                                ->title('Тип свойства'),

                            Switcher::make('enumeration.is_default')
                                ->sendTrueOrFalse()
                                ->title('Свойство должно использоватся по умолчанию?')
                                ->help('Да/нет'),

                            Switcher::make('enumeration.active')
                                ->sendTrueOrFalse()
                                ->title('Активность свойства')
                                ->help('Да/нет'),

                            Relation::make('enumeration.project_id')
                                ->fromModel(Project::class,'name')
                                ->title('Свойство должно использоваться только в конкретном проекте?'),

                            Relation::make('enumeration.parent_id')
                                ->fromModel(Enumeration::class,'name')
                                ->title('Свойство должно наследоваться от другого свойства?'),

                            input::make('enumeration.position')
                                ->type('number')
                                ->title('Порядок отбражения свойства'),

						])
					]
            ]),

        ];
    }

    /**
     *
     * @param Enumeration $enumeration
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Enumeration $enumeration, Request $request)
    {

        $enumeration->fill($request->get('enumeration'))->save();

      //  $enumeration->role()->sync($request->input('member.role', []));

        $this->exists
            ? Alert::info('Вы успешно обновили свойство проектов!')
            : Alert::info('Вы успешно добавили свойство в проект!')
           ;

        return redirect()->route('platform.taskmanager.enumeration.list');
    }

    /**
     *
     * @param Enumeration $enumeration
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(Enumeration $enumeration)
    {
        $enumeration->delete()
            ? Alert::info('Вы успешно удалили свойство проектов!')
            : Alert::warning('Упс. Ошибка')
        ;

        return redirect()->route('platform.taskmanager.enumeration.list');
    }
}
