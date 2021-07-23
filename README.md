# Programic - Roles & Permissions

[![Latest Version on Packagist](https://img.shields.io/packagist/v/programic/permissions.svg?style=flat-square)](https://packagist.org/packages/programic/laravel-permission)
![](https://github.com/programic/permissions/workflows/Run%20Tests/badge.svg?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/programic/permissions.svg?style=flat-square)](https://packagist.org/packages/programic/permissions)

This package allows you to manage target specific user permissions and roles in a database.

### Installation
This package requires PHP 7.2 and Laravel 5.8 or higher.

```
composer require programic/permissions
```

### Setup
Publish the config & migration files

```ssh
php artisan vendor:publish --provider="Programic\Permission\PermissionServiceProvider"
```

### Configuration
- Add the HasPermission & HasRole trait to the User model
```php
use Illuminate\Foundation\Auth\User as Authenticatable;
use Programic\Permission\Traits\HasPermissions;
use Programic\Permission\Traits\HasRole;

class User extends Authenticatable
{
    use HasPermissions;
    use HasRole;

    // ...
}
```
- Add the HasEntityPermissions trait to the Target model
```php
use Illuminate\Database\Eloquent\Model;
use Programic\Permission\Traits\HasEntityPermissions;

class Article extends Model
{
    use HasEntityPermissions;

    // ...
}
```

### Basic Usage
```php
// Assign global role to user
$user->assignRole('writer');

// Assign target specific role to user
$user->assignRole('writer', $model);

// Adding global permission to role
$role->givePermission('view-article');

```


### Testing
```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security-related issues, please email [info@programic.com](mailto:info@programic.com) instead of using the issue tracker.

## Credits

- [Rick Bongers](https://github.com/rbongers)
- [Arjen Zwerver](https://github.com/arjenprogramic)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
