<?php

namespace Tbruckmaier\Corcelacf\Models;

use Corcel\Model\Post as CorcelPost;

class PageLink extends BaseField
{
    /**
     * Relation to the linked post
     *
     * @return CorcelPost
     */
    public function relation()
    {
        return $this->hasOne(CorcelPost::class, 'ID', 'internal_value');
    }

    /**
     * Get the the related post
     *
     * @return CorcelPost
     */
    public function getValueAttribute()
    {
        return $this->relation;
    }
}
