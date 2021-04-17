<?php

namespace App\Http\Middleware;

use Closure;

class CheckSession
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
        $session = to_object(session()->all());
        if(!empty($session->authenticated)){
            $cart = curlSendRequest("GET", env("TESORERIA_HOSTNAME") . "/solicitudes-info/".session()->get("user")->id);
            // dd($cart);
            $firma = curlSendRequest("GET", env("TESORERIA_HOSTNAME") . "/solicitudes-info/".session()->get("user")->id."/firma");
            $cartCount = 0;
            $firmaCount = 0;
            if(isset($cart->tramites)){
                foreach($cart->tramites as $tramite){
                    $cartCount += count($tramite->solicitudes);
                }
            }
            if(isset($firma->tramites)){
                foreach($firma->tramites as $tramite){
                    $firmaCount += count($tramite->solicitudes);
                }
            }
            session()->put("tramites", $cartCount);
            session()->put("tramitesFirma", $firmaCount);
            if(!empty($session->authenticated->until) && $session->authenticated->until <= date()){
                return self::redirectLogin($request, $next);
            }
            return $request->getPathInfo() == (getenv("APP_PREFIX") ?? "").'/login' ? redirect()->route('dashboard') : $next($request);
        }
        return self::redirectLogin($request, $next);
    }
    
    protected function redirectLogin($request, Closure $next){
        $session_whitelist = config("layout.session_whitelist");
        $path = explode("/", $request->getPathInfo());
        $path = array_filter($path, function($var){
            return !empty($var);
        });
        $path = implode("/", $path);
        // dd($session_whitelist, $path);
        // if(getenv("APP_PREFIX"))
        $pass = array_map(function($a) use ($path){
            $whitePath = ((getenv("APP_PREFIX") ? explode("/", getenv("APP_PREFIX"))[1]."/" : "").$a);
            preg_match("/^".str_replace("/", "\/", $whitePath)."$/", $path, $matches);
            if(!empty($matches))
                return true;
        }, $session_whitelist);
        $pass = array_filter($pass);

        if(empty($pass))
            return redirect()->route('login');
        else
            return $next($request);
    }
}
