<?php

namespace Programic\Permission\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PermissionRole extends Pivot
{
    public $incrementing = true;
    public $timestamps = false;

    protected $guarded = ['id'];

    public function role()
    {
        return $this->belongsTo(config('permission.models')['role']);
    }

    public function permission()
    {
        return $this->belongsTo(config('permission.models')['permission']);
    }
}
