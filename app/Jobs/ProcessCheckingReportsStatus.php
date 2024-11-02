<?php

namespace App\Jobs;

use App\Models\Client;
use App\Models\Payment;
use App\Models\Report;
use App\Models\User;
use App\Notifications\ReportsForUsers;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Notifications\Notification;

class ProcessCheckingReportsStatus extends Notification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected User $user;
    protected Client $client;
    protected int $event;
    protected string $type;
    private string $eventId;
    private string $subtype;
    private array $mailData;

    /**
     * Create a new job instance.
     * @param User $user
     * @param Client $client
     * @param int $event
     * @param string $type
     * @param string $subtype
     * @param string $eventId
     */
    public function __construct(
        User $user,
        Client $client,
        int $event,
        string $type,
        string $subtype,
        string $eventId
    )
    {
        $this->user = $user;
        $this->client = $client;
        $this->event = $event;
        $this->type = $type;
        $this->subtype = $subtype;
        $this->eventId = $eventId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //dd($this->event);
        $warningEvent = "";
        $warningDate = "";
        $updateWord = "";
        $titlePart = "";

        if ($this->type === "Отчет"){
            $report = Report::where('id', $this->event)->first();
            $warningEvent = $report->report_name;
            $warningDate = $report->report_date;
            $updateWord = 'обновлен';
            $titlePart = 'ОТЧЕТЫ';
        }

        if ($this->type === "Оплата"){
            $payment = Payment::where('id', $this->event)->first();
            $warningEvent = $payment->payment_name;
            $warningDate = $payment->payment_date;
            $updateWord = 'обновлена';
            $titlePart = 'ОПЛАТЫ';
        }

        $this->mailData = [
            'clientID' => $this->client->id,
            'mail_activity' => (int) $this->user->lk_client_mail_notification,
            'text' => $this->type.' не '.$updateWord.' "'.$warningEvent.' '.$warningDate.' - дата сдачи',
        ];

        if(!$this->user->notifications()->where('data->eventId', $this->eventId)->exists()) {
            $notification = new ReportsForUsers(
                $titlePart . '- новое уведомление о статусе отчета по клиенту ' . $this->client->organization,
                $this->type . ' не ' . $updateWord . ' "' . $warningEvent . '"</br> ' . $warningDate . ' - дата сдачи',
                $this->subtype,
                $this->eventId,
                $this->mailData);

            $this->user->notify($notification->delay(now()->addMinutes(1)));

        }
    }
}
