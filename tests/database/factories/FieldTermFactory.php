<?php

use Tbruckmaier\Corcelacf\Models\BaseField;
use Tbruckmaier\Corcelacf\Models\Term;

$factory->define(Term::class, function (Faker\Generator $faker) {
    return array_replace(factory(BaseField::class)->make()->getAttributes(), [
        'post_content' => 'a:11:{s:4:"type";s:11:"post_object";s:12:"instructions";s:0:"";s:8:"required";i:0;s:17:"conditional_logic";i:0;s:7:"wrapper";a:3:{s:5:"width";s:0:"";s:5:"class";s:0:"";s:2:"id";s:0:"";}s:9:"post_type";a:0:{}s:8:"taxonomy";a:0:{}s:10:"allow_null";i:0;s:8:"multiple";i:0;s:13:"return_format";s:6:"object";s:2:"ui";i:1;}',
    ]);
});


$factory->state(Term::class, 'taxonomy', function (Faker\Generator $faker) {
    return [
        'post_content' => 'a:13:{s:4:"type";s:8:"taxonomy";s:12:"instructions";s:0:"";s:8:"required";i:0;s:17:"conditional_logic";i:0;s:7:"wrapper";a:3:{s:5:"width";s:0:"";s:5:"class";s:0:"";s:2:"id";s:0:"";}s:8:"taxonomy";s:8:"category";s:10:"field_type";s:8:"checkbox";s:10:"allow_null";i:0;s:8:"add_term";i:1;s:10:"save_terms";i:0;s:10:"load_terms";i:0;s:13:"return_format";s:2:"id";s:8:"multiple";i:0;}',
    ];
});

$factory->state(Term::class, 'taxonomy_single', function (Faker\Generator $faker) {
    return [
        'post_content' => 'a:13:{s:4:"type";s:8:"taxonomy";s:12:"instructions";s:0:"";s:8:"required";i:0;s:17:"conditional_logic";i:0;s:7:"wrapper";a:3:{s:5:"width";s:0:"";s:5:"class";s:0:"";s:2:"id";s:0:"";}s:8:"taxonomy";s:8:"category";s:10:"field_type";s:5:"radio";s:10:"allow_null";i:0;s:8:"add_term";i:1;s:10:"save_terms";i:0;s:10:"load_terms";i:0;s:13:"return_format";s:6:"object";s:8:"multiple";i:0;}',
    ];
});
