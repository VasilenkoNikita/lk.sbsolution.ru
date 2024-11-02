<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use App\Models\UsefulAccountingResource;
use App\Orchid\Layouts\Tag\TagFiltersLayout;
use App\Orchid\Layouts\UsefulAccountingResourceListLayout;
use App\Orchid\Filters\TagFilter;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class UsefulAccountingResourceListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Список полезных ресурсов';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Список полезных ресурсов, которые могут пригодиться по работе';

    /**
     * Tag type for TagFilter.
     *
     * @var string
     */
    public $tagType = 'resource';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Request $request): array
    {
        $request->request->add(['tagType' => $this->tagType]);
        return [
            'UsefulAccountingResource' => UsefulAccountingResource::filtersApply([TagFilter::class])->filters()->orderBy('id', 'desc')->paginate(100)
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
            Link::make('Создать новую запись о ресурсе')
                ->icon('pencil')
                ->route('platform.usefulAccountingResources.create')
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
            TagFiltersLayout::class,
            UsefulAccountingResourceListLayout::class
        ];
    }
}
