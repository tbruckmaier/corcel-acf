<?php

namespace Tbruckmaier\Corcelacf\Models;

use Corcel\Model\Attachment;

class Image extends BaseField
{
    public function value()
    {
        return $this->hasOne(Attachment::class, 'ID', 'internal_value');
    }
}
