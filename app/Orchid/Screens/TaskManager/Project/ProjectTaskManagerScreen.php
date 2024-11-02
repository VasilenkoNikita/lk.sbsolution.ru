<?php

namespace App\Orchid\Screens\TaskManager\Project;

use App\Models\Project;
use App\Models\Board;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\View\Components\ProjectScreenTaskManager;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class ProjectTaskManagerScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Проект';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = '';

    /**
     * Display projectId.
     *
     * @var int
     */
    public $projectId = '';

    /**
     * Display boardId.
     *
     * @var int
     */
    public $boardId = '';

    /**
     * Display boardTopicCount.
     *
     * @var int
     */
    public $boardTopicCount = '';

    /**
     * Display boardMessagesCount.
     *
     * @var int
     */
    public $boardMessagesCount = '';

    /**
     * @var bool
     */
    public $exists = false;

    /**
     * Query data.
     *
     * @param Project $project
     * @param User $user
     * @return array
     */
    public function query(Project $project, User $user): array
    {
        $this->exists = $project->exists;
        if($this->exists){
            $this->name = 'Проект "'. $project->name .'"';
        }

        $board = Board::where('project_id', $project->id)->get();
        $messages = Message::where('board_id', $board[0]->id)->get();

        $this->projectId = $project->id;
        $this->boardId = $board[0]->id;
        $this->boardTopicCount = $board[0]->topics_count;
        $this->boardMessagesCount = $board[0]->messages_count;

        return [
            'board' => $board,
            'messages' => $messages,
            'project' => Project::query()->filters()->defaultSort('id')->paginate(),
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

            ModalToggle::make(__('Отправить сообщение'))
                ->icon('lock-open')
                ->method('asyncNewMessage')
                ->modal('message')
                ->title(__('Новое сообщение')),

            Link::make('Доски')
                ->icon('settings')
                ->route('platform.taskmanager.board.create', $this->projectId),

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
            Layout::modal('message', [
                Layout::rows([
                    Input::make('message.subject')
                        ->title('Тема сообщение'),

                    Input::make('message.content')
                        ->title('Текст сообщения'),

                    Input::make('message.replies_count')
                        ->type('hidden')
                        ->value(0),

                    input::make('message.project_id')
                        ->type('number')
                        ->value($this->projectId)
                        ->type('hidden'),

                    input::make('message.board_id')
                        ->type('number')
                        ->value($this->boardId)
                        ->type('hidden'),

                    input::make('message.author_id')
                        ->type('number')
                        ->value(Auth::user()->id)
                        ->type('hidden'),

                    input::make('board.id')
                        ->type('number')
                        ->value($this->boardId)
                        ->type('hidden'),

                    input::make('board.project_id')
                        ->type('number')
                        ->value($this->projectId)
                        ->type('hidden'),

                    input::make('board.topics_count')
                        ->type('number')
                        ->value($this->boardTopicCount+1)
                        ->type('hidden'),

                    input::make('board.messages_count')
                        ->type('number')
                        ->value($this->boardMessagesCount+1)
                        ->type('hidden'),



                ]),
            ]),
            Layout::component(ProjectScreenTaskManager::class),
        ];
    }


    public function asyncNewMessage(Project $project, Message $message, Request $request)
    {

        $message->fill($request->get('message'))->save();

        $board = Board::find($request->input('board.id'));

        $board->topics_count = $request->input('board.topics_count');
        $board->messages_count = $request->input('board.messages_count');
        $board->save();

        Toast::info(__('Сообщение добавлено.'));

        return back();
    }
}
