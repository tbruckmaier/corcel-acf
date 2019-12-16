<?php

namespace Tbruckmaier\Corcelacf\Builder;

use Corcel\Model\Builder\PostBuilder as CorcelBuilder;

class FieldBuilder extends CorcelBuilder
{
    use BuilderTrait;

    protected $fieldConfig;

    /**
     * Sets the config / attributes of this field directly (is called when the
     * field is defined in php)
     */
    public function setFieldConfig(array $fieldConfig)
    {
        $this->fieldConfig = $fieldConfig;
        return $this;
    }

    /**
     * This method is called whenever the query is "fired", e.g. when an
     * instance of a field shall be retrieved
     */
    public function get($columns = ['*'])
    {
        // if the attributes of this field have been manually set (acf fields
        // defined in php), we directly return an instance of this field with
        // the attributes set (no need to check the database)
        if ($this->fieldConfig) {
            $field = $this->makeBaseField($this->fieldConfig);

            return \collect([$field]);
        }

        // otherwise we use the regular get() method (which fires a select query
        // to the database)
        return parent::get($columns);
    }
}
