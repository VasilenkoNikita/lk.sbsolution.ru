<?php

namespace App\Orchid\Layouts;

use App\Models\Tag;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;

class TagListLayout extends Table
{
    /**
     * Data source.
     *
     * @var string
     */
    public $target = 'tags';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::set('name', 'Наименование тэга')
                ->sort()
                ->filter(TD::FILTER_TEXT)
                ->render(function (Tag $tag) {
                    return Link::make($tag->name)
                        ->route('platform.tags.edit', $tag->id);
                }),

            TD::set('type', 'Группа тэга')
                ->sort()
                ->render(function (Tag $tag) {
                    if($tag->type === "news") {
                        return "Новости";
                    }

                    return "Полезные ресурсы";
                }),

            TD::set('order_column', 'Порядок отображения')
                ->sort(),

            TD::set('created_at', 'Дата создания')
                ->sort()
                ->render(function (Tag $tag) {
                    return $tag->updated_at->toDateTimeString();
                }),

            TD::set('updated_at', 'Дата обновления')
                ->sort()
                ->render(function (Tag $tag) {
                    return $tag->updated_at->toDateTimeString();
                }),
        ];

    }
}
