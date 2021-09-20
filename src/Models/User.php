<?php

namespace Tbruckmaier\Corcelacf\Models;

use Illuminate\Support\Arr;

class User extends BaseField
{
    use Traits\SerializedSometimes;

    protected function getUserClass()
    {
        return config('corcel-acf.user_class');
    }

    public function getIsMultipleAttribute() : bool
    {
        return (bool)Arr::get($this->config, 'multiple');
    }

    public function getIsSerializedAttribute() : bool
    {
        return $this->is_multiple;
    }

    public function relationSingle()
    {
        return $this->hasOne($this->getUserClass(), 'ID', 'internal_value');
    }

    public function getValueAttribute()
    {
        if (empty($this->internal_value)) {
            return ($this->is_multiple ? collect() : null);
        }

        if ($this->is_multiple) {
            return $this->getSortedRelation($this->getUserClass(), $this->internal_value, 'ID');
        }

        return $this->relationSingle;
    }
}
