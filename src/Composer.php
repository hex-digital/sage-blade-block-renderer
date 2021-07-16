<?php

namespace BladeBlock;

use Roots\Acorn\Application;
use Illuminate\Support\Str;

abstract class Composer
{
    /**
     * The application instance.
     *
     * @var \Roots\Acorn\Application
     */
    protected $app;

    /**
     * Create a new Composer instance.
     *
     * @param  \Roots\Acorn\Application $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Register the render callback with the block editor.
     *
     * @param  callable $callback
     * @return void
     */
    protected function register($callback = null)
    {
        add_filter('init', function () use ($callback) {
            if ($callback) {
                $callback();
            }
        }, 20);
    }
}
