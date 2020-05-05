<?php

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Admin
        $hashedPassword = Hash::make('adminpassword123');
        $user = new User();
        $user->name = 'admin';
        $user->email = 'admin@mail.com';
        $user->username = 'admin';
        $user->email_verified_at = Carbon::now();
        $user->division = 'admin';
        $user->password = $hashedPassword;
        $user->assignRole('admin');
        $user->save();

        // Dummy User
        factory(App\User::class)->create();
    }
}
