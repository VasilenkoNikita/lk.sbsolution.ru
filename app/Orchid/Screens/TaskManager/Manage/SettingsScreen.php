<?php

namespace App\Orchid\Screens\TaskManager\Manage;

use Orchid\Screen\Screen;
use App\Models\User;
use App\Models\Client;
use App\Models\Group;
use App\View\Components\SettingsTaskManager;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Relation;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;

class SettingsScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Настройки системы task-manager';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = '';

	/**
	* Query data.
	*
	* @return array
	*/
	public function query(): array
	{

		return [
			'subject' => 'Новости компании за '.date('d.m.Y')
		];

	}

    /**
     * Button commands.
     *
     * @return Action[]
     */
    public function commandBar(): array
    {
        return [];
    }

	/**
	* Views.
	*
	* @return Layout[]
	*/
	public function layout(): array
	{
		return [
			Layout::component(SettingsTaskManager::class),
		];	
		
	}
	
	/**
    * @param Request $request
    *
    * @return \Illuminate\Http\RedirectResponse
    */
}
