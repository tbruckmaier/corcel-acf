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

class CorcelIntegrationTest extends TestCase
{
    protected $acfFields;

    protected function setUp() : void
    {
        parent::setUp();
        $this->acfFields = [
            factory(Text::class)->create(),
            factory(DateTime::class)->states('date_picker')->create(),
        ];
    }

    protected function createPost($fakeText = 'Lorem ipsum')
    {
        Post::addAcfRelations(['fake_text', 'fake_date_picker']);

        $post = factory(Post::class)->create();

        $this->addAcfMetaField($post, 'fake_text', $fakeText, $this->acfFields[0]->post_name);

        $this->addAcfMetaField($post, 'fake_date_picker', '20161013', $this->acfFields[1]->post_name);

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

    public function testDeletedAcfGroup()
    {
        // create two acf fields with the same post_name ('field_asfsdf'). One
        // belongs to a deleted acf group, this must not be returned

        Post::addAcfRelations(['fake_field']);

        // deleted acf group, this field should not get returned
        $group1 = factory(BaseFieldGroup::class)->create(['post_status' => 'trash']);
        $text1 = factory(Text::class)->create(['post_parent' => $group1->ID]);
        $fieldKey = $text1->post_name;

        // published acf group, this is the field we want
        $group2 = factory(BaseFieldGroup::class)->create();
        $text2 = factory(DateTime::class)->states('date_picker')->create(['post_parent' => $group2->ID, 'post_name' => $fieldKey]);

        $post = factory(Post::class)->create();
        $this->addAcfMetaField($post, 'fake_field', '20161013', $fieldKey);

        $this->assertInstanceOf(DateTime::class, $post->acf->fake_field());
    }
}
