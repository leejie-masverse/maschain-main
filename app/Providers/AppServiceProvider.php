<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Src\Auth\Ability;
use Src\Auth\Role;
use Illuminate\Support\ServiceProvider;

use Dingo\Api\Exception\Handler as DingoExceptionHandler;
use Dingo\Api\Exception\ValidationHttpException as DingoValidationHttpException;
use Dingo\Api\Http\Response\Factory as DingoResponseFactory;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Silber\Bouncer\BouncerFacade as Bouncer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
//        if (env('APP_ENV') !== 'local') {
//            URL::forceSchema('https');
//        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->setupBouncer();
        $this->setupDingo();
    }

    /**
     * Setup bouncer
     */
    protected function setupBouncer()
    {
        Bouncer::useRoleModel(Role::class);
        Bouncer::useAbilityModel(Ability::class);
    }

    /**
     * Setup dingo
     */
    protected function setupDingo()
    {
        app(DingoExceptionHandler::class)->register(function (AuthenticationException $exception) {
            return app(DingoResponseFactory::class)->errorUnauthorized();
        });

        app(DingoExceptionHandler::class)->register(function (AuthorizationException $exception) {
            return app(DingoResponseFactory::class)->errorForbidden();
        });

        app(DingoExceptionHandler::class)->register(function (ModelNotFoundException $exception) {
            return app(DingoResponseFactory::class)->errorNotFound($exception->getMessage());
        });

        app(DingoExceptionHandler::class)->register(function (ValidationException $exception) {
            throw new DingoValidationHttpException($exception->validator->errors());
        });
    }
}
