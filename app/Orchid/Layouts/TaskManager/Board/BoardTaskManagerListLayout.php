<?php

namespace App\Orchid\Layouts\TaskManager\Board;

use App\Models\Board;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;

use Orchid\Screen\Layouts\Table;

class BoardTaskManagerListLayout extends Table
{
    /**
     * Data source.
     *
     * @var string
     */
    public $target = 'board';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::set('id', 'ID')
                ->sort(),

            TD::set('name', 'Название')
				->sort()
                ->render(function (Board $board) {
                    return Link::make($board->name)
                        ->route('platform.taskmanager.board.edit', [$board->project_id, $board->id]);
                }),

        ];
    }
}
