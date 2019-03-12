<?php

use Tbruckmaier\Corcelacf\Models\Post;
use Corcel\Model\Post as CorcelPost;
use Tbruckmaier\Corcelacf\Tests\TestCase;
use Corcel\Model\Attachment;
use Tbruckmaier\Corcelacf\OptionPage;
use Tbruckmaier\Corcelacf\Models\BaseField;
use Corcel\Model\Meta\PostMeta;
use Tbruckmaier\Corcelacf\Models\Boolean;
use Tbruckmaier\Corcelacf\Models\File;
use Tbruckmaier\Corcelacf\Models\Image;
use Tbruckmaier\Corcelacf\Models\Text;
use Tbruckmaier\Corcelacf\Models\FlexibleContent;
use Corcel\Model\Option;
use Illuminate\Support\Collection;
use Tbruckmaier\Corcelacf\Support\FlexibleContentLayout;

class OptionPageTest extends TestCase
{
    /**
     * Create a option page with several fields
     */
    protected function createOptionPage($prefix = 'options')
    {
        $page = factory(OptionPage::class)->create();

        $acfText = factory(Text::class)->create(['post_parent' => $page->ID]);
        $this->addAcfOption('fake_email', 'junior@corcel.org', $acfText->post_name, $prefix);

        $image = factory(Attachment::class)->create(['post_excerpt' => 'This is a caption.']);
        $image->meta()->save(factory(PostMeta::class)->states('attachment_metadata')->create());
        $acfImage = factory(Image::class)->create(['post_parent' => $page->ID]);
        $this->addAcfOption('fake_image', $image->ID, $acfImage->post_name, $prefix);

        $file = factory(Attachment::class)->states('file')->create();
        $acfFile = factory(File::class)->create(['post_parent' => $page->ID]);
        $this->addAcfOption('fake_file', $file->ID, $acfFile->post_name, $prefix);

        $acfBoolean = factory(Boolean::class)->create(['post_parent' => $page->ID]);
        $this->addAcfOption('fake_true_false', '1', $acfBoolean->post_name, $prefix);

        return $page;
    }

    protected function addAcfOption($fieldName, $value, $internal, $prefix = 'options')
    {
        factory(Option::class)->create([
            'option_name' => $prefix . '_' . $fieldName,
            'option_value' => $value,
        ]);
        factory(Option::class)->create([
            'option_name' => '_' . $prefix . '_' . $fieldName,
            'option_value' => $internal,
        ]);
    }

    public function testPrefix()
    {
        $page = $this->createOptionPage('fake-prefix')->loadOptions('fake-prefix');

        $this->assertInstanceOf(Image::class, $page->getOptionField('fake_image'));

        $image = $page->getOption('fake_image');
        $this->assertInstanceOf(Attachment::class, $image);
        $this->assertEquals('image/jpeg', $image->mime_type);
    }

    public function testMissingField()
    {
        $page = $this->createOptionPage()->loadOptions();

        $this->assertNull($page->getOptionField('missing-field'));
        $this->assertNull($page->getOption('missing-field'));
    }

    public function testText()
    {
        $page = $this->createOptionPage()->loadOptions();
        $this->assertEquals('junior@corcel.org', $page->getOption('fake_email'));
        $this->assertInstanceOf(Text::class, $page->getOptionField('fake_email'));
    }

    public function testImage()
    {
        $page = $this->createOptionPage()->loadOptions();

        $this->assertInstanceOf(Image::class, $page->getOptionField('fake_image'));

        $image = $page->getOption('fake_image');
        $this->assertInstanceOf(Attachment::class, $image);
        $this->assertEquals('image/jpeg', $image->mime_type);
    }

    public function testFile()
    {
        $page = $this->createOptionPage()->loadOptions();

        $this->assertInstanceOf(File::class, $page->getOptionField('fake_file'));

        $file = $page->getOption('fake_file');
        $this->assertInstanceOf(Attachment::class, $file);
        $this->assertEquals('application/pdf', $file->mime_type);
    }

    public function testBoolean()
    {
        $page = $this->createOptionPage()->loadOptions();

        $this->assertInstanceOf(Boolean::class, $page->getOptionField('fake_true_false'));
        $this->assertTrue($page->getOption('fake_true_false'));
    }

    public function testFlexibleContent()
    {
        $page = factory(OptionPage::class)->create();

        $post = factory(CorcelPost::class)->create();

        $fc = factory(FlexibleContent::class)->create(['post_parent' => $page->ID]);

        $fcText = factory(BaseField::class)->states('fc_text')->create(['post_parent' => $fc->ID]);
        $fcPost = factory(BaseField::class)->states('fc_post')->create(['post_parent' => $fc->ID]);

        $this->addAcfOption('fake_flexible_content', serialize(['normal_text', 'related_post']), $fc->post_name);
        $this->addAcfOption('fake_flexible_content_0_text', 'fake text', $fcText->post_name);
        $this->addAcfOption('fake_flexible_content_1_post', $post->ID, $fcPost->post_name);

        $page->loadOptions();

        $this->assertInstanceOf(FlexibleContent::class, $page->getOptionField('fake_flexible_content'));

        $layouts = $page->getOption('fake_flexible_content');
        $this->assertInstanceOf(Collection::class, $layouts);
        $this->assertEquals(2, $layouts->count());

        $layout0 = $layouts->get(0);
        $this->assertInstanceOf(FlexibleContentLayout::class, $layout0);
        $this->assertInstanceOf(Text::class, $layout0->text());
        $this->assertEquals('fake text', $layout0->text);

        $layout1 = $layouts->get(1);
        $this->assertInstanceOf(FlexibleContentLayout::class, $layout1);
        $this->assertInstanceOf(Post::class, $layout1->post());

        $this->assertInstanceOf(CorcelPost::class, $layout1->post);
        $this->assertTrue($post->is($layout1->post));

    }
}
