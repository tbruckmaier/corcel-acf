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

        $ret = collect();

        for ($i = 0; $i < $count; $i++) {
            $row = collect();

            foreach ($this->layouts as $layout) {
                $field = clone $layout; // not replicate(), as it strips the ID field and we need it for nested repeaters
                $internalName = sprintf('%s_%d_%s', $this->localKey, $i, $layout->post_excerpt);

                $field->setData($this->data)->setLocalKey($internalName);

                $row->put($layout->post_excerpt, $field);
            }

            $ret->push(new RepeaterLayout($row));
        }

        return $ret;
    }
}
