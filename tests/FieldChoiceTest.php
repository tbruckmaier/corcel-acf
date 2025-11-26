<?php

use Tbruckmaier\Corcelacf\Models\Choice;
use Tbruckmaier\Corcelacf\Tests\TestCase;

class FieldChoiceTest extends TestCase
{
    public function testSelectField()
    {
        $acfField = factory(Choice::class)->states('select')->create();
        $this->addData($acfField, 'fake_select', 'red');

        $this->assertEquals('red', $acfField->value);
    }

    public function testSelectWithDefaultField()
    {
        $acfField = factory(Choice::class)->states('select_with_default')->create();
        $acfField->setData(collect([]))->setLocalKey('fake_select_with_default');

        $this->assertEquals('yellow', $acfField->value);
    }

    public function testSelectMultipleField()
    {
        $acfField = factory(Choice::class)->states('select_multiple')->create();
        $this->addData($acfField, 'fake_select_multiple', serialize(['yellow', 'green']));

        $this->assertEquals(['yellow', 'green'], $acfField->value);
    }

    public function testSelectMultipleWithDefaultField()
    {
        $acfField = factory(Choice::class)->states('select_multiple_with_default')->create();
        $acfField->setData(collect([]))->setLocalKey('fake_select_multiple_with_default');
        $this->assertEquals(['red', 'yellow'], $acfField->value);
    }

    public function testCheckboxField()
    {
        $acfField = factory(Choice::class)->states('checkbox')->create();
        $this->addData($acfField, 'fake_checkbox', serialize(['blue', 'yellow']));
        $this->assertEquals(['blue', 'yellow'], $acfField->value);
    }

    public function testRadioField()
    {
        $acfField = factory(Choice::class)->states('radio_button')->create();
        $this->addData($acfField, 'fake_radio_button', 'green');
        $this->assertEquals('green', $acfField->value);
    }
}
