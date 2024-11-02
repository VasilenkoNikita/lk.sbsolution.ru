<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();

        Route::model('tag', \App\Models\Tag::class);
        Route::model('user', \App\Models\User::class);
        Route::model('role', \Orchid\Platform\Models\Role::class);
        Route::model('client', \App\Models\Client::class);
        Route::model('manual', \App\Models\Manual::class);
        Route::model('group', \App\Models\Group::class);
		Route::model('rate', \App\Models\Rate::class);
        Route::model('report', \App\Models\Report::class);
        Route::model('payment', \App\Models\Payment::class);
        Route::model('usefulAccountingResource', \App\Models\UsefulAccountingResource::class);
        Route::model('post', \App\Models\Post::class);
        Route::model('economicActivities', \App\Models\EconomicActivities::class);
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }
}
