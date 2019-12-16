<?php

use Tbruckmaier\Corcelacf\Models\BaseField;
use Tbruckmaier\Corcelacf\Models\User;

$factory->define(User::class, function (Faker\Generator $faker) {
    return array_replace(factory(BaseField::class)->make()->getAttributes(), [
        'post_content' => serialize(array (
          'type' => 'user',
          'instructions' => '',
          'required' => 0,
          'conditional_logic' => 0,
          'wrapper' => 
          array (
            'width' => '',
            'class' => '',
            'id' => '',
          ),
          'role' => '',
          'multiple' => 0,
          'allow_null' => 0,
          'return_format' => 'array',
        )),
    ]);
});

$factory->state(User::class, 'multiple', function (Faker\Generator $faker) {
    return array_replace(factory(BaseField::class)->make()->getAttributes(), [
        'post_content' => serialize(array (
          'type' => 'user',
          'instructions' => '',
          'required' => 0,
          'conditional_logic' => 0,
          'wrapper' => 
          array (
            'width' => '',
            'class' => '',
            'id' => '',
          ),
          'role' => '',
          'multiple' => 1,
          'allow_null' => 0,
          'return_format' => 'array',
        )),
    ]);
});
