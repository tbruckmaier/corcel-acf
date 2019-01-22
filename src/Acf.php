<?php

namespace Tbruckmaier\Corcelacf;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * A helper class, so acf relations can be accessed in an easy way
 */
class Acf
{
    /**
     * The related post instance
     *
     * @var Model
     */
    protected $post;

    public function __construct(Model $post)
    {
        $this->post = $post;
    }

    /**
     * All method calls to this class are forwarded to the matching acf relation
     * of the post model. When accessing an acf relation as a method
     * (`$post->acf->field1()`), we return an instance of the acf field (e.g.
     * the value of the relation)
     *
     * @param string $method
     *
     * @return Models\BaseField
     */
    public function __call($method, $parameters)
    {
        $relationName = 'acf_' . $method;
        return $this->post->$relationName;
    }

    /**
     * When accessing an acf relation as an attribute (`$post->acf->field1`), we
     * return the AcfField's value (e.g. the parsed response from
     * getValueAttribute())
     *
     * @author tbruckmaier
     *
     * @param string $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        $relation = $this->$key();
        if (!$relation) {
            return null;
        }
        return $this->$key()->value;
    }
}
