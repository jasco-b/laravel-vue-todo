<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Todo;
use Faker\Generator as Faker;

$statuses = array_keys(Todo::statuses());
$statusCount = count($statuses);

$factory->define(Todo::class, function (Faker $faker) use ($statuses, $statusCount) {
    return [
        'task' => $faker->text(),
        'status' => $statuses[random_int(0, $statusCount - 1)],
    ];
});
