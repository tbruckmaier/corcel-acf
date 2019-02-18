<?php

namespace Tbruckmaier\Corcelacf\Support;

use Illuminate\Support\Collection;

/**
 * The fields inside one group
 */
class GroupLayout
{
    use Traits\LayoutBlock;

    /**
     * The fields of this layout
     *
     * @var Collection|null
     */
    protected $data;

    public function __construct(Collection $data = null)
    {
        $this->data = $data;
    }
}
