<?php

namespace Tbruckmaier\Corcelacf\Models;

use Tbruckmaier\Corcelacf\Support\FlexibleContentLayout;
use Illuminate\Support\Arr;

class FlexibleContent extends BaseField
{
    use Traits\SerializedSometimes;

    protected $with = ['children'];

    public function getIsSerializedAttribute(): bool
    {
        return true;
    }

    public function getLayoutBlocksAttribute()
    {
        // all available layout blocks e.g. ["5898b06bd55ed" => "infobox"]
        $availableLayouts = collect(Arr::get($this->config, 'layouts'))->pluck('name', 'key');

        // the fields in the layout blocks are all children of the root fc field
        $layouts = $this->children
            // They are associated with a layout block via parent_layout
            // ("5898b06bd55ed"). So lets group them by their layout block
            ->groupBy('config.parent_layout')
            // and now change the keys from the internal id ("5898b06bd55ed") to
            // the internal block name ("infobox")
            ->keyBy(function ($item, $key) use ($availableLayouts) {
                return $availableLayouts->get($key);
            });

        return $layouts;
    }

    public function getValueAttribute()
    {
        $ret = collect();

        foreach ($this->internal_value as $i => $contentBlockType) {
            $block = $this->layout_blocks->get($contentBlockType);

            if (!$block) {
                // if the block can not be found in the flexible content field's
                // config, the fc has probably changed and the post has still
                // old data inside. In any case we have to ignore it, since we
                // do not know how to process it
                continue;
            }

            $block = $block->keyBy('post_excerpt')->map(function ($field) use ($i) {
                $internalName = sprintf('%s_%d_%s', $this->localKey, $i, $field->post_excerpt);
                return (clone $field)->setData($this->data)->setLocalKey($internalName);
            });

            $ret->push(new FlexibleContentLayout($contentBlockType, $block));
        }

        return $ret;
    }
}
