<?php

use Tbruckmaier\Corcelacf\Models\Text;
use Tbruckmaier\Corcelacf\Tests\TestCase;

class FieldTextTest extends TestCase
{
    public function testTextFieldValue()
    {
        $acfField = factory(Text::class)->create();
        $this->addData($acfField, 'fake_text', 'Proin eget tortor risus');

        $this->assertEquals('Proin eget tortor risus', $acfField->value);
    }

    public function testTextareaFieldValue()
    {
        $acfField = factory(Text::class)->states('textarea')->create();
        $this->addData($acfField, 'fake_textarea', 'Praesent sapien massa, convallis a pellentesque nec, egestas non nisi.');

        $this->assertEquals('Praesent sapien massa, convallis a pellentesque nec, egestas non nisi.', $acfField->value);
    }

    public function testNumberFieldValue()
    {
        $acfField = factory(Text::class)->states('number')->create();
        $this->addData($acfField, 'fake_number', '1984');

        $this->assertEquals('1984', $acfField->value);
    }

    public function testEmailFieldValue()
    {
        $acfField = factory(Text::class)->states('email')->create();
        $this->addData($acfField, 'fake_email', 'junior@corcel.org');

        $this->assertEquals('junior@corcel.org', $acfField->value);
    }

    public function testUrlFieldValue()
    {
        $acfField = factory(Text::class)->states('url')->create();
        $this->addData($acfField, 'fake_url', 'https://corcel.org');

        $this->assertEquals('https://corcel.org', $acfField->value);
    }

    public function testPasswordFieldValue()
    {
        $acfField = factory(Text::class)->states('password')->create();
        $this->addData($acfField, 'fake_password', '123change');

        $this->assertEquals('123change', $acfField->value);
    }

    public function testEditorFieldValue()
    {
        $acfField = factory(Text::class)->states('editor')->create();
        $this->addData($acfField, 'fake_editor', 'Nulla <em>porttitor</em> <del>accumsan</del> <strong>tincidunt</strong>. Sed porttitor lectus nibh.');
        $this->assertEquals('Nulla <em>porttitor</em> <del>accumsan</del> <strong>tincidunt</strong>. Sed porttitor lectus nibh.', $acfField->value);
    }

    public function testOembedFieldValue()
    {
        $acfField = factory(Text::class)->states('oembed')->create();
        $this->addData($acfField, 'fake_oembed', 'https://www.youtube.com/watch?v=LiyQ8bvLzIE');
        $this->assertEquals('https://www.youtube.com/watch?v=LiyQ8bvLzIE', $acfField->value);
    }

    public function testEmpty()
    {
        $acfField = factory(Text::class)->create();
        $this->addData($acfField, 'fake_empty', '');
        $this->assertEquals('', $acfField->value);
    }

    public function testNonExisting()
    {
        $acfField = factory(Text::class)->create();
        $acfField->setData(collect(['field1' => 'text']))->setLocalKey('field2');
        $this->assertEmpty($acfField->value);
    }

    public function testColorPickerField()
    {
        $acfField = factory(Text::class)->states('color_picker')->create();
        $this->addData($acfField, 'fake_color_picker', '#7263a8');
        $this->assertEquals('#7263a8', $acfField->value);
    }
}
