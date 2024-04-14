<?php
namespace Durbin\Processor;

class AttachActions implements OutputProcessorInterface
{
    /**
     * @inheritDoc
     */
    public function process(array $records): array
    {
        global $config;
        $header = array_shift($records);
        $statusCol = array_search('STATUS', $header);
        $idCol = array_search('CONTAINER ID', $header);

        array_unshift($header, "");

        $records = array_map(function ($row) use ($statusCol, $idCol, $config) {
            $logBtn = "<a href=\"{$config['base_url']}/logs/{$row[$idCol]}\" class=\"btn-action\">log</a>";

            if (str_starts_with($row[$statusCol], 'Exited')) {
                array_unshift($row, "{$logBtn} <button data-action=\"start\" data-container-id=\"{$row[$idCol]}\" class=\"btn-action\">start</button>");
            } else {
                array_unshift($row, "{$logBtn} <button data-action=\"stop\" data-container-id=\"{$row[$idCol]}\" class=\"btn-action\">stop</button>");
            }

            return $row;
        }, $records);

        array_unshift($records, $header);

        return $records;
    }
}