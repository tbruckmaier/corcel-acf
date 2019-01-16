<?php

namespace Tbruckmaier\Corcelacf\Support;

use Illuminate\Support\Collection;

/**
 * Represents one block of a repeater field
 */
class RepeaterLayout
{
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

    /**
     * Make subfields easily accessible
     */
    public function __get($key)
    {
        return array_get($this->data, $key);
    }
}
