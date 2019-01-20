<?php

use Tbruckmaier\Corcelacf\Models\BaseField;
use Tbruckmaier\Corcelacf\Models\Link;

$factory->define(Link::class, function (Faker\Generator $faker) {
    return array_replace(factory(BaseField::class)->make()->getAttributes(), [
        'post_content' => 'a:7:{s:4:"type";s:4:"link";s:12:"instructions";s:0:"";s:8:"required";i:1;s:17:"conditional_logic";i:0;s:7:"wrapper";a:3:{s:5:"width";s:0:"";s:5:"class";s:0:"";s:2:"id";s:0:"";}s:13:"return_format";s:5:"array";s:13:"parent_layout";s:13:"5bdad9ecb4da7";}',
    ]);
});

$factory->state(Link::class, 'url_return', function (Faker\Generator $faker) {
    return [
        'post_content' => 'a:7:{s:4:"type";s:4:"link";s:12:"instructions";s:0:"";s:8:"required";i:0;s:17:"conditional_logic";i:0;s:7:"wrapper";a:3:{s:5:"width";s:0:"";s:5:"class";s:0:"";s:2:"id";s:0:"";}s:13:"parent_layout";s:13:"5bdad904e889e";s:13:"return_format";s:3:"url";}',
    ];
});

