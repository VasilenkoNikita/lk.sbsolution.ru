<?php

namespace App\Jobs;

use App\Models\Client;
use App\Models\User;
use App\Notifications\ReportsForUsers;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessCheckingClientCertificateStatus extends Notification implements ShouldQueue
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
    private int $certificateDeadlineDays;
    private string $certificateWarningType;
    private string $eventId;
    private string $subtype;
    private array $mailData;

    /**
     * Create a new job instance.
     *
     * @param User $user
     * @param Client $client
     * @param int $certificateDeadlineDays
     * @param string $certificateWarningType
     * @param string $subtype
     * @param string $eventId
     */
    public function __construct(
        User $user,
        Client $client,
        int $certificateDeadlineDays,
        string $certificateWarningType,
        string $subtype,
        string $eventId
    )
    {
        $this->user = $user;
        $this->client = $client;
        $this->certificateDeadlineDays = $certificateDeadlineDays;
        $this->certificateWarningType = $certificateWarningType;
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

        if ($this->certificateWarningType === "Истекает"){
            $warning = 'До окончания сертификата осталось '.$this->certificateDeadlineDays.' дней';
        }
        if ($this->certificateWarningType === "Истек"){
            $warning = 'Действие сертификата просрочено на '.$this->certificateDeadlineDays.' дней';
        }

        $this->mailData = [
            'clientID' => $this->client->id,
            'mail_activity' => (int) $this->user->lk_client_mail_notification,
            'text' => 'Сертификат не обновлен. Дата окончания сертификата '.date("d-m-Y", strtotime($this->client->certificate_end_date)).'. '.$warning,
        ];

        if(!$this->user->notifications()->where('data->eventId', $this->eventId)->exists()){
            $notification = new ReportsForUsers(
                'СЕРТИФИКАТ - Новое уведомление о сертификате по клиенту '.$this->client->organization,
                'Сертификат не обновлен. Дата окончания сертификата '.date("d-m-Y", strtotime($this->client->certificate_end_date)).
                '<br>'.$warning,
                $this->subtype,
                $this->eventId,
                $this->mailData);

            $this->user->notify(($notification)->delay(now()->addMinutes(1)));
        }
    }
}
