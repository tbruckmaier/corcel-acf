<?php

namespace Corcel\Acf\Models;

use Corcel\Model\Post;
use Illuminate\Support\Collection;

class AcfField extends Post
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
     * When initializing new AcfFields, use the proper class for this type. The
     * requested type can be found in post_content attribute. If the type is not
     * supported, a AcfFieldText instance is returned
     */
    protected function getPostInstance(array $attributes)
    {
        $config = unserialize(data_get($attributes, 'post_content'));

        $type = data_get($config, 'type');

        $baseClassName = \Corcel\Acf\Models\AcfField::class;

        $className = $baseClassName . ucfirst(camel_case($type));

        if (class_exists($className)) {
            return new $className();
        }

        $textClassName = $baseClassName . 'Text';

        return new $textClassName;
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
     * Get this field's actual value, e.g. the entry in $this->data matching
     * $this->localKey
     *
     * @return string
     */
    public function getValueAttribute()
    {
        return $this->data->get($this->localKey);
    }

    /**
     * Set the local key, e.g. which entry from $this->data this field refers to
     */
    public function setLocalKey($value)
    {
        $this->localKey = $value;
        return $this;
    }
}
