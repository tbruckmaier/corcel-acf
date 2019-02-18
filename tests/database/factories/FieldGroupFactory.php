<?php

use Tbruckmaier\Corcelacf\Models\BaseField;
use Tbruckmaier\Corcelacf\Models\Group;

$factory->define(Group::class, function (Faker\Generator $faker) {
    return array_replace(factory(BaseField::class)->make()->getAttributes(), [
        'post_content' => 'a:7:{s:4:"type";s:5:"group";s:12:"instructions";s:0:"";s:8:"required";i:0;s:17:"conditional_logic";i:0;s:7:"wrapper";a:3:{s:5:"width";s:0:"";s:5:"class";s:0:"";s:2:"id";s:0:"";}s:6:"layout";s:5:"table";s:10:"sub_fields";a:0:{}}',
    ]);
});
