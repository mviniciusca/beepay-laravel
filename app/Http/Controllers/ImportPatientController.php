<?php

namespace App\Http\Controllers;

use App\Jobs\ImportPatientJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ImportPatientController extends Controller
{
    public function import(Request $request)
    {

        $file_validation = Validator::make($request->only('file'), [
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:2048'],
        ]);

        if ($file_validation->fails()) {
            return response()->json([
                'message' => 'Error on validation. Please, check your file.',
                'errors' => $file_validation->errors(),
            ], 400);
        }

        $file = $file_validation->validated()['file'];

        $fileContents = $this->csvHandle($file);

        if (empty($fileContents)) {
            return response()->json(['message' => 'File is empty'], 400);
        }

        foreach ($fileContents as $data) {

            $data['cpf'] = $this->formatCpf($data['cpf']);
            $data['cns'] = $this->formatCns($data['cns']);

            $data_validation = Validator::make($data, [
                'full_name' => ['required', 'string', 'max:255'],
                'mother_name' => ['required', 'string', 'max:255'],
                'birth_date' => ['required', 'date'],
                'cpf' => ['required', 'string', 'max:11', 'unique:patients,cpf'],
                'cns' => ['required', 'string', 'max:15', 'unique:patients,cns'],
                'picture' => ['required', 'string', 'max:255'],
                'zip_code' => ['required', 'string', 'max:10'],
                'street' => ['required', 'string', 'max:255'],
                'number' => ['required', 'string', 'max:10'],
                'complement' => ['required', 'string', 'max:255'],
                'district' => ['required', 'string', 'max:255'],
                'city' => ['required', 'string', 'max:255'],
                'state' => ['required', 'string', 'max:2'],
            ]);

            if ($data_validation->fails()) {
                return response()->json([
                    'message' => 'Error on validation. Please, check your data.',
                    'errors' => [
                        'fail' => $data_validation->errors(),
                        'info' => $data,
                    ]
                ], 400);
            }
            ImportPatientJob::dispatch($data)->delay(now()->addSeconds(5));
        }
        return response()->json(['message' => 'File imported successfully'], 200);
    }

    /**
     * This function will handle the csv file
     * Checks if has header and if has, create a array combine with the header and the data
     */
    public function csvHandle($file)
    {
        $file = file($file->getPathname());
        $header = null;
        $data = [];
        foreach ($file as $line) {
            $line = str_getcsv($line);
            if (!$header) {
                $header = $line;
            } else {
                $data[] = array_combine($header, $line);
            }
        }
        return $data;
    }

    /**
     * Format CPF and remove possible special characters or mask
     * @param mixed $cpf
     * @return array|string|null
     */
    public function formatCpf($cpf)
    {
        return preg_replace('/[^0-9]/', '', $cpf);
    }

    /**
     * Format CNS and remove possible special characters or mask
     * @param mixed $cns
     * @return array|string|null
     */
    public function formatCns($cns)
    {
        return preg_replace('/[^0-9]/', '', $cns);
    }
}

