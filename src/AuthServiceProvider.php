<?php

namespace JPNut\ExtendedAuth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use JPNut\ExtendedAuth\Passwords\PasswordBrokerManager;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
    	$this->publishes([
	        __DIR__.'/../config/extended-auth.php' => config_path('extended-auth.php')
	    ], 'config');

        if (! class_exists('CreateTokensTable')) {
            $timestamp = date('Y_m_d_His', time());

            $this->publishes([
                __DIR__.'/../migrations/create_tokens_table.stub' => $this->app->databasePath()."/migrations/{$timestamp}_create_tokens_table.php",
            ], 'migrations');
        }

        $this->loadMigrationsFrom(__DIR__.'/../migrations');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/extended-auth.php', 'extended-auth');

        $this->registerPasswordBrokerManager();

        Auth::provider(config('extended-auth.provider'), function ($app, array $config) {
            return new EloquentUserProvider($app['hash'], $config['model']);
        });

        Auth::extend(config('extended-auth.guard'), function ($app, $name, array $config) {
            $guard =  new SessionGuard(
                $name,
                Auth::createUserProvider($config['provider'] ?? null),
                $app->make('session.store')
            );

            // When using the remember me functionality of the authentication services we
            // will need to be set the encryption instance of the guard, which allows
            // secure, encrypted cookie values to get generated for those cookies.
            if (method_exists($guard, 'setCookieJar')) {
                $guard->setCookieJar($this->app['cookie']);
            }
            if (method_exists($guard, 'setDispatcher')) {
                $guard->setDispatcher($this->app['events']);
            }
            if (method_exists($guard, 'setRequest')) {
                $guard->setRequest($this->app->refresh('request', $guard, 'setRequest'));
            }

            return $guard;
        });
    }

    protected function registerPasswordBrokerManager()
    {
        $this->app->singleton('auth.password', function ($app) {
            return new PasswordBrokerManager($app);
        });
    }

    public function provides()
    {
        return ['auth.password'];
    }
}
