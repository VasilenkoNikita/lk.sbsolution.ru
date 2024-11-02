<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\User;

use App\Models\Group;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Layouts\Rows;

class UserGroupLayout extends Rows
{
    /**
     * Views.
     *
     * @return array
     */
    public function fields(): array
    {
        return [
            Select::make('user.groups.')
                ->fromModel(Group::class, 'name')
                ->multiple()
                ->title('Группа клиентов')
                ->help('Укажите, к каким группам клиентов должна принадлежать эта учетная запись')
        ];
    }
}
