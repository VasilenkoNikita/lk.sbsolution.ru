<?php

namespace App\Jobs;

use App\Models\Client;
use App\Models\User;
use App\Notifications\ReportsForUsers;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Notifications\Notification;

class ProcessCheckingClientPatentsStatus extends Notification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var User
     */
    private User $user;
    /**
     * @var Client
     */
    private Client $client;
    private int $patentDeadlineDays;
    private string $patentWarningType;
    private string $patentsDeadlinesDate;
    private string $eventId;
    private string $subtype;
    private array $mailData;

    /**
     * Create a new job instance.
     *
     * @param User $user
     * @param Client $client
     * @param int $patentDeadlineDays
     * @param string $patentWarningType
     * @param string $patentsDeadlinesDate
     * @param string $subtype
     * @param string $eventId
     */
    public function __construct(
        User $user,
        Client $client,
        int $patentDeadlineDays,
        string $patentWarningType,
        string $patentsDeadlinesDate,
        string $subtype,
        string $eventId
    )
    {
        $this->user = $user;
        $this->client = $client;
        $this->patentDeadlineDays = $patentDeadlineDays;
        $this->patentWarningType = $patentWarningType;
        $this->patentsDeadlinesDate = $patentsDeadlinesDate;
        $this->eventId = $eventId;
        $this->subtype = $subtype;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $warning = "";
        if ($this->patentWarningType === "Истекает"){
            $warning = 'До окончания патента осталось '.$this->patentDeadlineDays.' дней';
        }
        if ($this->patentWarningType === "Истек"){
            $warning = 'Действие патента просрочено на '.$this->patentDeadlineDays.' дней';
        }

        $this->mailData = [
            'clientID' => $this->client->id,
            'mail_activity' => (int) $this->user->lk_client_mail_notification,
            'text' => 'Патент не обновлен. Дата окончания патента '.date("d-m-Y", strtotime($this->patentsDeadlinesDate)).'. '.$warning,
        ];
        if(!$this->user->notifications()->where('data->eventId', $this->eventId)->exists()) {
            $notification = new ReportsForUsers(
                'ПАТЕНТ - Новое уведомление о патенте по клиенту ' . $this->client->organization,
                'Патент не обновлен. Дата окончания патента ' . date("d-m-Y", strtotime($this->patentsDeadlinesDate)) .
                '<br>' . $warning,
                $this->subtype,
                $this->eventId,
                $this->mailData);

            $this->user->notify(($notification)->delay(now()->addMinutes(1)));
        }
    }
}
