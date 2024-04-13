<?php
namespace Durbin\Processor;

class AttachStatusIndicator implements OutputProcessorInterface
{
    /**
     * @inheritDoc
     */
    public function process(array $records): array
    {
        $header = array_shift($records);
        $statusCol = array_search('STATUS', $header);

        array_unshift($header, "");

        $records = array_map(function ($row) use ($statusCol) {
            if (str_starts_with($row[$statusCol], 'Exited')) {
                array_unshift($row, "⚪️");
            } else {
                array_unshift($row, "🟢");
            }

            return $row;
        }, $records);

        array_unshift($records, $header);

        return $records;
    }
}