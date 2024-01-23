<?php

namespace CsvParser\OutputHandler;

interface OutputHandlerInterface
{
    public function writeHeader(array $header): void;
    public function writeRow(array $row): void;
    public function close(): void;
}
