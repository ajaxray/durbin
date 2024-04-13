<?php

require __DIR__ . '/../vendor/autoload.php';

$config = include(__DIR__. '/../inc/config.php');

$app = new FrameworkX\App();

// @TODO : Is docker running on middleware
// @TODO : Basic Auth on middleware

$app->get('/', function () {

    $output = shell_exec('docker ps');
    $rows = getColumnsAsArray($output);
    $rows = (new \Durbin\Processor\AttachStatusIndicator())->process($rows);

    return React\Http\Message\Response::html(
        render('layout', [
            'page' => 'running',
            'title' => 'Running Containers',
            'content' => rowsToTable($rows)
        ])
    );
});

$app->get('/all', function () {

    $output = shell_exec('docker ps -a');
    $rows = getColumnsAsArray($output);
    $rows = (new \Durbin\Processor\AttachStatusIndicator())->process($rows);

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
    $rows = (new \Durbin\Processor\AttachStatusIndicator())->process($rows);

    return React\Http\Message\Response::html(
        render('layout', [
            'page' => 'stats',
            'title' => 'Container Status',
            'content' => rowsToTable($rows)
        ])
    );
});


$app->get('/users/{name}', function (Psr\Http\Message\ServerRequestInterface $request) {
    return React\Http\Message\Response::plaintext(
        "Hello " . $request->getAttribute('name') . "!\n"
    );
});

$app->run();