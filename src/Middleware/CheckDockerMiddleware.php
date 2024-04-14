<?php

namespace Durbin\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\ServerRequest;

class CheckDockerMiddleware
{
    public function __invoke(ServerRequestInterface $request, callable $next)
    {
        global $config;

        if (!$this->isDockerRunning()) {
            //throw new \LogicException("Sorry, Docker is not in running state.");
            $request = new ServerRequest('GET', $config['base_url'] . '/error/no-docker');
        }

        return $next($request);
    }

    private function isDockerRunning(): bool
    {
        ob_start();
        system('curl -s --unix-socket /var/run/docker.sock http/_ping 2>&1 >/dev/null', $retval);
        ob_end_clean();

        return $retval === 0;
    }

}