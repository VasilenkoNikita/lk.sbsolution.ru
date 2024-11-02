<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use App\Models\Group;
use App\Models\Client;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;

class GroupEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Создать новую группу клиентов';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Группы клиентов компании';

    /**
     * @var bool
     */
    public $exists = false;

    /**
     * Query data.
     *
     * @param Group $group
     *
     * @return array
     */
    public function query(Group $group): array
    {
        $this->exists = $group->exists;

        if($this->exists){
            $this->name = 'Редактировать группу клиентов';
        }

		$groupId = $group->id;
		$clients = Client::whereHas('groups', function($query) use ($groupId){
			$query->where('group_id', $groupId);
		})->filters()->defaultSort('id')->paginate();

        return [
            'group' => $group,
			'clients' => $clients
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
            Button::make('Создать группу клиентов')
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
            Layout::rows([
                Input::make('group.name')
                    ->title('Название группы')
                    ->help('Укажите название для группы клиентов'),

                TextArea::make('group.description')
                    ->title('Описание группы клиентов')
                    ->rows(3)
                    ->maxlength(400)
            ]),

			Layout::accordion([
				'Клиенты входящие в группу' => [
				    Layout::table('clients', [
					    TD::set('name', 'Имя клиента')
						    ->sort()
						    ->filter(TD::FILTER_TEXT)
						    ->render(function (Client $client) {
							    return Link::make($client->name)
                                    ->route('platform.clients.edit', $client);
						    }),

					        TD::set('type_of_ownership', 'ИП/ООО')
                                ->sort(),

					        TD::set('organization', 'Организация')
                                ->sort(),

                            TD::set('emails.email.', 'Email')
                                ->render(function (Client $client) {
                                    return $client->emails[0]->email;
                                }),

					        TD::set('tax_system', 'СНО')
                                ->sort()
                        ]),
                    ],
                ]),
            ];
    }

    /**
     * @param Group $group
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Group $group, Request $request): \Illuminate\Http\RedirectResponse
    {
        $group->fill($request->get('group'))->save();
		//$group->clients()->sync($request->input('group.clients', []));

        Alert::info('Вы успешно создали группу клиентов!');

        return redirect()->route('platform.groups.list');
    }

    /**
     * @param Group $group
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(Group $group): \Illuminate\Http\RedirectResponse
    {
        $group->delete()
            ? Alert::info('Вы успешно удалили группу клиентов!')
            : Alert::warning('Упс. Ошибка')
        ;

        return redirect()->route('platform.groups.list');
    }
}
