<?php

namespace Tbruckmaier\Corcelacf\Models;

use Tbruckmaier\Corcelacf\Support\RepeaterLayout;

class Repeater extends BaseField
{
    protected $with = ['layouts'];

    public function layouts()
    {
        return $this->hasMany(BaseField::class, 'post_parent');
    }

    public function getValueAttribute()
    {
        $count = $this->internal_value;
        $layouts = $this->layouts->keyBy('type');

        $ret = collect();

        for ($i = 0; $i < $count; $i++) {
            $row = collect();

            foreach ($layouts as $layout) {
                $field = $layout->replicate();
                $internalName = sprintf('%s_%d_%s', $this->localKey, $i, $layout->post_excerpt);

                $field->setData($this->data)->setLocalKey($internalName);

                $row->put($layout->post_excerpt, $field);
            }

            $ret->push(new RepeaterLayout($row));
        }

        return $ret;
    }
}
