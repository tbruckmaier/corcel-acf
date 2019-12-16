<?php

namespace Tbruckmaier\Corcelacf\Models;

use Corcel\Model\User as CorcelUser;

class User extends BaseField
{
    use Traits\SerializedSometimes;

    public function getIsMultipleAttribute() : bool
    {
        return (bool)array_get($this->config, 'multiple');
    }

    public function getIsSerializedAttribute() : bool
    {
        return $this->is_multiple;
    }

    public function relationSingle()
    {
        return $this->hasOne(CorcelUser::class, 'ID', 'internal_value');
    }

    public function getValueAttribute()
    {
        if (empty($this->internal_value)) {
            return ($this->is_multiple ? collect() : null);
        }

        if ($this->is_multiple) {
            return $this->getSortedRelation(CorcelUser::class, $this->internal_value, 'ID');
        }

        return $this->relationSingle;
    }
}
