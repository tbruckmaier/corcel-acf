<?php

namespace Tbruckmaier\Corcelacf\Models;

use Corcel\Model\Term as CorcelTerm;

class Term extends BaseField
{
    use Traits\SerializedSometimes;

    /**
     * Whether more than one taxonomy can be selected. Apparently, this depends
     * on the configured field_type, multiple is always 0
     */
    public function getIsMultipleAttribute()
    {
        $fieldType = array_get($this->config, 'field_type');
        return in_array($fieldType, ['multi_select', 'checkbox']);
    }

    public function getIsSerializedAttribute()
    {
        return $this->is_multiple;
    }

    public function relationSingle()
    {
        return $this->hasOne(CorcelTerm::class, 'term_id', 'internal_value');
    }

    public function getValueAttribute()
    {
        if (empty($this->internal_value)) {
            return ($this->is_multiple ? collect() : null);
        }

        if ($this->is_multiple) {
            // FIXME add sorting
            return (new CorcelTerm)->whereIn('term_id', $this->internal_value)->get();
        }

        return $this->relationSingle;
    }
}
