<?php
namespace Durbin\Processor;

class AttachStartActions implements OutputProcessorInterface
{
    /**
     * @inheritDoc
     */
    public function process(array $records): array
    {
        $header = array_shift($records);
        $statusCol = array_search('STATUS', $header);
        $idCol = array_search('CONTAINER ID', $header);

        array_unshift($header, "");

        $records = array_map(function ($row) use ($statusCol, $idCol) {
            if (str_starts_with($row[$statusCol], 'Exited')) {
                array_unshift($row, "<button data-action=\"start\" data-container-id=\"{$row[$idCol]}\" class=\"btn-action\">start</button>");
            } else {
                array_unshift($row, "<button data-action=\"stop\" data-container-id=\"{$row[$idCol]}\" class=\"btn-action\">stop</button>");
            }

            return $row;
        }, $records);

        array_unshift($records, $header);

        return $records;
    }
}