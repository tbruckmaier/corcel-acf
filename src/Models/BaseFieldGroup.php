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

    public function fields()
    {
        return $this->hasMany(BaseField::class, 'post_parent', 'ID');
    }

    public function getConfigAttribute()
    {
        return unserialize($this->post_content);
    }

    public function scopeActive(Builder $query)
    {
        return $query->whereIn('post_status', ['publish', 'acf-disabled']);
    }
}