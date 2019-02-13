<?php

use Tbruckmaier\Corcelacf\Models\Post;
use Tbruckmaier\Corcelacf\Tests\TestCase;
use Corcel\Model\Attachment;
use Corcel\Model\Post as CorcelPost;
use Illuminate\Support\Collection;

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

    public function testPostMultiple()
    {
        $post0 = factory(CorcelPost::class)->create(['post_title' => 'Post #0']);
        $post1 = factory(CorcelPost::class)->create(['post_title' => 'Post #1']);
        $post2 = factory(CorcelPost::class)->create(['post_title' => 'Post #2']);

        $acfField = factory(Post::class)->states('multiple')->create();
        $this->addData($acfField, 'fake_post_object', serialize([$post0->ID, $post2->ID, $post1->ID]));

        $this->assertInstanceOf(Collection::class, $acfField->value);
        $this->assertEquals(['Post #0', 'Post #2', 'Post #1'], $acfField->value->pluck('post_title')->all());

    }
}
