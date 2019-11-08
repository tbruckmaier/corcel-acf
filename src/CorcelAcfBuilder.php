<?php

namespace Tbruckmaier\Corcelacf;

use Illuminate\Database\Query\Builder as QueryBuilder;
use Corcel\Model\Builder\PostBuilder as CorcelBuilder;

class CorcelAcfBuilder extends CorcelBuilder
{
    protected $fieldConfig;

    /**
     * Sets the config / attributes of this field directly (is called when the
     * field is defined in php)
     */
    public function setFieldConfig($fieldConfig)
    {
        $this->fieldConfig = $fieldConfig;
        return $this;
    }

    /**
     * Method to make an field instance from an attributes array (for acf fields
     * defined in php). Returns the proper instance depending on the field type.
     * If we have a group/repeater/flexible content field, the children
     * relationship is also manually populated with the according fields
     */
    protected function makeBaseField(array $attributes = [])
    {
        // create a proper instance with these attributes. We set only the
        // interesting attributes, but could theoretically add more
        $field = (new Models\BaseField())->newFromBuilder([
            'post_content' => serialize($attributes),
            'post_excerpt' => $attributes['name'],
        ]);

        // now check if we have sub_fields/layouts, and populate the main fields
        // relation
        if (array_has($attributes, 'sub_fields')) {

            // group and repeater. Sub fields can be taken directly from the
            // config array
            $subFieldConfigs = collect(array_get($attributes, 'sub_fields'));

        } elseif (array_has($attributes, 'layouts')) {
            // flexible content field. Here the sub fields are encapsulated
            // twice, the fc config has an array "layouts", and each layout has
            // multiple "sub_fields". We need to get a flat list of sub fields,
            // and each sub fields needs to have the key "parent_layout" set
            $subFieldConfigs = collect(array_get($attributes, 'layouts'))

                // each layout gets replaced by its sub fields, each having the
                // "parent_layout" key set
                ->map(function ($layout) {
                    return collect($layout['sub_fields'])->map(function ($subField) use ($layout) {
                        $subField['parent_layout'] = $layout['key'];
                        return $subField;
                    });
                })

                // remove one level from the multi-dimensional array to get a
                // flat list of sub fields
                ->flatten(1);

        }

        // manually set the related children
        if (isset($subFieldConfigs)) {
            $field->setRelation(
                'children',
                $subFieldConfigs->map(function ($x) {
                    return $this->makeBaseField($x);
                })
            );
        }

        return $field;
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
