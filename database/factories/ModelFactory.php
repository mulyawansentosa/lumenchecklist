<?php

use Illuminate\Support\Facades\Hash;

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
/*
$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password'   => Hash::make(str_random(10))
    ];
});
*/

// ========= TEMPLATE FACTORY ========= //
$factory->define(App\Models\Template\Eloquent\TemplateModel::class, function (Faker\Generator $faker) {
    return [
        'type'          => $faker->jobTitle
    ];
});

$factory->define(App\Models\Template\Eloquent\TemplatelinkModel::class, function (Faker\Generator $faker) {
    return [
        'self'          => $faker->url
    ];
});

$factory->define(App\Models\Template\Eloquent\TemplateattributeModel::class, function (Faker\Generator $faker) {
    return [
        'name'          => $faker->name
    ];
});
// ========= END TEMPLATE FACTORY ========= //

// ========= CHECKLIST FACTORY ========= //
$factory->define(App\Models\Checklist\Eloquent\ChecklistModel::class, function (Faker\Generator $faker) {
    return [
        'template_id'  => App\Models\Template\Eloquent\TemplateModel::first()->id,
        'type'          => $faker->jobTitle
    ];
});

$factory->define(App\Models\Checklist\Eloquent\ChecklistlinkModel::class, function (Faker\Generator $faker) {
    return [
        'self'          => $faker->url
    ];
});

$factory->define(App\Models\Checklist\Eloquent\ChecklistattributeModel::class, function (Faker\Generator $faker) {
    return [
        'object_domain'     => $faker->jobTitle,
        'object_id'         => $faker->randomDigit,
        'description'       => $faker->sentence($nbWords = 6, $variableNbWords = true),
        'is_completed'      => $faker->boolean,
        'completed_at'      => $faker->dateTime(),
        'updated_by'        => $faker->randomDigit,
        'due'               => $faker->dateTime(),
        'urgency'           => $faker->randomDigit


    ];
});
// ========= END CHECKLIST FACTORY ========= //

// ========= ITEM FACTORY ========= //
$factory->define(App\Models\Item\Eloquent\ItemModel::class, function (Faker\Generator $faker) {
    return [
        'checklist_id'  => App\Models\Checklist\Eloquent\ChecklistModel::first()->id,
        'type'          => $faker->jobTitle
    ];
});

$factory->define(App\Models\Item\Eloquent\ItemlinkModel::class, function (Faker\Generator $faker) {
    return [
        'self'          => $faker->url
    ];
});

$factory->define(App\Models\Item\Eloquent\ItemattributeModel::class, function (Faker\Generator $faker) {
    return [
        'description'       => $faker->sentence($nbWords = 6, $variableNbWords = true),
        'is_completed'      => $faker->boolean,
        'completed_at'      => $faker->dateTime(),
        'updated_by'        => $faker->randomDigit,
        'due'               => $faker->dateTime(),
        'urgency'           => $faker->randomDigit,
        'assignee_id'       => $faker->randomDigit,
        'task_id'           => $faker->randomDigit
    ];
});
// ========= END ITEM FACTORY ========= //
