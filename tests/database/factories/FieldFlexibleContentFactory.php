<?php

use Tbruckmaier\Corcelacf\Models\BaseField;
use Tbruckmaier\Corcelacf\Models\FlexibleContent;

$factory->define(FlexibleContent::class, function (Faker\Generator $faker) {
    return array_replace(factory(BaseField::class)->make()->getAttributes(), [
        'post_content' => 'a:9:{s:4:"type";s:16:"flexible_content";s:12:"instructions";s:0:"";s:8:"required";i:0;s:17:"conditional_logic";i:0;s:7:"wrapper";a:3:{s:5:"width";s:0:"";s:5:"class";s:0:"";s:2:"id";s:0:"";}s:12:"button_label";s:7:"Add Row";s:3:"min";s:0:"";s:3:"max";s:0:"";s:7:"layouts";a:3:{i:0;a:6:{s:3:"key";s:13:"589c18bcf10da";s:5:"label";s:11:"Normal text";s:4:"name";s:11:"normal_text";s:7:"display";s:5:"block";s:3:"min";s:0:"";s:3:"max";s:0:"";}i:1;a:6:{s:3:"key";s:13:"589c18dfc9b28";s:5:"label";s:12:"Related post";s:4:"name";s:12:"related_post";s:7:"display";s:5:"block";s:3:"min";s:0:"";s:3:"max";s:0:"";}i:2;a:6:{s:3:"key";s:13:"589c1ee35ec27";s:5:"label";s:14:"Multiple posts";s:4:"name";s:14:"multiple_posts";s:7:"display";s:5:"block";s:3:"min";s:0:"";s:3:"max";s:0:"";}}}',
    ]);
});
