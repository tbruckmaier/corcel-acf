<?php

use Tbruckmaier\Corcelacf\Tests\Models\Post;
use Tbruckmaier\Corcelacf\Tests\TestCase;
use Tbruckmaier\Corcelacf\Acf;
use Tbruckmaier\Corcelacf\AcfRelation;
use Tbruckmaier\Corcelacf\Models\Text;
use Tbruckmaier\Corcelacf\Models\DateTime;
use Corcel\Model\Meta\PostMeta;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class CorcelIntegrationTest extends TestCase
{
    protected function createPost($fakeText = 'Lorem ipsum')
    {
        Post::addAcfRelations(['fake_text', 'fake_date_picker']);

        $post = factory(Post::class)->create();

        $acfField0 = factory(Text::class)->create();
        $this->addAcfMetaField($post, 'fake_text', $fakeText, $acfField0->post_name);

        $acfField1 = factory(DateTime::class)->states('date_picker')->create();
        $this->addAcfMetaField($post, 'fake_date_picker', '20161013', $acfField1->post_name);

        return $post;
    }

    protected function addAcfMetaField(Post $post, $fieldName, $value, $internal)
    {
        $post->meta()->save(factory(PostMeta::class)->create([
            'meta_key' => $fieldName,
            'meta_value' => $value,
        ]));
        $post->meta()->save(factory(PostMeta::class)->create([
            'meta_key' => '_' . $fieldName,
            'meta_value' => $internal,
        ]));

        return $post;    
    }

    public function testIfCorcelIntegrationIsWorking()
    {
        $post = $this->createPost();

        $this->assertInstanceOf(AcfRelation::class, $post->acf_fake_text());
        $this->assertInstanceOf(Text::class, $post->acf_fake_text);

        $this->assertInstanceOf(Text::class, $post->acf->fake_text());
        $this->assertEquals('Lorem ipsum', $post->acf->fake_text);
        $this->assertInstanceOf(DateTime::class, $post->acf->fake_date_picker());
        $this->assertInstanceOf(Carbon::class, $post->acf->fake_date_picker);
    }

    public function testEagerLoad()
    {
        $post = $this->createPost();

        $this->assertFalse(array_key_exists('acf_fake_text', $post->toArray()));
        $this->assertFalse($post->relationLoaded('acf_fake_text'));

        $post->load('acf_fake_text');
        $this->assertTrue(array_key_exists('acf_fake_text', $post->toArray()));
        $this->assertTrue($post->relationLoaded('acf_fake_text'));

        $this->assertInstanceOf(Text::class, $post->acf->fake_text());
        $this->assertEquals('Lorem ipsum', $post->acf->fake_text);
    }

    public function testEagerLoadMultiple()
    {
        $post0 = $this->createPost('Lorem 1');
        $post1 = $this->createPost('Lorem 2');
        $post2 = $this->createPost('Lorem 3');

        $posts = Post::whereIn('ID', [$post0->ID, $post1->ID, $post2->ID])->get();

        $this->assertFalse($posts->get(0)->relationLoaded('acf_fake_text'));
        $this->assertFalse($posts->get(1)->relationLoaded('acf_fake_text'));

        $posts->load('acf_fake_text');

        $this->assertTrue($posts->get(0)->relationLoaded('acf_fake_text'));
        $this->assertTrue($posts->get(1)->relationLoaded('acf_fake_text'));

        $this->assertEquals('Lorem 1', $posts->get(0)->acf->fake_text);
        $this->assertEquals('Lorem 2', $posts->get(1)->acf->fake_text);
    }
}
