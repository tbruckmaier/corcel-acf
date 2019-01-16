<?php

namespace Corcel\Acf\Models;

use Corcel\Model\Post;

class AcfFieldImage extends AcfField
{
    public function attachment()
    {
        return $this->hasOne(\Corcel\Model\Attachment::class, 'ID', 'post_content_value');
    }
}
