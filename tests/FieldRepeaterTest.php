<?php

use Tbruckmaier\Corcelacf\Models\Repeater;
use Tbruckmaier\Corcelacf\Models\Text;
use Tbruckmaier\Corcelacf\Models\Boolean;
use Tbruckmaier\Corcelacf\Tests\TestCase;
use Illuminate\Support\Collection;
use Tbruckmaier\Corcelacf\Support\RepeaterLayout;

class FieldRepeaterTest extends TestCase
{
    public function testRepeaterField()
    {
        $acfField = factory(Repeater::class)->create();

        $data = [
            'fake_repeater' => '2',
            'fake_repeater_0_repeater_text' => 'First text',
            'fake_repeater_0_repeater_boolean' => '1',
            'fake_repeater_1_repeater_text' => 'Second text',
            'fake_repeater_1_repeater_boolean' => '0',
        ];

        $this->setData($acfField, $data)->setLocalKey('fake_repeater');

        $layout0 = factory(Text::class)->create(['post_parent' => $acfField->ID, 'post_excerpt' => 'repeater_text']);
        $layout1 = factory(Boolean::class)->create(['post_parent' => $acfField->ID, 'post_excerpt' => 'repeater_boolean']);

        $this->assertInstanceOf(Collection::class, $acfField->value);
        $this->assertEquals(2, $acfField->value->count());

        $layout0 = $acfField->value->first();
        $this->assertInstanceOf(RepeaterLayout::class, $layout0);
        $this->assertInstanceOf(Text::class, $layout0->repeater_text());
        $this->assertEquals('First text', $layout0->repeater_text);
        $this->assertInstanceOf(Boolean::class, $layout0->repeater_boolean());
        $this->assertTrue($layout0->repeater_boolean);

        $layout1 = $acfField->value->get(1);
        $this->assertInstanceOf(RepeaterLayout::class, $layout1);
        $this->assertInstanceOf(Text::class, $layout1->repeater_text());
        $this->assertEquals('Second text', $layout1->repeater_text);
        $this->assertInstanceOf(Boolean::class, $layout1->repeater_boolean());
        $this->assertFalse($layout1->repeater_boolean);
    }
}
