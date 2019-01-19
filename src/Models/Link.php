<?php

namespace Tbruckmaier\Corcelacf\Models;

class Link extends Generic
{
    use Traits\SerializedValue;

    public function getValueAttribute()
    {
        $value = $this->internal_value;

        return sprintf(
            '<a href="%s" title="%s" target="%s">%s</a>',
            $this->url,
            $this->title,
            $this->target,
            $this->title
        );
    }

    public function getUrlAttribute()
    {
        return array_get($this->internal_value, 'url');
    }

    public function getTitleAttribute()
    {
        return array_get($this->internal_value, 'title');
    }

    public function getTargetAttribute()
    {
        return array_get($this->internal_value, 'target');
    }
}
