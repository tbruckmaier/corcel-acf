<?php

use Tbruckmaier\Corcelacf\Models\BaseField;
use Tbruckmaier\Corcelacf\Models\Choice;

$factory->define(Choice::class, function (Faker\Generator $faker) {
    return factory(BaseField::class)->make()->getAttributes();
});

$factory->state(Choice::class, 'select', function (Faker\Generator $faker) {
    return [
        'post_content' => 'a:14:{s:4:"type";s:6:"select";s:12:"instructions";s:0:"";s:8:"required";i:0;s:17:"conditional_logic";i:0;s:7:"wrapper";a:3:{s:5:"width";s:0:"";s:5:"class";s:0:"";s:2:"id";s:0:"";}s:7:"choices";a:4:{s:3:"red";s:3:"Red";s:4:"blue";s:4:"Blue";s:6:"yellow";s:6:"Yellow";s:5:"green";s:5:"Green";}s:13:"default_value";a:0:{}s:10:"allow_null";i:0;s:8:"multiple";i:0;s:2:"ui";i:0;s:4:"ajax";i:0;s:11:"placeholder";s:0:"";s:8:"disabled";i:0;s:8:"readonly";i:0;}',
    ];
});

$factory->state(Choice::class, 'select_multiple', function (Faker\Generator $faker) {
    return [
        'post_content' => 'a:14:{s:4:"type";s:6:"select";s:12:"instructions";s:0:"";s:8:"required";i:0;s:17:"conditional_logic";i:0;s:7:"wrapper";a:3:{s:5:"width";s:0:"";s:5:"class";s:0:"";s:2:"id";s:0:"";}s:7:"choices";a:4:{s:3:"red";s:3:"Red";s:4:"blue";s:4:"Blue";s:6:"yellow";s:6:"Yellow";s:5:"green";s:5:"Green";}s:13:"default_value";a:0:{}s:10:"allow_null";i:0;s:8:"multiple";i:1;s:2:"ui";i:0;s:4:"ajax";i:0;s:11:"placeholder";s:0:"";s:8:"disabled";i:0;s:8:"readonly";i:0;}',
    ];
});

$factory->state(Choice::class, 'checkbox', function (Faker\Generator $faker) {
    return [
        'post_content' => 'a:9:{s:4:"type";s:8:"checkbox";s:12:"instructions";s:0:"";s:8:"required";i:1;s:17:"conditional_logic";i:0;s:7:"wrapper";a:3:{s:5:"width";s:0:"";s:5:"class";s:0:"";s:2:"id";s:0:"";}s:7:"choices";a:4:{s:3:"red";s:3:"Red";s:4:"blue";s:4:"Blue";s:6:"yellow";s:6:"Yellow";s:5:"green";s:5:"Green";}s:13:"default_value";a:0:{}s:6:"layout";s:8:"vertical";s:6:"toggle";i:0;}',
    ];
});

$factory->state(Choice::class, 'radio_button', function (Faker\Generator $faker) {
    return [
        'post_content' => 'a:11:{s:4:"type";s:5:"radio";s:12:"instructions";s:0:"";s:8:"required";i:1;s:17:"conditional_logic";i:0;s:7:"wrapper";a:3:{s:5:"width";s:0:"";s:5:"class";s:0:"";s:2:"id";s:0:"";}s:7:"choices";a:4:{s:3:"red";s:3:"Red";s:4:"blue";s:4:"Blue";s:6:"yellow";s:6:"Yellow";s:5:"green";s:5:"Green";}s:10:"allow_null";i:0;s:12:"other_choice";i:0;s:17:"save_other_choice";i:0;s:13:"default_value";s:0:"";s:6:"layout";s:8:"vertical";}',
    ];
});
