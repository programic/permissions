<?php

namespace Programic\Permission\Test;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Programic\Permission\Traits\HasPermissions;
use Programic\Permission\Traits\HasRole;

class User extends Model implements AuthorizableContract, AuthenticatableContract
{
    use HasRole;
    use HasPermissions;
    use Authorizable;
    use Authenticatable;

    protected $fillable = ['email'];

    public $timestamps = false;

    protected $table = 'users';
}
