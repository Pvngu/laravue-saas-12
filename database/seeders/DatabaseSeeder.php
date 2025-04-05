<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(SubscriptionPlansTableSeeder::class);
        $this->call(LangTableSeeder::class);
        $this->call(CompanyTableSeeder::class);
        $this->call(CurrencyTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(SettingTableSeeder::class);
        $this->call(FormFieldNamesTableSeeder::class);
        $this->call(EmailTemplatesTableSeeder::class);
        $this->call(FormsTableSeeder::class);

        // Creating SuperAdmin
        \App\SuperAdmin\Classes\SuperAdminCommon::createSuperAdmin(true);
    }
}
