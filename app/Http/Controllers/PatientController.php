<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{

    /**
     * Create a new patient on database
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function store()
    {
        $data = Validator::make(request()->all(), [
            'full_name' => ['required', 'string', 'max:255'],
            'mother_name' => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'date'],
            'cpf' => ['required', 'string', 'max:11', 'unique:patients'],
            'cns' => ['required', 'string', 'max:15', 'unique:patients'],
            'picture' => ['nullable', 'string', 'max:255'],
            //
            'zip_code' => ['required', 'string', 'max:8'],
            'street' => ['required', 'string', 'max:255'],
            'number' => ['required', 'string', 'max:20'],
            'complement' => ['nullable', 'string', 'max:255'],
            'district' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:2'],
        ]);

        if ($data->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $data->errors()
            ], 400);
        }

        $data = $data->validated();

        return response()->json([
            'message' => 'Patient created successfully',
        ], 201);
    }
}
