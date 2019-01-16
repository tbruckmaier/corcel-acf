<?php

namespace Corcel\Acf\Models;

use Corcel\Model\Post;

class AcfFieldRepeater extends AcfField
{
    protected $with = ['layouts'];

    public function layouts()
    {
        return $this->hasMany(AcfField::class, 'post_parent');
    }

    public function getItemsAttribute()
    {
        $count = $this->post_content_value;
        $layouts = $this->layouts->keyBy('type');

        $ret = collect();

        for ($i = 0; $i < $count; $i++) {

            $row = collect();

            foreach ($layouts as $layout) {

                $field = $layout->replicate();
                $internalName = sprintf('%s_%d_%s', $this->localKey, $i, $layout->post_excerpt);

                $field->setPostContent($this->postContent)->setLocalKey($internalName);

                $row->put($layout->post_excerpt, $field);
            }

            $ret->push($row);
        }

        return $ret;
    }
}
