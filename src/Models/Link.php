<?php

namespace Tbruckmaier\Corcelacf\Models;

use Illuminate\Support\Arr;

class Link extends Generic
{
    use Traits\SerializedValue;

    /**
     * Whether this field shall return a link array or the url
     */
    public function getReturnFormatAttribute()
    {
        return Arr::get($this->config, 'return_format');
    }

    public function getValueAttribute()
    {
        if ($this->return_format === 'url') {
            return $this->url;
        }

        return $this->internal_value;
    }

    /**
     * Render the link as a html link tag
     *
     * @param string $linkText optional custom link text
     * @param array $customAttributes Optional custom attributes added to a <a>
     *
     * @return string html
     */
    public function render(string $linkText = null, array $customAttributes = [])
    {
        $attributes = array_replace([
            'href' => $this->url,
            'title' => $this->title,
            'target' => $this->target,
        ], $customAttributes);

        $linkText = ($linkText ?: e($this->title));

        $html = '<a';
        foreach ($attributes as $key => $value) {
            $html .= ' ' . $key . '="' . e($value) . '"';
        }
        $html .= '>' . $linkText . '</a>';
        return $html;
    }

    /**
     * When using this field as string, render html
     */
    public function __toString()
    {
        return $this->render();
    }

    public function getUrlAttribute()
    {
        return Arr::get($this->internal_value, 'url');
    }

    public function getTitleAttribute()
    {
        return Arr::get($this->internal_value, 'title');
    }

    public function getTargetAttribute()
    {
        return Arr::get($this->internal_value, 'target');
    }
}
