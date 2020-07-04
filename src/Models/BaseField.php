<?php

namespace Tbruckmaier\Corcelacf\Models;

use Corcel\Model\Post as CorcelPost;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Tbruckmaier\Corcelacf\Builder\FieldBuilder;
use Corcel\Model\Option;
use Illuminate\Support\Arr;

/**
 * This class should actually be abstract (e.g. not instantiated), but some
 * laravel-/corcel-internal methods use it. Each derived class must implement
 * either getValueAttribute() or a relation value() (these should return the
 * parsed content)
 */
class BaseField extends CorcelPost
{
    /**
     * @var string
     */
    protected $postType = 'acf-field';

    /**
     * Holds the complete post's meta data
     *
     * @var Collection
     */
    protected $data;

    /**
     * The key of this field, e.g. which field in $data this field refers to
     *
     * @var string
     */
    protected $localKey;

    /**
     * Disable meta relation autoload, as acf fields do not have meta data
     */
    protected $with = [];

    /**
     * Which field type maps to which class?
     *
     * @param string $type Acf field type
     *
     * @return string class name
     */
    protected function mapTypeToClass(string $type)
    {
        return config('corcel-acf.classMapping.' . $type, Generic::class);
    }

    /**
     * Get the timezone_string from wordpress. Used for DateTime, so the carbon
     * instance has the correct timezone set (and the date is correct).
     */
    protected function getTimezoneString()
    {
        $configKey = 'corcel-acf.timezone_string';
        $timezoneString = config($configKey);

        if (empty($timezoneString)) {
            config([$configKey => Option::get('timezone_string')]);
        }

        return config($configKey);
    }

    /**
     * When initializing new AcfFields, use the proper class for this type. The
     * requested type can be found in post_content attribute. If the type is not
     * supported, a Generic instance is returned
     */
    protected function getPostInstance(array $attributes)
    {
        $config = unserialize(data_get($attributes, 'post_content'));
        $type = data_get($config, 'type');

        $className = $this->mapTypeToClass($type);

        return new $className();
    }

    public function getDataAttribute()
    {
        return $this->data;
    }

    /**
     * Set the post's meta data
     */
    public function setData(Collection $data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Get this field's acf config. It is stored serialized in the post_content
     * column
     *
     * @return array
     */
    public function getConfigAttribute()
    {
        return unserialize($this->post_content);
    }

    /**
     * Get this field's type from the config
     *
     * @return string
     */
    public function getTypeAttribute()
    {
        $fieldData = $this->config;
        return isset($fieldData['type']) ? $fieldData['type'] : 'text';
    }

    /**
     * Get this field's internal value, e.g. the entry in $this->data matching
     * $this->localKey. For text fields, this is the text, for image fields the
     * attachment id etc. Each field should implement a getValueAttribute()
     * method or value() relation, which returns the parsed value (e.g. the
     * image object for images)
     *
     * @return string
     */
    public function getInternalValueAttribute()
    {
        return $this->data->get($this->localKey, Arr::get($this->config, 'default_value'));
    }

    /**
     * Set the local key, e.g. which entry from $this->data this field refers to
     */
    public function setLocalKey(string $localKey)
    {
        $this->localKey = $localKey;
        return $this;
    }

    /**
     * The related field group
     */
    public function fieldGroup()
    {
        return $this->belongsTo(BaseFieldGroup::class, 'post_parent');
    }

    /**
     * Filter by active field groups
     */
    public function scopeActive(Builder $query)
    {
        return $query->whereHas('fieldGroup', function ($q) {
            $q->active();
        });
    }

    /**
     * Overwrite the original corcel relation so the class mapping works.
     */
    public function children()
    {
        return $this->hasMany(BaseField::class, 'post_parent');
    }

    /**
     * Find all instances of the related class with the given ids, return them
     * in the same order as the id array
     */
    public function getSortedRelation(string $relatedClass, array $ids, string $relatedKey = 'ID')
    {
        // find all posts with the given ids and sort them in the right order,
        // see https://stackoverflow.com/questions/40731863/sort-
        // collection-by-custom-order-in-eloquent
        return (new $relatedClass)->whereIn($relatedKey, $ids)->get()
            ->sortBy(function ($model) use ($ids) {
                return array_search($model->getKey(), $ids);
            })
            ->values();
    }

    /**
     * @see Tbruckmaier\Corcelacf\AcfTrait::hasAcf()
     * @param \Illuminate\Database\Query\Builder $query
     * @return FieldBuilder
     */
    public function newEloquentBuilder($query)
    {
        return new FieldBuilder($query);
    }
}
