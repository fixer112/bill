<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'create user']);
        Permission::create(['name' => 'view user']);
        Permission::create(['name' => 'view all users']);
        Permission::create(['name' => 'update user']);
        Permission::create(['name' => 'suspend user']);
        Permission::create(['name' => 'delete user']);

        Permission::create(['name' => 'debit wallet']);
        Permission::create(['name' => 'fund wallet']);
        Permission::create(['name' => 'send mass mail']);

        Permission::create(['name' => 'manage roles']);

// create roles and assign created permissions

// this can be done as separate statements
        //$role = Role::create(['name' => 'marketer']);
        //$role->givePermissionTo('edit articles');

// or may be done by chaining
        $role = Role::create(['name' => 'super admin'])
            ->givePermissionTo(Permission::all());

        $role = Role::create(['name' => 'staff'])
            ->givePermissionTo([
                'view user',
                'update user',
                'view all users',

            ]);

        $role = Role::create(['name' => 'share holder'])
            ->givePermissionTo([
                'view all users',
            ]);

        $role = Role::create(['name' => 'customer care'])
            ->givePermissionTo([
                'view all users',
            ]);

    }
}