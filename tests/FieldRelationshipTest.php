<?php

use Tbruckmaier\Corcelacf\Models\Relationship;
use Tbruckmaier\Corcelacf\Tests\TestCase;
use Corcel\Model\Post as CorcelPost;
use Illuminate\Support\Collection;

class FieldRelationshipTest extends TestCase
{
    public function testRelationshipField()
    {
        $post0 = factory(CorcelPost::class)->create(['post_title' => 'Post #0']);
        $post1 = factory(CorcelPost::class)->create(['post_title' => 'Post #1']);
        $post2 = factory(CorcelPost::class)->create(['post_title' => 'Post #2']);

        $acfField = factory(Relationship::class)->create();
        $this->addData($acfField, 'fake_relationship_object', serialize([$post0->ID, $post2->ID, $post1->ID]));

        $this->assertInstanceOf(Collection::class, $acfField->value);
        $this->assertEquals(['Post #0', 'Post #2', 'Post #1'], $acfField->value->pluck('post_title')->all());
    }
}
