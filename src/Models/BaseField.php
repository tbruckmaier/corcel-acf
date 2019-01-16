<?php

namespace Corcel\Acf\Models;

use Corcel\Model\Post;
use Illuminate\Support\Collection;

/**
 * This class should actually be abstract (e.g. not instantiated), but some
 * laravel-/corcel-internal methods use it
 */
class BaseField extends Post
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
     * @return string|null class name
     */
    protected function mapTypeToClass(string $type)
    {
        // TODO make mapping configurable
        switch ($type) {
            case 'text':
            case 'textarea':
            case 'number':
            case 'email':
            case 'url':
            case 'link':
            case 'password':
            case 'wysiwyg':
            case 'editor':
            case 'oembed':
            case 'embed':
            case 'color_picker':
            case 'select':
            case 'checkbox':
            case 'radio':
                return Text::class;
                break;
            case 'image':
            case 'img':
                return Image::class;
                break;
            case 'file':
                return Generic::class; // TODO
            case 'gallery':
                return Generic::class; // TODO
            case 'true_false':
            case 'boolean':
                return Generic::class; // TODO
            case 'post_object':
            case 'post':
            case 'relationship':
                return Generic::class; // TODO
            case 'page_link':
                return Generic::class; // TODO
            case 'taxonomy':
            case 'term':
                return Generic::class; // TODO
            case 'user':
                return Generic::class; // TODO
            case 'date_picker':
            case 'date_time_picker':
            case 'time_picker':
                return Generic::class; // TODO
            case 'repeater':
                return Repeater::class;
            case 'flexible_content':
                return FlexibleContent::class;
            case 'clone':
                return Generic::class; // TODO
        }

        return null;
    }

    /**
     * When initializing new AcfFields, use the proper class for this type. The
     * requested type can be found in post_content attribute. If the type is not
     * supported, a BaseField instance is returned
     */
    protected function getPostInstance(array $attributes)
    {
        $config = unserialize(data_get($attributes, 'post_content'));
        $type = data_get($config, 'type');

        $className = $this->mapTypeToClass($type);

        if (class_exists($className)) {
            return new $className();
        }

        return new Generic();
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
        return $this->data->get($this->localKey);
    }

    /**
     * Set the local key, e.g. which entry from $this->data this field refers to
     */
    public function setLocalKey(string $localKey)
    {
        $this->localKey = $localKey;
        return $this;
    }
}
