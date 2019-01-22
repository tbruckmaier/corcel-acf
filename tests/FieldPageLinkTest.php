<?php

use Tbruckmaier\Corcelacf\Models\PageLink;
use Tbruckmaier\Corcelacf\Tests\TestCase;
use Corcel\Model\Attachment;
use Tbruckmaier\Corcelacf\Tests\Models\Post;

class FieldPageLinkTest extends TestCase
{
    public function testFileFieldField()
    {
        $page = factory(Post::class)->states('page')->create([
            'guid' => 'http://wordpress.corcel.dev/?page_id=21',
            'post_name' => 'acf-content-fields',
        ]);

        $acfField = factory(PageLink::class)->create();
        $this->addData($acfField, 'fake_page_link', $page->ID);

        $this->assertEquals('http://wordpress.corcel.dev/acf-content-fields/', $acfField->value);
    }
}
