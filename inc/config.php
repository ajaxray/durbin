<?php
(__FILE__ == $_SERVER["SCRIPT_FILENAME"]) && http_response_code(403) && exit;

// These configs will also be available in view files as variables (e,g, $app_name)
return [
    'name' => 'Docker Container Monitor',
    'title' => 'Docker Container Monitor',
    'copyright' => '© 2024 Your company name. All Rights Reserved.',
    'base_url' => 'http://durbin.test',
    'base_dir' => realpath(__DIR__ .'/..'),
    'view_dir' => realpath(__DIR__ . '/../pages'),

    // Will be used for Basic Auth
    'auth' => [
        'user' => 'sysadmin',
        'password' => '123!@#',
    ],
];
