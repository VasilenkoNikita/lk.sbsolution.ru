<?php

declare(strict_types=1);

namespace App\Orchid\Layouts;

use App\Models\UsefulAccountingResource;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;

class UsefulAccountingResourceListLayout extends Table
{
    /**
     * Data source.
     *
     * @var string
     */
    public $target = 'UsefulAccountingResource';

    /**
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('resource_name', 'Наименование ресурcа')
                ->sort()
                ->filter(TD::FILTER_TEXT)
                ->render(function (UsefulAccountingResource $UsefulAccountingResource) {
                    return Link::make($UsefulAccountingResource->resource_name)
                        ->route('platform.usefulAccountingResources.edit', $UsefulAccountingResource->id);
                }),

            TD::make('resource_link', 'Ссылка на ресурс')
                ->sort()
                ->render(function (UsefulAccountingResource $UsefulAccountingResource) {

                    return Link::make('https://'.parse_url($UsefulAccountingResource->resource_link, PHP_URL_HOST))
                        ->href($UsefulAccountingResource->resource_link)
                        ->target('_blank');


                }),

            /*TD::set('tags', 'Тэги')
                ->width('200px')
                ->render(function (UsefulAccountingResource $UsefulAccountingResource) {
                    $tagslist = "";
                    if(isset($UsefulAccountingResource->tags[0])) {
                        foreach ($UsefulAccountingResource->tags as $tag) {
                           // dd($UsefulAccountingResource->tags[0]);
                            $tagslist .= $tag->name."<br>";
                        }
                        return $tagslist;
                    }

                    return "Не указаны";
                }),*/
        ];
    }
}
