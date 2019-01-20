<?php

namespace Tbruckmaier\Corcelacf\Models;

use Corcel\Model\Post as CorcelPost;

class PageLink extends Post
{
    /**
     * Get the the related post
     *
     * @return CorcelPost
     */
    public function getValueAttribute()
    {
        $page = $this->relationSingle;

        $domain = substr($page->guid, 0, strpos($page->guid, '?'));

        if (empty($page->post_name)) {
            return $page->guid;
        }

        return "{$domain}{$page->post_name}/";
    }
}
