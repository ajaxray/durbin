<?php

use Durbin\Middleware\BasicAuthMiddleware;
use Durbin\Processor\AttachStartActions;
use Durbin\Processor\AttachStatusIndicator;

require __DIR__ . '/../vendor/autoload.php';

$config = include(__DIR__. '/../inc/config.php');

$app = new FrameworkX\App(
        BasicAuthMiddleware::class
    );

// @TODO : Is docker running on middleware
// @TODO : Add CSRF middleware

$app->get('/', function () {

    $output = shell_exec('docker ps');
    $rows = getColumnsAsArray($output);
    $rows = (new AttachStatusIndicator())->process($rows);
    $rows = (new AttachStartActions())->process($rows);

    return React\Http\Message\Response::html(
        render('layout', [
            'page' => 'running',
            'title' => 'Running Containers',
            'content' => rowsToTable($rows)
        ])
    );
});

$app->get('/all',  function () {

    $output = shell_exec('docker ps -a');
    $rows = getColumnsAsArray($output);
    $rows = (new AttachStatusIndicator())->process($rows);
    $rows = (new AttachStartActions())->process($rows);

    return React\Http\Message\Response::html(
        render('layout', [
            'page' => 'all',
            'title' => 'All Containers (including stopped)',
            'content' => rowsToTable($rows)
        ])
    );
});

$app->get('/stats', function () {

    $output = shell_exec('docker stats --no-stream');
    $rows = getColumnsAsArray($output);
    $rows = (new AttachStatusIndicator())->process($rows);

    return React\Http\Message\Response::html(
        render('layout', [
            'page' => 'stats',
            'title' => 'Container Status',
            'content' => rowsToTable($rows)
        ])
    );
});


$app->post('/action', function (Psr\Http\Message\ServerRequestInterface $request) {
    $data = $request->getParsedBody();
    $command = match ($data['action']) {
        'start' => "docker start {$data['container_id']}",
        'stop' => "docker stop {$data['container_id']}",
    };
    $output = shell_exec($command);

    return React\Http\Message\Response::html(
        render('layout', [
            'page' => 'n/a',
            'title' => "Command executed to {$data['action']} {$data['container_id']}",
            'content' => "<div class=\"cmd-output\">&gt; {$command}\n{$output}</div>"
        ])
    );
});

$app->get('/logs', function () {

    $handle = popen('docker logs -f a11f2ba48daa 2>&1', 'r');

    $source = new React\Stream\ReadableResourceStream($handle, readChunkSize: -1);
    $dest = new React\Stream\ThroughStream();

    $source->on('data', function ($message) use ($dest) {
        $dest->write("data: $message\n\n");
    });

    return new React\Http\Message\Response(
        React\Http\Message\Response::STATUS_OK,
        [
            'Content-Type' => 'text/event-stream',
            'Cache-Control'  => 'no-store',
        ],
        $dest
    );
});

$app->run();