<?php

use Tbruckmaier\Corcelacf\Models\Post;
use Tbruckmaier\Corcelacf\Tests\TestCase;
use Corcel\Model\Attachment;
use Corcel\Model\Post as CorcelPost;

class FieldPostTest extends TestCase
{
    public function testFileFieldField()
    {
        $post = factory(CorcelPost::class)->create(['post_title' => 'ACF Basic Fields']);

        $acfField = factory(Post::class)->create();
        $this->addData($acfField, 'fake_post_object', $post->ID);

        $this->assertInstanceOf(CorcelPost::class, $acfField->value);
        $this->assertEquals('ACF Basic Fields', $acfField->value->post_title);
    }
}
