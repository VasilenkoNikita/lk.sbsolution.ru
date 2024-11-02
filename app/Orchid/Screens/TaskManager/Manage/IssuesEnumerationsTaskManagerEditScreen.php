<?php

namespace App\Orchid\Screens\TaskManager\Manage;

use App\Models\Enumeration;
use App\Models\User;
use App\Models\Project;
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


class IssuesEnumerationsTaskManagerEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Создать новую категорию задач';

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
     * @param IssueCategory $issuecategory
     * @return array
     */

    public function query(IssueCategory $issuecategory): array
    {
        $this->exists = $issuecategory->exists;

        if($this->exists){
            $this->name = 'Редактировать категориб задач';
        }

        return [
            'issuecategory' => $issuecategory
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

            Button::make('Создать новую категорию')
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
							Input::make('issuecategory.name')
								->title('Название категории')
								->help('Укажите название категории'),

                            Relation::make('issuecategory.project_id')
                                ->fromModel(Project::class, 'name')
                                ->title('Укажите проект в котором должна быть данная категория задач'),

                            Relation::make('issuecategory.assigned_to_id')
                                ->fromModel(User::class,'name')
                                ->displayAppend('full_name')
                                ->title('Участник проекта, которому вы хотите автоматически назначать созданные задачи в этой категории'),

						])
					],

            ]),

        ];
    }

    /**
     * @param IssueCategory $issuecategory
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(IssueCategory $issuecategory, Request $request)
    {
        $issuecategory->fill($request->get('issuecategory'))->save();
        Alert::info('Вы успешно создали категорию задач!');
        return redirect()->route('platform.taskmanager.issuescategorytaskmanager.list');
    }

    /**
     * @param IssueCategory $issuecategory
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(IssueCategory $issuecategory)
    {
        $issuecategory->delete()
            ? Alert::info('Вы успешно удалили категорию задач!')
            : Alert::warning('Упс. Ошибка')
        ;

        return redirect()->route('platform.taskmanager.issuescategorytaskmanager.list');
    }
}
