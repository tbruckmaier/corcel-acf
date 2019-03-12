<?php

use Illuminate\Support\Str;
use Tbruckmaier\Corcelacf\Models\BaseField;
use Tbruckmaier\Corcelacf\Models\BaseFieldGroup;
use Tbruckmaier\Corcelacf\Models\Text;
use Tbruckmaier\Corcelacf\Models\Boolean;
use Tbruckmaier\Corcelacf\Models\FlexibleContent;

$factory->define(BaseField::class, function (Faker\Generator $faker) {
    return [
        'post_author' => $faker->randomDigit,
        'post_date' => $faker->dateTimeThisYear,
        'post_date_gmt' => $faker->dateTimeThisYear,
        'post_content' => 'a:12:{s:4:"type";s:4:"text";s:12:"instructions";s:0:"";s:8:"required";i:0;s:17:"conditional_logic";i:0;s:7:"wrapper";a:3:{s:5:"width";s:0:"";s:5:"class";s:0:"";s:2:"id";s:0:"";}s:13:"default_value";s:0:"";s:11:"placeholder";s:0:"";s:7:"prepend";s:0:"";s:6:"append";s:0:"";s:9:"maxlength";s:0:"";s:8:"readonly";i:0;s:8:"disabled";i:0;}',
        'post_title' => $faker->title,
        'post_excerpt' => $faker->word,
        'post_status' => 'publish',
        'comment_status' => 'closed',
        'ping_status' => 'closed',
        'post_password' => '',
        'post_name' => 'field_' . Str::random(13),
        'to_ping' => '',
        'pinged' => '',
        'post_modified' => $faker->dateTimeThisMonth,
        'post_modified_gmt' => $faker->dateTimeThisMonth,
        'post_content_filtered' => '',
        'post_parent' => factory(BaseFieldGroup::class)->create()->ID,
        'guid' => 'http://example.com/?p=' . $faker->numberBetween(1, 100),
        'menu_order' => 0,
        'post_type' => 'acf-field',
        'post_mime_type' => '',
        'comment_count' => 0,
    ];
});

$factory->state(BaseField::class, 'user', function (Faker\Generator $faker) {
    return [
        'post_content' => 'a:8:{s:4:"type";s:4:"user";s:12:"instructions";s:0:"";s:8:"required";i:0;s:17:"conditional_logic";i:0;s:7:"wrapper";a:3:{s:5:"width";s:0:"";s:5:"class";s:0:"";s:2:"id";s:0:"";}s:4:"role";s:0:"";s:10:"allow_null";i:0;s:8:"multiple";i:0;}',
    ];
});

$factory->state(BaseField::class, 'relationship', function (Faker\Generator $faker) {
    return [
        'post_content' => 'a:12:{s:4:"type";s:12:"relationship";s:12:"instructions";s:0:"";s:8:"required";i:0;s:17:"conditional_logic";i:0;s:7:"wrapper";a:3:{s:5:"width";s:0:"";s:5:"class";s:0:"";s:2:"id";s:0:"";}s:9:"post_type";a:1:{i:0;s:4:"page";}s:8:"taxonomy";a:0:{}s:7:"filters";a:3:{i:0;s:6:"search";i:1;s:9:"post_type";i:2;s:8:"taxonomy";}s:8:"elements";s:0:"";s:3:"min";s:0:"";s:3:"max";s:0:"";s:13:"return_format";s:6:"object";}',
    ];
});

$factory->state(BaseField::class, 'fc_text', function (Faker\Generator $faker) {
    return [
        'post_parent' => factory(FlexibleContent::class)->create()->ID,
        'post_content' => 'a:11:{s:4:"type";s:4:"text";s:12:"instructions";s:0:"";s:8:"required";i:0;s:17:"conditional_logic";i:0;s:7:"wrapper";a:3:{s:5:"width";s:0:"";s:5:"class";s:0:"";s:2:"id";s:0:"";}s:13:"parent_layout";s:13:"589c18bcf10da";s:13:"default_value";s:0:"";s:11:"placeholder";s:0:"";s:7:"prepend";s:0:"";s:6:"append";s:0:"";s:9:"maxlength";s:0:"";}',
        'post_excerpt' => 'text',
    ];
});

$factory->state(BaseField::class, 'fc_post', function (Faker\Generator $faker) {
    return [
        'post_parent' => factory(FlexibleContent::class)->create()->ID,
        'post_content' => 'a:12:{s:4:"type";s:11:"post_object";s:12:"instructions";s:0:"";s:8:"required";i:0;s:17:"conditional_logic";i:0;s:7:"wrapper";a:3:{s:5:"width";s:0:"";s:5:"class";s:0:"";s:2:"id";s:0:"";}s:13:"parent_layout";s:13:"589c18dfc9b28";s:9:"post_type";a:0:{}s:8:"taxonomy";a:0:{}s:10:"allow_null";i:0;s:8:"multiple";i:0;s:13:"return_format";s:6:"object";s:2:"ui";i:1;}',
        'post_excerpt' => 'post',
    ];
});

$factory->state(BaseField::class, 'fc_post_multiple', function (Faker\Generator $faker) {
    return [
        'post_parent' => factory(FlexibleContent::class)->create()->ID,
        'post_content' => 'a:12:{s:4:"type";s:11:"post_object";s:12:"instructions";s:0:"";s:8:"required";i:0;s:17:"conditional_logic";i:0;s:7:"wrapper";a:3:{s:5:"width";s:0:"";s:5:"class";s:0:"";s:2:"id";s:0:"";}s:13:"parent_layout";s:13:"589c1ee35ec27";s:9:"post_type";a:0:{}s:8:"taxonomy";a:0:{}s:10:"allow_null";i:0;s:8:"multiple";i:1;s:13:"return_format";s:6:"object";s:2:"ui";i:1;}',
        'post_excerpt' => 'post',
    ];
});
