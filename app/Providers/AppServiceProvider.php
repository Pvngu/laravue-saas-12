<?php

namespace App\Providers;

use App\Models\Company;
use App\Models\Currency;
use App\Models\Role;
use App\Models\Settings;
use App\Models\User;
use App\Models\EmailTemplate;
use App\Models\Form;
use App\Models\FormFieldName;
use App\Observers\CurrencyObserver;
use App\Observers\RoleObserver;
use App\Observers\EmailTemplateObserver;
use App\Observers\FormObserver;
use App\Observers\FormFieldNameObserver;
use App\Observers\SettingObserver;
use App\Observers\UserObserver;
use App\SuperAdmin\Models\SuperAdmin;
use App\SuperAdmin\Observers\SuperAdminObserver;
use App\SuperAdmin\Observers\CompanyObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    protected $namespace = 'App\\Http\\Controllers';

    protected $superAdminNamespace = 'App\\SuperAdmin\\Http\\Controllers';

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        User::observe(UserObserver::class);
        Settings::observe(SettingObserver::class);
        Currency::observe(CurrencyObserver::class);
        Role::observe(RoleObserver::class);
        EmailTemplate::observe(EmailTemplateObserver::class);
        Form::observe(FormObserver::class);
        FormFieldName::observe(FormFieldNameObserver::class);
        Company::observe(CompanyObserver::class);
        SuperAdmin::observe(SuperAdminObserver::class);
    }
}
