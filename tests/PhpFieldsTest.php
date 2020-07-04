<?php

use Tbruckmaier\Corcelacf\Tests\Models\Post;
use Tbruckmaier\Corcelacf\Tests\TestCase;
use Tbruckmaier\Corcelacf\Acf;
use Tbruckmaier\Corcelacf\AcfRelation;
use Tbruckmaier\Corcelacf\Models\BaseFieldGroup;
use Tbruckmaier\Corcelacf\Models\Text;
use Tbruckmaier\Corcelacf\Models\DateTime;
use Corcel\Model\Meta\PostMeta;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Corcel\Model\Collection\MetaCollection;

class PhpFieldsTest extends TestCase
{
    protected function setUp() : void
    {
        parent::setUp();

        $this->acfFields = [
            factory(Text::class)->make(['post_parent' => 1]),
            factory(DateTime::class)->states('date_picker')->make(['post_parent' => 1]),
        ];
    }

    protected function createPost($fakeText = 'Lorem ipsum')
    {
        $keys = ['field_1234567890123', 'field_0123456789012'];

        Post::addAcfRelations([
            'fake_text' => ['name' => 'fake_text', 'type' => 'text', 'key' => $keys[0]],
            'fake_date_picker' => ['name' => 'fake_date_picker', 'type' => 'date_picker', 'key' => $keys[1]],
        ]);

        $post = factory(Post::class)->make();

        $this->addAcfMetaField($post, 'fake_text', $fakeText, $keys[0]);
        $this->addAcfMetaField($post, 'fake_date_picker', '20161013', $keys[1]);

        return $post;
    }

    protected function addAcfMetaField($post, $fieldName, $value, $internal)
    {
        if (!array_key_exists('meta', $post->getRelations())) {
            $post->setRelation('meta', new MetaCollection());
        }

        $post->meta->push(factory(PostMeta::class)->make([
            'meta_key' => $fieldName,
            'meta_value' => $value,
        ]));
        $post->meta->push(factory(PostMeta::class)->make([
            'meta_key' => '_' . $fieldName,
            'meta_value' => $internal,
        ]));

        return $post;    
    }

    public function testPhpFieldIntegration()
    {
        $post = $this->createPost();

        $this->assertInstanceOf(AcfRelation::class, $post->acf_fake_text());
        $this->assertInstanceOf(Text::class, $post->acf_fake_text);

        $this->assertInstanceOf(Text::class, $post->acf->fake_text());
        $this->assertEquals('Lorem ipsum', $post->acf->fake_text);
        $this->assertInstanceOf(DateTime::class, $post->acf->fake_date_picker());
        $this->assertInstanceOf(Carbon::class, $post->acf->fake_date_picker);
    }
}
