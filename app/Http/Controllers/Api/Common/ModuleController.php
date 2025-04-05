<?php

namespace App\Http\Controllers\Api\Common;

use App\Classes\Common;
use App\Classes\LangTrans;
use App\Http\Controllers\ApiBaseController;
use Examyou\RestAPI\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Nwidart\Modules\Facades\Module;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use ZanySoft\Zip\Zip;

class ModuleController extends ApiBaseController
{
    public function index()
    {
        $modulesData = Common::moduleInformations();

        return ApiResponse::make('Data fetched', $modulesData);
    }

    public function updateStatus(Request $request)
    {
        $moduleName = $request->verified_name;
        $checked = $request->checked;

        $module = Module::find($moduleName);

        if ($checked) {
            $module->enable();

            Artisan::call('module:migrate', ['module' => $moduleName, '--force' => true]);
        } else {
            $module->disable();
        }

        $modulesData = Common::moduleInformations();

        return ApiResponse::make('Success', $modulesData);
    }

    public function getModuleVersion($moduleName)
    {
        $module = Module::find($moduleName);

        if ($module) {
            $modulePath = $module->getPath();
            $versionFileName = app_type() == 'saas' ? 'superadmin_version.txt' : 'version.txt';
            $version = File::get($modulePath . '/' . $versionFileName);

            return preg_replace("/\r|\n/", "", $version);
        }

        return "-";
    }
}
