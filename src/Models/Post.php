<?php

namespace Tbruckmaier\Corcelacf\Models;

use Corcel\Model\Post as CorcelPost;

class Post extends BaseField
{
    use Traits\SerializedSometimes;

    /**
     * If "multiple" is checked, internal value is serialized
     *
     * @return bool
     */
    public function getIsSerializedAttribute() : bool
    {
        return !empty(array_get($this->config, 'multiple'));
    }

    /**
     * When only a single post can be selected, we use a relationship to fetch
     * it
     *
     * @return CorcelPost
     */
    public function relationSingle()
    {
        return $this->hasOne(CorcelPost::class, 'ID', 'internal_value');
    }

    /**
     * Get the related post instances (depending on is_serialized)
     *
     * @return mixed
     */
    public function getValueAttribute()
    {
        if ($this->is_serialized) {
            // it would be nice if we could implement this as a hasMany()
            // relation, but laravel does not support whereIn() in relationships
            return (new CorcelPost)->whereIn('ID', $this->internal_value)->get();
        }

        return $this->relationSingle;
    }
}
