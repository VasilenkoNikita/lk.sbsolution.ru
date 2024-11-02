<?php

namespace App\Orchid\Screens;

use Orchid\Attachment\Models\Attachment;
use Orchid\Screen\Screen;
use App\Models\User;
use App\Models\Client;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Upload;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Relation;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;

class EmailSenderScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Рассылка Email писем';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Инструмент для массовой отправки писем клиентам.';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {

        return [
            'subject' => 'Новости компании за '.date('d.m.Y')
        ];

    }

    /**
     * Button commands.
     *
     * @return Action[]
     */
    public function commandBar(): array
    {
        return [
            Button::make('Отправить письма')
                ->icon('paper-plane')
                ->method('sendMessage')
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
            Layout::rows([
                Input::make('subject')
                    ->title('Заголовок')
                    ->required()
                    ->placeholder('Текст заголовка письме')
                    ->help('Введите заголовок письма'),

                Relation::make('clients.')
                    ->title('Получатели')
                    ->multiple()
                    ->required()
                    ->placeholder('Email адрес')
                    ->help('Укажите получателей')
                    ->fromModel(Client::class,'name','email'),

                Relation::make('groups.')
                    ->title('Группа клиентов')
                    ->multiple()
                    ->help('Укажите группу получателей')
                    ->fromModel(Group::class, 'name'),

                Quill::make('content')
                    ->title('Сообщение')
                    ->required()
                    ->placeholder('Содержимое письма ...')
                    ->help('Введите текст сообщения.'),

                Upload::make('attachment')
                    ->title('Вложения'),

            ])
        ];
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendMessage(Request $request)
    {


        $request->validate([
            'subject' => 'required|min:6|max:50',
            'clients' => 'required',
            'content' => 'required|min:10'
        ]);



        Mail::send([], [], function (Message $message) use ($request) {

            $message->subject($request->get('subject'));

            $l = [];
            if($request->get('attachment')){
                foreach ($request->get('attachment') as $attach) {
                    $attachs = Attachment::where('id', $request->get('attachment'))->get();
                    foreach ($attachs as $clients_attachs) {
                        array_push($l, '/storage/' . $clients_attachs->path . $clients_attachs->name . '.' . $clients_attachs->extension);
                        $message->attach(public_path() .'/storage/' . $clients_attachs->path . $clients_attachs->name . '.' . $clients_attachs->extension);
                    }
                }
            }

            $p = [];
            if($request->get('groups')){
                foreach ($request->get('groups') as $groups_clients_id) {
                    $clr = Client::whereHas('groups', function($query) use ($groups_clients_id){
                        $query->where('group_id', $groups_clients_id);
                    })->get();
                    foreach ($clr as $clients_emails) {
                        if (!in_array($clients_emails->email, $p)) {
                            array_push($p, $clients_emails->email);
                        }
                    }
                }
            }

            foreach ($request->get('clients') as $email) {
                $message->to($email)->setBody($request->get('content'), 'text/html');
            }

            if (!empty($p)){
                foreach ($p as $a) {
                    $message->to($a)->setBody($request->get('content'), 'text/html');
                }
            }

        });

        Alert::info('Письма успешно отправлены.');
        return back();
    }
}
