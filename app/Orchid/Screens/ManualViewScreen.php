<?php

namespace App\Orchid\Screens;

use App\Models\Manual;
use App\Orchid\Layouts\ManualListLayout;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class ManualViewScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Руководства сервиса';

    /**
     * Display header description.
     *
     * @var string|null
     */
    public $description = 'Здесь отображены все руководства для сервиса';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(Manual $manual): array
    {
        $this->exists = $manual->exists;

        if($this->exists){
            $this->name = 'Руководство '.$manual->section;
            $this->description = 'Руководство по разделу '.$manual->section;
        }

        return [
            'manual' => $manual,
        ];
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return [];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): array
    {
        return [
            Layout::view('manuals/manual'),
        ];
    }
}
