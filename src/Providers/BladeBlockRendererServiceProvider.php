<?php

namespace BladeBlock\Providers;

use BladeBlock\Console\BlockMakeCommand;
use Roots\Acorn\ServiceProvider;
use BladeBlock\BladeBlockRenderer;

class BladeBlockRendererServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('BladeBlockRenderer', function () {
            return new BladeBlockRenderer($this->app);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make('BladeBlockRenderer');

        $this->publishes([
            __DIR__ . '/../../config/blade-block.php' => $this->app->configPath('blade-block.php'),
        ], 'config');

        $this->commands([
            BlockMakeCommand::class,
        ]);
    }
}
