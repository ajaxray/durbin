<?php
(__FILE__ == $_SERVER["SCRIPT_FILENAME"]) && http_response_code(403) && exit;

function render(string $view, array $vars = []): string
{
    global $config;

    $path = $config['view_dir']. "/{$view}.php";

    if (!is_readable($path)) {
        throw new \RuntimeException("Page '{$view}' was not found in expected location.");
    }

    ob_start();
    $data = array_merge($config, $vars);
    extract($data, EXTR_SKIP);
    require $path;

    return trim(ob_get_clean());
}



/**
 * Get string padded column output of shell command as an array
 *
 * @param string $shellOutput
 * @return array
 */
function getColumnsAsArray(string $shellOutput): array
{
    $rows = array_map(
        fn($line) => preg_split("/\s{2,}/", $line),
        explode("\n", $shellOutput)
    );

    // Trim empty rows
    return array_filter($rows, fn($row) => !empty($row) && !empty($row[0]));
}

/**
 * Display a 2D array in a table
 * @param iterable $rows
 * @return string
 */
function rowsToTable(iterable $rows): string
{
    $table = '<table class="data-table">';
    foreach ($rows as $tr)  {
        $table .= '<tr>';
        foreach ($tr as $td) {
            $table .= '<td>' . $td . '</td>'; }
        $table .= '</tr>'; }
    $table .= '</table>';

    return $table;
}
