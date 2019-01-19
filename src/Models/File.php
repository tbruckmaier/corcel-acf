<?php

namespace Tbruckmaier\Corcelacf\Models;

use Corcel\Model\Attachment;

class File extends BaseField
{
    public function attachment()
    {
        return $this->hasOne(Attachment::class, 'ID', 'internal_value');
    }

    public function getValueAttribute()
    {
        return $this->attachment;
    }
}
