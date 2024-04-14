<?php

namespace Durbin\Middleware;

use Psr\Http\Message\ServerRequestInterface;

class BasicAuthMiddleware
{
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        global $config;
        $server = $request->getServerParams();

        if (!isset($server['PHP_AUTH_USER'])) {

            header('WWW-Authenticate: Basic realm="Authenticate to watch Docker Containers');
            header('HTTP/1.0 401 Unauthorized');

            die('You have to authenticate as valid user. Try again after cleaning up cookies');
        } else if ($server['PHP_AUTH_USER'] !== $config['auth']['user'] || $server['PHP_AUTH_PW'] !== $config['auth']['password']) {
            die("<p>Incorrect username or password. Try again after cleaning up cookies.</p>");
        }

        return $next($request);
    }
}