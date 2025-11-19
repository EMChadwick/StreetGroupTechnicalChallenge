<?php

namespace App\Http\Controllers;

use App\Http\Requests\TenantUploadRequest;
use Spatie\SimpleExcel\SimpleExcelReader;

class TenantUploadController extends Controller
{
    public function __construct()
    {
        $titles = Title::values();

        $titlePattern = implode('|', $titles);

        $jointNamePattern = '/\b('.$titlePattern.')\b\s*(?:&|and)\s*\b('.$titlePattern.')\b/i';
    }

    /**
     * Accept a CSV file upload and return it in processed JSON format.
     */
    public function upload(TenantUploadRequest $request)
    {
        $file = $request->file('csv_file');

        $path = $file->getRealPath();

        $rows = SimpleExcelReader::create($path, 'csv')
            ->getRows()
            ->map(function ($row) {
                // Remove columns where the header is empty
                return array_filter($row, function ($value, $key) {
                    return $key !== '';
                }, ARRAY_FILTER_USE_BOTH);
            })
            ->toArray();

        $jsonData = [];

        foreach ($rows as $line) {
            // send to parser
        }

        return response()->json([
            'message' => 'CSV processed successfully.',
            'data' => $jsonData,
        ]);
    }
}
