<?php

class ParserTest
{
    public function testGenerateUniqueCombinations()
    {
        $csvFilePath = 'path/to/test.csv'; // replace with your test CSV file path
        $outputFilePath = 'path/to/output/test_output.csv'; // replace with your desired output file path
        $outputFormat = 'csv';
        $outputHeading = ['make', 'model', 'colour', 'capacity', 'network', 'grade', 'condition'];

        $csvParser = new Parser($csvFilePath, $outputFilePath, $outputFormat, $outputHeading);

        ob_start(); // Start output buffering
        $csvParser->generateUniqueCombinations();
        $output = ob_get_clean(); // Get the output and stop buffering

        // Ensure the output contains the expected message
        $this->assertContains("Unique combinations saved to: $outputFilePath", $output);

        // Add additional assertions as needed, e.g., to check the output file's existence or content
        if (file_exists($outputFilePath)) {
            // Add more assertions based on your specific requirements
        } else {
            $this->fail('Output file not generated.');
        }
    }

    private function assertContains($needle, $haystack)
    {
        if (strpos($haystack, $needle) === false) {
            throw new Exception("Assertion failed: '$needle' not found in '$haystack'");
        }
    }

    private function fail($message)
    {
        throw new Exception("Test failed: $message");
    }
}

// Run the test
$test = new ParserTest();
$test->testGenerateUniqueCombinations();