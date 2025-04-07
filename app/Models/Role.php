<?php

namespace App\Models;

use App\Scopes\CompanyScope;
use Spatie\Permission\Contracts\Role as SpatieRole;

class Role extends BaseModel implements SpatieRole
{
    protected  $table = 'roles';

    protected $default = ['xid', 'id', 'name'];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $hidden = ['id'];

    protected $appends = ['xid'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new CompanyScope);
    }

    public function permissions(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(
            config('permission.models.permission'),
            config('permission.table_names.role_has_permissions'),
            'role_id',
            'permission_id'
        );
    }

    public static function findById($id, $guardName = null): SpatieRole
    {
        return static::where('id', $id)->firstOrFail();
    }

    public static function findByName($name, $guardName = null): SpatieRole
    {
        return static::where('name', $name)->firstOrFail();
    }

    public static function findOrCreate($name, $guardName = null): SpatieRole
    {
        return static::firstOrCreate(['name' => $name]);
    }

    public function hasPermissionTo($permission, $guardName = null): bool
    {
        return $this->permissions()->where('name', $permission)->exists();
    }
}
