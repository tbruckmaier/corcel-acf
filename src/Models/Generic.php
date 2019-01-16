<?php

namespace Corcel\Acf\Models;

class Generic extends BaseField
{
    public function getValueAttribute()
    {
        return $this->internal_value;
    }
}
