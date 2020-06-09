<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\UserRole;
use App\User;
use App\Activity;
use App\HeartRate;

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
        $this->call(PatientActivitySeeder::class);
        $this->call(PatientHeartRateSeeder::class);
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
        $user = User::where('email', '=', 'superadmin@ainayati.com')->first();
        if (!$user) {
            User::create(['email' => 'superadmin@ainayati.com','password' => bcrypt('superadmin123')]);
            UserRole::create(['id_user' => $user->id, 'id_role'=> 1]);
        }
    }
}

class PatientActivitySeeder extends Seeder
{
    public function run()
    {
        Activity::create(['type'  => 'footing', 'distance'   => '10', 'speed'   => '8', 'date_time'   => '2020-01-01 15:00', 'duration'   => '75', 'user_email'   => 'patient10@gmail.com']);
        Activity::create(['type'  => 'walk', 'distance'   => '8.3', 'speed'   => '4', 'date_time'   => '2020-01-03 13:15', 'duration'   => '124.5', 'user_email'   => 'patient10@gmail.com']);
        Activity::create(['type'  => 'footing', 'distance'   => '8', 'speed'   => '9.5', 'date_time'   => '2020-01-04 12:10', 'duration'   => '50.52', 'user_email'   => 'patient10@gmail.com']);
        Activity::create(['type'  => 'walk', 'distance'   => '4.9', 'speed'   => '5.1', 'date_time'   => '2020-01-09 21:00', 'duration'   => '57.64', 'user_email'   => 'patient10@gmail.com']);
        Activity::create(['type'  => 'footing', 'distance'   => '5', 'speed'   => '8', 'date_time'   => '2020-01-12 22:30', 'duration'   => '37.5', 'user_email'   => 'patient10@gmail.com']);
        Activity::create(['type'  => 'walk', 'distance'   => '8.4', 'speed'   => '4.5', 'date_time'   => '2020-01-16 08:00', 'duration'   => '112', 'user_email'   => 'patient10@gmail.com']);
        Activity::create(['type'  => 'walk', 'distance'   => '6.5', 'speed'   => '4.2', 'date_time'   => '2020-01-18 06:22', 'duration'   => '92.85', 'user_email'   => 'patient10@gmail.com']);
        Activity::create(['type'  => 'footing', 'distance'   => '9', 'speed'   => '8', 'date_time'   => '2020-01-20 12:05', 'duration'   => '67.5', 'user_email'   => 'patient10@gmail.com']);
        Activity::create(['type'  => 'walk', 'distance'   => '6', 'speed'   => '5.6', 'date_time'   => '2020-01-23 15:10', 'duration'   => '64.28', 'user_email'   => 'patient10@gmail.com']);
        Activity::create(['type'  => 'footing', 'distance'   => '11', 'speed'   => '8', 'date_time'   => '2020-01-24 09:12', 'duration'   => '82.5', 'user_email'   => 'patient10@gmail.com']);
        Activity::create(['type'  => 'walk', 'distance'   => '7', 'speed'   => '5', 'date_time'   => '2020-02-04 16:15', 'duration'   => '84', 'user_email'   => 'patient10@gmail.com']);
        Activity::create(['type'  => 'walk', 'distance'   => '8', 'speed'   => '4', 'date_time'   => '2020-02-07 18:30', 'duration'   => '120', 'user_email'   => 'patient10@gmail.com']);
        Activity::create(['type'  => 'footing', 'distance'   => '10', 'speed'   => '8', 'date_time'   => '2020-02-08 20:20', 'duration'   => '75', 'user_email'   => 'patient10@gmail.com']);
        Activity::create(['type'  => 'footing',  'distance'   => '5', 'speed'   => '8', 'date_time'   => '2020-01-12 22:30', 'duration'   => '37.5', 'user_email'   => 'patient0@gmail.com']);
        Activity::create(['type'  => 'walk', 'distance'   => '6', 'speed'   => '5.6', 'date_time'   => '2020-01-23 15:10', 'duration'   => '64.28', 'user_email'   => 'patient0@gmail.com']);
        Activity::create(['type'  => 'footing', 'distance'   => '11', 'speed'   => '8', 'date_time'   => '2020-01-24 09:12', 'duration'   => '82.5', 'user_email'   => 'patient0@gmail.com']);
        Activity::create(['type'  => 'walk',  'distance'   => '8.4', 'speed'   => '4.5', 'date_time'   => '2020-01-16 08:00', 'duration'   => '112', 'user_email'   => 'patient0@gmail.com']);
        Activity::create(['type'  => 'walk', 'distance'   => '8', 'speed'   => '4', 'date_time'   => '2020-02-07 18:30', 'duration'   => '120', 'user_email'   => 'patient0@gmail.com']);
        Activity::create(['type'  => 'footing',  'distance'   => '8', 'speed'   => '9.5', 'date_time'   => '2020-01-04 12:10', 'duration'   => '50.52', 'user_email'   => 'patient0@gmail.com']);
    }
}

