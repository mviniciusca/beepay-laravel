<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Patient;
use Illuminate\Http\Request;

class ImportPatientController extends Controller
{
    public function import(Request $request)
    {
        $file = $request->file('file');
        $fileContents = file($file->getPathname());

        foreach ($fileContents as $line) {
            $data = str_getcsv($line);
            if (count($data) >= 13) {
                $patient = Patient::create([
                    'full_name' => $data[0],
                    'mother_name' => $data[1],
                    'birth_date' => $data[2],
                    'cpf' => $data[3],
                    'cns' => $data[4],
                    'picture' => $data[5],
                ]);
                Address::create([
                    'zip_code' => $data[6],
                    'street' => $data[7],
                    'number' => $data[8],
                    'complement' => $data[9],
                    'district' => $data[10],
                    'city' => $data[11],
                    'state' => $data[12],
                    'patient_id' => $patient->id,
                ]);
            } else {
                return response()->json(['message' => 'Invalid file format'], 400);
            }
        }
        return response()->json(['message' => 'File imported successfully'], 200);
    }
}

