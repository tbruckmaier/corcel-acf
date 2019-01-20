<?php

namespace Tbruckmaier\Corcelacf\Models;

class Choice extends Generic
{
    use Traits\SerializedMultiple;

    public function getIsSerializedAttribute()
    {
        // checkbox is always serialized
        if ($this->type === 'checkbox') {
            return true;
        }

        // otherwise the field "multiple" specifies
        return !empty(array_get($this->config, 'multiple'));
    }
}
