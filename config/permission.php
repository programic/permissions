<?php
return [

    /**
     * Specify the abbreviation for the namespace.
     * Example:
     *  1. m => Model1::class
     *  2. e => Model2::class
     */
    'abbreviation' => [],

    /**
     *  Specify the target sequence based on the abbreviation.
     *  Example:
     *  1. m*
     *  2. m*-e*
     */
    'targets' => [],

    'models' => [

        /*
         *  Specify custom user model
         *  Default is the model specified in config -> auth.providers.users.model
         */
        'user' => null,

        /*
         * When using the "HasPermissions" trait from this package, we need to know which
         * Eloquent model should be used to retrieve your permissions. Of course, it
         * is often just the "Permission" model but you may use whatever you like.
         *
         * The model you want to use as a Permission model needs to implement the
         * `Programic\Permission\Contracts\Permission` contract.
         */

        'permission' => Programic\Permission\Models\Permission::class,

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * Eloquent model should be used to retrieve your roles. Of course, it
         * is often just the "Role" model but you may use whatever you like.
         *
         * The model you want to use as a Role model needs to implement the
         * `Programic\Permission\Contracts\Role` contract.
         */

        'role' => Programic\Permission\Models\Role::class,

        'role_user' => Programic\Permission\Models\RoleUser::class,

        'permission_user' => Programic\Permission\Models\PermissionUser::class,

        'permission_role' => Programic\Permission\Models\PermissionRole::class,

        'permission_inheritance' => Programic\Permission\Models\PermissionInheritance::class,

    ],

    /*
    * Query Builder Settings
    */
    'query_builder' => [
        /*
         *  Permissions for the global scope.
         *  This setting determines which permission is the main permission for going deeper/ higher in the order.
         *  Use
         */
        'global_permission' => 'view',

        /*
         *  Permission Query Builder
         *  where the magic happens
         */
        'class' => Programic\Permission\PermissionQueryBuilder::class,
    ],
];

