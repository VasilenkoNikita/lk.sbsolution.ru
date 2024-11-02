<?php

namespace App\Orchid\Screens;

use App\Models\Tag;
use App\Orchid\Layouts\TagListLayout;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;

class TagListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Список тэгов';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Список тэгов используемых в сервисе';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'tags' => Tag::filters()->paginate(100)
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
            Link::make('Создать новый тэг')
                ->icon('pencil')
                ->route('platform.tags.create')
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
            TagListLayout::class
        ];
    }
}
