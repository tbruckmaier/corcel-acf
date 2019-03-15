<?php

use Tbruckmaier\Corcelacf\Models\Gallery;
use Tbruckmaier\Corcelacf\Tests\TestCase;
use Corcel\Model\Attachment;
use Corcel\Model\Meta\PostMeta;
use Illuminate\Support\Collection;

class FieldGalleryTest extends TestCase
{
    public function testGalleryFieldField()
    {
        $galleryImages = factory(Attachment::class, 7)->create()->each(function ($image) {
            $image->meta()->save(factory(PostMeta::class)->states('attachment_metadata')->create());
        });

        $ids = $galleryImages->pluck('ID')->shuffle()->all();

        $acfField = factory(Gallery::class)->create();
        $this->addData($acfField, 'fake_gallery', serialize($ids));

        $this->assertInstanceOf(Collection::class, $acfField->value);
        $this->assertEquals(7, $acfField->value->count());

        // make sure they are sorted correctly
        $this->assertEquals($ids, $acfField->value->pluck('ID')->all());

        $attachment = $acfField->value->get(3);

        $this->assertInstanceOf(Attachment::class, $attachment);
        $this->assertEquals('image/jpeg', $attachment->mime_type);
    }
}
