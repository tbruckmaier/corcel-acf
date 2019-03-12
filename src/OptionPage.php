<?php

namespace Tbruckmaier\Corcelacf;

use Corcel\Model\Option;
use Tbruckmaier\Corcelacf\Models\BaseFieldGroup;

class OptionPage extends BaseFieldGroup
{
    /**
     * The plain values from the db, [meta_key => meta_value]
     */
    protected $plain;

    /**
     * The acf fields, keyed by option name
     */
    protected $options;

    /**
     * Load the option data from the database & instantiate all needed BaseField
     * instances
     */
    public function loadOptions(string $prefix = 'options')
    {
        // load all plain values from the database, which match the prefix
        $this->plain = Option
            ::where('option_name', 'like', $prefix . '_%')
            ->orWhere('option_name', 'like', '_' . $prefix . '_%')

            // key them by option_name
            ->pluck('option_value', 'option_name')

            // remove the prefix from the option_name
            ->keyBy(function ($field, $key) use ($prefix) {
                return substr($key, strlen($prefix) + 1);
            });

        $this->options = $this->fields

            // key the fields by the option name (prefixed)
            ->keyBy(function ($field) {
                return substr($this->plain->search($field->post_name), 1);
            })
            // all "invalid" fields end up with the index 0, remove them (fields
            // like "tab" for instance)
            ->forget(0)

            // pass all options data to the fields and set their local key
            ->each(function ($field, $key) {
                $field->setLocalKey($key)->setData($this->plain);
            });

        return $this;
    }

    public function getOptionField($key)
    {
        if (empty($this->options)) {
            return null;
        }

        return $this->options->get($key);
    }

    public function getOption($key)
    {
        $field = $this->getOptionField($key);
        return ($field ? $field->value : null);
    }

    public function scopeByTitle($query, $title)
    {
        return $query->where('post_title', $title);
    }
}
