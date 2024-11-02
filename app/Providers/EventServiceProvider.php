<?php

namespace App\Providers;

use App\Models\Client;
use App\Models\ClientAccess;
use App\Models\ClientBankStatement;
use App\Models\ClientCashDesk;
use App\Models\ClientEmail;
use App\Models\ClientPatent;
use App\Models\ClientPhone;
use App\Models\ClientPlaceOfBusiness;
use App\Models\ClientReporting;
use App\Models\EconomicActivities;
use App\Models\Group;
use App\Models\Payment;
use App\Models\Report;
use App\Models\SectionActivity;
use App\Models\SubActivity;
use App\Models\TypesOfTaxes;
use App\Models\UsefulAccountingResource;
use App\Observers\ClientAccessObserver;
use App\Observers\ClientBankStatementObserver;
use App\Observers\ClientCashDeskObserver;
use App\Observers\ClientEmailObserver;
use App\Observers\ClientObserver;
use App\Observers\ClientPatentObserver;
use App\Observers\ClientPhoneObserver;
use App\Observers\ClientPlaceOfBusinessObserver;
use App\Observers\ClientReportingObserver;
use App\Observers\EconomicActivitiesObserver;
use App\Observers\PaymentObserver;
use App\Observers\ReportObserver;
use App\Observers\SectionActivityObserver;
use App\Observers\SubActivityObserver;
use App\Observers\GroupObserver;
use App\Observers\TypesOfTaxesObserver;
use App\Observers\UsefulAccountingResourceObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        Client::observe(ClientObserver::class);
        ClientPatent::observe(ClientPatentObserver::class);
        ClientEmail::observe(ClientEmailObserver::class);
        ClientPhone::observe(ClientPhoneObserver::class);
        ClientAccess::observe(ClientAccessObserver::class);
        ClientBankStatement::observe(ClientBankStatementObserver::class);
        ClientCashDesk::observe(ClientCashDeskObserver::class);
        ClientPlaceOfBusiness::observe(ClientPlaceOfBusinessObserver::class);
        ClientReporting::observe(ClientReportingObserver::class);
        TypesOfTaxes::observe(TypesOfTaxesObserver::class);
        EconomicActivities::observe(EconomicActivitiesObserver::class);
        SectionActivity::observe(SectionActivityObserver::class);
        SubActivity::observe(SubActivityObserver::class);
        UsefulAccountingResource::observe(UsefulAccountingResourceObserver::class);
        Group::observe(GroupObserver::class);
        Payment::observe(PaymentObserver::class);
        Report::observe(ReportObserver::class);
        //
    }
}
