<?php

namespace Tbruckmaier\Corcelacf\Models\Traits;

/**
 * For fields which are sometimes serialized
 */
trait SerializedSometimes
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
        if (!$this->data->has($this->localKey)) {
            return $this->getDefaultValue($this->is_serialized ? [] : null);
        }
        
        $value = $this->data->get($this->localKey);
        if (!$this->is_serialized) {
            return $value;
        }
        
        return (@unserialize($value) ?: []);
    }
}
