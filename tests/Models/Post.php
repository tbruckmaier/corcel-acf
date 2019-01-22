<?php

namespace Tbruckmaier\Corcelacf\Tests\Models;

use Corcel\Model\Post as BasePost;
use Tbruckmaier\Corcelacf\AcfTrait;

class Post extends BasePost
{
    use AcfTrait;
}
