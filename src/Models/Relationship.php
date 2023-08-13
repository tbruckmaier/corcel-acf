<?php

namespace Tbruckmaier\Corcelacf\Models;

use Corcel\Model\Post as CorcelPost;

class Relationship extends BaseField
{
    use Traits\SerializedValue;

    /**
     * Get the related post instances (depending on is_serialized)
     *
     * @return mixed
     */
    public function getValueAttribute()
    {
        // it would be nice if we could implement this as a hasMany()
        // relation, but laravel does not support whereIn() in relationships
        return $this->getSortedRelation(CorcelPost::class, $this->internal_value ?: []);
    }
}
