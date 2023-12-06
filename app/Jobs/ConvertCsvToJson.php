<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\CsvToJson;

class ConvertCsvToJson implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $csvFilePath;
    protected $csvFileName;
    protected $email;

    /**
     * Create a new job instance.
     */
    public function __construct($csvFilePath, $csvFileName, $email)
    {
        $this->csvFilePath = $csvFilePath;
        $this->csvFileName = $csvFileName;
        $this->email = $email;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Your CSV to JSON conversion logic here
        $csvData = file_get_contents($this->csvFilePath);
        $lines = explode("\n", $csvData);
        $headers = str_getcsv(array_shift($lines));

        $jsonData = [];
        foreach ($lines as $index => $line) {
            $values = str_getcsv($line);

            if (empty($values)) {
                continue;
            }

            // Ensure that the number of values matches the number of headers
            $values = array_pad($values, count($headers), null);
            $rowData = array_combine($headers, $values);

            // Remove null or empty values
            $rowData = array_filter($rowData, function ($value) {
                return $value !== null && $value !== '';
            });

            $jsonData[] = $rowData;
        }

        $jsonFileName = 'json_files/'.$this->csvFileName.'_converted_data.json';
        $jsonDirectory = dirname($jsonFileName);
        if (!is_dir($jsonDirectory)) {
            mkdir($jsonDirectory, 0755, true);
        }

        try {
            file_put_contents(public_path($jsonFileName), json_encode($jsonData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            
            // Send file to the Email.
            Mail::to($this->email)->send(new CsvToJson($jsonFileName));
            Log::info('Successfully wrote JSON data to file.');
        } catch (\Exception $e) {
            Log::error('Exception while writing JSON data to file: ' . $e->getMessage());
        }

        // Delete the CSV file
        unlink($this->csvFilePath);
    }
}
