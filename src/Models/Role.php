<?php

namespace Programic\Permission\Models;

use Illuminate\Database\Eloquent\Model;
use Programic\Permission\Contracts\Role as RoleContract;
use Programic\Permission\PermissionRegistrar;
use Programic\Permission\Traits\HasPermissions;


class Role extends Model implements RoleContract
{
    protected $guarded = [];
    protected $hidden = ['guard'];
    protected $primaryKey = 'name';

    public $timestamps = false;
    public $incrementing = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class)
            ->using(PermissionRole::class);
    }

    public function users()
    {
        return $this->belongsToMany(app(PermissionRegistrar::class)->getUserClass(), 'role_user')
            ->withPivot(['target_type', 'target_id']);
    }

    public function targetUsers($target)
    {
        return $this->belongsToMany(app(PermissionRegistrar::class)->getUserClass(), 'role_user')
            ->wherePivot('target_type', get_class($target))
            ->wherePivot('target_id', $target->id)
            ->withPivot(['target_type', 'target_id']);
    }

    /**
     * @param array|string $permissions
     * @return $this
     */
    public function givePermission($permissions) : self
    {
        if (is_array($permissions) === false) {
            $permissions = [$permissions];
        }

        foreach ($permissions as $permission) {
            $this->permissions()->attach($permission);
        }

        return $this;
    }

    /**
     * @param array|string $permissions
     * @return $this
     */
    public function revokePermission($permissions) : self
    {
        if (is_array($permissions) === false) {
            $permissions = [$permissions];
        }

        foreach ($permissions as $permission) {
            $this->permissions()->detach($permission);
        }

        return $this;
    }

    /**
     * @param $permission
     * @param null $target
     * @return bool
     */
    public function hasPermission($permission, $target = null) : bool
    {
        return $this->permissions()->where('name', $permission)->exists();
    }
}
