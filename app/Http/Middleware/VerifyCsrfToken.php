<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
<<<<<<< HEAD
        'user/*',
        'menu/*',
        'order/*',
        'cart/'
=======
        //
       
       'user/*'
>>>>>>> d871a09ebe74f6ec079e8d9d66ff7fe23839c927
    ];
}
