<?php

use Tbruckmaier\Corcelacf\Models\Group;
use Tbruckmaier\Corcelacf\Models\Text;
use Tbruckmaier\Corcelacf\Models\Boolean;
use Tbruckmaier\Corcelacf\Tests\TestCase;
use Illuminate\Support\Collection;
use Tbruckmaier\Corcelacf\Support\GroupLayout;

class FieldGroupTest extends TestCase
{
    public function testGroupField()
    {
        $groupField = factory(Group::class)->create();
        $field1 = factory(Text::class)->create(['post_parent' => $groupField->ID, 'post_excerpt' => 'fake_text']);
        $field2 = factory(Boolean::class)->create(['post_parent' => $groupField->ID, 'post_excerpt' => 'fake_boolean']);

        $data = [
            'fake_group' => '',
            'fake_group_fake_text' => 'First text',
            'fake_group_fake_boolean' => '1',
        ];

        $this->setData($groupField, $data)->setLocalKey('fake_group');

        $this->assertInstanceOf(GroupLayout::class, $groupField->value);
        $this->assertInstanceOf(Text::class, $groupField->value->fake_text());
        $this->assertEquals('First text', $groupField->value->fake_text);

        $this->assertInstanceOf(Boolean::class, $groupField->value->fake_boolean());
        $this->assertTrue($groupField->value->fake_boolean);
    }
}
