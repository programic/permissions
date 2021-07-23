<?php

namespace Programic\Permission;

use \Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class PermissionQueryBuilder
{
    private $query = null;

    protected $target;
    protected $includeUp = false;
    protected $includeDown = false;
    protected $userId = null;
    protected $permissions = [];

    public function __construct($userId = null, $target = null)
    {
        $this->userId = $userId ?? auth()->user()->id;
        if ($target) {
            $this->target = $target;
        }
    }

    public function __call($name, $arguments)
    {
        return $this->buildQuery()->get()->{$name}($arguments);
    }

    public function get() : Collection
    {
        return $this->buildQuery()->query->get();
    }

    public function permission($permission)
    {
        if (is_array($permission)) {
            return $this->permissions($permission);
        }

        $this->permissions[] = $permission;

        return $this;
    }

    public function permissions(array $permissions)
    {
        $this->permissions = array_merge($this->permissions, $permissions);

        return $this;
    }

    private function buildQuery() : self
    {
        if ($this->query !== null) {
            return $this->query;
        }

        $query = app(PermissionRegistrar::class)->getPermissionUserClass()->query();

        if ($this->target) {
            $targetPath = array_search(get_class($this->target), config('permission.targets'));
            if (method_exists($this->target, 'targetPath')) {
                $targetPath = $this->target->targetPath();
            }

            $query->where(function ($query) use ($targetPath) {
                $query->whereNull('target_path')
                    ->orWhere('target_path', 'LIKE', $targetPath . '%');

                if ($this->includeDown) {
                    $query->orWhere(function ($query) use ($targetPath) {
                        $paths = explode('-', $targetPath);
                        for ($i = 0; $i < substr_count($targetPath, '-'); $i++) {
                            array_pop($paths);
                            $targetPathDown = (is_array($paths)) ? implode('-', $paths) : $paths;
                            if ($targetPathDown) {
                                $query->orWhere('target_path', 'LIKE', $targetPathDown . '%');
                            }
                        }
                    });
                }
            });
        }

        if ($this->permissions) {
            $query->whereIn('permission_name', $this->permissions);
        }

        if ($this->userId) {
            $query->where('user_id', $this->userId);
        }

        $this->query = $query;

        return $this;
    }

    public function getQuery() : Builder
    {
        return $this->buildQuery()->query;
    }

    public function includeUp(bool $bool = true) : self
    {
        $this->includeUp = $bool;

        return $this;
    }

    public function includeDown(bool $bool = true) : self
    {
        $this->includeDown = $bool;

        return $this;
    }

    public function user(int $userId) : self
    {
        $this->userId = $userId;

        return $this;
    }

    public static function setGlobalScope(
        EloquentBuilder $builder,
        $permissions,
        $model,
        $userId = null
    ) : EloquentBuilder {
        if (auth()->check() || $userId) {
            if (is_array($permissions) === false) {
                $permissions = [$permissions];
            }

            if ($userId) {
                $user = app(PermissionRegistrar::class)->getUserClass()->find($userId);
            } else {
                $user = auth()->user();
            }

            $targets = $user->getTargetsFromPermissions($permissions, $model);
            $permissionsConfig = config('permission');

            $builder->where(function ($query) use ($targets, $permissions, $permissionsConfig) {
                foreach ($permissionsConfig['abbreviation'] as $abbreviation => $model) {
                    $tableName = (new $model)->getTable();
                    $permissionName = Str::singular($tableName);
                    $globalPermission = $permissionsConfig['query_builder']['global_permission'];
                    $permission = $globalPermission . '-' . $permissionName;
                    $magazinePermission = in_array($permission, $permissions)
                        && $targets['null_' . $abbreviation] === false;

                    $query->when($magazinePermission, function ($permissionQuery) use (
                        $targets,
                        $tableName,
                        $abbreviation
                    ) {
                        $permissionQuery->whereIn($tableName . '.id', $targets['all_' . $abbreviation]);
                    });
                }
            });
        }

        return $builder;
    }
}
