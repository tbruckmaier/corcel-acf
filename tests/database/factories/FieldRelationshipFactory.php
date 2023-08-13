<?php

use Tbruckmaier\Corcelacf\Models\BaseField;
use Tbruckmaier\Corcelacf\Models\Relationship;

$factory->define(Relationship::class, function (Faker\Generator $faker) {
    return array_replace(factory(BaseField::class)->make()->getAttributes(), [
        'post_content' => 'a:14:{s:10:"aria-label";s:0:"";s:4:"type";s:12:"relationship";s:12:"instructions";s:0:"";s:8:"required";i:0;s:17:"conditional_logic";i:0;s:7:"wrapper";a:3:{s:5:"width";s:0:"";s:5:"class";s:0:"";s:2:"id";s:0:"";}s:9:"post_type";s:0:"";s:11:"post_status";s:0:"";s:8:"taxonomy";s:0:"";s:7:"filters";a:3:{i:0;s:6:"search";i:1;s:9:"post_type";i:2;s:8:"taxonomy";}s:13:"return_format";s:6:"object";s:3:"min";s:0:"";s:3:"max";s:0:"";s:8:"elements";s:0:"";}',
    ]);
});
