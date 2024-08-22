<?php

namespace Tbruckmaier\Corcelacf\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\RelationNotFoundException;
use InvalidArgumentException;
use Tbruckmaier\Corcelacf\Support\RepeaterLayout;

class Repeater extends BaseField
{
    protected $with = ['children'];

    protected $_with = [];

    public function getValueAttribute()
    {
        $count = $this->internal_value;

        $layouts = collect();

        for ($i = 0; $i < $count; $i++) {
            $row = collect();

            foreach ($this->children as $layout) {
                $field = clone $layout; // not replicate(), as it strips the ID field and we need it for nested repeaters
                $internalName = sprintf('%s_%d_%s', $this->localKey, $i, $layout->post_excerpt);

                $field->setData($this->data)->setLocalKey($internalName);

                $row->put($layout->post_excerpt, $field);
            }

            $layouts->push(new RepeaterLayout($row));
        }

        // eager load all relations. Makes sense for fields like "Image" which
        // has a hasOne relation to "Attachment". If "foo_image.attachment" is
        // requested, load the relation "attachment" on the foo_image field of
        // each layout
        foreach ($this->_with as $fullRelationName) {
            if (!str_contains($fullRelationName, '.')) {
                throw new InvalidArgumentException(sprintf(
                    "Eager-loaded ACF repeater relations must contain '.'. Available parents:\n%s",
                    $layouts->first()->getData()->map(fn ($layout, $key) => sprintf('- %s: %s', $key, get_class($layout)))->join("\n"),
                ));
            }

            list($layoutKey, $relationName) = explode('.', $fullRelationName, 2);

            // get a plain collection of the relationship's parent field (e.g. a
            // list of "Image::class")
            $fields = $layouts->map(function (RepeaterLayout $layout) use ($layoutKey) : BaseField {
                $field = $layout->$layoutKey();

                // "foo_image" does not exist in thsi repeater
                if (!$field) {
                    throw RelationNotFoundException::make($this->getModel(), $layoutKey);
                }

                return $field;
            });

            // turn it into a Eloquent Collection and use its load() function
            Collection::make($fields)->load($relationName);
        }

        return $layouts;
    }

    /**
     * Eager load relations. Makes sense for repeaters with images, to preload
     * their attachment relationship
     */
    public function load($relations)
    {
        $this->_with = array_merge($this->_with, is_string($relations) ? func_get_args() : $relations);
        return $this;
    }
}
