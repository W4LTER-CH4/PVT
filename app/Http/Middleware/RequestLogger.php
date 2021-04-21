<?php

namespace App\Http\Middleware;

use Closure;

class RequestLogger
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
      $start = microtime(true);
      $response = $next($request);
      $end = microtime(true);

      $duration = $end - $start;
      $url = $request->fullUrl();
      $method = $request->getMethod();
      $ip = $request->getClientIp();
      $status = $response->getStatusCode();

      $log = "{$ip}: [{$status}] {$method}@{$url} - {$duration}ms";
      if($method != 'OPTIONS') {
        \Log::channel('request')->info($log);
      }
      return $response;
  }
}
