<?php

use Tbruckmaier\Corcelacf\Models\BaseField;
use Tbruckmaier\Corcelacf\Models\DateTime;

$factory->define(DateTime::class, function (Faker\Generator $faker) {
    return array_replace(factory(BaseField::class)->make()->getAttributes(), [
        'post_content' => null,
    ]);
});

$factory->state(DateTime::class, 'date_picker', function (Faker\Generator $faker) {
    return [
        'post_content' => 'a:8:{s:4:"type";s:11:"date_picker";s:12:"instructions";s:0:"";s:8:"required";i:0;s:17:"conditional_logic";i:0;s:7:"wrapper";a:3:{s:5:"width";s:0:"";s:5:"class";s:0:"";s:2:"id";s:0:"";}s:14:"display_format";s:5:"d/m/Y";s:13:"return_format";s:5:"d/m/Y";s:9:"first_day";i:1;}',
    ];
});

$factory->state(DateTime::class, 'date_time_picker', function (Faker\Generator $faker) {
    return [
        'post_content' => 'a:8:{s:4:"type";s:16:"date_time_picker";s:12:"instructions";s:0:"";s:8:"required";i:0;s:17:"conditional_logic";i:0;s:7:"wrapper";a:3:{s:5:"width";s:0:"";s:5:"class";s:0:"";s:2:"id";s:0:"";}s:14:"display_format";s:11:"d/m/Y g:i a";s:13:"return_format";s:11:"d/m/Y g:i a";s:9:"first_day";i:1;}',
    ];
});

$factory->state(DateTime::class, 'time_picker', function (Faker\Generator $faker) {
    return [
        'post_content' => 'a:7:{s:4:"type";s:11:"time_picker";s:12:"instructions";s:0:"";s:8:"required";i:0;s:17:"conditional_logic";i:0;s:7:"wrapper";a:3:{s:5:"width";s:0:"";s:5:"class";s:0:"";s:2:"id";s:0:"";}s:14:"display_format";s:5:"g:i a";s:13:"return_format";s:5:"g:i a";}',
    ];
});