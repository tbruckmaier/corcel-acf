<?php

use Tbruckmaier\Corcelacf\Models\Repeater;
use Tbruckmaier\Corcelacf\Models\Text;
use Tbruckmaier\Corcelacf\Models\Boolean;
use Tbruckmaier\Corcelacf\Tests\TestCase;
use Illuminate\Support\Collection;
use Tbruckmaier\Corcelacf\Support\RepeaterLayout;
use Corcel\Model\Attachment;
use Illuminate\Database\Eloquent\RelationNotFoundException;
use Illuminate\Support\Arr;
use Tbruckmaier\Corcelacf\Models\Image;

class FieldRepeaterPreload extends TestCase
{
    protected $acfField;
    protected array $imgs;

    protected function setUp() : void
    {
        parent::setUp();
        $this->acfField = factory(Repeater::class)->create();

        $this->imgs = array_map(fn () => factory(Attachment::class)->create(), range(0, 3));

        $data = [
            'fake_repeater' => '2',
            'fake_repeater_0_text' => 'First text',
            'fake_repeater_0_image' => $this->imgs[0]->getKey(),
            'fake_repeater_0_another_image' => $this->imgs[1]->getKey(),
            'fake_repeater_1_text' => 'Second text',
            'fake_repeater_1_image' => $this->imgs[2]->getKey(),
            'fake_repeater_1_another_image' => $this->imgs[3]->getKey(),
        ];

        $this->setAcfData($this->acfField, $data)->setLocalKey('fake_repeater');

        factory(Text::class)->create(['post_parent' => $this->acfField->getKey(), 'post_excerpt' => 'text']);
        factory(Image::class)->create(['post_parent' => $this->acfField->getKey(), 'post_excerpt' => 'image']);
        factory(Image::class)->create(['post_parent' => $this->acfField->getKey(), 'post_excerpt' => 'another_image']);
    }

    public function testLoad()
    {
        $this->acfField->load('image.attachment');

        $array = $this->acfField->value->toArray();

        $this->assertNotNull(Arr::get($array, '0.image.attachment'));
        $this->assertEquals($this->imgs[0]->getKey(), Arr::get($array, '0.image.attachment.ID'));
        $this->assertNull(Arr::get($array, '0.another_image.attachment'));

        $this->assertNotNull(Arr::get($array, '1.image.attachment'));
        $this->assertEquals($this->imgs[2]->getKey(), Arr::get($array, '1.image.attachment.ID'));
        $this->assertNull(Arr::get($array, '1.another_image.attachment'));
    }

    public function testLoadMultiple()
    {
        $this->acfField->load('image.attachment', 'another_image.attachment');

        $array = $this->acfField->value->toArray();

        $this->assertNotNull(Arr::get($array, '0.image.attachment'));
        $this->assertEquals($this->imgs[0]->getKey(), Arr::get($array, '0.image.attachment.ID'));
        $this->assertNotNull(Arr::get($array, '0.another_image.attachment'));
        $this->assertEquals($this->imgs[1]->getKey(), Arr::get($array, '0.another_image.attachment.ID'));

        $this->assertNotNull(Arr::get($array, '1.image.attachment'));
        $this->assertEquals($this->imgs[2]->getKey(), Arr::get($array, '1.image.attachment.ID'));
        $this->assertNotNull(Arr::get($array, '1.another_image.attachment'));
        $this->assertEquals($this->imgs[3]->getKey(), Arr::get($array, '1.another_image.attachment.ID'));
    }

    public function testLoadMultipleArray()
    {
        $this->acfField->load(['image.attachment', 'another_image.attachment']);

        $array = $this->acfField->value->toArray();

        $this->assertNotNull(Arr::get($array, '0.image.attachment'));
        $this->assertEquals($this->imgs[0]->getKey(), Arr::get($array, '0.image.attachment.ID'));
        $this->assertNotNull(Arr::get($array, '0.another_image.attachment'));
        $this->assertEquals($this->imgs[1]->getKey(), Arr::get($array, '0.another_image.attachment.ID'));

        $this->assertNotNull(Arr::get($array, '1.image.attachment'));
        $this->assertEquals($this->imgs[2]->getKey(), Arr::get($array, '1.image.attachment.ID'));
        $this->assertNotNull(Arr::get($array, '1.another_image.attachment'));
        $this->assertEquals($this->imgs[3]->getKey(), Arr::get($array, '1.another_image.attachment.ID'));
    }

    public function testNoAutoload()
    {
        // per default, attachments should not be loaded
        $array = $this->acfField->value->toArray();

        $this->assertNull(Arr::get($array, '0.image.attachment'));
        $this->assertNull(Arr::get($array, '0.another_image.attachment'));
        $this->assertNull(Arr::get($array, '1.image.attachment'));
        $this->assertNull(Arr::get($array, '1.another_image.attachment'));
    }

    public function testLoadInvalid1()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->acfField->load('invalid')->value;
    }

    public function testLoadInvalid2()
    {
        $this->expectException(RelationNotFoundException::class);
        $this->acfField->load('imag2e.attachment')->value;
    }

    public function testLoadInvalid3()
    {
        $this->expectException(RelationNotFoundException::class);
        $this->acfField->load('image.atta2chment')->value;
    }
}
