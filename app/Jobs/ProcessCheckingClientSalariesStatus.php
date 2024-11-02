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
use Illuminate\Support\Carbon;

class ProcessCheckingClientSalariesStatus extends Notification implements ShouldQueue
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
    private string $salaryDate;
    private string $type;
    private string $eventId;
    private string $subtype;
    private array $mailData;

    /**
     * Create a new job instance.
     *
     * @param User $user
     * @param Client $client
     * @param string $salaryDate
     * @param string $type
     * @param string $subtype
     * @param string $eventId
     */
    public function __construct(
        User $user,
        Client $client,
        string $salaryDate,
        string $type,
        string $subtype,
        string $eventId
    )
    {
        $this->user = $user;
        $this->client = $client;
        $this->salaryDate = $salaryDate;
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
        Carbon::setlocale(config('app.locale'));
        $month = Carbon::parse($this->salaryDate)->translatedFormat('F');

        $titlePart = "";
        $updateWord = "";

        if($this->type === "Аванс"){
            $titlePart = "АВАНСЫ";
            $updateWord = "аванса";
        }

        if($this->type === "Зарплата"){
            $titlePart = "ЗАРПЛАТЫ";
            $updateWord = "зарплаты";
        }

        $this->mailData = [
            'clientID' => $this->client->id,
            'mail_activity' => (int) $this->user->lk_client_mail_notification,
            'text' => 'Статус '.$updateWord.' не обновлен за месяц "'.$month.'"',
        ];
        if(!$this->user->notifications()->where('data->eventId', $this->eventId)->exists()) {
            $notification = new ReportsForUsers(
                $titlePart . ' - Новое уведомление о зарплате по клиенту ' . $this->client->organization,
                'Статус ' . $updateWord . ' не обновлен за месяц "' . $month . '"',
                $this->subtype,
                $this->eventId,
                $this->mailData);

            $this->user->notify(($notification)->delay(now()->addMinutes(1)));
        }
    }
}
