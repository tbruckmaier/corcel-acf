<?php

namespace Tbruckmaier\Corcelacf;

use Auth;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use Corcel\Model\Post;
use Corcel\Model\Page;
use Corcel\Model\CustomLink;
use Corcel\Model\Taxonomy;

/**
 * Class CorcelServiceProvider
 *
 * @package Corcel\Providers\Laravel
 * @author Mickael Burguet <www.rundef.com>
 * @author Junior Grossi <juniorgro@gmail.com>
 */
class ServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config-custom.php' => config_path('corcel-acf.php'),
        ]);
    }

    /**
     * @return void
     */
    public function register()
    {
        // mergeConfig() only merges the first level, so implement it with
        // array_replace_recursive()

        $key = 'corcel-acf';
        $path = __DIR__ . '/config.php';

        $config = $this->app['config']->get($key, []);

        $this->app['config']->set(
            $key,
            array_replace_recursive(require $path, $config)
        );
    }
}
