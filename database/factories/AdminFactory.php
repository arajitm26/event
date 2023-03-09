<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Admin;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
$factory->define(Admin::class, function (Faker $faker) {
    return [
        'name' => "Arajit Mondal",
        'email' => "admin@gmail.com",
        'phone_no' => "1234567899",
        'parent_id' => "0",
        'role_id' => "0",
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
    ];
});
