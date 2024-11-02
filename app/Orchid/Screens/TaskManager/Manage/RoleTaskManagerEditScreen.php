<?php

namespace App\Orchid\Screens\TaskManager\Manage;

use App\Models\RolesProjects;
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


class RoleTaskManagerEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Создать новую роль';

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
     * @param Client $client
     *
     * @return array
     */

    public function query(RolesProjects $rolesprojects): array
    {
        $this->exists = $rolesprojects->exists;

        if($this->exists){
            $this->name = 'Редактировать клиента';
        }

        return [
            'rolesprojects' => $rolesprojects
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
		
            Button::make('Создать Роль')
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
							Input::make('rolesprojects.name')
								->title('Название роли')
								->placeholder('Программист')
								->help('Укажите название роли'),
								
							Switcher::make('rolesprojects.assignable')
								->sendTrueOrFalse()
								->title('Может являться исполнителем')
								->help('Переключите флаг если хотите что данная роль могля являться исполнитем задач'),	
								
							TextArea::make('rolesprojects.permissions')
								->title('Разрешения')
								->rows(3)
								->maxlength(400)
								->placeholder('Опишите какие доступы должны быть у роли'),
								
							Input::make('rolesprojects.builtin')
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
     * @param RolesProjects  $rolesprojects
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(RolesProjects $rolesprojects, Request $request)
    {
		
	
	
        $rolesprojects->fill($request->get('rolesprojects'))->save();
		
		/*
		$client->groups()->sync($request->input('client.groups', []));
		
		$client->attachment()->syncWithoutDetaching(
			$request->input('client.attachment', [])
		);
		*/
		
        Alert::info('Вы успешно создали роль!');

        return redirect()->route('platform.taskmanager.roletaskmanager.list');
    }

    /**
     * @param RolesProjects $rolesprojects
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(RolesProjects $rolesprojects)
    {
        $rolesprojects->delete()
            ? Alert::info('Вы успешно удалили роль!.')
            : Alert::warning('Упс. Ошибка')
        ;

        return redirect()->route('platform.taskmanager.roletaskmanager.list');
    }
}
