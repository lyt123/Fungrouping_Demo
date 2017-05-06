<?php

namespace App\Http\Middleware;


class MustLogin
{
    public function handle($request, \Closure $next)
    {
        if(!session()->get('user.id'))
            return fail(trans('tip.not_login'));
        return $next($request);
    }
}