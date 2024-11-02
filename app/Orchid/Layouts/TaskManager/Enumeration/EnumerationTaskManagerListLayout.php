<?php

namespace App\Orchid\Layouts\TaskManager\Enumeration;

use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Layouts\Table;

class EnumerationTaskManagerListLayout extends Table
{
    /**
     * Data source.
     *
     * @var string
     */
    public $target = 'enumeration';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::set('name', 'Название')
                ->sort(),

			TD::set('is_default', 'По умолчанию')
                ->sort(),

            TD::set('active', 'По умолчанию')
                ->sort(),

            TD::set('', '')
                ->sort()
                ->render(function ($enumeration) {
                    return Link::make('Редактировать')
                        ->icon('note')
                        ->route('platform.taskmanager.enumeration.edit', $enumeration->project_id);
                })
        ];
    }
}
