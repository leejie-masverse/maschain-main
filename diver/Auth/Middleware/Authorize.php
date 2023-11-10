<?php

namespace Diver\Auth\Middleware;

use Closure;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Middleware\Authorize as BaseAuthorize;

class Authorize extends BaseAuthorize
{
    use HandlesAuthorization;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string $abilities
     * @param mixed ...$models
     *
     * @return mixed
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function handle($request, Closure $next, $abilities, ...$models)
    {
        $this->auth->authenticate();

	    if (!is_array($abilities)) {
            $abilities = explode('|', $abilities);
        }

        $isAuthorized = collect($abilities)->reduce(function($isAuthorized, $ability) use ($request, $models) {
            return $isAuthorized ?: $this->gate->allows($ability, $this->getGateArguments($request, $models));
        }, false);

        if (!$isAuthorized) {
            $this->deny();
        }

        return $next($request);
    }
}
