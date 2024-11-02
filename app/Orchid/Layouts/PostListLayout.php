<?php

declare(strict_types=1);

namespace App\Orchid\Layouts;

use App\Models\Post;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;

class PostListLayout extends Table
{
    /**
     * Data source.
     *
     * @var string
     */
    public $target = 'posts';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::set('title', 'Заголовок')
				->sort()
				->filter(TD::FILTER_TEXT)
                ->render(function (Post $post) {
                    return Link::make($post->title)
                        ->route('platform.posts.edit', $post);
                }),

            TD::set('created_at', 'Создана')
				->sort(),
            TD::set('updated_at', 'Последнее обновление')
				->sort(),
        ];
    }
}
