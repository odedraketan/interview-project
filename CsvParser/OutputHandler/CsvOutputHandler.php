<?php

namespace CsvParser\OutputHandler;

class CsvOutputHandler implements OutputHandlerInterface
{
    private $handle;
    private $outputFilePath;

    public function __construct(string $outputFilePath)
    {
        $this->outputFilePath = $outputFilePath;
        $this->handle = fopen($this->outputFilePath, 'w');
    }

    public function writeHeader(array $header): void
    {
        fputcsv($this->handle, $header);
    }

    public function writeRow(array $row): void
    {
        fputcsv($this->handle, $row);
    }

    public function close(): void
    {
        fclose($this->handle);
    }
}
