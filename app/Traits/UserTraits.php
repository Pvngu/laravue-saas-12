<?php

namespace App\Traits;

use App\Models\Role;
use App\Classes\Common;
use App\Classes\Notify;
use App\Imports\UserImport;
use App\Scopes\CompanyScope;
use Examyou\RestAPI\ApiResponse;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Examyou\RestAPI\Exceptions\ApiException;
use App\Http\Requests\Api\User\ImportRequest;

trait UserTraits
{
    public $userType = "";

    public function modifyIndex($query)
    {

        $query = $query->where('users.user_type', $this->userType);

        return $query;
    }

    public function storing($user)
    {
        $loggedUser = user();
        $request = request();

        if ($user->user_type != $this->userType) {
            throw new ApiException("Don't have valid permission");
        }

        if ($user->user_type == 'staff_members') {
            $user->role_id = $loggedUser->hasRole('admin') && $request->has('role_id') && $request->role_id ? $this->getIdFromHash($request->role_id) : $loggedUser->role_id;
        }

        return $user;
    }

    public function stored($user)
    {
        $this->saveAndUpdateRole($user);

        // Notifying to Company
        Notify::send('staff_member_create', $user);

        // Updating Company Total Users
        Common::calculateTotalUsers($user->company_id, true);
    }

    public function updating($user)
    {
        $loggedUser = user();
        $request = request();

        // Can not change role because only one
        // Admin exists for whole app
        if ($user->user_type == "staff_members") {
            $adminRoleUserCount = Role::join('role_user', 'roles.id', '=', 'role_user.role_id')
                ->where('roles.name', '=', 'admin')
                ->count('role_user.user_id');

            if ($adminRoleUserCount <= 1 && $user->isDirty('role_id')) {
                throw new ApiException("Can not change role because you are only admin of app");
            }
        }

        if ($user->user_type != $this->userType) {
            throw new ApiException("Don't have valid permission");
        }

        // Demo mode admin cannot be edit
        // email, password, status, role_id
        if (env('APP_ENV') == 'production' && ($user->isDirty('password') || $user->isDirty('email') || $user->isDirty('status') || $user->isDirty('role_id'))) {
            if ($user->user_type == 'staff_members' && ($user->getOriginal('email') == 'admin@example.com' || $user->getOriginal('email') == 'stockmanager@example.com' || $user->getOriginal('email') == 'salesman@example.com')) {
                throw new ApiException('Not Allowed In Demo Mode');
            }
        }

        if ($user->user_type == 'staff_members') {
            $user->role_id = $loggedUser->hasRole('admin') && $request->has('role_id') && $request->role_id ? $this->getIdFromHash($request->role_id) : $loggedUser->role_id;
        }

        return $user;
    }

    public function updated($user)
    {
        $this->saveAndUpdateRole($user);

        // Notifying to Company
        Notify::send('staff_member_update', $user);
    }

    public function saveAndUpdateRole($user)
    {
        $request = request();
        error_log('User Type: ' . $user);

        // Only For Staff Members
        if ($user->user_type == 'staff_members') {
            $adminRole = Role::withoutGlobalScope(CompanyScope::class)->where('name', 'admin')->where('company_id', $user->company_id)->first();

            $user->roles()->detach();
            $user->assignRole($adminRole->name, '');
        }

        return $user;
    }

    public function destroying($user)
    {
        if ($user->user_type != $this->userType) {
            throw new ApiException("Don't have valid permission");
        }

        $loggedUser = user();
        $loggedUserCompany = company();

        if ($loggedUserCompany->admin_id == $user->id) {
            throw new ApiException('Can not delete company root admin');
        }

        if (env('APP_ENV') == 'production' && $user->user_type == 'staff_members' && ($user->getOriginal('email') == 'admin@example.com' || $user->getOriginal('email') == 'stockmanager@example.com' || $user->getOriginal('email') == 'salesman@example.com')) {
            throw new ApiException('Not Allowed In Demo Mode');
        }

        // If application have only one admin
        // Then staff member cannot be deleted
        if ($user->user_type == "staff_members") {
            if ($user->role_id) {

                // if ($user->hasRole('admin')) {
                //     $adminRoleUserCount = Role::join('role_user', 'roles.id', '=', 'role_user.role_id')
                //         ->where('roles.name', '=', 'admin')
                //         ->count('role_user.user_id');

                //     if ($adminRoleUserCount <= 1) {
                //         throw new ApiException('You are the only admin of app. So not able to delete.');
                //     }
                // }
            }
        }

        if ($loggedUser->id == $user->id) {
            throw new ApiException('Can not delete yourself.');
        }

        return $user;
    }

    public function destroyed($user)
    {
        // Updating Company Total Users
        Common::calculateTotalUsers($user->company_id, true);

        // Notifying to Company
        Notify::send('staff_member_delete', $user);
    }

    public function import(ImportRequest $request)
    {
        if ($request->hasFile('file')) {
            Excel::import(new UserImport($this->userType), request()->file('file'));
        }

        return ApiResponse::make('Imported Successfully', []);
    }
};