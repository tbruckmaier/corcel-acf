<?php

namespace Tbruckmaier\Corcelacf\Support;

use Illuminate\Support\Collection;

/**
 * Represents one layout block of a flexible content field.
 */
class FlexibleContentLayout extends BaseLayout
{
    /**
     * Layout block type
     *
     * @var string
     */
    protected $type;

    public function __construct(string $type, Collection $data = null)
    {
        $this->type = $type;
        $this->data = $data;
    }

    /**
     * Return this layout's type
     *
     * @return string
     */
    public function getType() : string
    {
        return $this->type;
    }
}
