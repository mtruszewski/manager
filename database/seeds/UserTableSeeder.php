<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;

class UserTableSeeder extends Seeder
{
  public function run()
  {
    $role_guest = Role::where('name', 'guest')->first();
    $role_admin  = Role::where('name', 'admin')->first();
    $role_superadmin  = Role::where('name', 'superadmin')->first();

    $guest = new User();
    $guest->name = 'Guest';
    $guest->email = 'test@gmail.com';
    $guest->password = bcrypt('guest');
    $guest->save();
    $guest->roles()->attach($role_guest);

    $superadmin = new User();
    $superadmin->name = 'admin';
    $superadmin->email = 'mariusz.truszewski@gmail.com';
    $superadmin->password = bcrypt('admin');
    $superadmin->save();
    $superadmin->roles()->attach($role_superadmin);
  }
}