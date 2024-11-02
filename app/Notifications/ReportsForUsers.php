<?php

namespace App\Notifications;

use DateTime;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Orchid\Platform\Notifications\DashboardChannel;
use Orchid\Platform\Notifications\DashboardMessage;
use Orchid\Support\Color;

class ReportsForUsers extends Notification implements ShouldQueue
{
    use Queueable;

    private string $title;
    private string $text;
    private string $subtype;
    private string $eventId;
    private array $mailData = [];

    /**
     * Create a new notification instance.
     *
     * @param string $title
     * @param string $text
     * @param string $subtype
     * @param string $eventId
     * @param array $mailData
     */
    public function __construct(string $title, string $text, string $subtype, string $eventId, array $mailData)
    {
        $this->title = $title;
        $this->text = $text;
        $this->eventId = $eventId;
        $this->subtype = $subtype;
        $this->mailData = $mailData;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        if(!empty($this->mailData) && $this->mailData['mail_activity'] === 1) {
            return [
                DashboardChannel::class,
                'mail'
            ];
        }

        return [
            DashboardChannel::class
        ];

    }

    public function toDashboard($notifiable)
    {
        return (new DashboardMessage())
            ->title($this->title)
            ->message($this->text)
            ->type(Color::DANGER())
            ->subtype($this->subtype)
            ->eventId($this->eventId);
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
            $url = url('/dashboard/clients/'.$this->mailData['clientID'].'/edit');
            return (new MailMessage)
                ->subject($this->title)
                ->greeting($this->title)
                ->line($this->mailData['text'])
                ->action('Ссылка на клиента', $url)
                ->line(date("d-m-Y H:i:s"))
                ->salutation('Личный кабинет lk.sbsolution.ru');
    }


    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [

        ];
    }
}
