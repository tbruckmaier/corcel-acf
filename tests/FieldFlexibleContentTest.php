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

        factory(BaseField::class)->create([
            'post_parent' => $acfField->ID,
            'post_content' => 'a:11:{s:4:"type";s:4:"text";s:12:"instructions";s:0:"";s:8:"required";i:0;s:17:"conditional_logic";i:0;s:7:"wrapper";a:3:{s:5:"width";s:0:"";s:5:"class";s:0:"";s:2:"id";s:0:"";}s:13:"parent_layout";s:13:"589c18bcf10da";s:13:"default_value";s:0:"";s:11:"placeholder";s:0:"";s:7:"prepend";s:0:"";s:6:"append";s:0:"";s:9:"maxlength";s:0:"";}',
            'post_excerpt' => 'text',
        ]);

        factory(BaseField::class)->create([
            'post_parent' => $acfField->ID,
            'post_content' => 'a:12:{s:4:"type";s:11:"post_object";s:12:"instructions";s:0:"";s:8:"required";i:0;s:17:"conditional_logic";i:0;s:7:"wrapper";a:3:{s:5:"width";s:0:"";s:5:"class";s:0:"";s:2:"id";s:0:"";}s:13:"parent_layout";s:13:"589c18dfc9b28";s:9:"post_type";a:0:{}s:8:"taxonomy";a:0:{}s:10:"allow_null";i:0;s:8:"multiple";i:0;s:13:"return_format";s:6:"object";s:2:"ui";i:1;}',
            'post_excerpt' => 'post',
        ]);

        factory(BaseField::class)->create([
            'post_parent' => $acfField->ID,
            'post_content' => 'a:12:{s:4:"type";s:11:"post_object";s:12:"instructions";s:0:"";s:8:"required";i:0;s:17:"conditional_logic";i:0;s:7:"wrapper";a:3:{s:5:"width";s:0:"";s:5:"class";s:0:"";s:2:"id";s:0:"";}s:13:"parent_layout";s:13:"589c1ee35ec27";s:9:"post_type";a:0:{}s:8:"taxonomy";a:0:{}s:10:"allow_null";i:0;s:8:"multiple";i:1;s:13:"return_format";s:6:"object";s:2:"ui";i:1;}',
            'post_excerpt' => 'post',
        ]);

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
}
