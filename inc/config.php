<?php
(__FILE__ == $_SERVER["SCRIPT_FILENAME"]) && die('Trying to hack something? 🥱');

// These configs will also be available in view files as variables (e,g, $app_name)
return [
    'name' => 'Docker Container Monitor',
    'title' => 'Docker Container Monitor',
    'copyright' => '© 2024 Your company name. All Rights Reserved.',
    'base_url' => 'http://localhost:8081',
    'base_dir' => realpath(__DIR__ .'/..'),
    'view_dir' => realpath(__DIR__ . '/../pages'),

    // Will be used for Basic Auth
    'auth' => [
        'user' => 'sysadmin',
        'password' => '123!@#',
    ],
];
