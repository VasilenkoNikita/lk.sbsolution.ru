<?php

namespace App\Orchid\Screens\TaskManager\Board;


use App\Models\Project;
use App\Models\Board;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Screen\Fields\Switcher;


class BoardTaskManagerEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Создать новую доску';

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
     * Display header description.
     *
     * @var string
     */
    public $projectId = '';

    /**
     * Query data.
     *
     * @param Project $project
     * @param Board $board
     *
     * @return array
     */

    public function query(Project $project, Board $board): array
    {
        $this->projectId = $project->id;
        $this->exists = $board->exists;

        if($this->exists){
            $this->name = 'Редактировать доску';
        }

        return [
            'project' => $project,
            'board' => $board,
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
            Button::make('Добавить доску')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee(!$this->exists),

            Button::make('Редактировать доску')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->exists),

            Link::make('Обсуждение')
                ->icon('settings')
                ->route('platform.taskmanager.board.list', $this->projectId),

            Link::make('Участники')
                ->icon('settings')
                ->route('platform.taskmanager.member.list', $this->projectId),

            Link::make('Задачи')
                ->icon('settings')
                ->route('platform.taskmanager.issue.list', $this->projectId),

            Link::make('Версии проекта')
                ->icon('settings')
                ->route('platform.taskmanager.member.list', $this->projectId),

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
							Input::make('board.name')
								->title('Наименование доски')
								->help('Укажите название для доски'),

                            TextArea::make('board.description')
                                ->title('Описание доски')
                                ->rows(3)
                                ->maxlength(400)
                                ->placeholder('Опишите для чего предназначена доска'),

                            input::make('board.topics_count')
                                ->type('hidden'),

                            input::make('board.messages_count')
                                ->type('hidden'),

                            input::make('project.id')
                                ->type('hidden')

						])
					],

            ]),

        ];
    }

    /**
     * @param Project $project
     * @param Board  $board
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Project $project, Board $board, Request $request)
    {

        if (!$this->exists){
            $request->request->add(['board'=>array_merge($request->request->get('board'),['topics_count'=> 0])]);
            $request->request->add(['board'=>array_merge($request->request->get('board'),['messages_count'=> 0])]);
            $request->request->add(['board'=>array_merge($request->request->get('board'),['project_id'=> $request->input('project.id')])]);
        }
        $board->fill($request->get('board'))->save();


        Alert::info('Вы успешно создали доску!');

        return redirect()->route('platform.taskmanager.board.list', $this->projectId);
    }

    /**
     * @param Board $board
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(Board $board)
    {
        $board->delete()
            ? Alert::info('Вы успешно удалили доску!.')
            : Alert::warning('Упс. Ошибка')
        ;

        return redirect()->route('platform.taskmanager.board.list', $this->projectId);
    }
}
