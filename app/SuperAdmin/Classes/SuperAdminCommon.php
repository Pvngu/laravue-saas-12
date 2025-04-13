<?php

namespace App\SuperAdmin\Classes;

use Carbon\Carbon;
use App\Models\Lang;
use App\Classes\Common;
use App\Models\Currency;
use App\Models\SuperAdmin;
use App\Scopes\CompanyScope;
use App\Models\SubscriptionPlan;
use Illuminate\Database\Eloquent\Model;
use App\SuperAdmin\Models\GlobalCompany;
use App\SuperAdmin\Models\GlobalSettings;

class SuperAdminCommon
{
    public static function createGlobalPaymentSettings($company)
    {
        if ($company->is_global == 1) {
            // For Superadmin Payment Gateway
            // Paypal
            $paypal = new GlobalSettings();
            $paypal->is_global = 1;
            $paypal->company_id = $company->id;
            $paypal->setting_type = 'payment_settings';
            $paypal->name = 'Paypal Payment Settings';
            $paypal->name_key = 'paypal';
            $paypal->credentials = [
                'paypal_client_id' => '',
                'paypal_secret' => '',
                'paypal_mode' => 'sandbox',
                'paypal_status' => 'active',
            ];
            $paypal->status = 1; // Also Remove this
            $paypal->save();

            // Stripe
            $stripe = new GlobalSettings();
            $stripe->is_global = 1;
            $stripe->company_id = $company->id;
            $stripe->setting_type = 'payment_settings';
            $stripe->name = 'Stripe Payment Settings';
            $stripe->name_key = 'stripe';
            $stripe->credentials = [
                'stripe_api_key' => '',
                'stripe_api_secret' => '',
                'stripe_webhook_key' => '',
                'stripe_status' => 'active',
            ];
            $stripe->status = 1; // Also Remove this
            $stripe->save();

            // Razorpay
            $razorpay = new GlobalSettings();
            $razorpay->is_global = 1;
            $razorpay->company_id = $company->id;
            $razorpay->setting_type = 'payment_settings';
            $razorpay->name = 'Razorpay Payment Settings';
            $razorpay->name_key = 'razorpay';
            $razorpay->credentials = [
                'razorpay_key' => '',
                'razorpay_secret' => '',
                'razorpay_webhook_secret' => '',
                'razorpay_status' => 'active',
            ];
            $razorpay->status = 1; // Also Remove this
            $razorpay->save();

            // Paystack
            $paystack = new GlobalSettings();
            $paystack->is_global = 1;
            $paystack->company_id = $company->id;
            $paystack->setting_type = 'payment_settings';
            $paystack->name = 'Paystack Payment Settings';
            $paystack->name_key = 'paystack';
            $paystack->credentials = [
                'paystack_client_id' => '',
                'paystack_secret' => '',
                'paystack_merchant_email' => '',
                'paystack_status' => 'inactive',
            ];
            $paystack->save();

            // Mollie
            $mollie = new GlobalSettings();
            $mollie->is_global = 1;
            $mollie->company_id = $company->id;
            $mollie->setting_type = 'payment_settings';
            $mollie->name = 'Mollie Payment Settings';
            $mollie->name_key = 'mollie';
            $mollie->credentials = [
                'mollie_api_key' => '',
                'mollie_status' => 'inactive',
            ];
            $mollie->save();

            // Authorize
            $authorize = new GlobalSettings();
            $authorize->is_global = 1;
            $authorize->company_id = $company->id;
            $authorize->setting_type = 'payment_settings';
            $authorize->name = 'Authorize Payment Settings';
            $authorize->name_key = 'authorize';
            $authorize->credentials = [
                'authorize_api_login_id' => '',
                'authorize_transaction_key' => '',
                'authorize_signature_key' => '',
                'authorize_environment' => 'sandbox',
                'authorize_status' => 'inactive',
            ];
            $authorize->save();
        }
    }

    public static function addWebsiteImageUrl($settingData, $keyName)
    {
        if ($settingData && array_key_exists($keyName, $settingData)) {
            if ($settingData[$keyName] != '') {
                $imagePath = Common::getFolderPath('websiteImagePath');

                $settingData[$keyName . '_url'] = Common::getFileUrl($imagePath, $settingData[$keyName]);
            } else {
                $settingData[$keyName] = null;
                $settingData[$keyName . '_url'] = asset('images/website.png');
            }
        }

        return $settingData;
    }

    public static function addUrlToAllSettings($allSettings, $keyName)
    {
        $allData = [];

        foreach ($allSettings as $allSetting) {
            $allData[] = self::addWebsiteImageUrl($allSetting, $keyName);
        }

        return $allData;
    }

