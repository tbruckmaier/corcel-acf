<?php

namespace Tbruckmaier\Corcelacf\Support;

use Illuminate\Support\Collection;

/**
 * The fields inside one group
 */
class GroupLayout extends BaseLayout
{
    public function __construct(Collection $data = null)
    {
        $this->data = $data;
    }
}
