<?php

namespace Tbruckmaier\Corcelacf\Models;

use Corcel\Model\Attachment;

class Gallery extends Generic
{
    use Traits\SerializedValue;

    public function getValueAttribute()
    {
        // FIXME add sorting
        return Attachment::whereIn('ID', $this->internal_value)->get();
    }
}
