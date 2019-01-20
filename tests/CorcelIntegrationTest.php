<?php

use Corcel\Model\Post;
use Tbruckmaier\Corcelacf\Tests\TestCase;
use Tbruckmaier\Corcelacf\Acf;
use Tbruckmaier\Corcelacf\Models\Text;
use Tbruckmaier\Corcelacf\Models\DateTime;
use Corcel\Model\Meta\PostMeta;
use Carbon\Carbon;

class CorcelIntegrationTest extends TestCase
{
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
        $post = factory(Post::class)->create();

        $acfField0 = factory(Text::class)->create();
        $this->addAcfMetaField($post, 'fake_text', 'Lorem ipsum', $acfField0->post_name);

        $acfField1 = factory(DateTime::class)->states('date_picker')->create();
        $this->addAcfMetaField($post, 'fake_date_picker', '20161013', $acfField1->post_name);

        $acf = new Acf($post);

        $this->assertInstanceOf(Text::class, $acf->fake_text());
        $this->assertEquals('Lorem ipsum', $acf->fake_text);
        $this->assertInstanceOf(DateTime::class, $acf->fake_date_picker());
        $this->assertInstanceOf(Carbon::class, $acf->fake_date_picker);
    }

    public function testEmptyAcfRelation()
    {
        $post = factory(Post::class)->create();
        $acf = new Acf($post);

        $this->assertNull($acf->nonexisting());
        $this->assertNull($acf->nonexisting);
    }
}
