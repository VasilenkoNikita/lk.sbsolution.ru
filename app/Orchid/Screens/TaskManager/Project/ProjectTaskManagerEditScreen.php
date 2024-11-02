<?php

namespace App\Orchid\Screens\TaskManager\Project;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Screen\Fields\Switcher;


class ProjectTaskManagerEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Создать новый проект';

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
     * @param Project $project
     *
     * @return array
     */

    public function query(Project $project): array
    {
        $this->exists = $project->exists;

        if($this->exists){
            $this->name = 'Редактировать проект';
        }

        return [
            'project' => $project
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
		    Link::make('Настройки task-manager')
                ->icon('settings')
                ->route('platform.taskmanager.settings'),

            Button::make('Создать проект')
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

				Layout::tabs([
					'Основная информация' => [
						Layout::rows([
							Input::make('project.name')
								->title('Наименование проекта')
								->help('Укажите название для проекта'),

							Input::make('project.homepage')
								->title('Домашняя страница проекта')
								->help('Укажите адрес страницы проекта'),

							Input::make('project.identifier')
								->title('Ключ проекта')
								->help('Укажите уникальный ключ проекта, пример "CRM"'),

							TextArea::make('project.description')
								->title('Описание проекта')
								->rows(3)
								->maxlength(400)
								->placeholder('Опишите для чего предназначен проект'),

							Switcher::make('project.status')
								->sendTrueOrFalse()
								->title('Статус проекта')
								->help('Активен/закрыт'),

							Switcher::make('project.is_public')
								->sendTrueOrFalse()
								->title('Опубликовать для всех')
								->help('Укажите должен ли быть проект видимым для всех')

						])
					],

                'Дополнительная информация' => [
					Layout::rows([
					])
				]
            ]),

        ];
    }

    /**
     * @param Project  $project
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Project $project, Request $request)
    {

        $project->fill($request->get('project'))->save();

        Alert::info('Вы успешно создали проект!');

        return redirect()->route('platform.taskmanager.project.list');
    }

    /**
     * @param Project $project
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(Project $project)
    {
        $project->delete()
            ? Alert::info('Вы успешно удалили проект!.')
            : Alert::warning('Упс. Ошибка')
        ;

        return redirect()->route('platform.taskmanager.project.list');
    }
}
