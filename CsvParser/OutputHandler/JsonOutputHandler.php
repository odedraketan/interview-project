<?php

namespace CsvParser\OutputHandler;

class JsonOutputHandler implements OutputHandlerInterface
{
    private $outputFilePath;

    public function __construct(string $outputFilePath)
    {
        $this->outputFilePath = $outputFilePath;
    }

    public function writeHeader(array $header): void
    {
        // Json format doesn't have a header
    }

    public function writeRow(array $row): void
    {
        // In JSON, each row is written individually
        file_put_contents($this->outputFilePath, json_encode($row, JSON_PRETTY_PRINT) . PHP_EOL, FILE_APPEND);
    }

    public function close(): void
    {
        // No explicit close needed for JSON
    }
}
