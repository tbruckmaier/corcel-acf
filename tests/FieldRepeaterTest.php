<?php

use Tbruckmaier\Corcelacf\Models\Repeater;
use Tbruckmaier\Corcelacf\Models\Text;
use Tbruckmaier\Corcelacf\Models\Boolean;
use Tbruckmaier\Corcelacf\Tests\TestCase;
use Illuminate\Support\Collection;
use Tbruckmaier\Corcelacf\Support\RepeaterLayout;

class FieldRepeaterTest extends TestCase
{
    protected $acfField;

    protected function setUp() : void
    {
        parent::setUp();
        $this->acfField = factory(Repeater::class)->create();

        $data = [
            'fake_repeater' => '2',
            'fake_repeater_0_repeater_text' => 'First text',
            'fake_repeater_0_repeater_boolean' => '1',
            'fake_repeater_0_repeater_text2' => 'First entry text2',
            'fake_repeater_1_repeater_text' => 'Second text',
            'fake_repeater_1_repeater_boolean' => '0',
            'fake_repeater_1_repeater_text2' => 'Second entry text2',
        ];

        $this->setData($this->acfField, $data)->setLocalKey('fake_repeater');

        factory(Text::class)->create(['post_parent' => $this->acfField->ID, 'post_excerpt' => 'repeater_text']);
        factory(Boolean::class)->create(['post_parent' => $this->acfField->ID, 'post_excerpt' => 'repeater_boolean']);
        factory(Text::class)->create(['post_parent' => $this->acfField->ID, 'post_excerpt' => 'repeater_text2']);
    }

    public function testRepeaterField()
    {
        $this->assertInstanceOf(Collection::class, $this->acfField->value);
        $this->assertEquals(2, $this->acfField->value->count());

        $layout0 = $this->acfField->value->first();
        $this->assertInstanceOf(RepeaterLayout::class, $layout0);
        $this->assertInstanceOf(Text::class, $layout0->repeater_text());
        $this->assertEquals('First text', $layout0->repeater_text);
        $this->assertInstanceOf(Boolean::class, $layout0->repeater_boolean());
        $this->assertTrue($layout0->repeater_boolean);
        $this->assertEquals('First entry text2', $layout0->repeater_text2);

        $layout1 = $this->acfField->value->get(1);
        $this->assertInstanceOf(RepeaterLayout::class, $layout1);
        $this->assertInstanceOf(Text::class, $layout1->repeater_text());
        $this->assertEquals('Second text', $layout1->repeater_text);
        $this->assertInstanceOf(Boolean::class, $layout1->repeater_boolean());
        $this->assertFalse($layout1->repeater_boolean);
        $this->assertEquals('Second entry text2', $layout1->repeater_text2);
    }

    public function testToArray()
    {
        $array = $this->acfField->value->toArray();

        $this->assertEqualsCanonicalizing([
            [
                "repeater_text" => "First text",
                "repeater_boolean" => true,
                "repeater_text2" => "First entry text2",
            ],
            [
                "repeater_text" => "Second text",
                "repeater_boolean" => false,
                "repeater_text2" => "Second entry text2",
            ]
        ], $array);
    }

    public function testToJson()
    {
        $json = $this->acfField->value->toJson();
        $this->assertEquals('[{"repeater_text":"First text","repeater_boolean":true,"repeater_text2":"First entry text2"},{"repeater_text":"Second text","repeater_boolean":false,"repeater_text2":"Second entry text2"}]', $json);
    }
}
