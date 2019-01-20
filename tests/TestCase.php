<?php

namespace Tbruckmaier\Corcelacf\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Corcel\Model\Post;
use Corcel\Model\Meta\PostMeta;
use Tbruckmaier\Corcelacf\Models\BaseField;
use Tbruckmaier\Corcelacf\Models\BaseFieldGroup;
use Corcel\Model\Option;

class TestCase extends OrchestraTestCase
{
    protected function setUp()
    {
        parent::setUp();

        // FIXME we load migrations and factories from corcel, but is that
        // really better than copying it over here?
        $this->pathPrefix = '/../../../jgrossi/corcel';

        // --realpath can be used once we upgrade to 5.6
        $this->loadMigrationsFrom([
            '--database' => 'foo',
            '--path' => $this->pathPrefix.'/tests/database/migrations',
        ]);

        $this->loadMigrationsFrom([
            '--database' => 'wp',
            '--path' => $this->pathPrefix.'/tests/database/migrations',
        ]);

        $this->withFactories(base_path() . '/' .$this->pathPrefix.'/tests/database/factories');
        $this->withFactories(__DIR__ . '/database/factories');
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $this->configureDatabaseConfig($app);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    private function configureDatabaseConfig($app)
    {
        $app['config']->set('database.connections.wp', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => 'wp_',
        ]);

        $app['config']->set('database.connections.foo', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => 'foo_',
        ]);

        $app['config']->set('database.default', 'wp');
    }

    protected function addData(BaseField $acfField, $key, $value)
    {
        return $this->setData($acfField, [$key => $value])->setLocalKey($key);
    }

    protected function setData(BaseField $acfField, $data)
    {
        return $acfField->setData(collect($data));
    }

    /**
     * Create a acf field for a post with a field name and a value
     */
    protected function createAcfField(Post $post, $fieldName, $value, $states = [], $override = [], $internal = null)
    {
        if (!$internal) {
            $internal = 'field_' . str_random(13);
        }

        $postmeta1 = factory(PostMeta::class)->create([
            'post_id' => $post->ID,
            'meta_key' => $fieldName,
            'meta_value' => $value,
        ]);
        $postmeta2 = factory(PostMeta::class)->create([
            'post_id' => $post->ID,
            'meta_key' => '_' . $fieldName,
            'meta_value' => $internal,
        ]);

        $override['post_name'] = $internal;

        $BaseField = factory(BaseField::class)->states($states)->create($override);
        return $BaseField;
    }

    /**
     * Create an acf field for use in an option page
     */
    protected function createOptionAcfField(BaseFieldGroup $fieldGroup, $prefix, $fieldName, $value, $states = [], $override = [], $internal = null)
    {
        if (!$internal) {
            $internal = 'field_' . str_random(13);
        }

        factory(Option::class)->create([
            'option_name' => $prefix . $fieldName,
            'option_value' => $value,
        ]);
        factory(Option::class)->create([
            'option_name' => '_' . $prefix . $fieldName,
            'option_value' => $internal,
        ]);

        $override['post_name'] = $internal;
        $override['post_parent'] = $fieldGroup->ID;
        $override['post_excerpt'] = $fieldName;

        $BaseField = factory(BaseField::class)->states($states)->create($override);
        return $BaseField;
    }
}
