<?php

use Tbruckmaier\Corcelacf\Models\Text;
use Tbruckmaier\Corcelacf\Models\Choice;
use Tbruckmaier\Corcelacf\Models\Link;
use Tbruckmaier\Corcelacf\Models\Image;
use Tbruckmaier\Corcelacf\Models\File;
use Tbruckmaier\Corcelacf\Models\Boolean;
use Tbruckmaier\Corcelacf\Models\Post;
use Tbruckmaier\Corcelacf\Models\PageLink;
use Tbruckmaier\Corcelacf\Models\Term;
use Tbruckmaier\Corcelacf\Models\DateTime;
use Tbruckmaier\Corcelacf\Models\Group;
use Tbruckmaier\Corcelacf\Models\Repeater;
use Tbruckmaier\Corcelacf\Models\FlexibleContent;
use Tbruckmaier\Corcelacf\Models\Generic;
use Tbruckmaier\Corcelacf\Models\Gallery;
use Tbruckmaier\Corcelacf\Models\User;

return [
    'classMapping' => [
        'text' => Text::class,
        'textarea' => Text::class,
        'number' => Text::class,
        'email' => Text::class,
        'url' => Text::class,
        'password' => Text::class,
        'wysiwyg' => Text::class,
        'editor' => Text::class,
        'oembed' => Text::class,
        'embed' => Text::class,
        'color_picker' => Text::class,
        'select' => Choice::class,
        'checkbox' => Choice::class,
        'radio' => Choice::class,
        'link' => Link::class,
        'image' => Image::class,
        'img' => Image::class,
        'file' => File::class,
        'gallery' => Gallery::class,
        'true_false' => Boolean::class,
        'boolean' => Boolean::class,
        'post_object' => Post::class,
        'post' => Post::class,
        'relationship' => Post::class,
        'page_link' => PageLink::class,
        'taxonomy' => Term::class,
        'term' => Term::class,
        'user' => User::class,
        'date_picker' => DateTime::class,
        'date_time_picker' => DateTime::class,
        'time_picker' => DateTime::class,
        'group' => Group::class,
        'repeater' => Repeater::class,
        'flexible_content' => FlexibleContent::class,
    ],

    'user_class' => \Corcel\Model\User::class,

    'timezone_string' => null,
];
