<?php

namespace Corcel\Acf\Models;

use Corcel\Model\Attachment;

class Image extends BaseField
{
    public function attachment()
    {
        return $this->hasOne(Attachment::class, 'ID', 'value');
    }
}
