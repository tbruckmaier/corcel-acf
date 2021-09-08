<?php

namespace Tbruckmaier\Corcelacf\Models;

use Corcel\Model\Term as CorcelTerm;
use Illuminate\Support\Arr;

class Term extends BaseField
{
    use Traits\SerializedSometimes;

    /**
     * Whether more than one taxonomy can be selected. Apparently, this depends
     * on the configured field_type, multiple is always 0
     */
    public function getIsMultipleAttribute()
    {
        $fieldType = Arr::get($this->config, 'field_type');
        return in_array($fieldType, ['multi_select', 'checkbox']);
    }

    public function getIsSerializedAttribute(): bool
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
            return $this->getSortedRelation(CorcelTerm::class, $this->internal_value, 'term_id');
        }

        return $this->relationSingle;
    }
}
