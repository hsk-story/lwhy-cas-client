<?php

namespace Hsk9044\LwhyCasClient;

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
        $this->registerRouter();
        $this->registerPermissions();

        Auth::viaRequest('cas-token', function ($request) {
            dd($request);
//            return User::where('token', $request->token)->first();
        });

        config([
            'auth.guards.cas.driver' => 'cas-token',
        ]);

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
}
