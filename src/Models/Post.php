<?php

namespace Tbruckmaier\Corcelacf\Models;

use Corcel\Model\Post as CorcelPost;

class Post extends BaseField
{
    /**
     * Whether "multiple" is checked.
     *
     * @return bool
     */
    public function getIsMultipleAttribute()
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
     * If multiple is true, the internal value is a serialized array of post
     * ids. Otherwise it is a plain single post id
     *
     * @return mixed
     */
    public function getInternalValueAttribute()
    {
        $value = $this->data->get($this->localKey);
        if (!$this->is_multiple) {
            return $value;
        }
        
        return (@unserialize($value) ?: []);
    }

    /**
     * Get the related post instances (depending on is_multiple)
     *
     * @return mixed
     */
    public function getValueAttribute()
    {
        if ($this->is_multiple) {
            // it would be nice if we could implement this as a hasMany()
            // relation, but laravel does not support whereIn() in relationships
            return (new CorcelPost)->whereIn('ID', $this->internal_value)->get();
        }

        return $this->relationSingle;
    }
}
