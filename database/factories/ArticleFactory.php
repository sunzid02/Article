<?php

use Faker\Generator as Faker;

$factory->define(App\Article::class, function (Faker $faker) {
    return [
        'user_id' => $faker->numberBetween( 1, 5 ),
        'title' => $faker->text( 10),
        'body' => $faker->text(150),

    ];
});
