<?php

use Durbin\Middleware\{BasicAuthMiddleware, CheckDockerMiddleware};
use Durbin\Processor\{AttachActions, AttachStatusIndicator};
use Fig\Http\Message\StatusCodeInterface;
use React\Stream\WritableResourceStream;

require __DIR__ . '/../vendor/autoload.php';

$config = load_first_available([
    __DIR__. '/../inc/config.local.php',
    __DIR__. '/../inc/config.php',
]);

$app = new FrameworkX\App(
        FrameworkX\ErrorHandler::class,
        BasicAuthMiddleware::class,
        CheckDockerMiddleware::class,
    );

// @TODO : Add CSRF middleware

$app->get('/', function () {

    $output = shell_exec('docker ps');
    $rows = getColumnsAsArray($output);
    $rows = (new AttachStatusIndicator())->process($rows);
    $rows = (new AttachActions())->process($rows);

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
    $rows = (new AttachActions())->process($rows);

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

$app->get('/logs/{container-id}', function (Psr\Http\Message\ServerRequestInterface $request) {

    $containerId = $request->getAttribute('container-id', 'no-container-id');
    $output = shell_exec('docker logs -n20 '. $containerId);

    return React\Http\Message\Response::html(
        render('layout', [
            'page' => 'n/a',
            'actions' => render('_watch_log_actions', ['containerId' => $containerId]),
            'title' => 'Latest logs from '. $containerId,
            'content' => htmlentities(trim(strval($output))),
        ])
    );
});

$app->get('/logs/watch/{container-id}', function (Psr\Http\Message\ServerRequestInterface $request) {
    $containerId = $request->getAttribute('container-id', 'no-container-id');
    $command = "docker logs -f -t -n20 {$containerId} 2>&1";

    $dest = new React\Stream\ThroughStream();

    $process = new React\ChildProcess\Process($command);
    $process->start();

    $process->on('exit', function($exitCode, $termSignal) {
        exit('Process exited with code ' . $exitCode . PHP_EOL);
    });

    $process->stdout->on('data', function ($message) use ($dest) {
        $dest->write("data: $message\n\n");

        if (connection_status() !== CONNECTION_NORMAL || connection_aborted()) {
            exit();
        }
    });

    $headers = [
        'Content-Type' => 'text/event-stream',
        'Cache-Control'  => 'no-cache',
    ];

    return new React\Http\Message\Response(StatusCodeInterface::STATUS_OK, $headers, $dest);
});

$app->get('/error/{error-type}', function (Psr\Http\Message\ServerRequestInterface $request) {

    $message = match ($request->getAttribute('error-type')) {
        'no-docker' => 'Seems like Docker is not running... 🧐',
        default => 'Something went wrong!',
    };

    return React\Http\Message\Response::html(
        render('layout', [
            'page' => 'n/a',
            'title' => 'Something unexpected happened!',
            'content' => "<b style='color: lightcoral'>ERROR: {$message}</b>",
        ])
    );
});

$app->run();