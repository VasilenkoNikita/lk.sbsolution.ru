<?php

namespace App\Modules\Reporting\Filters;

use App\Models\Group;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Orchid\Filters\Filter;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Select;
use Orchid\Support\Facades\Layout;

class GroupFilter extends Filter
{
    /**
     * @var array
     */
    public $parameters = [
        'group'
    ];

    /**
     * @return string
     */
    public function name(): string
    {
        return 'Группа';
    }
    /**
     * @return array
     */
    public function getGroupsIds(){

    }
    /**
     * @param Builder $builder
     *
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {

        return $builder->whereHas('groups', function (Builder $query) {
            $query->whereIn('group_id', $this->request->get('group'));
        });
    }

    /**
     * @return Field[]
     */
    public function display(): array
    {
        if(Auth::user()->name === 'natalia.s' || Auth::user()->name === 'anastasia.e' || Auth::user()->name === 'admin' ) {
            return [
                Select::make('group.')
                    ->fromModel(Group::class, 'name', 'id')
                    ->multiple()
                    ->value($this->request->get('group'))
                    ->title('Фильтр по группе'),
            ];
        }

        return [
            Select::make('group.')
                ->fromModel(Group::class, 'name', 'id')
                ->multiple()
                ->value($this->request->get('group'))
                ->title('Фильтр по группе')
                ->disabled(true),
        ];
    }

    /**
     * @return string
     */
    public function value(): string
    {
        $groups = Group::whereIn('id', $this->request->get('group'))->get();
        $groupsNames = [];
        $groupsStrNames = '';
        foreach ($groups as $group) {
            $groupsNames[] = $group->name;
        }
        $groupsStrNames = implode(", ", $groupsNames);

        return $this->name().': '.$groupsStrNames;
    }
}
