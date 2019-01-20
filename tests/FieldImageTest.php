<?php

use Tbruckmaier\Corcelacf\Models\Image;
use Tbruckmaier\Corcelacf\Tests\TestCase;
use Corcel\Model\Attachment;

class FieldImageTest extends TestCase
{
    public function testFileFieldField()
    {
        $file = factory(Attachment::class)->create([
            'post_content' => 'Description here',
            'post_title' => 'Title here',
            'post_excerpt' => 'Caption here',
        ]);

        $acfField = factory(Image::class)->create();
        $this->addData($acfField, 'fake_image', $file->ID);

        $attachment = $acfField->value;

        $this->assertInstanceOf(Attachment::class, $attachment);

        $this->assertEquals('Description here', $attachment->description);
        $this->assertEquals('Title here', $attachment->title);
        $this->assertEquals('Caption here', $attachment->caption);
        $this->assertEquals('image/jpeg', $attachment->mime_type);
    }
}
