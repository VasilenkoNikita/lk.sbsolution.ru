<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use App\Orchid\Layouts\RatesListLayout;
use App\Models\Rate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class RatesListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Список тарифов';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Все тарифы';

    public $cansee = false;

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        if(Auth::user()->name === 'natalia.s' || Auth::user()->name === 'anastasia.e' || Auth::user()->name === 'admin' ) {
            $this->cansee = true;
        }

        return [
            'rates' => Rate::filters()->paginate(30),
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
            Link::make('Создать новый тариф')
                ->icon('pencil')
                ->canSee($this->cansee)
                ->route('platform.rates.create'),
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
            RatesListLayout::class,
        ];
    }
   
}
