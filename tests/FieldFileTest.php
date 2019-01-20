<?php

use Tbruckmaier\Corcelacf\Models\File;
use Tbruckmaier\Corcelacf\Tests\TestCase;
use Corcel\Model\Attachment;

class FieldFileTest extends TestCase
{
    public function testFileFieldField()
    {
        $file = factory(Attachment::class)->states('file')->create([
            'post_content' => 'Description here',
            'post_title' => 'Title here',
            'post_excerpt' => 'Caption here',
        ]);

        $acfField = factory(File::class)->create();
        $this->addData($acfField, 'fake_file', $file->ID);

        $attachment = $acfField->value;

        $this->assertInstanceOf(Attachment::class, $attachment);

        $this->assertEquals('Description here', $attachment->description);
        $this->assertEquals('Title here', $attachment->title);
        $this->assertEquals('Caption here', $attachment->caption);
        $this->assertEquals('application/pdf', $attachment->mime_type);
    }
}
