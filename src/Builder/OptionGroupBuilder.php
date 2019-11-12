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
        // if the attributes of this option group have been manually set (acf
        // fields defined in php), we directly return an instance of this field
        // with the children set (no need to check the database)
        if ($this->fieldConfigs) {

            // turn the field array into (sub)instances of BaseField
            $childFields = collect($this->fieldConfigs)
                ->map(function ($fieldConfig) {
                    return $this->makeBaseField($fieldConfig);
                });

            // create the OptionGroup and set its children relation
            $optionGroup = $this->getModel()->newFromBuilder();
            $optionGroup->setRelation(
                'children',
                $childFields
            );

            return \collect([$optionGroup]);
        }

        // if the fields are not defined in php,  we use the regular get()
        // method (which fires a select query to the database)
        return parent::get($columns);
    }
}
