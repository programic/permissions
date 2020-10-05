# Programic - Roles & Permissions

[![Latest Version on Packagist](https://img.shields.io/packagist/v/programichq/laravel-permission.svg?style=flat-square)](https://packagist.org/packages/programichq/laravel-permission)
[![Total Downloads](https://img.shields.io/packagist/dt/programichq/laravel-permission.svg?style=flat-square)](https://packagist.org/packages/programichq/laravel-permission)

This package allows you to manage target specific user permissions and roles in a database.

### Installation
This package requires PHP 7.2 and Laravel 5.8 or higher.

```
composer require programichq/permissions
```

### Setup
Publish the config & migration files

```ssh
php artisan vendor:publish --provider="Programic\Permission\PermissionServiceProvider"
```

### Configuration
- 
- Add the HasPermission & HasRole trait to the User model
- Add the HasEntityPermissions trait to the Target model
### Usage
##### Auth model
