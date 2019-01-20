<?php

use Tbruckmaier\Corcelacf\Models\BaseField;
use Tbruckmaier\Corcelacf\Models\Text;
use Tbruckmaier\Corcelacf\Models\Generic;
use Tbruckmaier\Corcelacf\Tests\TestCase;

class BaseFieldTest extends TestCase
{
    public function testTextField()
    {
        $acfField = factory(BaseField::class)->make();
        $instance = $acfField->newFromBuilder(['post_content' => serialize(['type' => 'text'])]);
        $this->assertInstanceOf(Text::class, $instance);
    }

    public function testInvalidField()
    {
        $acfField = factory(BaseField::class)->make();
        $instance = $acfField->newFromBuilder(['post_content' => serialize(['type' => 'invalid'])]);
        $this->assertInstanceOf(Generic::class, $instance);
    }
}
