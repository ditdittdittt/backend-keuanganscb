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
        $hashedPassword = Hash::make('password');
        $users = [
            "admin" => [
                "name" => "admin",
                "email" => "admin@mail.com",
                "username" => "admin",
                "email_verified_at" => Carbon::now(),
                "division" => "admin",
                "password" => $hashedPassword,
            ],
            "pic" => [
                "name" => "pic1",
                "email" => "pic1@mail.com",
                "username" => "pic1",
                "email_verified_at" => Carbon::now(),
                "division" => "pic1",
                "password" => $hashedPassword,
            ],
            "verificator" => [
                "name" => "verificator1",
                "email" => "verificator1@mail.com",
                "username" => "verificator1",
                "email_verified_at" => Carbon::now(),
                "division" => "verificator1",
                "password" => $hashedPassword,
            ],
            "head_dept" => [
                "name" => "head_dept1",
                "email" => "head_dept1@mail.com",
                "username" => "head_dept1",
                "email_verified_at" => Carbon::now(),
                "division" => "head_dept1",
                "password" => $hashedPassword,
            ],
            "head_office" => [
                "name" => "head_office1",
                "email" => "head_office1@mail.com",
                "username" => "head_office1",
                "email_verified_at" => Carbon::now(),
                "division" => "head_office1",
                "password" => $hashedPassword,
            ],
            "cashier" => [
                "name" => "cashier1",
                "email" => "cashier1@mail.com",
                "username" => "cashier1",
                "email_verified_at" => Carbon::now(),
                "division" => "cashier1",
                "password" => $hashedPassword,
            ],
            "manager_ops" => [
                "name" => "manager_ops1",
                "email" => "manager_ops1@mail.com",
                "username" => "manager_ops1",
                "email_verified_at" => Carbon::now(),
                "division" => "manager_ops1",
                "password" => $hashedPassword,
            ],
        ];

        foreach ($users as $role => $user) {
            $user = User::create($user);
            $user->assignRole($role);
            $user->save();
        }
    }
}
