# Corcel ACF Plugin

[![Travis](https://travis-ci.org/tbruckmaier/corcel-acf.svg?branch=master)](https://travis-ci.org/tbruckmaier/corcel-acf?branch=master)
[![Packagist](https://img.shields.io/packagist/v/tbruckmaier/corcel-acf.svg)](https://github.com/ctbruckmaier/corcel-acf/releases)
[![Packagist](https://img.shields.io/packagist/dt/tbruckmaier/corcel-acf.svg)](https://packagist.org/packages/tbruckmaier/corcel-acf)

> Fetch all Advanced Custom Fields (ACF) fields inside Corcel easily.

This Corcel plugin allows you to fetch WordPress custom fields created by the [ACF](http://advancedcustomfields.com) plugin using the same syntax of Eloquent, from the [Laravel Framework](http://laravel.com). You can use Eloquent models and Collections to improve your development, using the WordPress backend with any PHP application.

For more information about how Corcel works please visit [the repository](http://github.com/jgrossi/corcel).

* [Installation](#installation)
* [Features](#features)
* [Usage](#usage)
    - [Functionality](#functionality)
    - [Fields](#fields)
    - [Custom field types](#custom-field-types)
* [Running Tests](#running-tests)
* [Licence](#licence)

# Installation

To install the ACF plugin for Corcel is easy:

```
composer require tbruckmaier/corcel-acf
```

Corcel is required for this plugin, but don't worry, if it's missing it will be installed as well.

# Features

* load acf fields via eloquent relations
* load acf data for a post only once and save sql queries
* support for eager loading of acf relations
* return suitable data types for the different acf fields (see table below), including a fallback for unknown field types
* support for deeply encapsulated fields (e.g. a image in a repeater in a flexible content)
* TODO: consider custom post types
* possible to access acf field config and internal attributes
* TODO: support for option page

# Basic usage

The easiest way to create the acf relations is the included trait:

```php

use \Corcel\Models\Post as BasePost;
use Tbruckmaier\Corcelacf\AcfTrait;

class Post extends BasePost
{
    use AcfTrait;

    public static function boot()
    {
        self::addAcfRelations(['title', 'thumbnail']);
        parent::boot();
    }
}

```
This dynamically creates the relationships `acf_title()` and `acf_thumbnail()`. Acf fields can now be accessed:

```php

use Corcel\Models\Post;
use Corcel\Models\Attachment;
use Tbruckmaier\Corcelacf\Models\Text;
use Tbruckmaier\Corcelacf\Models\Image;

$post = Post::find(1);

// post has a text field named "title" and a image field called "thumbnail"

$post->acf_title; // an instance of Text::class
$post->acf_title->value; // "Example title"
$post->acf_thumbnail; // Image::class
$post->acf_thumbnail->value; // an instance of Attachment::class representing the specified image
```

To make things easier, `AcfTrait` also includes `getAcfAttribute`, which returns an instance of the helper class `Acf` (this replaces the outdated corcel acf plugin from https://github.com/corcel/acf). Relation can also be accessed like:

```php
$post->acf->title; // (string) the parsed value, for instance "Example Page #1"
$post->acf->title(); // an instance of the underlying Text::class
$post->acf->title()->value; // the parsed value, $post->acf->title is a short version of this
$post->acf->title()->internal_value; // the unparsed value, for text's this is the same as value
$post->acf->title()->config; // the acf field config array defined in wordpress, sth like ['type' => 'text', 'instructions' => 'The site title', ...]

$post->acf->thumbnail; // an instance of Attachment::class representing the specified image
$post->acf->thumbnail(); // an instance of the underlying Image::class
$post->acf->thumbnail()->value; // again the same Attachment::class
$post->acf->thumbnail()->internal_value; // the unparsed value from the `postmeta` table, in this case the attachment id
$post->acf->thumbnail()->config; // the thumbnail acf field config array (['type' => 'image', ...])
```

To determine which model is used for which field, the acf field's `type` variable is used. See below for a full list.

## Functionality

Wordpress stores the config of each acf field in the table `wp_posts` as a custom post type `acf-field`. `post_content` contains the serialized config array, which contains the acf field type (`type`) and everything else you can specify when creating acf fields. Some of these values are important for parsing, some only specify things on how to display the field in wordpress itself.

When a post is saved in Wordpress with acf fields, those values are stored as the post's meta data in the table `wp_postmeta`. Each acf field saves two values: the according acf field name in `wp_posts` and the actual value. Depending on the acf field type and configuration, the saved value differs (text field values are stored as plain text, relation fields like image as the attachment id, and repeater & flexible content fields as a whole bunch of different fields).

For instance, the example fields from above are saved like this:
```
| meta_key   | meta_value          |
|:-----------|:--------------------|
| _title     | field_5bdae4fb72c4a |
| title      | Example Page #1     |
| _thumbnail | field_5c3b6543480d6 |
| thumbnail  | 5                   |
```

When loading a post with corcel, the meta data is automatically retrieved from the database anyway. If the static propery `$acfRelations` is defined in the model's `boot()` method, 

The base `Acf` class uses this data and passes it to all fields (and possibly subfields), so no extra queries are needed. Just relational fields like `Image` need another query to find the correct `Attachment` class (though if you are only interested in the attachment's id, you can access `$post->acf->thumbnail()->internal_value` and no additional query is used).

This just adds a `getAcfAttribute()` method, which returns an instance of the base `Tbruckmaier\Corcelacf\Acf` class (this overwrites the corcel-internal default support for the outdated Corcel acf plugin: https://github.com/corcel/acf/tree/. If you would like to use it in parallel, you can define the `getAcfAttribute` method by yourself with a different name)


## Advanced usage

### Defining acf relations

Instead of using the model's `boot()` method to create relationships on the fly, one can also define them manually:

```php
use Corcel\Models\Post as BasePost;
use Tbruckmaier\Corcelacf\AcfTrait;

class Post extends BasePost
{
    use AcfTrait;

    public function thumbnail()
    {
        return $this->hasAcf('thumbnail');
    }
}

$post = Post::find(1);
$post->thumbnail; // Image::class
$post->thumbnail->value; // Attachment
```

### Eager loading

If you want to eager-load acf fields, you can use the standard eloquent syntax. If the relationships are created from `$acfRelations`, do not forget to pass the prefix:

```php
$posts = Post::all()->load('acf_thumbnail');
```

## Fields

The following field types are supported (everything else just returns a `Generic` field):

| Field             | Internal class  | Parsed response                               | __toString()        |
|:------------------|:----------------|:----------------------------------------------|:--------------------|
| Text              | Text            | `string`                                      |                     |
| Textarea          | Text            | `string`                                      |                     |
| Number            | Text            | `string`                                      |                     |
| E-mail            | Text            | `string`                                      |                     |
| URL               | Text            | `string`                                      |                     |
| Password          | Text            | `string`                                      |                     |
| WYSIWYG (Editor)  | Text            | `string`                                      |                     |
| oEmbed            | Text            | `string`                                      |                     |
| Image             | Image           | `Corcel\Model\Attachment`                     |                     |
| File              | File            | `Corcel\Model\Attachment`                     |                     |
| Gallery           | Gallery         | `Collection` of `Corcel\Model\Attachment`     |                     |
| Select            | Choice          | `string` or `array`                           |                     |
| Checkbox          | Choice          | `string` or `array`                           |                     |
| Radio             | Choice          | `string`                                      |                     |
| True/False        | Boolean         | `boolean`                                     |                     |
| Post Object       | Post            | `Corcel\Model\Post` or `Collection` of `Post` |                     |
| Relationship      | Post            | `Corcel\Model\Post` or `Collection` of `Post` |                     |
| Page Link         | PageLink        | `string`                                      |                     |
| Link              | Link            | `array` or `string`                           | HTML <a> tag or url |
| Taxonomy          | Term            | `Corcel\Term` or `Collection` of `Term`       |                     |
| User              | Generic TODO    | `Corcel\User`                                 |                     |
| Date Picker       | DateTime        | `Carbon\Carbon`                               |                     |
| Date Time Picker  | DateTime        | `Carbon\Carbon`                               |                     |
| Time Picker       | DateTime        | `Carbon\Carbon`                               |                     |
| Color Picker      | Text            | `string`                                      |                     |
| Repeater          | Repeater        | `Collection` of `RepeaterLayout`              |                     |
| Flexible Content  | FlexibleContent | `Collection` of `FlexibleContentLayout`       |                     |
| (everything else) | Generic         | string                                        |                     |

### Link

The link field reacts on the configured return value, so it returns either an array with `title`, `text` and `url` or just the `url` as string.

The field has a `render()` method, which renders a html <a> tag. `render()` supports custom link text and custom attributes: `render('<img src="img.jpg" />', ['class' => 'class-1'])` returns `<a href="#" target="_blank" class="class-1" title="acf title"><img src="img.jpg" /></a>`

When accessing the field as string (`(string)$post->acf->link()` or in blade `{!! $post->acf->link !!}`), `render()` is called, so a html string is returned.`

### Repeater & Flexible Content

Repeater and flexible content fields return a `Collection` of `RepeaterLayout` respectively `FlexibleContentLayout`. These models act like the original `Acf` class: when accessing fields as attributes, the parsed value of the field is returned, otherwise a field like in the table above.

```php
use Corcel\Models\Post;
use Tbruckmaier\Corcelacf\Models\Text;
use Tbruckmaier\Corcelacf\Models\Repeater;
use Tbruckmaier\Corcelacf\Models\FlexibleContent;
use Tbruckmaier\Corcelacf\Support\RepeaterLayout;
use Tbruckmaier\Corcelacf\Support\FlexibleContent;

$post = Post::find(1);

$post->acf->main_repeater(); // Repeater
$repeaterFields = $post->acf->main_repeater; // Collection of RepeaterLayout
$repeaterFields->first()->title(); // Text::class
$repeaterFields->first()->title; // parsed response "Main repeater title #1"
$repeaterFields->get(1)->title(); // Text::class
$repeaterFields->get(1)->title; // "Main repeater title #2"

$post->acf->main_content(); // FlexibleContent
$fcLayouts = $post->acf->main_content; // Collection of FlexibleContentLayout

$fcLayouts->get(0)->getType(); // layout type of the first block, for example "text_with_image"
$fcLayouts->get(0)->text(); // Text::class
$fcLayouts->get(0)->text; // "Text of the first content block"
$fcLayouts->get(0)->image(); // Image::class
$fcLayouts->get(0)->image; // Attachment::class (linked image)

$fcLayouts->get(1)->getType(); // layout type of the second block, for example "accordion"
$fcLayouts->get(1)->accordion_title; // "Accordion #1"
$fcLayouts->get(1)->accordion_items(); // Repeater::class
$fcLayouts->get(1)->accordion_items; // Collection of RepeaterLayouts
$fcLayouts->get(1)->accordion_items->first()->title; // "First accordion element"
$fcLayouts->get(1)->accordion_items->first()->content; // "First accordion content..."
```

## Custom field types

TODO: it should be possible to have a config to use own field types. Currently just corcel models can be configured (https://github.com/corcel/corcel#-custom-post-type)

# Running Tests

To run the phpunit tests, execute `phpunit`:

```
./vendor/bin/phpunit
```

# Licence

[MIT License](http://jgrossi.mit-license.org/) © Junior Grossi
