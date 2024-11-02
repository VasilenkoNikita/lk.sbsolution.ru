<?php

namespace App\Orchid\Screens\TaskManager\Member;

use App\Models\Project;
use App\Models\RolesProjects;
use App\Models\Member;
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


class MemberTaskManagerEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Добавить участника проекта';

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
     * @param Member $member
     *
     * @return array
     */

    public function query(Project $project, Member $member): array
    {
        $this->projectId = $project->id;
        $this->exists = $member->exists;

        if($this->exists){
            $this->name = 'Редактировать роль';
        }

        return [
            'member' => $member,
            'project' => $project,
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
            Button::make('Добавить пользователя')
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
					'Информация о пользователе' => [
						Layout::rows([
                            Relation::make('member.user_id')
                                ->fromModel(User::class,'name')
                                ->displayAppend('full_name')
                                ->title('Имя пользователя'),

                            Relation::make('member.role.')
                                ->fromModel(RolesProjects::class, 'name')
                                ->displayAppend('name')
                                ->title('Роль пользователя в проекте'),

                            Switcher::make('member.mail_notification')
                                ->sendTrueOrFalse()
                                ->title('Отправлять уведомления по проекту на почту?')
                                ->help('Да/нет'),

                            input::make('project.id')
                                ->type('hidden')

						])
					]
            ]),

        ];
    }

    /**
     * @param project $project
     * @param member $member
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Project $project, Member $member, Request $request)
    {

        $request->request->add(['member'=>array_merge($request->request->get('member'),['project_id'=> $request->input('project.id')])]);

        $member->fill($request->get('member'))->save();

        $member->role()->sync($request->input('member.role', []));

        $this->exists
            ? Alert::info('Вы успешно обновили роль пользователя!')
            : Alert::info('Вы успешно пользователя в проект!')
           ;

        return redirect()->route('platform.taskmanager.member.list', $request->input('project.id'));
    }

    /**
     * @param Project $project
     * @param Member $member
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(Project $project, Member $member)
    {
        $member->delete()
            ? Alert::info('Вы успешно удалили участника проекта!')
            : Alert::warning('Упс. Ошибка')
        ;

        return redirect()->route('platform.taskmanager.member.list', $this->projectId);
    }
}
