<?php

namespace CsvParser\FileParser;

interface FileParserInterface
{
    public function parseFile(): void;
}