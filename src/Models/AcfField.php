<?php

namespace Corcel\Acf\Models;

use Corcel\Model\Post;

class AcfField extends Post
{
    /**
     * @var string
     */
    protected $postType = 'acf-field';

    protected $postContent;

    protected $localKey;

    // do not load meta fields for acf fields (it is empty anyway)
    protected $with = [];

    /**
     * When initializing new AcfFields, use the proper class for this type. The
     * requested type can be found in post_content attribute
     */
    protected function getPostInstance(array $attributes)
    {
        $config = unserialize(data_get($attributes, 'post_content'));

        $type = data_get($config, 'type');

        // FIXME default to text

        $className = $className = \Corcel\Acf\Models\AcfField::class . ucfirst(camel_case($type));

        return new $className();
    }

    public function setPostContent($post)
    {
        $this->postContent = $post;
        return $this;
    }

    /**
     * Get this fields acf config. It is stored serialized in the post_content
     * column
     */
    public function getConfigAttribute()
    {
        return unserialize($this->post_content);
    }

    /**
     * Get this fields type
     */
    public function getTypeAttribute()
    {
        $fieldData = $this->config;
        return isset($fieldData['type']) ? $fieldData['type'] : 'text';
    }

    public function getPostContentValueAttribute()
    {
        return $this->postContent->getMeta($this->localKey);
    }

    public function setLocalKey($value)
    {
        $this->localKey = $value;
        return $this;
    }

    /**
     * Children of an acf field are acf fields themselves. Override parent
     * method to get the correct class type
     */
    public function children()
    {
        return $this->hasMany(AcfField::class, 'post_parent');
    }
}
