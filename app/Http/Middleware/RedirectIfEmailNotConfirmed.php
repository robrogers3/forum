<?php

namespace App\Http\Middleware;

use Closure;
use App\Mail\ConfirmEmailAddress;
use Illuminate\Support\Facades\Mail;

class RedirectIfEmailNotConfirmed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (!$request->user()->confirmed) {
            Mail::to(request()->user())->send(new ConfirmEmailAddress(auth()->user()));

            return redirect('/threads')->with('flash', 'You must confirm your email address. Please check your email');
        }

        return $next($request);
    }
}
