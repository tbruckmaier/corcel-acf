<?php

namespace Tbruckmaier\Corcelacf\Models;

use Corcel\Model\Post;
use Illuminate\Database\Eloquent\Builder;

class BaseFieldGroup extends Post
{
    /**
     * @var string
     */
    protected $postType = 'acf-field-group';

    public function scopeActive(Builder $query)
    {
        return $query->whereIn('post_status', ['publish', 'acf-disabled']);
    }
}
