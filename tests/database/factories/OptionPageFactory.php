<?php

use Tbruckmaier\Corcelacf\Models\BaseFieldGroup;
use Tbruckmaier\Corcelacf\OptionPage;

$factory->define(OptionPage::class, function (Faker\Generator $faker) {
    return array_replace(factory(BaseFieldGroup::class)->make()->getAttributes(), [
    ]);
});
