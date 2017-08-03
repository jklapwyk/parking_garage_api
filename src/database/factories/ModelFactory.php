<?php

use App\Models\User;
use App\Models\CurrencyType;
use App\Models\ParkingTicket;
use App\Models\ParkingVenue;
use App\Models\ParkingVenuePriceTier;
use App\Models\ParkingVenueQueue;
use App\Models\PriceTier;
use App\Models\UserParkingTicket;
use Webpatser\Uuid\Uuid;
/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'first_name' => $faker->name,
        'last_name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'user_hash' => Uuid::generate()->string,
        'is_registered' => 1,
    ];
});

$factory->define(ParkingTicket::class, function (Faker\Generator $faker) {
    return [];
});

$factory->define(ParkingVenue::class, function (Faker\Generator $faker) {
    return [
      'name' => $faker->name,
      'total_lots' => $faker->numberBetween(1, 20),
    ];
});
