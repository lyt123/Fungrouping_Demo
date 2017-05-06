<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'Home/*',//暂时将csrf过滤给去掉，用于postman测试
        'home/*',//暂时将csrf过滤给去掉，用于postman测试
        'Fungrouping/*',//暂时将csrf过滤给去掉，用于postman测试
    ];
}
