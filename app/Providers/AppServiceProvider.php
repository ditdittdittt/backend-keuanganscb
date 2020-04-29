<?php

namespace App\Providers;

use App\FormPettyCash;
use App\FormRequest;
use App\FormSubmission;
use App\Observers\FormPettyCashObserver;
use App\Observers\FormRequestObserver;
use App\Observers\FormSubmissionObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Schema\Builder;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        config(['app.locale' => 'id']);
        \Carbon\Carbon::setLocale('id');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Builder::defaultStringLength(191);

        Passport::routes();

        // Observer
        FormPettyCash::observe(FormPettyCashObserver::class);
        FormRequest::observe(FormRequestObserver::class);
        FormSubmission::observe(FormSubmissionObserver::class);
    }
}
