<?php

namespace App\Orchid\Screens\TaskManager\Issue;

use App\Models\IssueStatus;
use App\Models\Enumeration;
use App\Models\IssueCategory;
use App\Models\Issue;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Cropper;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\UTM;
use Orchid\Screen\Fields\Switcher;


class IssueTaskManagerEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Создать новую задачу';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = '';

    /**
     * Display projectid.
     *
     * @var string
     */
    public $projectId = '';

    /**
     * @var bool
     */
    public $exists = false;

    /**
     * Query data.
     *
     * @param Project $project
     * @param Issue $issue
     *
     * @return array
     */

    public function query(Project $project, Issue $issue): array
    {
        $this->projectId = $project->id;
        $this->exists = $issue->exists;

        if($this->exists){
            $this->name = 'Редактировать задачу';
        }

        return [
            'project' => $project,
            'issue' => $issue,
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
            Button::make('Создать задачу')
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
							Input::make('issue.subject')
                                ->required()
								->title('Загаловок задачи'),

                            TextArea::make('issue.description')
                                ->title('Описание задачи')
                                ->required()
                                ->rows(3)
                                ->maxlength(400),

                            Relation::make('issue.status_id')
                                ->fromModel(IssueStatus::class,'name')
                                ->title('Статус задачи'),

                            DateTimer::make('issue.start_date')
                                ->title('Дата начала выполнения задачи')
                                ->placeholder('Укажите дату')
                                ->required()
                                ->allowInput(),

                            DateTimer::make('issue.due_date')
                                ->title('Дата начала выполнения задачи')
                                ->placeholder('Укажите дату сдачи задачи')
                                ->allowInput(),

                            Input::make('estimated_hours')
                                ->type('time')
                                ->title('Время потраченное на задачу')
                                ->value('00:00'),

                            Select::make('issue.priority_id')
                                ->fromQuery(Enumeration::where('type', '=', 'Приоритеты задач'), 'name')
                                ->title('Приоритет'),

                            Relation::make('issue.assigned_to_id')
                                ->fromModel(User::class,'name')
                                ->displayAppend('full_name')
                                ->title('Исполнитель'),

                            Relation::make('issue.category_id')
                                ->fromModel(IssueCategory::class,'name')
                                ->title('Категория задачи'),

                            input::make('issue.author_id')
                                ->value(Auth::user()->id),

                            input::make('issue.lock_version')
                                ->value(0),

                            input::make('project.id')
                                ->type('hidden'),

						])
					],

            ]),

        ];
    }

    /**
     *
     * @param Issue $issue
     * @param Project $project
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Project $project, Issue $issue, Request $request){
        $estimated_hours = str_replace(":", ".", $request->input('estimated_hours'));

        $request->request->add(['issue'=>array_merge($request->request->get('issue'),['estimated_hours'=> $estimated_hours])]);
        $request->request->add(['issue'=>array_merge($request->request->get('issue'),['project_id'=> $request->input('project.id')])]);

        $issue->fill($request->get('issue'))->save();

       // $issue->role()->sync($request->input('member.role', []));

        $this->exists
            ? Alert::info('Вы успешно обновили задачу!')
            : Alert::info('Вы успешно добавили задачу в проект!')
        ;

        return redirect()->route('platform.taskmanager.issue.list', $request->input('project.id'));

    }

    /**
     * @param Issue $issue
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(Issue $issue)
    {
        $issue->delete()
            ? Alert::info('Вы успешно удалили задачу!')
            : Alert::warning('Упс. Ошибка')
        ;

        return redirect()->route('platform.taskmanager.project.issues.list');
    }
}