    public static function getAppPaymentSettings($showType = 'limited')
    {
        $allPaymentMethods = GlobalSettings::withoutGlobalScope(CompanyScope::class)->where('setting_type', 'payment_settings')
            ->where('status', 1)
            ->get();

        if ($showType == 'limited') {
            foreach ($allPaymentMethods as $allPaymentMethod) {
                if ($allPaymentMethod->name_key == 'paypal') {
                    $allPaymentMethod->credentials = [
                        'paypal_client_id' => $allPaymentMethod->credentials['paypal_client_id'],
                        'paypal_mode' => $allPaymentMethod->credentials['paypal_mode'],
                        'paypal_status' => $allPaymentMethod->credentials['paypal_status'],
                    ];
                } else if ($allPaymentMethod->name_key == 'stripe') {
                    $allPaymentMethod->credentials = [
                        'stripe_api_key' => $allPaymentMethod->credentials['stripe_api_key'],
                        'stripe_status' => $allPaymentMethod->credentials['stripe_status'],
                    ];
                } else if ($allPaymentMethod->name_key == 'razorpay') {
                    $allPaymentMethod->credentials = [
                        'razorpay_key' => $allPaymentMethod->credentials['razorpay_key'],
                        'razorpay_status' => $allPaymentMethod->credentials['razorpay_status'],
                    ];
                } else if ($allPaymentMethod->name_key == 'paystack') {
                    $allPaymentMethod->credentials = [
                        'paystack_client_id' => $allPaymentMethod->credentials['paystack_client_id'],
                        'paystack_status' => $allPaymentMethod->credentials['paystack_status'],
                    ];
                } else if ($allPaymentMethod->name_key == 'mollie') {
                    $allPaymentMethod->credentials = [
                        'mollie_api_key' => $allPaymentMethod->credentials['mollie_api_key'],
                        'mollie_status' => $allPaymentMethod->credentials['mollie_status'],
                    ];
                } else if ($allPaymentMethod->name_key == 'authorize') {
                    $allPaymentMethod->credentials = [
                        'authorize_api_login_id' => $allPaymentMethod->credentials['authorize_api_login_id'],
                        'authorize_environment' => $allPaymentMethod->credentials['authorize_environment'],
                        'authorize_status' => $allPaymentMethod->credentials['authorize_status'],
                    ];
                }
            }
        }


        return $allPaymentMethods;
    }

    public static function createSuperAdmin($resetAdminCompany = false)
    {
        $enLang = Lang::where('key', 'en')->first();

        // Global Company for superadmin
        // Added here because on creating company observer will call
        // And on observer currency will be created
        $globalCompany = new GlobalCompany();
        $globalCompany->is_global = 1;
        $globalCompany->name = 'Larakit SAAS';
        $globalCompany->short_name = 'LaraKitSaas';
        $globalCompany->email = 'superadmin_company@example.com';
        $globalCompany->phone = '+9199999999';
        $globalCompany->address = '7 street, city, state, 762782';
        $globalCompany->verified = true;
        $globalCompany->lang_id = $enLang->id;
        if ($resetAdminCompany) {
            $globalCompany->white_label_completed = 1;
        }
        $globalCompany->save();

        if (env('APP_ENV') == 'production') {
            Common::addCurrencies($globalCompany);
        }

        $superAdmin = new SuperAdmin();
        $superAdmin->company_id = $globalCompany->id;
        $superAdmin->name = 'Super Admin';
        $superAdmin->email = 'superadmin@example.com';
        $superAdmin->password = '12345678';
        $superAdmin->is_superadmin = true;
        $superAdmin->user_type = 'super_admins';
        $superAdmin->status = 'enabled';
        $superAdmin->save();

        // Creating SuperAdmin

        $globalCompany->admin_id = $superAdmin->id;
        $globalCompany->save();

        $usdCurrency = new Currency();
        $usdCurrency->company_id = $superAdmin->id;
        $usdCurrency->name = 'Dollar';
        $usdCurrency->code = 'USD';
        $usdCurrency->symbol = '$';
        $usdCurrency->position = 'front';
        $usdCurrency->is_deletable = false;
        $usdCurrency->save();

        $globalCompany->currency_id = $usdCurrency->id;
        $globalCompany->save();

        // Settings
        Common::insertInitSettings($globalCompany);

        self::createGlobalPaymentSettings($globalCompany);
    }

    public static function formatAmountCurrency($amount)
    {
        $newAmount = $amount;
        $superAdminCurrency = GlobalCompany::select('id', 'currency_id')->with('currency')->first();

        if ($superAdminCurrency->currency->position == "front") {
            $newAmountString = $superAdminCurrency->currency->symbol . '' . $newAmount;
        } else {
            $newAmountString = $newAmount . '' . $superAdminCurrency->currency->symbol;
        }

        return $newAmountString < 0 ? $newAmountString : $newAmountString;
    }

    public static function createSubscriptionPlans()
    {
        // Inseting Subscription Plans
        $defaultPlan = new SubscriptionPlan();
        $defaultPlan->name                    = 'Default';
        $defaultPlan->description             = 'Its a default package and cannot be deleted';
        $defaultPlan->annual_price            = 0;
        $defaultPlan->monthly_price           = 0;
        $defaultPlan->max_users            = 5;
        $defaultPlan->stripe_annual_plan_id   = 'default_plan';
        $defaultPlan->stripe_monthly_plan_id  = 'default_plan';
        $defaultPlan->default                 = 'yes';
        $defaultPlan->modules = [
            // Add modules here
        ];
        $defaultPlan->features = [];
        $defaultPlan->save();

        // Trail Subscription Plan
        $trailPlan = new SubscriptionPlan();
        $trailPlan->name                  = 'Trail';
        $trailPlan->description             = 'Its a trial package';
        $trailPlan->annual_price            = 0;
        $trailPlan->monthly_price           = 0;
        $trailPlan->max_users           = env('APP_ENV') == 'production' ? 200 : 5;
        $trailPlan->stripe_annual_plan_id   = 'trial_plan';
        $trailPlan->stripe_monthly_plan_id  = 'trial_plan';
        $trailPlan->default                 = 'trial';
        $trailPlan->modules = [
            // Add modules here
        ];
        $defaultPlan->features = [];
        $trailPlan->save();
    }

    public static function addInitialSubscriptionPlan($company)
    {
        // Adding trial or default plan as initial plan
        $trialPlan = SubscriptionPlan::where('default', 'trial')->first();
        $defaultPlan = SubscriptionPlan::where('default', 'yes')->first();

        // if trial package is active set package to company
        if ($trialPlan && $trialPlan->active == 1) {
            $company->subscription_plan_id = $trialPlan->id;

            // set company license expire date
            $company->licence_expire_on = Carbon::now()->addDays($trialPlan->duration)->format('Y-m-d');

            $company->save();
        }

        return $company;
    }
}
