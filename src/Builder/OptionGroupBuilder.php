<?php

namespace Tbruckmaier\Corcelacf\Builder;

use Tbruckmaier\Corcelacf\Models\BaseField;

class OptionGroupBuilder extends FieldGroupBuilder
{
    protected $fieldConfigs;

    /**
     * Sets the config / attributes of this field directly (is called when the
     * field is defined in php)
     */
    public function setFieldConfigs(array $fieldConfigs)
    {
        $this->fieldConfigs = $fieldConfigs;
        return $this;
    }

    /**
     * This method is called whenever the query is "fired", e.g. when an
     * instance of a field shall be retrieved
     */
    public function get($columns = ['*'])
    {
        $instances = parent::get($columns);
        
        if ($this->fieldConfigs) {
            $instances->each(function($instance) {
                $instance->setRelation(
                    'fields',
                    collect($this->fieldConfigs)->map(function ($x) {
                        return $this->makeBaseField($x);
                    })
                );
            });

        }

        return $instances;
    }
}
