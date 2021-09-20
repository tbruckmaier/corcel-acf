<?php

use Tbruckmaier\Corcelacf\Models\User;
use Tbruckmaier\Corcelacf\Tests\TestCase;
use Corcel\Model\User as CorcelUser;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Tbruckmaier\Corcelacf\Tests\Models\CustomUser;

class FieldUserTest extends TestCase
{
    public function testUserField()
    {
        $user = factory(CorcelUser::class)->create();

        $acfField = factory(User::class)->create();

        $this->addData($acfField, 'fake_user_single', $user->ID);

        $this->assertInstanceOf(CorcelUser::class, $acfField->value);

        $this->assertTrue($user->is($acfField->value));
    }

    public function testTermMultiple()
    {
        $user = factory(CorcelUser::class)->create();
        $user2 = factory(CorcelUser::class)->create();

        $acfField = factory(User::class)->states('multiple')->create();
        $this->addData($acfField, 'fake_user_multiple', serialize([$user2->ID, $user->ID]));

        $this->assertInstanceOf(Collection::class, $acfField->value);
        $this->assertEquals(2, $acfField->value->count());
        $this->assertTrue($user2->is($acfField->value->first()));
        $this->assertTrue($user->is($acfField->value->get(1)));
    }

    public function testCustomUser()
    {
        Config::set('corcel-acf.user_class', CustomUser::class);

        $user = factory(CorcelUser::class)->create();
        $acfField = factory(User::class)->create();

        $this->addData($acfField, 'fake_user_single', $user->ID);

        Config::set('corcel-acf.user_class', CustomUser::class);
        $this->assertEquals(CustomUser::class, get_class($acfField->value));
    }
}
