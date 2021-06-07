<?php

namespace Tbruckmaier\Corcelacf\Support;

use Illuminate\Support\Collection;

/**
 * Represents one block of a repeater field
 */
class RepeaterLayout extends BaseLayout
{
    public function __construct(Collection $data = null)
    {
        $this->data = $data;
    }
}
