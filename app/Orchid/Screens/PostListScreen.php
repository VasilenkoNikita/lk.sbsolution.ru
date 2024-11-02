<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use App\Orchid\Layouts\PostListLayout;
use App\Models\Post;
use App\Orchid\Filters\TagFilter;
use App\Orchid\Layouts\Tag\TagFiltersLayout;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class PostListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Лента новостей';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Все новости компании';

    /**
     * Tag type for TagFilter.
     *
     * @var string
     */
    public $tagType = 'news';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Request $request): array
    {
        $request->request->add(['tagType' => $this->tagType]);
        return [
            'posts' => Post::filtersApply([TagFilter::class])->filters()->defaultSort('id')->paginate()
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
            Link::make('Создать новую новость')
                ->icon('pencil')
                ->route('platform.posts.create')
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
            PostListLayout::class
        ];
    }
}