class PatientHeartRateSeeder extends Seeder
{
    public function run()
    {
        HeartRate::create(['heart_rate'  => '80','date_time'   => '2020-01-01 15:00', 'user_email'   => 'patient10@gmail.com']);
        HeartRate::create(['heart_rate'  => '79','date_time'   => '2020-01-02 12:30', 'user_email'   => 'patient10@gmail.com']);
        HeartRate::create(['heart_rate'  => '85','date_time'   => '2020-01-05 22:25', 'user_email'   => 'patient10@gmail.com']);
        HeartRate::create(['heart_rate'  => '90','date_time'   => '2020-01-08 23:55', 'user_email'   => 'patient10@gmail.com']);
        HeartRate::create(['heart_rate'  => '70','date_time'   => '2020-01-11 11:20', 'user_email'   => 'patient10@gmail.com']);
        HeartRate::create(['heart_rate'  => '75','date_time'   => '2020-01-15 13:05', 'user_email'   => 'patient10@gmail.com']);
        HeartRate::create(['heart_rate'  => '80','date_time'   => '2020-01-28 09:15', 'user_email'   => 'patient10@gmail.com']);
        HeartRate::create(['heart_rate'  => '79','date_time'   => '2020-02-01 08:10', 'user_email'   => 'patient10@gmail.com']);
        HeartRate::create(['heart_rate'  => '82','date_time'   => '2020-02-03 09:45', 'user_email'   => 'patient10@gmail.com']);
        HeartRate::create(['heart_rate'  => '90','date_time'   => '2020-02-04 21:15', 'user_email'   => 'patient10@gmail.com']);
        HeartRate::create(['heart_rate'  => '85','date_time'   => '2020-02-05 22:30', 'user_email'   => 'patient10@gmail.com']);
        HeartRate::create(['heart_rate'  => '69','date_time'   => '2020-03-12 11:20', 'user_email'   => 'patient0@gmail.com']);
        HeartRate::create(['heart_rate'  => '71','date_time'   => '2020-03-13 13:05', 'user_email'   => 'patient0@gmail.com']);
        HeartRate::create(['heart_rate'  => '67','date_time'   => '2020-03-27 09:15', 'user_email'   => 'patient0@gmail.com']);
        HeartRate::create(['heart_rate'  => '68','date_time'   => '2020-04-02 08:10', 'user_email'   => 'patient0@gmail.com']);
        HeartRate::create(['heart_rate'  => '72','date_time'   => '2020-04-05 09:45', 'user_email'   => 'patient0@gmail.com']);
        HeartRate::create(['heart_rate'  => '69','date_time'   => '2020-04-07 21:15', 'user_email'   => 'patient0@gmail.com']);
        HeartRate::create(['heart_rate'  => '70','date_time'   => '2020-04-08 22:30', 'user_email'   => 'patient0@gmail.com']);
    }
}
