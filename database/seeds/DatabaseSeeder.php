<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\UserRole;
use App\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleTableSeeder::class);
        $this->call(SuperAdminSeeder::class);
    }
}
class RoleTableSeeder extends Seeder
{
    public function run()
    {
        Role::create(['type' => 'super admin']);
        Role::create(['type' => 'admin']);
        Role::create(['type' => 'doctor']);
        Role::create(['type' => 'nurse']);
        Role::create(['type' => 'patient']);
    }
}
class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        $user = User::create(['email' => 'superadmin@ainayati.com','password' => bcrypt('superadmin123')]);
        UserRole::create(['id_user' => $user->id, 'id_role'=> 1]);
    }
}
