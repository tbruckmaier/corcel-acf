<?php

namespace Tbruckmaier\Corcelacf\Models;

use Corcel\Model\Option;
use Tbruckmaier\Corcelacf\Builder\OptionGroupBuilder;

/**
 * A option field group
 */
class OptionFieldGroup extends BaseFieldGroup
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
     * Whether the options have been loaded from the db already
     */
    protected $loaded = false;

    /**
     * Prefix in wp_options
     */
    protected $prefix = 'options';

    /**
     * Set the prefix
     */
    public function setPrefix(string $prefix)
    {
        $this->prefix = $prefix;
        return $this;
    }

    /**
     * Load the option data from the database & instantiate all needed BaseField
     * instances. Optionally set the prefix beforehand
     */
    public function loadOptions(string $prefix = null)
    {
        if ($prefix) {
            $this->setPrefix($prefix);
        }

        // load all plain values from the database, which match the prefix
        $this->plain = Option::where('option_name', 'like', $this->prefix . '_%')
            ->orWhere('option_name', 'like', '_' . $this->prefix . '_%')

            // key them by option_name
            ->pluck('option_value', 'option_name')

            // remove the prefix from the option_name
            ->keyBy(function ($field, $key) {
                return substr($key, strlen($this->prefix) + 1);
            });

        // convert the plain values into BaseField instances
        $this->options = $this->children

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

        $this->loaded = true;

        return $this;
    }

    /**
     * Return an option (e.g. the according BaseField)
     */
    public function getOptionField($key)
    {
        if (!$this->loaded) {
            $this->loadOptions();
        }

        if (empty($this->options)) {
            return null;
        }

        return $this->options->get($key);
    }

    /**
     * Return an option (e.g. the parsed value of the according BaseField)
     */
    public function getOption($key)
    {
        $field = $this->getOptionField($key);
        return ($field ? $field->value : null);
    }

    /**
     * Helper method to find option pages easily
     */
    public function scopeByTitle($query, $title)
    {
        return $query->where('post_title', $title);
    }

    /**
     * @param \Illuminate\Database\Query\Builder $query
     * @return OptionGroupBuilder
     */
    public function newEloquentBuilder($query)
    {
        return new OptionGroupBuilder($query);
    }
}
