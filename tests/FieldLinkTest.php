<?php

use Tbruckmaier\Corcelacf\Models\Link;
use Tbruckmaier\Corcelacf\Tests\TestCase;
use Corcel\Model\Attachment;

class FieldLinkTest extends TestCase
{
    public function testLinkField()
    {
        $acfField = factory(Link::class)->create();

        $data = [
            'title' => 'test "title"',
            'url' => 'https://www.example.com/example',
            'target' => '_blank',
        ];

        $this->addData($acfField, 'fake_link', serialize($data));

        $this->assertEquals($data, $acfField->value);
        $this->assertEquals($data['url'], $acfField->url);
        $this->assertEquals($data['title'], $acfField->title);
        $this->assertEquals($data['target'], $acfField->target);

        $this->assertEquals(
            '<a href="https://www.example.com/example" title="test &quot;title&quot;" target="_blank">test &quot;title&quot;</a>',
            (string)$acfField
        );

        $this->assertEquals(
            '<a href="https://www.example.com/example" title="test &quot;title&quot;" target="_self" class="class-1">test &quot;title&quot;</a>',
            $acfField->render(null, ['target' => '_self', 'class' => 'class-1'])
        );

        $this->assertEquals(
            '<a href="https://www.example.com/example" title="test &quot;title&quot;" target="_blank" class="class-1"><b>custom text</b></a>',
            $acfField->render('<b>custom text</b>', ['class' => 'class-1'])
        );
    }

    public function testLinkUrlField()
    {
        $acfField = factory(Link::class)->states('url_return')->create();

        $data = [
            'title' => 'test "title"',
            'url' => 'https://www.example.com/example',
            'target' => '_blank',
        ];

        $this->addData($acfField, 'fake_link', serialize($data));

        $this->assertEquals('https://www.example.com/example', $acfField->value);
        $this->assertEquals(
            '<a href="https://www.example.com/example" title="test &quot;title&quot;" target="_blank">test &quot;title&quot;</a>',
            (string)$acfField
        );
    }
}
