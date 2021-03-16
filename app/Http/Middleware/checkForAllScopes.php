<?php

namespace App\Http\Middleware;

use App\Http\Traits\ApiResponseTrait;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpFoundation\Response;

class checkForAllScopes
{
    use ApiResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     * @throws AuthenticationException
     */
    public function handle($request, $next, ...$scopes)
    {
        if (!$request->user() || !$request->user()->token()) {
            throw new AuthenticationException;
        }

        foreach ($scopes as $scope) {
            if ($request->user()->tokenCan($scope)) {
                return $next($request);
            }
        }

        return $this->apiResponse(null, 'you are not allowed (Forbidden)', Response::HTTP_FORBIDDEN);

    }
}
