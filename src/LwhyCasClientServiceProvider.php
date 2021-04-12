<?php

namespace Hsk9044\LwhyCasClient;

use Hsk9044\LwhyCasClient\Providers\CasUserProvider;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class LwhyCasClientServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerConfig();
        $this->registerRouter();
        $this->registerPermissions();

/*        Auth::viaRequest('cas-token', function ($request) {
            dd($request);
//            return User::where('token', $request->token)->first();
        });*/

        $this->registerAuth();




    }

    private function registerRouter()
    {
        require __DIR__.'/../routes.php';
    }

    public function registerPermissions(): bool
    {
        app(Gate::class)->before(function (Authorizable $user, string $ability) {
            if (method_exists($user, 'checkCasPermission')) {
                return $user->checkCasPermission($ability) ?: null;
            }
        });

        return true;
    }

    private function registerAuth() {

        Auth::provider('cas_client', function ($app, array $config) {
            // 返回 Illuminate\Contracts\Auth\UserProvider... 实例
            return new CasUserProvider($config);
//            return new RiakUserProvider($app->make('riak.connection'));
        });


/*        Auth::extend('cas', function ($app, $name, array $config) {
            // 返回一个 Illuminate\Contracts\Auth\Guard 实例...

            dd($config);
        });*/
    }

    private function registerConfig() {
        $config = config('lwhy-cas.auth');

        config([
            'auth.guards.' . $config['guard_name'] => $config['guard'],
            'auth.providers.cas_users' => $config['provider']
        ]);

    }
}
