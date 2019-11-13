<?php

namespace Tbruckmaier\Corcelacf\Models;

use Corcel\Model\Post;
use Illuminate\Database\Eloquent\Builder;
use Tbruckmaier\Corcelacf\Builder\FieldGroupBuilder;

class BaseFieldGroup extends Post
{
    /**
     * @var string
     */
    protected $postType = 'acf-field-group';

    public function children()
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

    /**
     * @param \Illuminate\Database\Query\Builder $query
     * @return FieldGroupBuilder
     */
    public function newEloquentBuilder($query)
    {
        return new FieldGroupBuilder($query);
    }
}
