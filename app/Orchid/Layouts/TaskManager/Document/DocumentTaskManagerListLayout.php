<?php

namespace App\Orchid\Layouts\TaskManager\Document;

use App\Document;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Layouts\Table;

class DocumentTaskManagerListLayout extends Table
{
    /**
     * Data source.
     *
     * @var string
     */
    public $target = 'document';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [

            TD::set('name', 'Название')
				->sort(),
			TD::set('identifier', 'Ключ')
				->sort(),
            TD::set('status', 'Статус')
				->sort(),

			TD::set('homepage', 'Домашняя страница')
				->sort(),

            TD::set('', '')
				->sort()
				->render(function (Project $project) {
                    return Link::make('Редактировать')
						->icon('note')
						->route('platform.taskmanager.project.edit', $project);
                })
        ];
    }
}
