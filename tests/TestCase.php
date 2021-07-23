<?php

namespace Programic\Permission\Test;

use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as Orchestra;
use Programic\Permission\Models\Permission;
use Programic\Permission\Models\Role;
use Programic\Permission\PermissionServiceProvider;

abstract class TestCase extends Orchestra
{
    /** @var \Progrmaic\Permission\Test\User */
    protected $testUser;

    /** @var \Programic\Permission\Models\Role */
    protected $testRole;

    public function setUp(): void
    {
        parent::setUp();

        // Note: this also flushes the cache from within the migration
        $this->setUpDatabase($this->app);

        $this->testUser = User::first();
        $this->testRole = Role::first();

    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            PermissionServiceProvider::class,
        ];
    }

    /**
     * Set up the environment.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        // Use test User model for users provider
        $app['config']->set('auth.providers.users.model', User::class);
    }

    protected function setUpDatabase($app)
    {
        $app['config']->set('permission.column_names.model_morph_key', 'model_test_id');

        $app['db']->connection()->getSchemaBuilder()->create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email');
            $table->softDeletes();
        });

        include_once __DIR__.'/../database/migrations/1_create_roles_table.php.stub';
        include_once __DIR__.'/../database/migrations/2_create_permissions_table.php.stub';
        include_once __DIR__.'/../database/migrations/3_create_permission_role.php.stub';
        include_once __DIR__.'/../database/migrations/4_create_permission_inheritances_table.php.stub';
        include_once __DIR__.'/../database/migrations/5_create_role_user.php.stub';
        include_once __DIR__.'/../database/migrations/6_create_permission_user.php.stub';

        (new \CreateRolesTable())->up();
        (new \CreatePermissionsTable())->up();
        (new \CreatePermissionRole())->up();
        (new \CreatePermissionInheritancesTable())->up();
        (new \CreateRoleUser())->up();
        (new \CreatePermissionUser())->up();

        User::create(['email' => 'test@user.com']);

        $app[Role::class]->create(['name' => 'testRole']);
        $app[Role::class]->create(['name' => 'testRole2']);

        $app[Permission::class]->create(['name' => 'edit-articles']);
        $app[Permission::class]->create(['name' => 'edit-news']);
        $app[Permission::class]->create(['name' => 'edit-blog']);
        $app[Permission::class]->create(['name' => 'admin-permission', 'guard_name' => 'admin']);
        $app[Permission::class]->create(['name' => 'Edit News']);
    }
}
