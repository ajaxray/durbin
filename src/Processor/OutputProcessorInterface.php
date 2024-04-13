<?php
namespace Durbin\Processor;

interface OutputProcessorInterface
{
    /**
     * @param array<array<string>> $records
     * @return array<array<string>>
     */
    public function process(array $records): array;
}