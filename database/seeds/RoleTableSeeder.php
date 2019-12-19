<?php

use Illuminate\Database\Seeder;
use App\Role;
class RoleTableSeeder extends Seeder
{
  public function run()
  {
    $role_superadmin = new Role();
    $role_superadmin->name = 'superadmin';
    $role_superadmin->description = 'Super Admin';
    $role_superadmin->save();

    $role_admin = new Role();
    $role_admin->name = 'admin';
    $role_admin->description = 'admin';
    $role_admin->save();
    
    $role_guest = new Role();
    $role_guest->name = 'guest';
    $role_guest->description = 'guest';
    $role_guest->save();
  }
}