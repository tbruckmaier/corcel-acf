<?php

namespace Tbruckmaier\Corcelacf\Models\Traits;

/**
 * For fields which have a flag "multiple" which states, if the value is in
 * serialized form or not
 */
trait SerializedMultiple
{
    /**
     * Whether the internal value is serialized.
     *
     * @return bool
     */
    abstract public function getIsSerializedAttribute() : bool;

    /**
     * If multiple is true, the internal value is a serialized array. Otherwise
     * it is a plain single value
     *
     * @return mixed
     */
    public function getInternalValueAttribute()
    {
        $value = $this->data->get($this->localKey);
        if (!$this->is_serialized) {
            return $value;
        }
        
        return (@unserialize($value) ?: []);
    }
}
