<?php
namespace App\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

class EncryptCookies extends Middleware
{
    /**
     * Disable encryption for specific routes/cookies if needed
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function disable($request)
    {
        return parent::disable($request);
    }

    /**
     * The names of the cookies that should not be encrypted.
     *
     * @var array
     */
    protected $except = [
        //
    ];
}
