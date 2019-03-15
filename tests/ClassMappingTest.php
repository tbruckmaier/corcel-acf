<?php

use Tbruckmaier\Corcelacf\Models\BaseField;
use Tbruckmaier\Corcelacf\Models\Text;
use Tbruckmaier\Corcelacf\Models\Generic;
use Tbruckmaier\Corcelacf\Models\Gallery;
use Tbruckmaier\Corcelacf\Tests\TestCase;
use Tbruckmaier\Corcelacf\Tests\Models\CustomField;

class ClassMappingTest extends TestCase
{
    public function testGallery()
    {
        $acfField = factory(BaseField::class)->make();
        $instance = $acfField->newFromBuilder(['post_content' => serialize(['type' => 'gallery'])]);
        $this->assertInstanceOf(Gallery::class, $instance);
    }

    public function testCustomClassExisting()
    {
        \Config::set('corcel-acf.classMapping', ['text' => CustomField::class]);

        $acfField = factory(BaseField::class)->make();
        $instance = $acfField->newFromBuilder(['post_content' => serialize(['type' => 'text'])]);
        $this->assertInstanceOf(CustomField::class, $instance);
    }

    public function testCustomClassNewField()
    {
        \Config::set('corcel-acf.classMapping', ['new_field_type' => CustomField::class]);

        $acfField = factory(BaseField::class)->make();
        $instance = $acfField->newFromBuilder(['post_content' => serialize(['type' => 'new_field_type'])]);
        $this->assertInstanceOf(CustomField::class, $instance);
    }
}
