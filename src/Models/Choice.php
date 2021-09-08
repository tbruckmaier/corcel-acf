<?php

namespace Tbruckmaier\Corcelacf\Models;

use Illuminate\Support\Arr;

class Choice extends Generic
{
    use Traits\SerializedSometimes;

    public function getIsSerializedAttribute(): bool
    {
        // checkbox is always serialized
        if ($this->type === 'checkbox') {
            return true;
        }

        // otherwise the field "multiple" specifies
        return !empty(Arr::get($this->config, 'multiple'));
    }
}
