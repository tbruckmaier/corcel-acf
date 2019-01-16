<?php

namespace Corcel\Acf\Models;

use Corcel\Acf\Support\FlexibleContentLayout;

class FlexibleContent extends BaseField
{
    protected $with = ['layouts'];

    public function layouts()
    {
        return $this->hasMany(BaseField::class, 'post_parent');
    }

    public function getLayoutBlocksAttribute()
    {
        // all available layout blocks e.g. ["5898b06bd55ed" => "infobox"]
        $availableLayouts = collect(array_get($this->config, 'layouts'))->pluck('name', 'key');

        // the fields in the layout blocks are all children of the root fc field
        $layouts = $this->layouts
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

        $contentBlockTypes = unserialize($this->internal_value);

        foreach ($contentBlockTypes as $i => $contentBlockType) {
            $block = $this->layout_blocks->get($contentBlockType)->keyBy('post_excerpt');

            $block->each(function($field) use ($i){
                $internalName = sprintf('%s_%d_%s', $this->localKey, $i, $field->post_excerpt);
                $field->setData($this->data)->setLocalKey($internalName);
            });

            $ret->push(new FlexibleContentLayout($contentBlockType, $block));
       }

       return $ret;
    }
}
