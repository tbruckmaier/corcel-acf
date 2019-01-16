<?php

namespace Tbruckmaier\Corcelacf\Support;

use Illuminate\Support\Collection;

/**
 * Represents one layout block of a flexible content field.
 */
class FlexibleContentLayout
{
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
     * Make subfields easily accessible
     */
    public function __get($key)
    {
        if ($key === 'type') {
            return $this->type;
        }

        return array_get($this->data, $key);
    }
}
