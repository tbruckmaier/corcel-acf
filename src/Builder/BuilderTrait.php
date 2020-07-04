<?php

namespace Tbruckmaier\Corcelacf\Builder;

use Tbruckmaier\Corcelacf\Models\BaseField;
use Illuminate\Support\Arr;

trait BuilderTrait
{
    /**
     * Method to make an field instance from an attributes array (for acf fields
     * defined in php). Returns the proper instance depending on the field type.
     * If we have a group/repeater/flexible content field, the children
     * relationship is also manually populated with the according fields
     */
    protected function makeBaseField(array $attributes = [])
    {
        // create a proper instance with these attributes. We set only the
        // interesting attributes, but could theoretically add more.
        $field = (new BaseField())->newFromBuilder([
            // TODO we serialize the array here to mimic the database, but
            // BaseField calls unserialize() anyway. Can we somehow spare these
            // function calls?
            'post_content' => serialize($attributes),
            'post_excerpt' => $attributes['name'],
            'post_name' => $attributes['key'],
            'post_status' => 'publish',
        ]);

        // now check if we have sub_fields/layouts, and populate the main fields
        // relation
        if (Arr::has($attributes, 'sub_fields')) {

            // group and repeater. Sub fields can be taken directly from the
            // config array
            $subFieldConfigs = collect(Arr::get($attributes, 'sub_fields'));
        } elseif (Arr::has($attributes, 'layouts')) {
            // flexible content field. Here the sub fields are encapsulated
            // twice, the fc config has an array "layouts", and each layout has
            // multiple "sub_fields". We need to get a flat list of sub fields,
            // and each sub fields needs to have the key "parent_layout" set
            $subFieldConfigs = collect(Arr::get($attributes, 'layouts'))

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
}
