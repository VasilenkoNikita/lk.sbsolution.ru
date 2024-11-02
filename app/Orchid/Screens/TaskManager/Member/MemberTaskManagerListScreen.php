<?php

namespace App\Orchid\Screens\TaskManager\Member;

use App\Orchid\Layouts\TaskManager\Member\MemberTaskManagerListLayout;
use App\Models\Member;
use App\Models\Project;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class MemberTaskManagerListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Список участников проекта';

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
     * Query data.
     *
     * @param Project $project
     *
     * @return array
     */
    public function query(Project $project): array
    {
        $this->projectId = $project->id;

        return [
            'project' => $project,
            'members' => Member::select('users.id', 'members.id as member_id', 'first_name', 'last_name', 'roles_projects.id as role_id', 'roles_projects.name as role', 'members.project_id as project_id')
                ->leftJoin('users', 'members.user_id', '=', 'users.id')
                ->leftJoin('member_roles', 'members.id', '=', 'member_roles.member_id')
                ->leftJoin('roles_projects', 'member_roles.role_id', '=', 'roles_projects.id')
                ->where('members.project_id', '=', $project->id)
                ->get()
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
            Link::make('Добавить нового участника проекта')
                ->icon('pencil')
                ->route('platform.taskmanager.member.create', $this->projectId),

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
            MemberTaskManagerListLayout::class
        ];
    }
}
