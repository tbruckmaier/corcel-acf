<?php

use Tbruckmaier\Corcelacf\Models\BaseField;
use Tbruckmaier\Corcelacf\Models\Text;
use Tbruckmaier\Corcelacf\Models\Boolean;
use Tbruckmaier\Corcelacf\Models\Post;
use Tbruckmaier\Corcelacf\Models\FlexibleContent;
use Tbruckmaier\Corcelacf\Tests\TestCase;
use Illuminate\Support\Collection;
use Tbruckmaier\Corcelacf\Support\FlexibleContentLayout;
use Corcel\Model\Post as CorcelPost;

class FieldFlexibleContentTest extends TestCase
{
    protected function createFcField()
    {
        $acfField = factory(FlexibleContent::class)->create();

        factory(BaseField::class)->states('fc_text')->create(['post_parent' => $acfField->ID]);
        factory(BaseField::class)->states('fc_post')->create(['post_parent' => $acfField->ID]);
        factory(BaseField::class)->states('fc_post_multiple')->create(['post_parent' => $acfField->ID]);

        return $acfField;
    }

    public function testFlexibleContentField()
    {
        $acfField = $this->createFcField();

        $post = factory(CorcelPost::class)->create();
        $multiplePosts = factory(CorcelPost::class, 5)->create();

        $data = [
            'fake_flexible_content' => serialize(['normal_text', 'related_post', 'multiple_posts']),
            'fake_flexible_content_0_text' => 'Lorem ipsum',
            'fake_flexible_content_1_post' => $post->ID,
            'fake_flexible_content_2_post' => serialize($multiplePosts->pluck('ID')->all()),
        ];

        $this->setData($acfField, $data)->setLocalKey('fake_flexible_content');

        $this->assertInstanceOf(Collection::class, $acfField->value);
        $this->assertEquals(3, $acfField->value->count());

        $layout0 = $acfField->value->get(0);
        $this->assertInstanceOf(FlexibleContentLayout::class, $layout0);
        $this->assertInstanceOf(Text::class, $layout0->text());
        $this->assertEquals('Lorem ipsum', $layout0->text);

        $layout1 = $acfField->value->get(1);
        $this->assertInstanceOf(FlexibleContentLayout::class, $layout1);
        $this->assertInstanceOf(Post::class, $layout1->post());
        $this->assertInstanceOf(CorcelPost::class, $layout1->post);
        $this->assertTrue($post->is($layout1->post));

        $layout2 = $acfField->value->get(2);
        $this->assertInstanceOf(FlexibleContentLayout::class, $layout2);
        $this->assertInstanceOf(Post::class, $layout2->post());
        $this->assertInstanceOf(Collection::class, $layout2->post);
        $this->assertEquals(5, $layout2->post->count());
        $this->assertInstanceOf(CorcelPost::class, $layout2->post->first());
    }

    public function testFlexibleContentOldMetadata()
    {
        $acfField = $this->createFcField();

        // when a fc field has been saved before, and then a layout gets
        // removed, the old meta data is still in the database. Make sure we do
        // not return that
        $data = [
            'fake_flexible_content' => serialize(['normal_text']),
            'fake_flexible_content_0_text' => 'Lorem ipsum',
            'fake_flexible_content_1_text' => 'Lorem ipsum obsolete',
        ];

        $this->setData($acfField, $data)->setLocalKey('fake_flexible_content');

        $this->assertInstanceOf(Collection::class, $acfField->value);
        $this->assertEquals(1, $acfField->value->count()); // only ONE field
    }

    public function testEmptyFlexibleContent()
    {
        $acfField = $this->createFcField();

        $data = ['fake_flexible_content' => ''];

        $this->setData($acfField, $data)->setLocalKey('fake_flexible_content');

        $this->assertInstanceOf(Collection::class, $acfField->value);
        $this->assertEquals(0, $acfField->value->count());
    }
}
