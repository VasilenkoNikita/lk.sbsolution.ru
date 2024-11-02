<?php

namespace App\Orchid\Screens\TaskManager\Manage;

use App\Models\IssueStatus;
use App\Models\User;
use Illuminate\Http\Request;
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


class IssuesStatusTaskManagerEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Создать новый статус';

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
     * @param IssueStatus $issuestatus
     *
     * @return array
     */

    public function query(IssueStatus $issuestatus): array
    {
        $this->exists = $issuestatus->exists;

        if($this->exists){
            $this->name = 'Редактировать статус';
        }

        return [
            'issuestatus' => $issuestatus
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

            Button::make('Создать новый статус')
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
							Input::make('issuestatus.name')
								->title('Название статуса')
								->placeholder('В работе')
								->help('Укажите название статуса'),

							Switcher::make('issuestatus.is_closed')
								->sendTrueOrFalse()
								->title('Открыта/закрыта')
								->help('Укажите какому этапу задачи соответствует статус'),

							Switcher::make('issuestatus.is_default')
								->sendTrueOrFalse()
								->title('Задача по умолчанию')
								->help('Укажите должен ли этот статус являться статусом по умолчанию для задачи'),

							Input::make('issuestatus.number_of_employees')
								->title('Коэффициент готовности по умолчанию')
								->type('number')
								->value(0)
								->placeholder('Укажите от 1 до 100 коофициент готовности задачи при данном статусе'),

							Input::make('issuestatus.default_done_ratio')
								->type('hidden')
								->value(0)
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
     * @param IssueStatus  $issuestatus
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(IssueStatus $issuestatus, Request $request)
    {



        $issuestatus->fill($request->get('issuestatus'))->save();

		/*
		$client->groups()->sync($request->input('client.groups', []));

		$client->attachment()->syncWithoutDetaching(
			$request->input('client.attachment', [])
		);
		*/

        Alert::info('Вы успешно создали статус!');

        return redirect()->route('platform.taskmanager.issuesstatustaskmanager.list');
    }

    /**
     * @param IssueStatus $issuestatus
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(IssueStatus $issuestatus)
    {
        $issuestatus->delete()
            ? Alert::info('Вы успешно удалили статус!')
            : Alert::warning('Упс. Ошибка')
        ;

        return redirect()->route('platform.taskmanager.issuesstatustaskmanager.list');
    }
}
