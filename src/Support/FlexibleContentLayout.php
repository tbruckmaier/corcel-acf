<?php

namespace Tbruckmaier\Corcelacf\Support;

use Illuminate\Support\Collection;

/**
 * Represents one layout block of a flexible content field.
 */
class FlexibleContentLayout
{
    use Traits\LayoutBlock;

    /**
     * Layout block type
     *
     * @var string
     */
    protected $type;

    /**
     * The fields of this layout
     *
     * @var Collection|null
     */
    protected $data;

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
