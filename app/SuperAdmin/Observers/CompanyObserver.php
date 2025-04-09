<?php

namespace App\SuperAdmin\Observers;

use App\Classes\Common;
use App\Models\Company;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use App\SuperAdmin\Classes\SuperAdminCommon;

class CompanyObserver
{

    public function created(Company $company)
    {
        // $company = Common::addCurrencies($company);

        if (!$company->is_global) {
            $company = $this->addAdminRole($company);
            Common::insertInitSettings($company);

            // Adding Default Subscription Plan
            $company =  SuperAdminCommon::addInitialSubscriptionPlan($company);
        }
    }

    public function addAdminRole($company)
    {
        app(PermissionRegistrar::class)->setPermissionsTeamId($company->id);
        
        // Seeding Data
        Role::create([
            'name' => 'admin',
            'company_id' => $company->id,
            'display_name' => 'Admin',
            'description' => 'Admin is allowed to manage everything of the app.',
        ]);

        return $company;
    }
}
