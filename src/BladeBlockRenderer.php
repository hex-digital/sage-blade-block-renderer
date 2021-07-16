<?php

namespace BladeBlock;

use ReflectionClass;
use Illuminate\Support\Str;
use BladeBlock\Composer;
use Roots\Acorn\Application;
use Symfony\Component\Finder\Finder;
use Illuminate\Support\Facades\File;

class BladeBlockRenderer
{
   /**
     * The application instance.
     *
     * @var \Roots\Acorn\Application
     */
    protected $app;

    /**
     * The registered paths.
     *
     * @var array
     */
    protected $paths = [];

    /**
     * The registered composers.
     *
     * @var array
     */
    protected $composers = [];

    /**
     * The composer classes.
     *
     * @var array
     */
    protected $classes = [
        'BladeBlocks',
    ];

    /**
     * Create a new Composer instance.
     *
     * @param  \Roots\Acorn\Application $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;

        $this->registerPath($this->app->path());
    }

    /**
     * Register the default theme paths with Blade Block Renderer.
     *
     * @param  string $path
     * @param  string $namespace
     * @return array
     */
    public function registerPath($path, $namespace = null)
    {
        $paths = collect(File::directories($path))->filter(function ($item) {
            return Str::contains($item, $this->classes);
        });

        if ($paths->isEmpty()) {
            return;
        }

        if (empty($namespace)) {
            $namespace = $this->app->getNamespace();
        }

        foreach ((new Finder())->in($paths->toArray())->files() as $file) {
            $composer = $namespace . str_replace(
                ['/', '.php'],
                ['\\', ''],
                Str::after(
                    $file->getPathname(),
                    Str::beforeLast($file->getPath(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR
                )
            );

            if (
                ! is_subclass_of($composer, Composer::class) ||
                (new ReflectionClass($composer))->isAbstract()
            ) {
                continue;
            }

            $this->composers[$namespace][] = (new $composer($this->app))->compose();
            $this->paths[dirname($file->getPath())][] = $composer;
        }

        return $this->paths;
    }

    /**
     * Retrieve the registered composers.
     *
     * @return array
     */
    public function getComposers()
    {
        return $this->composers;
    }

    /**
     * Retrieve the registered paths.
     *
     * @return array
     */
    public function getPaths()
    {
        return array_unique($this->paths);
    }
}
