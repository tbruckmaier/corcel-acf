<?php

use Tbruckmaier\Corcelacf\Models\DateTime;
use Tbruckmaier\Corcelacf\Tests\TestCase;
use Carbon\Carbon;

class FieldDateTimeTest extends TestCase
{
    public function testDatePickerField()
    {
        $acfField = factory(DateTime::class)->states('date_picker')->create();
        $this->addData($acfField, 'fake_date_picker', '20161013');

        $this->assertInstanceOf(Carbon::class, $acfField->value);
        $this->assertEquals('10/13/2016', $acfField->value->format('m/d/Y'));
    }

    public function testDateTimePickerField()
    {
        $acfField = factory(DateTime::class)->states('date_time_picker')->create();
        $this->addData($acfField, 'fake_date_time_picker', '2016-10-19 08:06:05');

        $this->assertInstanceOf(Carbon::class, $acfField->value);
        $this->assertEquals('05:06:08/19-10:2016', $acfField->value->format('s:i:H/d-m:Y')); // 2016-10-19 08:06:05
    }

    public function testTimePickerField()
    {
        $acfField = factory(DateTime::class)->states('time_picker')->create();
        $this->addData($acfField, 'fake_time_picker', '17:30:00');

        $this->assertInstanceOf(Carbon::class, $acfField->value);
        $this->assertEquals('00/17/30', $acfField->value->format('s/H/i')); // 17:30:00
    }
}
